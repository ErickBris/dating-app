<?php

/*!
 * ifsoft.co.uk engine v1.0
 *
 * http://ifsoft.com.ua, http://ifsoft.co.uk
 * raccoonsquare@gmail.com
 *
 * Copyright 2012-2018 Demyanchuk Dmitry (raccoonsquare@gmail.com)
 */

class hotgame extends db_connect
{
    private $requestFrom = 0;

    public function __construct($dbo = NULL)
    {
        parent::__construct($dbo);

    }

    private function getMaxId()
    {
        $stmt = $this->db->prepare("SELECT MAX(id) FROM users");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function get($itemId, $lat, $lng, $distance = 1000, $sex = 2, $sexOrientation = 0, $liked = 1, $matches = 1)
    {
        if ($itemId == 0) {

            $itemId = $this->getMaxId();
            $itemId++;
        }

        $result = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "itemId" => $itemId,
                        "items" => array());

        $tableName = "users";
        $origLat = $lat;
        $origLon = $lng;
        $dist = $distance; // This is the maximum distance (in miles) away from $origLat, $origLon in which to search

        if ($sex == 2) {

            $sex_sql = "";

        } else {

            $sex_sql = " and (sex = {$sex}) ";
        }

        if ($sexOrientation == 0) {

            $sex_orientation_sql = "";

        } else {

            $sex_orientation_sql = " and (sex_orientation = {$sexOrientation}) ";
        }

        $sql = "SELECT id, lat, lng, 3956 * 2 *
                    ASIN(SQRT( POWER(SIN(($origLat - lat)*pi()/180/2),2)
                    +COS($origLat*pi()/180 )*COS(lat*pi()/180)
                    *POWER(SIN(($origLon-lng)*pi()/180/2),2)))
                    as distance FROM $tableName WHERE
                    lng between ($origLon-$dist/cos(radians($origLat))*69)
                    and ($origLon+$dist/cos(radians($origLat))*69)
                    and lat between ($origLat-($dist/69))
                    and ($origLat+($dist/69))
                    and (id < $itemId)
                    and (id <> $this->requestFrom)
                    $sex_sql
                    $sex_orientation_sql
                    and (lowPhotoUrl <> '')
                    and (state = 0)
                    having distance < $dist ORDER BY id DESC LIMIT 20";

        $stmt = $this->db->prepare($sql);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                while ($row = $stmt->fetch()) {

                    $profile = new profile($this->db, $row['id']);
                    $profile->setRequestFrom($this->requestFrom);
                    $profileInfo = $profile->get();
                    $profileInfo['distance'] = round($this->getDistance($lat, $lng, $profileInfo['lat'], $profileInfo['lng']), 1);
                    unset($profile);

                    if ($matches == 0 && $profileInfo['match']) {


                    } else if ($liked == 0 && $profileInfo['myLike']) {


                    } else {

                        array_push($result['items'], $profileInfo);
                    }

                    $result['itemId'] = $row['id'];

                    unset($profile);
                }
            }
        }

        return $result;
    }

    public function getDistance($fromLat, $fromLng, $toLat, $toLng) {

        $latFrom = deg2rad($fromLat);
        $lonFrom = deg2rad($fromLng);
        $latTo = deg2rad($toLat);
        $lonTo = deg2rad($toLng);

        $delta = $lonTo - $lonFrom;

        $alpha = pow(cos($latTo) * sin($delta), 2) + pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($delta), 2);
        $beta = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($delta);

        $angle = atan2(sqrt($alpha), $beta);

        return ($angle * 6371000) / 1000;
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

