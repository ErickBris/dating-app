<?php

    /*!
	 * ifsoft engine v1.0
	 *
	 * http://ifsoft.com.ua, http://ifsoft.co.uk
	 * qascript@ifsoft.co.uk, qascript@mail.ru
	 *
	 * Copyright 2012-2016 Demyanchuk Dmitry (https://vk.com/dmitry.demyanchuk)
	 */

    include_once($_SERVER['DOCUMENT_ROOT']."/core/init.inc.php");

    if (admin::isSession()) {

        header("Location: /admin/main.php");
    }

    $page_id = "login";

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

        if (helper::getAuthenticityToken() !== $token) {

            $error = true;
            $error_message = 'Error!';
        }

        if (!$error) {

            $access_data = array();

            $admin = new admin($dbo);
            $access_data = $admin->signin($user_username, $user_password);

            if ($access_data['error'] === false) {

                $clientId = 0; // Desktop version

                admin::createAccessToken();

                admin::setSession($access_data['accountId'], admin::getAccessToken());

                header("Location: /admin/main.php");

            } else {

                $error = true;
                $error_message = 'Incorrect login or password.';
            }
        }
    }

    helper::newAuthenticityToken();

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

        <div class="row">
            <form class="col s12 m6" action="/admin/login.php" method="post" style="margin: 0 auto; float: none; margin-top: 100px;">

                <input autocomplete="off" type="hidden" name="authenticity_token" value="<?php echo helper::getAuthenticityToken(); ?>">

                <div class="card ">
                    <div class="card-content black-text">
                        <span class="card-title">Log In</span>
                        <p style="<?php if (!$error) echo "display: none"; ?>"><?php echo $error_message; ?></p>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="username" type="text" class="validate valid" name="user_username" value="<?php echo $user_username; ?>">
                                <label for="username" class="active">Username</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="password" type="password" class="validate valid" name="user_password" value="">
                                <label for="password" class="active">Password</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-action">
                        <button class="waves-effect waves-light btn">Log In</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

<script src="/js/init.js"></script>

</body>
</html>