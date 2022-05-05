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

    $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : '';
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';

    $currentPassword = isset($_POST['currentPassword']) ? $_POST['currentPassword'] : '';

    $currentPassword = helper::clearText($currentPassword);
    $currentPassword = helper::escapeText($currentPassword);

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $result = array("error" => true,
                    "error_code" => ERROR_UNKNOWN);

    // Remove All Medias

    $photos = new photos($dbo);
    $photos->setRequestFrom($accountId);
    $photos->removeAll();
    unset($photos);

    $account = new account($dbo, $accountId);

    // Remove Avatar

    $photos = array("error" => false,
                    "originPhotoUrl" => "",
                    "normalPhotoUrl" => "",
                    "bigPhotoUrl" => "",
                    "lowPhotoUrl" => "");

    $account->setPhoto($photos);

    // Unset Facebook Id

    $account->setFacebookId("");

    // Unset Email

    $account->setEmail("");

    // Deactivate Account

    $result = $account->deactivation($currentPassword);

    echo json_encode($result);
    exit;
}
