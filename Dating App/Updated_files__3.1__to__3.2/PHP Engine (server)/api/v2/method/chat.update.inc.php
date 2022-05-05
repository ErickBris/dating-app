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

    $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : 0;
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';

    $chatFromUserId = isset($_POST['chatFromUserId']) ? $_POST['chatFromUserId'] : 0;
    $chatToUserId = isset($_POST['chatToUserId']) ? $_POST['chatToUserId'] : 0;

    $chatId = isset($_POST['chatId']) ? $_POST['chatId'] : 0;

    $freeMessagesCount = isset($_POST['freeMessagesCount']) ? $_POST['freeMessagesCount'] : 0;

    $clientId = helper::clearInt($clientId);
    $accountId = helper::clearInt($accountId);

    $chatFromUserId = helper::clearInt($chatFromUserId);
    $chatToUserId = helper::clearInt($chatToUserId);

    $chatId = helper::clearInt($chatId);

    $freeMessagesCount = helper::clearInt($freeMessagesCount);

    $result = array("error" => false,
                    "error_code" => ERROR_UNKNOWN);

//    $auth = new auth($dbo);
//
//    if (!$auth->authorize($accountId, $accessToken)) {
//
//        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
//    }

    // Set Free Messages Count for Function "Pro Mode"

    $account = new account($dbo, $accountId);
    $account->setFreeMessagesCount($freeMessagesCount);
    unset($account);

    // Update Chat info

    $msg = new msg($dbo);
    $msg->setRequestFrom($accountId);

    $profileId = $chatFromUserId;

    if ($profileId == $accountId) {

        $msg->setChatLastView_FromId($chatId);

    } else {

        $msg->setChatLastView_ToId($chatId);
    }

    echo json_encode($result);
    exit;
}
