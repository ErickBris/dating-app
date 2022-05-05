<?php

    /*!
     * ifsoft.co.uk v1.0
     *
     * http://ifsoft.com.ua, http://ifsoft.co.uk
     * raccoonsquare@gmail.com
     *
     * Copyright 2012-2018 Demyanchuk Dmitry (raccoonsquare@gmail.com)
     */

    include_once($_SERVER['DOCUMENT_ROOT']."/core/init.inc.php");

    if (!$auth->authorize(auth::getCurrentUserId(), auth::getAccessToken())) {

        header('Location: /');
    }

    $accountId = auth::getCurrentUserId();

    $error = false;

    if (!empty($_POST)) {

        $token = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';

        $password = isset($_POST['pswd']) ? $_POST['pswd'] : '';

        $password = helper::clearText($password);
        $password = helper::escapeText($password);

        if (auth::getAuthenticityToken() !== $token) {

            $error = true;
        }

        if ( !$error ) {

            // Remove All Medias

            $photos = new photos($dbo);
            $photos->setRequestFrom($accountId);
            $photos->removeAll();
            unset($photos);

            $account = new account($dbo, $accountId);

            $result = array();

            // Remove Avatar

            $photos = array("error" => false,
                            "originPhotoUrl" => "",
                            "normalPhotoUrl" => "",
                            "bigPhotoUrl" => "",
                            "lowPhotoUrl" => "");

            $account->setPhoto($photos);

            // Unset Facebook Id

            $account->setFacebookId("");

            // Unset Email

            $account->setEmail("");

            // Deactivate Account

            $result = $account->deactivation($password);

            if ($result['error'] === false) {

                header("Location: /logout.php/?access_token=".auth::getAccessToken());
                exit;
            }
        }

        header("Location: /settings/deactivation/?error=true");
        exit;
    }

    auth::newAuthenticityToken();

    $page_id = "settings_deactivation";

    $css_files = array("my.css", "account.css");
    $page_title = $LANG['page-profile-deactivation']." | ".APP_TITLE;

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

                    <form class="" action="/settings/deactivation/" method="POST">

                        <div class="input-field col s12">
                            <div class="card teal lighten-2">
                                <div class="card-content white-text">
                                    <span class="card-title"><?php echo $LANG['page-profile-deactivation-sub-title2']; ?></span>
                                </div>
                            </div>
                        </div>

                        <?php

                        if (isset($_GET['error']) ) {

                            ?>

                            <div class="input-field col s12">
                                <div class="card red lighten-2">
                                    <div class="card-content white-text">
                                        <span class="card-title"><?php echo $LANG['msg-error-deactivation']; ?></span>
                                    </div>
                                </div>
                            </div>

                            <?php
                        }
                        ?>

                        <input autocomplete="off" type="hidden" name="authenticity_token" value="<?php echo auth::getAuthenticityToken(); ?>">

                        <div class="input-field col s12">
                            <input id="pswd" type="password" class="validate valid" name="pswd" value="">
                            <label for="pswd" class="active"><?php echo $LANG['label-password']; ?></label>
                        </div>

                        <div class="input-field col s12">
                            <button type="submit" class="btn waves-effect waves-light teal btn-large" name=""><?php echo $LANG['action-deactivation-profile']; ?></button>
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