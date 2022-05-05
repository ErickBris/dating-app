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

    $page_id = "update";

    include_once($_SERVER['DOCUMENT_ROOT']."/core/initialize.inc.php");

    $update = new update($dbo);
    $update->addColumnToChatsTable();
    $update->addColumnToChatsTable2();

    $update->addColumnToAdminsTable();

    $update->addColumnToUsersTable15();

    $update->addColumnToGalleryTable1();
    $update->addColumnToGalleryTable2();
    $update->addColumnToGalleryTable3();

    $update->addColumnToUsersTable1();
    $update->addColumnToUsersTable2();
    $update->addColumnToUsersTable3();
    $update->addColumnToUsersTable4();
    $update->addColumnToUsersTable5();

    // For version 2.7

    $update->addColumnToUsersTable6();

    // Only For version 2.8

    $update->updateUsersTable();

    // For version 3.0

    $update->addColumnToUsersTable7();
    $update->addColumnToUsersTable8();
    $update->addColumnToUsersTable9();
    $update->addColumnToUsersTable10();

    // For version 3.1

    $update->addColumnToUsersTable11();
    $update->addColumnToUsersTable12();

    // For version 3.2

    $update->addColumnToUsersTable14();

    // For version 3.4

    $update->addColumnToMessagesTable1();

    // Add standard stickers

    $stickers = new sticker($dbo);

    if ($stickers->db_getMaxId() < 1) {

        for ($i = 1; $i < 28; $i++) {

            $stickers->db_add(APP_URL."/stickers/".$i.".png");

        }
    }

    unset($stickers);

    unset($update);

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
                <div class="col s12 m6" style="margin: 0 auto; float: none; margin-top: 100px;">
                    <div class="card teal lighten-2">
                        <div class="card-content white-text">
                                <span class="card-title">
                                    <strong>Success!</strong>
                                    <br>
                                    Your MySQL version: <?php print mysql_get_client_info(); ?>
                                    <br>
                                    Database refactoring success!
                                </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="/js/init.js"></script>

</body>
</html>