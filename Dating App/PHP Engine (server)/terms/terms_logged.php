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

    $css_files = array("my.css", "account.css");
    $page_title = $LANG['page-terms']." | ".APP_TITLE;

    include_once($_SERVER['DOCUMENT_ROOT'] . "/common/site_header.inc.php");

?>


<body>

<?php

    include_once($_SERVER['DOCUMENT_ROOT']."/common/site_topbar.inc.php");
?>

<main class="content">

    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">

                <h2 class="header"><?php echo $LANG['page-terms']; ?></h2>

                <?php

                    include_once("terms_content.php");
                ?>

            </div>
        </div>
    </div>
</main>

<?php

    include_once($_SERVER['DOCUMENT_ROOT']."/common/site_footer.inc.php");
?>

</body>
</html>