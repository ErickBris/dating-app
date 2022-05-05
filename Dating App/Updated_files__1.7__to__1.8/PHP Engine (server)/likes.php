<?php

    /*!
     * ifsoft.co.uk v1.0
     *
     * http://ifsoft.com.ua, http://ifsoft.co.uk
     * qascript@ifsoft.co.uk
     *
     * Copyright 2012-2016 Demyanchuk Dmitry (https://vk.com/dmitry.demyanchuk)
     */

    include_once($_SERVER['DOCUMENT_ROOT']."/core/init.inc.php");

    if (!$auth->authorize(auth::getCurrentUserId(), auth::getAccessToken())) {

        header('Location: /');
    }

    $account = new account($dbo, auth::getCurrentUserId());
    $profile = new profile($dbo, auth::getCurrentUserId());

    $profileInfo = $profile->get();

    $account->setLastActive();

    $items_all = $profileInfo['likesCount'];
    $items_loaded = 0;

    if (!empty($_POST)) {

        $itemId = isset($_POST['itemId']) ? $_POST['itemId'] : 0;
        $loaded = isset($_POST['loaded']) ? $_POST['loaded'] : 0;

        $itemId = helper::clearInt($itemId);
        $loaded = helper::clearInt($loaded);

        $result = $profile->getFans($itemId);

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
                            <a href="javascript:void(0)" onclick="Likes.moreItems('<?php echo $result['itemId']; ?>'); return false;">
                                <button class="btn waves-effect waves-light <?php echo SITE_THEME; ?> more_link"><?php echo $LANG['action-more']; ?></button>
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

    $page_id = "likes";

    $css_files = array("my.css", "account.css");
    $page_title = $LANG['page-profile-likes']." | ".APP_TITLE;

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

                <h2 class="header"><?php echo $LANG['page-profile-likes']; ?></h2>

                        <div class="col s12 m12 l12 left likes_cont" style="padding-right: 0; padding-left: 0;">

                                <?php

                                    $result = $profile->getFans(0);

                                    $items_loaded = count($result['items']);

                                    if ($items_loaded != 0) {

                                        foreach ($result['items'] as $key => $value) {

                                            draw($value, $LANG, $helper);
                                        }

                                        if ($items_all > 20) {

                                            ?>

                                            <div class="row more_cont">
                                                <div class="col s12">
                                                    <a href="javascript:void(0)" onclick="Likes.moreItems('<?php echo $result['itemId']; ?>'); return false;">
                                                        <button class="btn waves-effect waves-light <?php echo SITE_THEME; ?> more_link"><?php echo $LANG['action-more']; ?></button>
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
    </div>
</main>

        <?php

            include_once($_SERVER['DOCUMENT_ROOT']."/common/site_footer.inc.php");
        ?>

        <script type="text/javascript">

            var items_all = <?php echo $items_all; ?>;
            var items_loaded = <?php echo $items_loaded; ?>;

            window.Likes || ( window.Likes = {} );

            Likes.moreItems = function (offset) {

                $.ajax({
                    type: 'POST',
                    url: '/likes.php',
                    data: 'itemId=' + offset + "&loaded=" + items_loaded,
                    dataType: 'json',
                    timeout: 30000,
                    success: function(response){

                        $('div.more_cont').remove();

                        if (response.hasOwnProperty('html')){

                            $("div.likes_cont").append(response.html);
                        }

                        items_loaded = response.items_loaded;
                        items_all = response.items_all;
                    },
                    error: function(xhr, type){

                    }
                });
            };

        </script>

        <script type="text/javascript" src="/js/chat.js"></script>

</body>
</html>

<?php

    function draw($profile, $LANG, $helper) {

        $profilePhotoUrl = "/img/profile_default_photo.png";

        if (strlen($profile['lowPhotoUrl']) != 0) {

            $profilePhotoUrl = $profile['lowPhotoUrl'];
        }

        ?>

            <div class="col s12 m3">
              <div class="card">
                <div class="card-image">
                    <a href="/profile.php/?id=<?php echo $profile['id']; ?>">
                        <img src="<?php echo $profilePhotoUrl; ?>">
                  </a>
                </div>
                <div class="card-content center-align">
                    <p><h6 style="white-space: nowrap; text-overflow: ellipsis; overflow: hidden;"><?php echo $profile['fullname']; ?></h6></p>
                    <p>@<?php echo $profile['username']; ?></p>
                    <?php
                            if ($profile['online']) {

                                echo "<p class=\"teal-text\">Online</p>";

                            } else {

                                echo "<p>Offline</p>";
                            }
                        ?>
                    </p>
                </div>
              </div>
            </div>

        <?php
    }

?>