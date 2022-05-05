<?php

    /*!
	 * ifsoft engine v1.0
	 *
	 * http://ifsoft.com.ua, http://ifsoft.co.uk
	 * raccoonsquare@gmail.com
	 *
	 * Copyright 2012-2018 Demyanchuk Dmitry (raccoonsquare@gmail.com)
	 */

    include_once($_SERVER['DOCUMENT_ROOT']."/core/init.inc.php");

    if (auth::isSession()) {

        header("Location: /messages.php");
    }

    $user_username = '';

    $error = false;
    $error_message = '';

    if (!empty($_POST)) {

        $user_username = isset($_POST['user_username']) ? $_POST['user_username'] : '';
        $user_password = isset($_POST['user_password']) ? $_POST['user_password'] : '';
        $token = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';

        $user_username = helper::clearText($user_username);
        $user_password = helper::clearText($user_password);

        $user_username = helper::escapeText($user_username);
        $user_password = helper::escapeText($user_password);

        if (auth::getAuthenticityToken() !== $token) {

            $error = true;
        }

        if (!$error) {

            $access_data = array();

            $account = new account($dbo);

            $access_data = $account->signin($user_username, $user_password);

            unset($account);

            if ($access_data['error'] === false) {

                $account_fullname = $access_data['fullname'];
                $account_photo_url = $access_data['photoUrl'];
                $account_verify = $access_data['verify'];
                $account_pro_mode = $access_data['pro'];
                $account_free_messages_count = $access_data['free_messages_count'];

                $account = new account($dbo, $access_data['accountId']);

                switch ($account->getState()) {

                    case ACCOUNT_STATE_BLOCKED: {

                        break;
                    }

                    case ACCOUNT_STATE_DISABLED: {

                        break;
                    }

                    default: {

                        $account->setState(ACCOUNT_STATE_ENABLED);

                        $clientId = 0; // Desktop version

                        $auth = new auth($dbo);
                        $access_data = $auth->create($access_data['accountId'], $clientId);

                        if ($access_data['error'] === false) {

                            auth::setSession($access_data['accountId'], $user_username, $account_fullname, $account_photo_url, $account_verify, $account_pro_mode, $account_free_messages_count, $account->getAccessLevel($access_data['accountId']), $access_data['accessToken']);
                            auth::updateCookie($user_username, $access_data['accessToken']);

                            unset($_SESSION['oauth']);
                            unset($_SESSION['oauth_id']);
                            unset($_SESSION['oauth_name']);
                            unset($_SESSION['oauth_email']);
                            unset($_SESSION['oauth_link']);

                            $account->setLastActive();

                            header("Location: /messages.php");
                        }
                    }
                }

            } else {

                $error = true;
            }
        }
    }

    auth::newAuthenticityToken();

    $page_id = "login";

    $css_files = array("my.css");
    $page_title = $LANG['page-login']." | ".APP_TITLE;

    include_once($_SERVER['DOCUMENT_ROOT'] . "/common/header.inc.php");
?>

<body>

<?php

    include_once($_SERVER['DOCUMENT_ROOT'] . "/common/site_topbar.inc.php");
?>

<div class="section no-pad-bot" id="index-banner">
    <div class="container">

        <div class="row">
            <form class="col s12 m6" action="/login.php" method="post" style="margin: 0 auto; float: none; margin-top: 100px;">

                <input autocomplete="off" type="hidden" name="authenticity_token" value="<?php echo helper::getAuthenticityToken(); ?>">

                <div class="card ">
                    <div class="card-content black-text">
                        <span class="card-title"><?php echo $LANG['page-login']; ?></span>
                        <p class="red-text" style="margin-top: 10px; margin-bottom: 10px; <?php if (!$error) echo "display: none"; ?>"><?php echo $LANG['msg-error-authorize']; ?></p>

                        <?php

                            if (FACEBOOK_AUTHORIZATION) {

                                ?>
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <a class="fb-icon-btn fb-btn-large btn-facebook" href="/facebook/login">
                                        <span class="icon-container">
                                            <i class="icon icon-facebook"></i>
                                        </span>
                                                <span><?php echo $LANG['action-login-with']." ".$LANG['label-facebook']; ?></span>
                                            </a>
                                        </div>
                                    </div>
                                <?php
                            }
                        ?>

                        <div class="row">
                            <div class="input-field col s12">
                                <input id="username" type="text" class="validate valid" name="user_username" value="<?php echo $user_username; ?>">
                                <label for="username" class="active"><?php echo $LANG['label-username']; ?></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="password" type="password" class="validate valid" name="user_password" value="">
                                <label for="password" class="active"><?php echo $LANG['label-password']; ?></label>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 0px">
                            <div class="col s12">
                                <a style="font-size: 1rem;" href="/remind.php"><?php echo $LANG['action-forgot-password']; ?></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-action">
                        <button class="waves-effect waves-light btn <?php echo SITE_THEME; ?>"><?php echo $LANG['action-login']; ?></button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

<script src="/js/init.js"></script>

</body>
</html>