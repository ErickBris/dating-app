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

	$error = false;
    $error_message = '';

    $account = new account($dbo, auth::getCurrentUserId());
    $fb_id = $account->getFacebookId();

    if (!empty($_POST)) {

    }

	$page_id = "services";

    $css_files = array("my.css", "account.css");
    $page_title = $LANG['page-services']." | ".APP_TITLE;

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

                <h2 class="header"><?php echo $LANG['page-services']; ?></h2>

                                <?php

                                    $msg = $LANG['page-services-sub-title'];

                                    if (isset($_GET['status'])) {

                                        switch($_GET['status']) {

                                            case "connected": {

                                                $msg = $LANG['label-services-facebook-connected'];
                                                break;
                                            }

                                            case "error": {

                                                $msg = $LANG['label-services-facebook-error'];
                                                break;
                                            }

                                            case "disconnected": {

                                                $msg = $LANG['label-services-facebook-disconnected'];
                                                break;
                                            }

                                            default: {

                                                $msg = $LANG['page-services-sub-title'];
                                                break;
                                            }
                                        }
                                    }
                                ?>

                <div class="input-field col s12">
                    <div class="card teal lighten-2">
                        <div class="card-content white-text">
                            <span class="card-title"><?php echo $msg; ?></span>
                        </div>
                    </div>
                </div>

                <div class="input-field col s12">
                    <ul class="collection">
                        <li class="collection-item avatar">
                            <img src="/img/i_facebook.png" alt="" class="circle">

                                <?php

                                if ($fb_id == 0) {

                                    ?>
                                        <span class="title"><?php echo $LANG['page-services-sub-title'];; ?></span>
                                        <p>
                                            <a href="/facebook/connect/?access_token=<?php echo auth::getAccessToken(); ?>"><?php echo $LANG['action-connect-facebook']; ?></a>
                                        </p>
                                    <?php

                                } else {

                                    ?>
                                        <span class="title"><?php echo $LANG['label-connected-with-facebook']; ?></span>
                                        <p>
                                            <a href="/facebook/disconnect/?access_token=<?php echo auth::getAccessToken(); ?>"><?php echo $LANG['action-disconnect']; ?></a>
                                        </p>
                                    <?php
                                }
                                ?>
                            </p>
                        </li>
                    </ul>
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