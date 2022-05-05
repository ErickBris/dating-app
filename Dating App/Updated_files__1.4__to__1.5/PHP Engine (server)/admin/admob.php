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

    $stats = new stats($dbo);
    $settings = new settings($dbo);
    $admin = new admin($dbo);

    $default = $settings->getIntValue("admob");

    if (isset($_GET['act'])) {

        $accessToken = isset($_GET['access_token']) ? $_GET['access_token'] : 0;
        $act = isset($_GET['act']) ? $_GET['act'] : '';

        if ($accessToken === admin::getAccessToken() && !APP_DEMO) {

            switch ($act) {

                case "global_off": {

                    $settings->setValue("admob", 0);

                    header("Location: /admin/admob.php");
                    break;
                }

                case "global_on": {

                    $settings->setValue("admob", 1);

                    header("Location: /admin/admob.php");
                    break;
                }

                case "on": {

                    $admin->setAdmobValueForAccounts(1);

                    header("Location: /admin/admob.php");
                    break;
                }

                case "off": {

                    $admin->setAdmobValueForAccounts(0);

                    header("Location: /admin/admob.php");
                    break;
                }

                default: {

                    header("Location: /admin/admob.php");
                    exit;
                }
            }
        }

    }

    $page_id = "admob";

    $error = false;
    $error_message = '';

    helper::newAuthenticityToken();

    $css_files = array("my.css");
    $page_title = "AdMob Settings";

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
                                <h4>AdMob Settings</h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col s12">
                                <div class="card blue-grey lighten-2">
                                    <div class="card-content white-text">
                                        <span class="card-title">In application changes will take effect during the next user authorization.</span>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col s12">
                            <table class="striped responsive-table">
                                <tbody>
                                    <tr>
                                        <th class="text-left">Type</th>
                                        <th>Count</th>
                                    </tr>
                                    <tr>
                                        <td class="text-left">AdMob active in accounts (On)</td>
                                        <td><?php echo $stats->getAccountsCountByAdmob(1); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">Accounts count with deactivated AdMob (Off)</td>
                                        <td><?php echo $stats->getAccountsCountByAdmob(0); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">Default AdMob value for new users</td>
                                        <td><?php if ($default == 1) {echo "On";} else {echo "Off"; } ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="row">
                            <div class="col s12" style="margin-top: 20px">
                                <a href="/admin/admob.php/?access_token=<?php echo admin::getAccessToken(); ?>&act=global_off">
                                    <button class="btn waves-effect waves-light teal">Turn Off AdMob for new users</button>
                                </a>
                                <a href="/admin/admob.php/?access_token=<?php echo admin::getAccessToken(); ?>&act=on">
                                    <button class="btn waves-effect waves-light teal">Turn On AdMob in all accounts</button>
                                </a>
                                <a href="/admin/admob.php/?access_token=<?php echo admin::getAccessToken(); ?>&act=off">
                                    <button class="btn waves-effect waves-light teal">Turn Off AdMob in all accounts</button>
                                </a>
                            </div>
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