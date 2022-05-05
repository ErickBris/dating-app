<?php

    /*!
	 * ifsoft engine v1.0
	 *
	 * http://ifsoft.com.ua, http://ifsoft.co.uk
	 * qascript@ifsoft.co.uk, qascript@mail.ru
	 *
	 * Copyright 2012-2016 Demyanchuk Dmitry (https://vk.com/dmitry.demyanchuk)
	 */

    include_once($_SERVER['DOCUMENT_ROOT']."/core/init.inc.php");

    if (admin::isSession()) {

        header("Location: /admin/main.php");
    }

    $css_files = array();
    $page_title = APP_TITLE;

    include_once($_SERVER['DOCUMENT_ROOT']."/common/header.inc.php");
?>

<body>

<?php

    include_once($_SERVER['DOCUMENT_ROOT']."/common/admin_panel_topbar.inc.php");
?>

<div class="section no-pad-bot" id="index-banner">
    <div class="container" style="margin-top: 160px; margin-bottom: 340px;">
        <br><br>
        <h1 class="header center orange-text"><?php echo APP_NAME; ?></h1>
        <div class="row center">
            <h5 class="header col s12 light">Create your own Dating App now!</h5>
        </div>
        <div class="row center">
            <a href="<?php echo GOOGLE_PLAY_LINK; ?>">
                <button class="btn-large waves-effect waves-light blue">Download <?php echo APP_NAME; ?> from Google Play<i class="material-icons right">file_download</i></button>
            </a>
        </div>
        <br><br>

    </div>
</div>


<footer class="page-footer light-blue lighten-1" style="padding-top: 0px;">
    <div class="footer-copyright">
        <div class="container">
            <?php echo APP_TITLE; ?> © <?php echo APP_YEAR; ?> <a target="_blank" class="orange-text text-lighten-3" href="<?php echo COMPANY_URL; ?>"><?php echo APP_VENDOR; ?></a>
        </div>
    </div>
</footer>

<script src="/js/init.js"></script>

</body>
</html>