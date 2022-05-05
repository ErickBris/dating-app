<?php

/*!
* ifsoft.co.uk v1.0
*
* http://ifsoft.co.uk
* vsysteme@mail.ru
*
* Copyright 2012-2016 Demyanchuk Dmitry (https://vk.com/dmitry.demyanchuk)
*/

include_once($_SERVER['DOCUMENT_ROOT']."/core/init.inc.php");


if (admin::isSession()) {

    header("Location: /");
}

$admin = new admin($dbo);
//    $gift = new gift($dbo);

if ($admin->getCount() > 0) {

    header("Location: /");
}

include_once($_SERVER['DOCUMENT_ROOT']."/core/initialize.inc.php");

$page_id = "install";

$error = false;
$error_message = array();

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

    $user_username = isset($_POST['user_username']) ? $_POST['user_username'] : '';
    $user_password = isset($_POST['user_password']) ? $_POST['user_password'] : '';
    $user_fullname = isset($_POST['user_fullname']) ? $_POST['user_fullname'] : '';
    $token = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';

    $user_username = helper::clearText($user_username);
    $user_fullname = helper::clearText($user_fullname);
    $user_password = helper::clearText($user_password);
    $user_password_repeat = helper::clearText($user_password_repeat);

    $user_username = helper::escapeText($user_username);
    $user_fullname = helper::escapeText($user_fullname);
    $user_password = helper::escapeText($user_password);
    $user_password_repeat = helper::escapeText($user_password_repeat);

    if (auth::getAuthenticityToken() !== $token) {

        $error = true;
        $error_token = true;
        $error_message[] = 'Error!';
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
        $result = $admin->signup($user_username, $user_password, $user_fullname);

        if ($result['error'] === false) {

            $access_data = $admin->signin($user_username, $user_password);

            if ($access_data['error'] === false) {

                $clientId = 0; // Desktop version

                admin::createAccessToken();

                admin::setSession($access_data['accountId'], admin::getAccessToken());

                // Add standard settings

                $settings = new settings($dbo);
                $settings->createValue("admob", 1); //Default show admob
                unset($settings);

                // Add standard gifts

                    for ($i = 1; $i < 31; $i++) {

                        $gift->db_add(3, 0, APP_URL."/".GIFTS_PATH.$i.".jpg");

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

$css_files = array();
$page_title = APP_TITLE;

include_once($_SERVER['DOCUMENT_ROOT']."/common/header.inc.php");
?>

<body>

<?php

include_once($_SERVER['DOCUMENT_ROOT']."/common/admin_panel_topbar.inc.php");
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
                        <button class="waves-effect waves-light btn">Install</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

<script src="/js/init.js"></script>

</body>
</html>