<?php

    /*!
     * ifsoft.co.uk v1.0
     *
     * http://ifsoft.com.ua, http://ifsoft.co.uk
     * qascript@ifsoft.co.uk
     *
     * Copyright 2012-2018 Demyanchuk Dmitry (https://vk.com/dmitry.demyanchuk)
     */

    include_once($_SERVER['DOCUMENT_ROOT']."/core/init.inc.php");

    if (!$auth->authorize(auth::getCurrentUserId(), auth::getAccessToken())) {

        header('Location: /');
    }

    $profile = new profile($dbo, auth::getCurrentUserId());

    $refsys = new refsys($dbo);
    $refsys->setRequestFrom(auth::getCurrentUserId());

    $items_all = $refsys->getReferralsCount(auth::getCurrentUserId());
    $items_loaded = 0;

    if (!empty($_POST)) {

        $itemId = isset($_POST['itemId']) ? $_POST['itemId'] : '';
        $loaded = isset($_POST['loaded']) ? $_POST['loaded'] : '';

        $itemId = helper::clearInt($itemId);
        $loaded = helper::clearInt($loaded);

        $result = $refsys->getReferrals($itemId);

        $items_loaded = count($result['items']);

        $result['items_loaded'] = $items_loaded + $loaded;
        $result['items_all'] = $items_all;

        if ($items_loaded != 0) {

            ob_start();

            foreach ($result['items'] as $key => $value) {

                draw($value, $LANG, $helper);
            }

            if ($result['items_loaded'] < $items_all) {

                ?>

                    <div class="row more_cont">
                        <div class="col s12">
                            <a href="javascript:void(0)" onclick="Referrals.moreItems('<?php echo $result['itemId']; ?>'); return false;">
                                <button class="btn waves-effect waves-light teal more_link"><?php echo $LANG['action-more']; ?></button>
                            </a>
                        </div>
                    </div>

            <?php
            }

            $result['html'] = ob_get_clean();
        }

        echo json_encode($result);
        exit;
    }

    $page_id = "referrals";

    $css_files = array("my.css", "account.css");
    $page_title = $LANG['page-referrals']." | ".APP_TITLE;

    include_once($_SERVER['DOCUMENT_ROOT']."/common/site_header.inc.php");

?>

<body>

    <?php

        include_once($_SERVER['DOCUMENT_ROOT']."/common/site_topbar.inc.php");
    ?>

<main class="content">

    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">

                <h2 class="header"><?php echo $LANG['page-referrals']; ?></h2>
                <h5><?php echo $LANG['page-referrals-label-id']; ?> <?php echo auth::getCurrentUserId(); ?></h5>
                <p><?php echo $LANG['page-referrals-label-hint'] ?></p>
                <p><?php echo $LANG['page-referrals-label-hint2']; ?></p>

                            <?php

                                $result = $refsys->getReferrals(0);

                                $items_loaded = count($result['items']);

                                    if ($items_loaded != 0) {

                                        foreach ($result['items'] as $key => $value) {

                                            ?>

                                                <ul class="collection">

                                            <?php

                                                draw($value, $LANG, $helper);

                                            ?>

                                                </ul>

                                            <?php
                                        }

                                        if ($items_all > 20) {

                                            ?>

                                                <div class="row more_cont">
                                                    <div class="col s12">
                                                        <a href="javascript:void(0)" onclick="Referrals.moreItems('<?php echo $result['itemId']; ?>'); return false;">
                                                            <button class="btn waves-effect waves-light teal more_link"><?php echo $LANG['action-more']; ?></button>
                                                        </a>
                                                    </div>
                                                </div>

                                            <?php
                                        }

                                    } else {

                                        ?>

                                            <div class="row">
                                                <div class="col s12">
                                                    <div class="card blue-grey darken-1">
                                                        <div class="card-content white-text">
                                                            <span class="card-title"><?php echo $LANG['label-empty-list']; ?></span>
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
</main>

        <?php

            include_once($_SERVER['DOCUMENT_ROOT']."/common/site_footer.inc.php");
        ?>

        <script type="text/javascript">

            var items_all = <?php echo $items_all; ?>;
            var items_loaded = <?php echo $items_loaded; ?>;

            window.Referrals || ( window.Referrals = {} );

            Referrals.moreItems = function (offset) {

                $.ajax({
                    type: 'POST',
                    url: '/settings/referrals',
                    data: 'itemId=' + offset + "&loaded=" + inbox_loaded,
                    dataType: 'json',
                    timeout: 30000,
                    success: function(response){

                        $('div.more_cont').remove();

                        if (response.hasOwnProperty('html')){

                            $("ul.collection").append(response.html);
                        }

                        inbox_loaded = response.inbox_loaded;
                        inbox_all = response.inbox_all;
                    },
                    error: function(xhr, type){

                        $('a.more_link').show();
                        $('a.loading_link').hide();
                    }
                });
            };

        </script>

        <script type="text/javascript" src="/js/chat.js"></script>

</body>
</html>

<?php

    function draw($item, $LANG, $helper) {

        $time = new language(NULL, $LANG['lang-code']);

        ?>

        <li class="collection-item avatar" data-id="<?php echo $item['id']; ?>">
            <a href="/profile.php?id=<?php echo $item['id']; ?>"><img src="<?php if (strlen($item['normalPhotoUrl']) != 0) { echo $item['normalPhotoUrl']; } else { echo "/img/profile_default_photo.png"; } ?>" alt="" class="circle"></a>
            <span class="title"><?php echo $item['fullname']; ?></span>
            <p>
            <span>@<?php echo $item['username']; ?></span>
            <br>
            <span class="time"><?php echo $time->timeAgo($item['createAt']); ?></span>
            </p>
        </li>

        <?php
    }

?>