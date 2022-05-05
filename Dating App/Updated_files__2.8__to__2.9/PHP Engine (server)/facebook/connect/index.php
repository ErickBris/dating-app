<?php

/*!
 * ifsoft.co.uk v1.0
 *
 * http://ifsoft.com.ua, http://ifsoft.co.uk
 * qascript@ifsoft.co.uk
 *
 * Copyright 2012-2017 Demyanchuk Dmitry (https://vk.com/dmitry.demyanchuk)
 */

    include_once($_SERVER['DOCUMENT_ROOT']."/core/init.inc.php");

    if (!$auth->authorize(auth::getCurrentUserId(), auth::getAccessToken())) {

        header('Location: /');
    }

    if (isset($_GET['error'])) {

        header("Location: /settings/services");
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
$helper2 = new FacebookRedirectLoginHelper(APP_URL.'/facebook/connect');

try {

    $session = $helper2->getSessionFromRedirect();

} catch(FacebookRequestException $ex) {

    // When Facebook returns an error
    header("Location: /facebook/connect");

} catch( Exception $ex ) {

    // When validation fails or other local issues
    header("Location: /facebook/connect");
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

    if ($accountId != 0) {

        //user with fb id exists in db
        header("Location: /settings/services/?oauth_provider=facebook&status=error");
        exit;

    } else {

        //new user

        $account = new account($dbo, auth::getCurrentUserId());
        $account->setFacebookId($fbid);

        header("Location: /settings/services/?oauth_provider=facebook&status=connected");
        exit;
    }

} else {

    $loginUrl = $helper2->getLoginUrl();
    header("Location: ".$loginUrl);
}
