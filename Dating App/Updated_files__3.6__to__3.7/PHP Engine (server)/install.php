<?php

    /*!
    * ifsoft.co.uk v1.0
    *
    * http://ifsoft.co.uk
    * raccoonsquare@gmail.com
    *
    * Copyright 2012-2018 Demyanchuk Dmitry (raccoonsquare@gmail.com)
    */

    include_once($_SERVER['DOCUMENT_ROOT']."/core/init.inc.php");


    if (admin::isSession()) {

        header("Location: /");
    }

    $admin = new admin($dbo);
    $gift = new gift($dbo);
    $stickers = new sticker($dbo);

    if ($admin->getCount() > 0) {

        header("Location: /");
    }

    include_once($_SERVER['DOCUMENT_ROOT']."/core/initialize.inc.php");

    $page_id = "install";

    $itemId = 14781822; // Dating App Android = 14781822
                        // Dating App iOS = 19393764
                        // My Social Network Android = 13965025
                        // My Social Network iOS = 19414706

    $error = false;
    $error_message = array();

    $pcode = '';
    $user_username = '';
    $user_fullname = '';
    $user_password = '';
    $user_password_repeat = '';

    $error_token = false;
    $error_username = false;
    $error_fullname = false;
    $error_password = false;
    $error_password_repeat = false;

    if (!empty($_POST)) {

        $error = false;

        $pcode = isset($_POST['pcode']) ? $_POST['pcode'] : '';
        $user_username = isset($_POST['user_username']) ? $_POST['user_username'] : '';
        $user_password = isset($_POST['user_password']) ? $_POST['user_password'] : '';
        $user_fullname = isset($_POST['user_fullname']) ? $_POST['user_fullname'] : '';
        $token = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';

        $pcode = helper::clearText($pcode);
        $user_username = helper::clearText($user_username);
        $user_fullname = helper::clearText($user_fullname);
        $user_password = helper::clearText($user_password);
        $user_password_repeat = helper::clearText($user_password_repeat);

        $pcode = helper::escapeText($pcode);
        $user_username = helper::escapeText($user_username);
        $user_fullname = helper::escapeText($user_fullname);
        $user_password = helper::escapeText($user_password);
        $user_password_repeat = helper::escapeText($user_password_repeat);

        if (auth::getAuthenticityToken() !== $token) {

            $error = true;
            $error_token = true;
            $error_message[] = 'Error!';
        }

        $pcode_result = helper::verify_pcode($pcode, $itemId);

        if (isset($pcode_result['verify']) && $pcode_result['verify'] == 'true') {

            $error = false;

        } else {

            $error = true;
            $error_pcode = true;
            $error_message[] = 'Incorrect purchase code.';
        }

        if (!helper::isCorrectLogin($user_username)) {

            $error = true;
            $error_username = true;
            $error_message[] = 'Incorrect username.';
        }

        if (!helper::isCorrectPassword($user_password)) {

            $error = true;
            $error_password = true;
            $error_message[] = 'Incorrect password.';
        }

        if (!$error) {

            $admin = new admin($dbo);

            // Create admin account

            $result = array();
            $result = $admin->signup($user_username, $user_password, $user_fullname, ADMIN_ACCESS_LEVEL_FULL);

            if ($result['error'] === false) {

                $access_data = $admin->signin($user_username, $user_password);

                if ($access_data['error'] === false) {

                    $clientId = 0; // Desktop version

                    admin::createAccessToken();

                    admin::setSession($access_data['accountId'], admin::getAccessToken(), $access_data['username'], $access_data['fullname'], $access_data['access_level']);

                    // Add standard settings

                    $settings = new settings($dbo);
                    $settings->createValue("admob", 1); //Default show admob
                    $settings->createValue("defaultBalance", 10); //Default balance for new users
                    $settings->createValue("defaultReferralBonus", 10); //Default bonus - referral signup
                    $settings->createValue("defaultFreeMessagesCount", 150); //Default free messages count after signup
                    $settings->createValue("allowFriendsFunction", 1);
                    $settings->createValue("allowSeenTyping", 1);
                    $settings->createValue("allowMultiAccountsFunction", 1);
                    $settings->createValue("allowFacebookAuthorization", 1);
                    $settings->createValue("allowUpgradesSection", 1);
                    unset($settings);

                    // Add standard gifts

                    if ($gift->db_getMaxId() < 1) {

                        for ($i = 1; $i < 31; $i++) {

                            $gift->db_add(3, 0, APP_URL."/".GIFTS_PATH.$i.".jpg");

                        }
                    }

                    // Add standard stickers

                    if ($stickers->db_getMaxId() < 1) {

                        for ($i = 1; $i < 28; $i++) {

                            $stickers->db_add(APP_URL."/stickers/".$i.".png");

                        }
                    }

                    // Add standard feelings

                    $feelings = new feelings($dbo);

                    if ($feelings->db_getMaxId() < 1) {

                        for ($i = 1; $i <= 12; $i++) {

                            $feelings->db_add(APP_URL."/feelings/".$i.".png");

                        }
                    }

                    // Redirect to Admin Panel main page

                    header("Location: /admin/main.php");
                    exit;
                }

                header("Location: /install.php");
            }
        }
    }

    auth::newAuthenticityToken();

    $css_files = array("my.css");
    $page_title = APP_TITLE;

    include_once($_SERVER['DOCUMENT_ROOT']."/common/header.inc.php");
?>

<body>

<?php

    include_once($_SERVER['DOCUMENT_ROOT']."/common/site_topbar.inc.php");
?>

<div class="section no-pad-bot" id="index-banner">
    <div class="container">

        <div class="row col s12 m6">
            <div class="card" style="margin: 0 auto; float: none; margin-top: 30px;">
                <div class="card-content black-text">
                    <span class="card-title">Warning!</span>
                    <p class="teal-text">Remember that now Create an account administrator!</p>
                </div>
            </div>
        </div>

        <div class="row">
            <form class="col s12 m6" action="/install.php" method="post" style="margin: 0 auto; float: none; margin-top: 50px;">

                <input autocomplete="off" type="hidden" name="authenticity_token" value="<?php echo helper::getAuthenticityToken(); ?>">

                <div class="card ">
                    <div class="card-content black-text">
                        <span class="card-title">Install</span>
                        <p class="red-text" style="<?php if (!$error) echo "display: none"; ?>">
                            <?php

                            foreach ($error_message as $msg) {

                                echo $msg."<br/>";
                            }
                            ?>
                        </p>

                        <div class="row">
                            <div class="input-field col s12">
                                <p>How to get purchase code you can read here: <a href="http://ifsoft.co.uk/help/how_to_get_purchase_code/" target="_blank">How to get purchase code?</a></p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <input id="pcode" type="text" class="validate valid" name="pcode" value="<?php echo $pcode; ?>">
                                <label for="pcode" class="active">Purchase code</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <input id="user_username" type="text" class="validate valid" name="user_username" value="<?php echo $user_username; ?>">
                                <label for="user_username" class="active">Username</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <input id="user_fullname" type="text" class="validate valid" name="user_fullname" value="<?php echo $user_fullname; ?>">
                                <label for="user_fullname" class="active">Fullname</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <input id="user_password" type="password" class="validate valid" name="user_password" value="">
                                <label for="user_password" class="active">Password</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-action">
                        <button class="waves-effect waves-light btn <?php echo SITE_THEME; ?>">Install</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

<script src="/js/init.js"></script>

</body>
</html>