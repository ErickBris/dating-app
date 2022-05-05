<?php

    /*!
	 * ifsoft engine v1.0
	 *
	 * http://ifsoft.com.ua, http://ifsoft.co.uk
	 * qascript@ifsoft.co.uk, qascript@mail.ru
	 *
	 * Copyright 2012-2018 Demyanchuk Dmitry (https://vk.com/dmitry.demyanchuk)
	 */

    include_once($_SERVER['DOCUMENT_ROOT']."/core/init.inc.php");

    if (auth::isSession()) {

        header("Location: /messages.php");
    }

    $user_username = '';
    $user_email = '';
    $user_fullname = '';
    $gender = 2;
    $user_referrer = 0;

    $error = false;
    $error_message = '';

    if (!empty($_POST)) {

        $error = false;

        $user_username = isset($_POST['username']) ? $_POST['username'] : '';
        $user_fullname = isset($_POST['fullname']) ? $_POST['fullname'] : '';
        $user_password = isset($_POST['password']) ? $_POST['password'] : '';
        $user_email = isset($_POST['email']) ? $_POST['email'] : '';
        $user_referrer = isset($_POST['referrer']) ? $_POST['referrer'] : 0;
        $token = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';

        $gender = isset($_POST['gender']) ? $_POST['gender'] : 0;

        $user_referrer = helper::clearInt($user_referrer);

        $user_username = helper::clearText($user_username);
        $user_fullname = helper::clearText($user_fullname);
        $user_password = helper::clearText($user_password);
        $user_email = helper::clearText($user_email);

        $user_username = helper::escapeText($user_username);
        $user_fullname = helper::escapeText($user_fullname);
        $user_password = helper::escapeText($user_password);
        $user_email = helper::escapeText($user_email);

        $gender = helper::clearInt($gender);

        if (auth::getAuthenticityToken() !== $token) {

            $error = true;
            $error_token = true;
            $error_message[] = $LANG['msg-error-unknown'];
        }

        if (!helper::isCorrectLogin($user_username)) {

            $error = true;
            $error_username = true;
            $error_message[] = $LANG['msg-login-incorrect'];
        }

        if ($helper->isLoginExists($user_username)) {

            $error = true;
            $error_username = true;
            $error_message[] = $LANG['msg-login-taken'];
        }

        if (!helper::isCorrectFullname($user_fullname)) {

            $error = true;
            $error_fullname = true;
            $error_message[] = $LANG['msg-fullname-incorrect'];
        }

        if (!helper::isCorrectPassword($user_password)) {

            $error = true;
            $error_password = true;
            $error_message[] = $LANG['msg-password-incorrect'];
        }

        if (!helper::isCorrectEmail($user_email)) {

            $error = true;
            $error_email = true;
            $error_message[] = $LANG['msg-email-incorrect'];
        }

        if ($helper->isEmailExists($user_email)) {

            $error = true;
            $error_email = true;
            $error_message[] = $LANG['msg-email-taken'];
        }

        if (!$error) {

            $account = new account($dbo);

            $result = array();
            $result = $account->signup($user_username, $user_fullname, $user_password, $user_email, $gender, 2000, 1, 1, $LANG['lang-code']);

            if ($result['error'] === false) {

                $clientId = 0; // Desktop version

                $auth = new auth($dbo);
                $access_data = $auth->create($result['accountId'], $clientId);

                if ($access_data['error'] === false) {

                    auth::setSession($access_data['accountId'], $user_username, $user_fullname, "", 0, 0, 150, $account->getAccessLevel($access_data['accountId']), $access_data['accessToken']);
                    auth::updateCookie($user_username, $access_data['accessToken']);

                    $language = $account->getLanguage();

                    $account->setState(ACCOUNT_STATE_ENABLED);

                    $account->setLastActive();

                    // refsys

                    if ($user_referrer != 0) {

                        $ref = new refsys($dbo);
                        $ref->setRequestFrom($account->getId());
                        $ref->setReferrer($user_referrer);

                        $ref->setReferralsCount($user_referrer, $ref->getReferralsCount($user_referrer));

                        $ref->addSignupBonus($user_referrer);

                        unset($ref);
                    }

                    //Facebook connect

                    if (isset($_SESSION['oauth']) && $_SESSION['oauth'] === 'facebook' && $helper->getUserIdByFacebook($_SESSION['oauth_id']) == 0) {

                        $account->setFacebookId($_SESSION['oauth_id']);

                        $time = time();
                        $fb_id = $_SESSION['oauth_id'];

                        $img = @file_get_contents('https://graph.facebook.com/'.$fb_id.'/picture?type=large');
                        $file =  TEMP_PATH.$time.".jpg";
                        @file_put_contents($file, $img);

                        $imglib = new imglib($dbo);
                        $response = $imglib->createPhoto($file);
                        unset($imglib);

                        if ($response['error'] === false) {

                            $account->setPhoto($response);
                        }

                        unset($_SESSION['oauth']);
                        unset($_SESSION['oauth_id']);
                        unset($_SESSION['oauth_name']);
                        unset($_SESSION['oauth_email']);
                        unset($_SESSION['oauth_link']);

                    } else {

                        $account->setFacebookId("");
                    }

                    header("Location: /messages.php");
                    exit;
                }

            } else {

                $error = true;
                $error_message[] = "You can not create multi-accounts!";
            }
        }
    }

    if (isset($_SESSION['oauth']) && empty($user_username) && empty($user_email)) {

        $user_fullname = $_SESSION['oauth_name'];
        $user_email = $_SESSION['oauth_email'];
    }

    auth::newAuthenticityToken();

    $page_id = "signup";

    $css_files = array("my.css");
    $page_title = $LANG['page-signup']." | ".APP_TITLE;

    include_once($_SERVER['DOCUMENT_ROOT'] . "/common/header.inc.php");
?>

<body>

<?php

    include_once($_SERVER['DOCUMENT_ROOT'] . "/common/site_topbar.inc.php");
?>

<div class="section no-pad-bot" id="index-banner">
    <div class="container">

        <div class="row">
            <form class="col s12 m6" action="/signup.php" method="post" style="margin: 0 auto; float: none; margin-top: 100px;">

                <input autocomplete="off" type="hidden" name="authenticity_token" value="<?php echo helper::getAuthenticityToken(); ?>">

                <div class="card">
                    <div class="card-content black-text">
                        <span class="card-title"><?php echo $LANG['page-signup']; ?></span>
                        <p class="red-text" style="margin-top: 10px; margin-bottom: 10px; <?php if (!$error) echo "display: none"; ?>">
                            <?php

                                foreach ($error_message as $key => $value) {

                                    echo $value."<br>";
                                }
                            ?>
                        </p>

                        <?php

                        if (isset($_SESSION['oauth'])) {

                            ?>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <ul class="collection">
                                            <li class="collection-item avatar">

                                                <?php

                                                    $headers = get_headers('https://graph.facebook.com/'.$_SESSION['oauth_id'].'/picture',1);

                                                    if (isset($headers['Location'])) {

                                                        $url = $headers['Location']; // string

                                                        echo "<img src=\"$url\" alt=\"\" class=\"circle\">";

                                                    } else {

                                                        echo "<img src=\"\img\profile_default_photo.png\" alt=\"\" class=\"circle\">";
                                                    }
                                                ?>

<!--                                                <img src="images/yuna.jpg" alt="" class="circle">-->
                                                <span class="title"><a target="_blank" href="https://www.facebook.com/app_scoped_user_id/<?php echo $_SESSION['oauth_id']; ?>"><?php echo $_SESSION['oauth_name']; ?></a></span>
                                                <p><?php echo $LANG['label-authorization-with-facebook']; ?>
                                                    <br>
                                                    <a href="/facebook"><?php echo $LANG['action-back-to-default-signup']; ?></a>
                                                </p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                            <?php

                        } else {

                            if (FACEBOOK_AUTHORIZATION) {

                                ?>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <a class="fb-icon-btn fb-btn-large btn-facebook" href="/facebook/signup">
                                        <span class="icon-container">
                                            <i class="icon icon-facebook"></i>
                                        </span>
                                            <?php echo $LANG['action-signup-with'] . " " . $LANG['label-facebook']; ?>
                                        </a>
                                    </div>
                                </div>

                                <?php
                            }
                        }

                        ?>

                        <div class="row">
                            <div class="input-field col s12">
                                <input id="username" type="text" class="validate valid" name="username" value="<?php echo $user_username; ?>">
                                <label for="username" class="active"><?php echo $LANG['label-username']; ?></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="fullname" type="text" class="validate valid" name="fullname" value="<?php echo $user_fullname; ?>">
                                <label for="fullname" class="active"><?php echo $LANG['label-fullname']; ?></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="password" type="password" class="validate valid" name="password" value="">
                                <label for="password" class="active"><?php echo $LANG['label-password']; ?></label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <input id="email" type="text" class="validate valid" name="email" value="<?php echo $user_email; ?>">
                                <label for="email" class="active"><?php echo $LANG['label-email']; ?></label>
                            </div>
                        </div>

                        <div class="row" style="margin-bottom: 0px">
                            <div class="input-field col s12">
                                <select name="gender">
                                    <option value="2" <?php if ($gender != SEX_FEMALE && $gender != SEX_MALE) echo "selected=\"selected\""; ?>><?php echo $LANG['gender-unknown']; ?></option>
                                    <option value="0" <?php if ($gender == SEX_MALE) echo "selected=\"selected\""; ?>><?php echo $LANG['gender-male']; ?></option>
                                    <option value="1" <?php if ($gender == SEX_FEMALE) echo "selected=\"selected\""; ?>><?php echo $LANG['gender-female']; ?></option>
                                </select>
                                <label><?php echo $LANG['label-gender']; ?></label>

                                <script type="text/javascript">

                                    $(document).ready(function() {

                                        $('select').material_select();
                                    });

                                </script>

                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <input id="referrer" type="number" size="8" name="referrer" value="<?php echo $user_referrer; ?>">
                                <label for="referrer" class="active"><?php echo $LANG['label-signup-invite']; ?></label>
                            </div>
                        </div>

                        <div class="row" style="margin-bottom: 0px">
                            <div class="col s12">
                                <label><?php echo $LANG['label-signup-confirm']; ?></label>
                                <a style="font-size: 0.8rem;" href="/terms.php"><?php echo $LANG['page-terms']; ?></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-action">
                        <button class="waves-effect waves-light btn <?php echo SITE_THEME; ?>"><?php echo $LANG['action-signup']; ?></button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

<script src="/js/init.js"></script>

</body>
</html>