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

    if (!$auth->authorize(auth::getCurrentUserId(), auth::getAccessToken())) {

        header('Location: /');
    }

    if (isset($_GET['access_token'])) {

        $accessToken = (isset($_GET['access_token'])) ? ($_GET['access_token']) : '';
        $continue = (isset($_GET['continue'])) ? ($_GET['continue']) : '/';

        if (auth::getAccessToken() === $accessToken) {

            $account = new account($dbo);
            $account->logout(auth::getCurrentUserId(), auth::getAccessToken());
            $account->setLastActive();

            auth::unsetSession();
            auth::clearCookie();

            header('Location: '.$continue);
            exit;
        }
    }

    header('Location: /');

?>