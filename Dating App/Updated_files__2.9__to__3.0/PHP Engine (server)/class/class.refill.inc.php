<?php

/*!
 * ifsoft.co.uk engine v1.1
 *
 * http://ifsoft.com.ua, http://ifsoft.co.uk
 * raccoonsquare@gmail.com
 *
 * Copyright 2012-2018 Demyanchuk Dmitry (raccoonsquare@gmail.com)
 */

class refill extends db_connect
{
	private $requestFrom = 0;
    private $language = 'en';

    private $signupBonus = 5;   // Amount

	public function __construct($dbo = NULL)
    {
		parent::__construct($dbo);
	}

    public function setReferrer($referrerId = 0)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        if ($referrerId != 0) {

            $profile = new profile($this->db, $referrerId);

            $profileInfo = $profile->get();

            if ($profileInfo['error'] == true) {

                $referrerId = 0;
            }

            unset($profile);
            unset($profileInfo);
        }

        $stmt = $this->db->prepare("UPDATE users SET referrer = (:referrer) WHERE id = (:referral)");
        $stmt->bindParam(":referral", $this->requestFrom, PDO::PARAM_INT);
        $stmt->bindParam(":referrer", $referrerId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array("error" => false,
                            "error_code" => ERROR_SUCCESS);
        }

        return $result;
    }

    public function getAllCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM guests");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    private function getMaxId()
    {
        $stmt = $this->db->prepare("SELECT MAX(id) FROM refill_history");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getReferralsCount($referrerId)
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM users WHERE referrer = (:referrer) AND state = 0");
        $stmt->bindParam(":referrer", $referrerId, PDO::PARAM_INT);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function setReferralsCount($referrerId, $referralsCount = 0)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("UPDATE users SET referrals_count = (:referrals_count) WHERE id = (:referrer)");
        $stmt->bindParam(":referrals_count", $referralsCount, PDO::PARAM_INT);
        $stmt->bindParam(":referrer", $referrerId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array("error" => false,
                            "error_code" => ERROR_SUCCESS);
        }

        return $result;
    }

    public function addToHistory($toUserId, $refillType, $amount)
    {
        $u_agent = helper::u_agent();
        $ip_addr = helper::ip_addr();
        $currentTime = time();

        $stmt = $this->db->prepare("INSERT INTO refill_history (toUserId, refillType, amount, createAt, u_agent, ip_addr) value (:toUserId, :refillType, :amount, :createAt, :u_agent, :ip_addr)");
        $stmt->bindParam(":toUserId", $toUserId, PDO::PARAM_INT);
        $stmt->bindParam(":refillType", $refillType, PDO::PARAM_INT);
        $stmt->bindParam(":amount", $amount, PDO::PARAM_INT);
        $stmt->bindParam(":createAt", $currentTime, PDO::PARAM_INT);
        $stmt->bindParam(":u_agent", $u_agent, PDO::PARAM_INT);
        $stmt->bindParam(":ip_addr", $ip_addr, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function count()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM refill_history WHERE toUserId = (:toUserId)");
        $stmt->bindParam(":toUserId", $this->requestFrom, PDO::PARAM_INT);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }


    public function getHistory($itemId = 0)
    {
        if ($itemId == 0) {

            $itemId = $this->getMaxId();
            $itemId++;
        }

        $result = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "itemId" => $itemId,
                        "items" => array());

        $stmt = $this->db->prepare("SELECT * FROM refill_history WHERE toUserId = (:toUserId) AND id < (:itemId) ORDER BY id DESC LIMIT 20");
        $stmt->bindParam(':toUserId', $this->requestFrom, PDO::PARAM_INT);
        $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {

                $info = array("error" => false,
                              "error_code" => ERROR_SUCCESS,
                              "id" => $row['id'],
                              "toUserId" => $row['toUserId'],
                              "refillType" => $row['refillType'],
                              "amount" => $row['amount'],
                              "date" => date("Y-m-d", $row['createAt']));

                array_push($result['items'], $info);

                $result['itemId'] = $row['id'];

                unset($info);
            }
        }

        return $result;
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
}
