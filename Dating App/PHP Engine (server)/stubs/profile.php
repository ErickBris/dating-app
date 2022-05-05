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

    $css_files = array("my.css", "account.css");
    $page_title = $profileInfo['fullname']." | ".APP_TITLE;

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

                <div class="row">
                    <div class="col s12 m6" style="margin: 0 auto; float: none; margin-top: 100px;">
                        <div class="card teal lighten-2">
                            <div class="card-content white-text">
                            <span class="card-title">
                                <strong><?php echo $LANG['label-warning']; ?></strong>
                                <br>
                                <?php

                                    if ($profileInfo['state'] == ACCOUNT_STATE_DISABLED) {

                                        // deactivated

                                        echo $LANG['label-account-disabled'];

                                    } else {

                                        echo $LANG['label-account-blocked'];
                                    }
                                ?>
                            </span>
                            </div>
                        </div>
                    </div>
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