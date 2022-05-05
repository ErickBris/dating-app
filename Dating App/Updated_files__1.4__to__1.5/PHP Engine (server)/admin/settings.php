<?php

    /*!
     * ifsoft.co.uk engine v1.0
     *
     * http://ifsoft.com.ua, http://ifsoft.co.uk
     * qascript@ifsoft.co.uk
     *
     * Copyright 2012-2016 Demyanchuk Dmitry (https://vk.com/dmitry.demyanchuk)
     */

    include_once($_SERVER['DOCUMENT_ROOT']."/core/init.inc.php");

    if (!admin::isSession()) {

        header("Location: /admin/login.php");
    }

    if (!empty($_POST)) {

        $authToken = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';
        $current_passw = isset($_POST['current_passw']) ? $_POST['current_passw'] : '';
        $new_passw = isset($_POST['new_passw']) ? $_POST['new_passw'] : '';

        $current_passw = helper::clearText($current_passw);
        $current_passw = helper::escapeText($current_passw);

        $new_passw = helper::clearText($new_passw);
        $new_passw = helper::escapeText($new_passw);

        if ($authToken === helper::getAuthenticityToken() && !APP_DEMO) {

            $admin = new admin($dbo);
            $admin->setId(admin::getCurrentAdminId());

            $result = $admin->setPassword($current_passw, $new_passw);

            if ($result['error'] === false) {

                header("Location: /admin/settings.php/?result=success");
                exit;

            } else {

                header("Location: /admin/settings.php/?result=error");
                exit;
            }
        }

        header("Location: /admin/settings.php");
        exit;
    }

    $stats = new stats($dbo);

    $page_id = "settings";

    $error = false;
    $error_message = '';

    helper::newAuthenticityToken();

    $css_files = array("my.css");
    $page_title = "Settings";

    include_once($_SERVER['DOCUMENT_ROOT']."/common/header.inc.php");
?>

<body>

<?php

    include_once($_SERVER['DOCUMENT_ROOT']."/common/admin_panel_topbar.inc.php");
?>

<main class="content">
    <div class="row">
        <div class="col s12 m12 l12">

            <?php

                include_once($_SERVER['DOCUMENT_ROOT']."/common/admin_panel_banner.inc.php");
            ?>

            <div class="card">
                <div class="card-content">
                    <div class="row">
                        <div class="col s12">

                            <div class="row">
                                <div class="col s2">
                                    <h4>Settings</h4>
                                </div>
                            </div>

                            <?php

                            if (isset($_GET['result'])) {

                                $result = isset($_GET['result']) ? $_GET['result'] : '';

                                switch ($result) {

                                    case "success": {

                                        ?>

                                        <div class="card teal">
                                            <div class="card-content white-text">
                                                <span class="card-title">Thanks!</span>
                                                <p>New password is saved.</p>
                                            </div>
                                        </div>

                                        <?php

                                        break;
                                    }

                                    case "error": {

                                        ?>

                                        <div class="card red">
                                            <div class="card-content white-text">
                                                <span class="card-title">Error!</span>
                                                <p>Invalid current password or incorrectly enter a new password.</p>
                                            </div>
                                        </div>

                                        <?php

                                        break;
                                    }

                                    default: {

                                        break;
                                    }
                                }
                            }
                            ?>

                            <form method="post" action="/admin/settings.php">

                                <input type="hidden" name="authenticity_token" value="<?php echo helper::getAuthenticityToken(); ?>">

                                <div class="row">

                                    <div class="input-field col s12">
                                        <input type="password" class="validate" name="current_passw" id="current_passw">
                                        <label for="current_passw">Current Password</label>
                                    </div>

                                    <div class="input-field col s12">
                                        <input type="password" class="validate" name="new_passw" id="new_passw">
                                        <label for="new_passw">New Password</label>
                                    </div>

                                </div>
                                <button type="submit" class="btn waves-effect waves-light" name="" >Save</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php

    include_once($_SERVER['DOCUMENT_ROOT']."/common/admin_panel_footer.inc.php");
?>

<script type="text/javascript">


</script>

</body>
</html>
