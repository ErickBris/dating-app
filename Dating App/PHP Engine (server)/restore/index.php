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

    $restorePointInfo = array();

    if (isset($_GET['hash'])) {

        $hash = isset($_GET['hash']) ? $_GET['hash'] : '';

        $hash = helper::clearText($hash);
        $hash = helper::escapeText($hash);

        $restorePointInfo = $helper->getRestorePoint($hash);

        if ($restorePointInfo['error'] !== false) {

            header("Location: /");
        }

    } else {

        header("Location: /");
    }


    $error = false;
    $error_message = array();

    $user_password = '';
    $user_password_repeat = '';

    if (!empty($_POST)) {

        $error = false;

        $user_password = isset($_POST['user_password']) ? $_POST['user_password'] : '';
        $user_password_repeat = isset($_POST['user_password_repeat']) ? $_POST['user_password_repeat'] : '';
        $token = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';

        $user_password = helper::clearText($user_password);
        $user_password_repeat = helper::clearText($user_password_repeat);

        $user_password = helper::escapeText($user_password);
        $user_password_repeat = helper::escapeText($user_password_repeat);

        if (helper::getAuthenticityToken() !== $token) {

            $error = true;
            $error_message[] = 'Error!';
        }

        if (!helper::isCorrectPassword($user_password)) {

            $error = true;
            $error_message[] = 'Incorrect password.';
        }

        if ($user_password != $user_password_repeat) {

            $error = true;
            $error_message[] = 'Passwords do not match.';
        }

        if (!$error) {

            $account = new account($dbo, $restorePointInfo['accountId']);

            $account->newPassword($user_password);
            $account->restorePointRemove();

            header("Location: /restore/success");
            exit;
        }
    }

    helper::newAuthenticityToken();

    $page_id = "restore";

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

        <div class="row">
            <form class="col s12 m6" action="/restore/?hash=<?php echo $hash; ?>" method="post" style="margin: 0 auto; float: none; margin-top: 100px;">

                <input autocomplete="off" type="hidden" name="authenticity_token" value="<?php echo helper::getAuthenticityToken(); ?>">

                <div class="card ">
                    <div class="card-content black-text">
                        <span class="card-title"><?php echo $LANG['page-restore']; ?></span>

                        <p class="red-text" style="<?php if (!$error) echo "display: none"; ?>">
                            <?php

                                foreach ($error_message as $msg) {

                                    echo $msg . "<br />";
                                }
                            ?>
                        </p>

                        <div class="row">
                            <div class="input-field col s12">
                                <input id="user_password" type="password" class="validate valid" name="user_password" value="">
                                <label for="user_password" class="active"><?php echo $LANG['label-new-password']; ?></label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <input id="user_password_repeat" type="password" class="validate valid" name="user_password_repeat" value="">
                                <label for="user_password_repeat" class="active"><?php echo $LANG['label-repeat-password']; ?></label>
                            </div>
                        </div>

                    </div>
                    <div class="card-action">
                        <button class="waves-effect waves-light btn <?php echo SITE_THEME; ?>"><?php echo $LANG['action-change']; ?></button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

<script src="/js/init.js"></script>

</body>
</html>