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

    $accountId = auth::getCurrentUserId();

    $error = false;

    if (!empty($_POST)) {

        $token = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';

        $old_password = isset($_POST['old_password']) ? $_POST['old_password'] : '';
        $new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';

        $old_password = helper::clearText($old_password);
        $new_password = helper::clearText($new_password);

        $old_password = helper::escapeText($old_password);
        $new_password = helper::escapeText($new_password);

        if (auth::getAuthenticityToken() !== $token) {

            $error = true;
        }

        if ( !$error ) {

            $account = new account($dbo, $accountId);

            if ( helper::isCorrectPassword($new_password) ) {

                $result = array();

                $result = $account->setPassword($old_password, $new_password);

                if ( $result['error'] === false ) {

                    header("Location: /settings/password/?error=false");
                    exit;

                } else {

                    header("Location: /settings/password/?error=old_password");
                    exit;
                }

            } else {

                header("Location: /settings/password/?error=new_password");
                exit;
            }
        }

        header("Location: /settings/password/?error=true");
        exit;
    }

    auth::newAuthenticityToken();

    $page_id = "settings_password";

    $css_files = array("my.css", "account.css");
    $page_title = $LANG['page-profile-password']." | ".APP_TITLE;

    include_once($_SERVER['DOCUMENT_ROOT']."/common/site_header.inc.php");

?>

<body>

    <?php

        include_once($_SERVER['DOCUMENT_ROOT']."/common/site_topbar.inc.php");
    ?>

<main class="content">

    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">

                <h2 class="header"><?php echo $LANG['page-profile-password']; ?></h2>

                <div class="row msg-form">

                    <form class="" action="/settings/password/" method="POST">

                        <?php

                        if (isset($_GET['error']) ) {

                            switch ($_GET['error']) {

                                case "true" : {

                                    ?>

                                    <div class="input-field col s12">
                                        <div class="card red lighten-2">
                                            <div class="card-content white-text">
                                                <span class="card-title"><?php echo $LANG['msg-error-unknown']; ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <?php

                                    break;
                                }

                                case "old_password" : {

                                    ?>

                                    <div class="input-field col s12">
                                        <div class="card red lighten-2">
                                            <div class="card-content white-text">
                                                <span class="card-title"><?php echo $LANG['msg-password-save-error']; ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <?php

                                    break;
                                }

                                case "new_password" : {

                                    ?>

                                    <div class="input-field col s12">
                                        <div class="card red lighten-2">
                                            <div class="card-content white-text">
                                                <span class="card-title"><?php echo $LANG['msg-password-incorrect']; ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <?php

                                    break;
                                }

                                default: {

                                    ?>

                                    <div class="input-field col s12">
                                        <div class="card teal lighten-2">
                                            <div class="card-content white-text">
                                                <span class="card-title"><?php echo $LANG['label-thanks']; ?></span>
                                                <p><?php echo $LANG['label-password-saved']; ?></p>
                                            </div>
                                        </div>
                                    </div>

                                    <?php

                                    break;
                                }
                            }
                        }
                        ?>

                        <input autocomplete="off" type="hidden" name="authenticity_token" value="<?php echo auth::getAuthenticityToken(); ?>">

                        <div class="input-field col s12">
                            <input id="old_password" type="password" class="validate valid" name="old_password" value="">
                            <label for="old_password" class="active"><?php echo $LANG['label-old-password']; ?></label>
                        </div>

                        <div class="input-field col s12">
                            <input id="new_password" type="password" class="validate valid" name="new_password" value="">
                            <label for="new_password" class="active"><?php echo $LANG['label-new-password']; ?></label>
                        </div>

                        <div class="input-field col s12">
                            <button type="submit" class="btn waves-effect waves-light teal btn-large" name=""><?php echo $LANG['action-save']; ?></button>
                        </div>

                    </form>
                </div>

	        </div>
        </div>
    </div>
</main>

        <?php

            include_once($_SERVER['DOCUMENT_ROOT']."/common/site_footer.inc.php");
        ?>

</body>
</html>