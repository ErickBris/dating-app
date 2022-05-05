<?php

/*!
 * ifsoft.co.uk engine v1.0
 *
 * http://ifsoft.com.ua, http://ifsoft.co.uk
 * raccoonsquare@gmail.com
 *
 * Copyright 2012-2018 Demyanchuk Dmitry (raccoonsquare@gmail.com)
 */

class matches extends db_connect
{
	private $requestFrom = 0;
    private $language = 'en';
    private $profileId = 0;

	public function __construct($dbo = NULL, $profileId = 0)
    {
		parent::__construct($dbo);

        $this->setProfileId($profileId);
	}

    public function getAllCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM matches");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    private function getMaxId()
    {
        $stmt = $this->db->prepare("SELECT MAX(id) FROM matches");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function count()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM matches WHERE u_matchTo = (:u_matchTo) AND removeAt = 0");
        $stmt->bindParam(":u_matchTo", $this->profileId, PDO::PARAM_INT);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function add($u_match)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $currentTime = time();

        $stmt = $this->db->prepare("INSERT INTO matches (u_match, u_matchTo, createAt) value (:u_match, :u_matchTo, :createAt)");
        $stmt->bindParam(":u_match", $u_match, PDO::PARAM_INT);
        $stmt->bindParam(":u_matchTo", $this->profileId, PDO::PARAM_INT);
        $stmt->bindParam(":createAt", $currentTime, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array("error" => false,
                            "error_code" => ERROR_SUCCESS,
                            "itemId" => $this->db->lastInsertId());

            $stmt2 = $this->db->prepare("INSERT INTO matches (u_match, u_matchTo, createAt) value (:u_match, :u_matchTo, :createAt)");
            $stmt2->bindParam(":u_match", $this->profileId, PDO::PARAM_INT);
            $stmt2->bindParam(":u_matchTo", $u_match, PDO::PARAM_INT);
            $stmt2->bindParam(":createAt", $currentTime, PDO::PARAM_INT);
            $stmt2->execute();

            $account = new account($this->db, $this->profileId);
            $account->updateCounters();
            unset($account);

            $account = new account($this->db, $u_match);
            $account->updateCounters();
            unset($account);
        }

        return $result;
    }

    public function clear()
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $currentTime = time();

        $stmt = $this->db->prepare("UPDATE matches SET removeAt = (:removeAt) WHERE u_matchTo = (:u_matchTo) AND removeAt = 0");
        $stmt->bindParam(":u_matchTo", $this->profileId, PDO::PARAM_INT);
        $stmt->bindParam(":removeAt", $currentTime, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array("error" => false,
                            "error_code" => ERROR_SUCCESS);
        }

        return $result;
    }

    public function remove($u_match)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $my_profile = new profile($this->db, $this->profileId);

        if ($my_profile->is_match_exists($u_match)) {

            $currentTime = time();

            $stmt = $this->db->prepare("UPDATE matches SET removeAt = (:removeAt) WHERE u_matchTo = (:u_matchTo) AND u_match = (:u_match) AND removeAt = 0");
            $stmt->bindParam(":u_matchTo", $this->profileId, PDO::PARAM_INT);
            $stmt->bindParam(":u_match", $u_match, PDO::PARAM_INT);
            $stmt->bindParam(":removeAt", $currentTime, PDO::PARAM_INT);

            if ($stmt->execute()) {

                $result = array("error" => false,
                                "error_code" => ERROR_SUCCESS);

                $stmt2 = $this->db->prepare("UPDATE matches SET removeAt = (:removeAt) WHERE u_match = (:u_match) AND u_matchTo = (:u_matchTo) AND removeAt = 0");
                $stmt2->bindParam(":u_match", $this->profileId, PDO::PARAM_INT);
                $stmt2->bindParam(":u_matchTo", $u_match, PDO::PARAM_INT);
                $stmt2->bindParam(":removeAt", $currentTime, PDO::PARAM_INT);
                $stmt2->execute();

                $account = new account($this->db, $this->profileId);
                $account->updateCounters();
                unset($account);

                $account = new account($this->db, $u_match);
                $account->updateCounters();
                unset($account);
            }
        }

        return $result;
    }

    public function get($itemId = 0)
    {
        if ($itemId == 0) {

            $itemId = $this->getMaxId();
            $itemId++;
        }

        $result = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "itemId" => $itemId,
                        "items" => array());

        $stmt = $this->db->prepare("SELECT id, u_match FROM matches WHERE u_matchTo = (:u_matchTo) AND removeAt = 0 AND id < (:itemId) ORDER BY id DESC LIMIT 20");
        $stmt->bindParam(':u_matchTo', $this->profileId, PDO::PARAM_INT);
        $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {

                $profile = new profile($this->db, $row['u_match']);
                $profileInfo = $profile->getVeryShort();
                unset($profile);

                array_push($result['items'], $profileInfo);

                $result['itemId'] = $row['id'];

                unset($profileInfo);
            }
        }

        return $result;
    }

    public function getNewCount($lastMatchesView)
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM matches WHERE u_matchTo = (:u_matchTo) AND createAt > (:lastMatchesView) AND removeAt = 0");
        $stmt->bindParam(":u_matchTo", $this->profileId, PDO::PARAM_INT);
        $stmt->bindParam(":lastMatchesView", $lastMatchesView, PDO::PARAM_INT);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function setLanguage($language)
    {
        $this->language = $language;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function setRequestFrom($requestFrom)
    {
        $this->requestFrom = $requestFrom;
    }

    public function getRequestFrom()
    {
        return $this->requestFrom;
    }

    public function setProfileId($profileId)
    {
        $this->profileId = $profileId;
    }

    public function getProfileId()
    {
        return $this->profileId;
    }
}
