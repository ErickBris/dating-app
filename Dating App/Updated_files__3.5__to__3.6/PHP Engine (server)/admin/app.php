<?php

    /*!
     * ifsoft.co.uk engine v1.1
     *
     * http://ifsoft.com.ua, http://ifsoft.co.uk
     * raccoonsquare@gmail.com
     *
     * Copyright 2012-2018 Demyanchuk Dmitry (raccoonsquare@gmail.com)
     */

    include_once($_SERVER['DOCUMENT_ROOT']."/core/init.inc.php");

    if (!admin::isSession()) {

        header("Location: /admin/login.php");
    }

    $stats = new stats($dbo);
    $settings = new settings($dbo);
    $admin = new admin($dbo);

    $allowFacebookAuthorization = 1;
    $allowMultiAccountsFunction = 1;

    $defaultFreeMessagesCount = 150;
    $defaultReferralBonus = 10;
    $defaultBalance = 10;

    if (!empty($_POST)) {

        $authToken = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';

        $allowFacebookAuthorization_checkbox = isset($_POST['allowFacebookAuthorization']) ? $_POST['allowFacebookAuthorization'] : '';
        $allowMultiAccountsFunction_checkbox = isset($_POST['allowMultiAccountsFunction']) ? $_POST['allowMultiAccountsFunction'] : '';

        $defaultFreeMessagesCount = isset($_POST['defaultFreeMessagesCount']) ? $_POST['defaultFreeMessagesCount'] : 150;
        $defaultReferralBonus = isset($_POST['defaultReferralBonus']) ? $_POST['defaultReferralBonus'] : 10;
        $defaultBalance = isset($_POST['defaultBalance']) ? $_POST['defaultBalance'] : 10;

        if ($authToken === helper::getAuthenticityToken() && !APP_DEMO) {

            if ($allowFacebookAuthorization_checkbox === "on") {

                $allowFacebookAuthorization = 1;

            } else {

                $allowFacebookAuthorization = 0;
            }

            if ($allowMultiAccountsFunction_checkbox === "on") {

                $allowMultiAccountsFunction = 1;

            } else {

                $allowMultiAccountsFunction = 0;
            }

            $defaultBalance = helper::clearInt($defaultBalance);
            $defaultReferralBonus = helper::clearInt($defaultReferralBonus);
            $defaultFreeMessagesCount = helper::clearInt($defaultFreeMessagesCount);

            if ($defaultBalance < 0) {

                $defaultBalance = 0;
            }

            if ($defaultReferralBonus < 0) {

                $defaultReferralBonus = 0;
            }

            if ($defaultFreeMessagesCount < 0) {

                $defaultFreeMessagesCount = 0;
            }

            $settings->setValue("allowFacebookAuthorization", $allowFacebookAuthorization);
            $settings->setValue("allowMultiAccountsFunction", $allowMultiAccountsFunction);

            $settings->setValue("defaultBalance", $defaultBalance);
            $settings->setValue("defaultReferralBonus", $defaultReferralBonus);
            $settings->setValue("defaultFreeMessagesCount", $defaultFreeMessagesCount);
        }
    }

    $config = $settings->get();

    $arr = array();

    $arr = $config['allowFacebookAuthorization'];
    $allowFacebookAuthorization = $arr['intValue'];

    $arr = $config['allowMultiAccountsFunction'];
    $allowMultiAccountsFunction = $arr['intValue'];

    $arr = $config['defaultBalance'];
    $defaultBalance = $arr['intValue'];

    $arr = $config['defaultReferralBonus'];
    $defaultReferralBonus = $arr['intValue'];

    $arr = $config['defaultFreeMessagesCount'];
    $defaultFreeMessagesCount = $arr['intValue'];

    $page_id = "app";

    $error = false;
    $error_message = '';

    helper::newAuthenticityToken();

    $css_files = array("my.css", "admin.css");
    $page_title = "App Settings";

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
                            <div class="col s6">
                                <h4>App Settings</h4>
                            </div>
                        </div>


                        <div class="col s12">

                            <form action="/admin/app.php" method="post">

                                <input type="hidden" name="authenticity_token" value="<?php echo helper::getAuthenticityToken(); ?>">

                                <p style="display: none">
                                    <input type="checkbox" name="allowFacebookAuthorization" id="allowFacebookAuthorization" <?php if ($allowFacebookAuthorization == 1) echo "checked=\"checked\"";  ?> />
                                    <label for="allowFacebookAuthorization">Allow registration/authorization via Facebook</label>
                                </p>

                                <p>
                                    <input type="checkbox" name="allowMultiAccountsFunction" id="allowMultiAccountsFunction" <?php if ($allowMultiAccountsFunction == 1) echo "checked=\"checked\"";  ?> />
                                    <label for="allowMultiAccountsFunction">Enable creation of multi-accounts</label>
                                </p>

                                <p class="padding_top_15">
                                    <input id="defaultBalance" type="number" size="4" name="defaultBalance" value="<?php echo $defaultBalance; ?>">
                                    <label for="defaultBalance" class="active">Balance of the user after registration (credits)</label>
                                </p>

                                <p class="padding_top_15">
                                    <input id="defaultReferralBonus" type="number" size="4" name="defaultReferralBonus" value="<?php echo $defaultReferralBonus; ?>">
                                    <label for="defaultReferralBonus" class="active">Number of credits for referral registration</label>
                                </p>

                                <p class="padding_top_15">
                                    <input id="defaultFreeMessagesCount" type="number" size="4" name="defaultFreeMessagesCount" value="<?php echo $defaultFreeMessagesCount; ?>">
                                    <label for="defaultFreeMessagesCount" class="active">Number of free messages for the user</label>
                                </p>

                                <p class="padding_top_30">
                                    <button class="btn waves-effect waves-light teal">Save</button>
                                </p>

                            </form>
                        </div>

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