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

    if (auth::isSession()) {

        header("Location: /messages.php");
    }

    $css_files = array();
    $page_title = APP_TITLE;

    include_once($_SERVER['DOCUMENT_ROOT']."/common/header.inc.php");
?>

<body>

<?php

    include_once($_SERVER['DOCUMENT_ROOT']."/common/site_topbar.inc.php");
?>

<div class="section no-pad-bot" id="index-banner">

    <div class="container" style="margin-top: 100px; margin-bottom: 70px;">
        <br><br>
        <h1 class="header center orange-text"><?php echo APP_NAME; ?></h1>

        <div class="row center">
            <h5 class="header col s12 light">Create your own <?php echo APP_NAME; ?> App now!</h5>
        </div>

        <div class="row center">
            <a href="<?php echo GOOGLE_PLAY_LINK; ?>">
                <button class="btn-large waves-effect waves-light teal">Download <?php echo APP_NAME; ?> from Google Play<i class="material-icons right">file_download</i></button>
            </a>
        </div>

        <div class="row col s12 center" style="margin-top: 150px;">

            <h5 style="padding: 20px;" class="header col s12 light"><?php echo $LANG['label-who-us']; ?></h5>

            <?php

                $app = new app($dbo);

                $load_count = 6;

                $result = $app->getUsersPreview($load_count);

                $items_count = count($result['items']);

                if ($items_count > 0) {

                    foreach ($result['items'] as $key => $value) {

                        draw($value, $LANG, $helper);
                    }

                    if ($items_count < $load_count) {

                        drawFake(($load_count + 1) - $items_count);
                    }

                } else {

                    drawFake($load_count++);
                }
            ?>

        </div>

        <br><br>
    </div>

</div>


<footer class="page-footer <?php echo SITE_THEME; ?>" style="padding-top: 0px;">
    <div class="footer-copyright">
        <div class="container">
            <span><?php echo APP_TITLE; ?> © <?php echo APP_YEAR; ?></span>
            <span style="margin-left: 10px;"><a class="grey-text text-lighten-4 modal-trigger" href="#lang-box"><?php echo $LANG['lang-name']; ?></a></span>
            <span class="right"><a class="grey-text text-lighten-4" target="_blank" href="<?php echo COMPANY_URL; ?>"><?php echo APP_VENDOR; ?></a></span>
        </div>
    </div>
</footer>

<div id="lang-box" class="modal">
    <div class="modal-content">
        <h4><?php echo $LANG['page-language']; ?></h4>
        <?php

        foreach ($LANGS as $name => $val) {

            echo "<a onclick=\"App.setLanguage('$val'); return false;\" class=\"waves-effect waves-teal btn-flat\" style=\"display: block\" href=\"javascript:void(0)\">$name</a>";
        }

        ?>
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-action modal-close waves-effect waves-teal btn-flat"><?php echo $LANG['action-close']; ?></a>
    </div>
</div>

<script src="/js/init.js"></script>

<script type="text/javascript">

    $('.modal-trigger').leanModal({
                dismissible: true, // Modal can be dismissed by clicking outside of the modal
                opacity: .5, // Opacity of modal background
                in_duration: 300, // Transition in duration
                out_duration: 200, // Transition out duration
                ready: function() {  }, // Callback for Modal open
                complete: function() { } // Callback for Modal close
        }
    );

    window.App || ( window.App = {} );

    App.setLanguage = function(language) {

        $.cookie("lang", language, { expires : 7, path: '/' });
        $('#lang-box').closeModal();
        location.reload();
    };

</script>

</body>
</html>

<?php

    function draw($profile, $LANG, $helper) {

        $profilePhotoUrl = "/img/profile_default_photo.png";

        if (strlen($profile['lowPhotoUrl']) != 0) {

            $profilePhotoUrl = $profile['lowPhotoUrl'];
        }

        ?>

            <div class="col s12 m2">
                <div class="card">
                    <div class="card-image">
                        <a href="javascript:void(0)">
                            <img src="<?php echo $profilePhotoUrl; ?>">
                        </a>
                    </div>
                </div>
            </div>

        <?php
    }

    function drawFake($count = 7) {

        if ($count > 7) $count = 7;

        for ($i = 1; $i < $count; $i++) {

            ?>
                <div class="col s12 m2">
                    <div class="card">
                        <div class="card-image">
                            <a href="javascript:void(0)">
                                <img src="/img/<?php echo $i; ?>.jpg">
                            </a>
                        </div>
                    </div>
                </div>
            <?php
        }
    }