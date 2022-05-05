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

    if (isset($_GET['act'])) {

        $accessToken = isset($_GET['access_token']) ? $_GET['access_token'] : 0;
        $act = isset($_GET['act']) ? $_GET['act'] : '';

        if ($accessToken === admin::getAccessToken() && !APP_DEMO) {

            switch($act) {

                case "delete": {

                    $reports = new report($dbo);
                    $reports->removeAllProfilesReports();
                    unset($reports);

                    header("Location: /admin/profile_reports.php");
                    exit;

                    break;
                }

                default: {

                    header("Location: /admin/profile_reports.php");
                    exit;

                    break;
                }
            }
        }
    }

    $page_id = "reports";

    $error = false;
    $error_message = '';

    helper::newAuthenticityToken();

    $css_files = array("my.css");
    $page_title = "Profile Reports";

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
                            <div class="col s12">
                                <h4>Profile Reports (Latest reports)</h4>
                            </div>
                        </div>

                        <div class="col s12">

                            <?php

                                $reports = new report($dbo);

                                $result = $reports->getProfilesReports(50);

                                $inbox_loaded = count($result['items']);

                                if ($inbox_loaded != 0) {

                                ?>

                                <div class="row">
                                    <div class="col s12">
                                        <a href="/admin/profile_reports.php/?act=delete&access_token=<?php echo admin::getAccessToken(); ?>">
                                            <button class="btn waves-effect waves-light teal">Delete all reports<i class="material-icons right">delete</i></button>
                                        </a>
                                    </div>
                                </div>

                                <table class="bordered data-tables responsive-table">
                                    <tbody>
                                        <tr>
                                            <th class="text-left">Id</th>
                                            <th>From account</th>
                                            <th>To account</th>
                                            <th>Abuse</th>
                                            <th>Date</th>
                                        </tr>

                                    <?php

                                    foreach ($result['items'] as $key => $value) {

                                        draw($value);
                                    }

                                    ?>

                                    </tbody>
                                </table>

                                <?php

                                    } else {

                                        ?>

                                        <div class="row">
                                            <div class="col s12">
                                                <div class="card blue-grey darken-1">
                                                    <div class="card-content white-text">
                                                        <span class="card-title">List is empty.</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <?php
                                    }
                                    ?>
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

<?php

    function draw($user)
    {
        ?>

        <tr>
            <td class="text-left"><?php echo $user['id']; ?></td>
            <td><?php if ($user['abuseFromUserId'] == 0) {echo "-";} else {echo "<a href=\"/admin/profile.php/?id={$user['abuseFromUserId']}\">From profile Id ({$user['abuseFromUserId']})</a>";} ?></td>
            <td><?php echo "<a href=\"/admin/profile.php/?id={$user['abuseToUserId']}\">To profile Id ({$user['abuseToUserId']})</a>"; ?></td>
            <td>
                <?php

                    switch ($user['abuseId']) {

                        case 0: {

                            echo "This is spam.";

                            break;
                        }

                        case 1: {

                            echo "Hate Speech or violence.";

                            break;
                        }

                        case 2: {

                            echo "Nudity or Pornography.";

                            break;
                        }

                        default: {

                            echo "Fake profile.";

                            break;
                        }
                    }
                ?>
            </td>
            <td><?php echo $user['date']; ?></td>
        </tr>

        <?php
    }