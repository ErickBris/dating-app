<?php
    /**
     * Created by PhpStorm.
     * User: Администратор
     * Date: 05.06.2016
     * Time: 13:11
     */

    /*!
     * ifsoft.co.uk v1.0
     *
     * http://ifsoft.com.ua, http://ifsoft.co.uk
     * qascript@ifsoft.co.uk
     *
     * Copyright 2012-2016 Demyanchuk Dmitry (https://vk.com/dmitry.demyanchuk)
     */

    $page_id = "terms";

    $css_files = array("my.css");
    $page_title = $LANG['page-terms']." | ".APP_TITLE;

    include_once($_SERVER['DOCUMENT_ROOT'] . "/common/header.inc.php");

?>


<body>

<?php

include_once($_SERVER['DOCUMENT_ROOT'] . "/common/site_topbar.inc.php");
?>

<div class="section no-pad-bot" id="index-banner">
    <div class="container">

        <div class="row">
            <div class="card ">
                <div class="card-content black-text">
                    <span class="card-title"><?php echo $LANG['page-terms']; ?></span>

                    <?php

                        include_once("terms_content.php");
                    ?>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="/js/init.js"></script>

</body>
</html>