<?php

    /*!
     * ifsoft.co.uk engine v1.0
     *
     * http://ifsoft.com.ua, http://ifsoft.co.uk
     * qascript@ifsoft.co.uk
     *
     * Copyright 2012-2018 Demyanchuk Dmitry (https://vk.com/dmitry.demyanchuk)
     */

    include_once($_SERVER['DOCUMENT_ROOT']."/core/init.inc.php");

    if (!admin::isSession()) {

        header("Location: /admin/login.php");
    }

    $stats = new stats($dbo);

    $page_id = "purchases";

    $error = false;
    $error_message = '';

    helper::newAuthenticityToken();

    $css_files = array("my.css");
    $page_title = "Last Purchases";

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
                                <h4>Last Purchases</h4>
                            </div>
                        </div>

                        <div class="col s12">

                            <?php

                                $reports = new report($dbo);

                                $result = $stats->getPurchases(0);

                                $inbox_loaded = count($result['items']);

                                if ($inbox_loaded != 0) {

                                ?>

                                <table class="bordered data-tables responsive-table">
                                    <tbody>
                                        <tr>
                                            <th class="text-left">Id</th>
                                            <th>Account</th>
                                            <th>Amount (Credits)</th>
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

    function draw($item)
    {
        ?>

        <tr>
            <td class="text-left"><?php echo $item['id']; ?></td>
            <td><?php echo "<a href=\"/admin/profile.php?id={$item['toUserId']}\">Account Id ({$item['toUserId']})</a>"; ?></td>
            <td><?php echo $item['amount']; ?></td>
            <td><?php echo date("Y-m-d H:i:s", $item['createAt']); ?></td>
        </tr>

        <?php
    }