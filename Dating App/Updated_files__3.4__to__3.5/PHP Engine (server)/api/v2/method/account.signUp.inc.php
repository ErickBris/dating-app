<?php

/*!
 * ifsoft.co.uk engine v1.0
 *
 * http://ifsoft.com.ua, http://ifsoft.co.uk
 * raccoonsquare@gmail.com
 *
 * Copyright 2012-2018 Demyanchuk Dmitry (raccoonsquare@gmail.com)
 */

include_once($_SERVER['DOCUMENT_ROOT']."/core/init.inc.php");
include_once($_SERVER['DOCUMENT_ROOT']."/config/api.inc.php");

if (!empty($_POST)) {

    $clientId = isset($_POST['clientId']) ? $_POST['clientId'] : 0;

    $gcm_regId = isset($_POST['gcm_regId']) ? $_POST['gcm_regId'] : '';
    $ios_fcm_regId = isset($_POST['ios_fcm_regId']) ? $_POST['ios_fcm_regId'] : '';

    $facebookId = isset($_POST['facebookId']) ? $_POST['facebookId'] : '';

    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $fullname = isset($_POST['fullname']) ? $_POST['fullname'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $referrer = isset($_POST['referrer']) ? $_POST['referrer'] : 0;

    $photoUrl = isset($_POST['photo']) ? $_POST['photo'] : '';

    $user_sex = isset($_POST['sex']) ? $_POST['sex'] : 0;
    $user_year = isset($_POST['year']) ? $_POST['year'] : 0;
    $user_month = isset($_POST['month']) ? $_POST['month'] : 0;
    $user_day = isset($_POST['day']) ? $_POST['day'] : 0;

    $u_age = isset($_POST['age']) ? $_POST['age'] : 0;
    $u_sex_orientation = isset($_POST['sex_orientation']) ? $_POST['sex_orientation'] : 0;

    $language = isset($_POST['language']) ? $_POST['language'] : '';

    $clientId = helper::clearInt($clientId);

    $referrer = helper::clearInt($referrer);

    $user_sex = helper::clearInt($user_sex);
    $user_year = helper::clearInt($user_year);
    $user_month = helper::clearInt($user_month);
    $user_day = helper::clearInt($user_day);

    $u_age = helper::clearInt($u_age);
    $u_sex_orientation = helper::clearInt($u_sex_orientation);

    $facebookId = helper::clearText($facebookId);

    $gcm_regId = helper::clearText($gcm_regId);
    $username = helper::clearText($username);
    $fullname = helper::clearText($fullname);
    $password = helper::clearText($password);
    $email = helper::clearText($email);
    $photoUrl = helper::clearText($photoUrl);
    $language = helper::clearText($language);

    $facebookId = helper::escapeText($facebookId);
    $gcm_regId = helper::escapeText($gcm_regId);
    $username = helper::escapeText($username);
    $fullname = helper::escapeText($fullname);
    $password = helper::escapeText($password);
    $email = helper::escapeText($email);
    $photoUrl = helper::escapeText($photoUrl);
    $language = helper::escapeText($language);

    $ios_fcm_regId = helper::clearText($ios_fcm_regId);
    $ios_fcm_regId = helper::escapeText($ios_fcm_regId);

    if ($clientId != CLIENT_ID) {

        api::printError(ERROR_UNKNOWN, "Error client Id.");
    }

    $result = array("error" => true);

    $account = new account($dbo);
    $result = $account->signup($username, $fullname, $password, $email, $user_sex, $user_year, $user_month, $user_day, $u_age, $u_sex_orientation, $language);
    unset($account);

    if ($result['error'] === false) {

        $account = new account($dbo);
        $account->setState(ACCOUNT_STATE_ENABLED);
        $account->setLastActive();
        $result = $account->signin($username, $password);
        unset($account);

        if ($result['error'] === false) {

            $auth = new auth($dbo);
            $result = $auth->create($result['accountId'], $clientId);

            if ($result['error'] === false) {

                $account = new account($dbo, $result['accountId']);

                // refsys

                if ($referrer != 0) {

                    $ref = new refsys($dbo);
                    $ref->setRequestFrom($account->getId());
                    $ref->setReferrer($referrer);

                    $ref->setReferralsCount($referrer, $ref->getReferralsCount($referrer));

                    $ref->addSignupBonus($referrer);

                    unset($ref);
                }

                if (strlen($photoUrl) != 0) {

                    $photos = array("error" => false,
                                    "originPhotoUrl" => $photoUrl,
                                    "normalPhotoUrl" => $photoUrl,
                                    "bigPhotoUrl" => $photoUrl,
                                    "lowPhotoUrl" => $photoUrl);

                    $account->setPhoto($photos);

                    unset($photos);
                }

                if (strlen($facebookId) != 0) {

                    $helper = new helper($dbo);

                    if ($helper->getUserIdByFacebook($facebookId) == 0) {

                        $account->setFacebookId($facebookId);
                    }

                } else {

                    $account->setFacebookId("");
                }

                if (strlen($gcm_regId) != 0) {

                    $account->setGCM_regId($gcm_regId);
                }

                if (strlen($ios_fcm_regId) != 0) {

                    $account->set_iOS_regId($ios_fcm_regId);
                }

                $result['account'] = array();

                array_push($result['account'], $account->get());
            }
        }
    }

    echo json_encode($result);
    exit;
}
