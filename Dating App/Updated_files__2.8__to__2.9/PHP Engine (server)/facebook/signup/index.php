<?php

/*!
 * ifsoft.co.uk v1.0
 *
 * http://ifsoft.com.ua, http://ifsoft.co.uk
 * qascript@ifsoft.co.uk
 *
 * Copyright 2012-2016 Demyanchuk Dmitry (https://vk.com/dmitry.demyanchuk)
 */

include_once($_SERVER['DOCUMENT_ROOT']."/core/init.inc.php");


if (auth::isSession()) {

    header("Location: /messages.php");
    exit;
}

if (isset($_SESSION['oauth']) && $_SESSION['oauth'] === 'facebook') {

    header("Location: /signup.php");
    exit;
}

if (isset($_GET['error'])) {

    header("Location: /signup.php");
    exit;
}

require_once '../autoload.php';

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\Entities\AccessToken;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\HttpClients\FacebookHttpable;

// init app with app id and secret
FacebookSession::setDefaultApplication(FACEBOOK_APP_ID, FACEBOOK_APP_SECRET);

// login helper with redirect_uri
$helper2 = new FacebookRedirectLoginHelper(APP_URL.'/facebook/login');

try {

    $session = $helper2->getSessionFromRedirect();

} catch(FacebookRequestException $ex) {

    // When Facebook returns an error
    header("Location: /signup.php");

} catch( Exception $ex ) {

    // When validation fails or other local issues
    header("Location: /signup.php");
}

// see if we have a session
if (isset($session)) {

    // graph api request for user data
    $request = new FacebookRequest( $session, 'GET', '/me' );
    $response = $request->execute();

    // get response
    $graphObject = $response->getGraphObject();
    $fbid = $graphObject->getProperty('id');              // To Get Facebook ID
    $fbfullname = $graphObject->getProperty('name'); // To Get Facebook full name
    $femail = $graphObject->getProperty('email');    // To Get Facebook email ID
    $flink = $graphObject->getProperty('link');

    $accountId = $helper->getUserIdByFacebook($fbid);

    $account = new account($dbo, $accountId);
    $accountInfo = $account->get();

    if ($accountInfo['error'] === false) {

        //user with fb id exists in db

        if ($accountInfo['state'] == ACCOUNT_STATE_BLOCKED) {

            header("Location: /");
            exit;

        } else {

            $account->setState(ACCOUNT_STATE_ENABLED);
            $account->setLastActive();

            $clientId = 0; // Desktop version

            $auth = new auth($dbo);
            $access_data = $auth->create($accountId, $clientId);

            if ($access_data['error'] === false) {

                auth::setSession($access_data['accountId'], $accountInfo['username'], $accountInfo['fullname'], $accountInfo['lowPhotoUrl'], $accountInfo['verify'], $account->getAccessLevel($access_data['accountId']), $access_data['accessToken']);
                auth::updateCookie($accountInfo['username'], $access_data['accessToken']);

                header("Location: /messages.php");
            }
        }

    } else {

        //new user
        $_SESSION['oauth'] = 'facebook';
        $_SESSION['oauth_id'] = $fbid;
        $_SESSION['oauth_name'] = $fbfullname;

        if (isset($flink)) {

            $_SESSION['oauth_link'] = $flink;
        }

        $_SESSION['oauth_email'] = "";

        if (isset($femail)) {

            $_SESSION['oauth_email'] = $femail;
        }

        header("Location: /signup.php");
        exit;
    }

} else {

    $loginUrl = $helper2->getLoginUrl();
    header("Location: ".$loginUrl);
}
