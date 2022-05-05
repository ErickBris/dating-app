<?php

    /*!
     * ifsoft.co.uk v1.0
     *
     * http://ifsoft.com.ua, http://ifsoft.co.uk
     * qascript@ifsoft.co.uk
     *
     * Copyright 2012-2016 Demyanchuk Dmitry (https://vk.com/dmitry.demyanchuk)
     */

?>

    <footer class="page-footer content <?php echo SITE_THEME; ?>">
        <div class="footer-copyright">
            <span><?php echo APP_TITLE; ?> © <?php echo APP_YEAR; ?></span>
            <span style="margin-left: 10px;"><a class="grey-text text-lighten-4 modal-trigger" href="#lang-box"><?php echo $LANG['lang-name']; ?></a></span>
            <span class="right"><a class="grey-text text-lighten-4" target="_blank" href="<?php echo COMPANY_URL; ?>"><?php echo APP_VENDOR; ?></a></span>
        </div>
    </footer>

    <div id="lang-box" class="modal">
        <div class="modal-content">
            <h4><?php echo $LANG['page-language']; ?></h4>
            <?php

            foreach ($LANGS as $name => $val) {

                echo "<a onclick=\"App.setLanguage('$val'); return false;\" class=\"waves-effect waves-ripple btn-flat\" style=\"display: block\" href=\"javascript:void(0)\">$name</a>";
            }

            ?>
        </div>
        <div class="modal-footer">
            <a href="#!" class=" modal-action modal-close waves-effect waves-ripple btn-flat"><?php echo $LANG['action-close']; ?></a>
        </div>
    </div>

    <script type="text/javascript" src="/js/jquery-2.1.1.js"></script>

    <script type="text/javascript" src="/js/jquery.cookie.js"></script>

    <script type="text/javascript" src="/js/materialize.min.js"></script>

    <script src="/js/init.js"></script>

    <script src="/js/common.js"></script>

    <script type="text/javascript">

        <?php

            if (auth::isSession()) {

                ?>
                    App.init();
                <?php
            }

        ?>

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

    <script type="text/javascript">

        var options = {

            pageId: "<?php echo $page_id; ?>"
        }

        var account = {

            id: "<?php echo auth::getCurrentUserId(); ?>",
            username: "<?php echo auth::getCurrentUserLogin(); ?>",
            accessToken: "<?php echo auth::getAccessToken(); ?>"
        }

    </script>