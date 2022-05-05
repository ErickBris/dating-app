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

    $profile = new account($dbo, auth::getCurrentUserId());

    if (isset($_GET['action'])) {

        $notifications = new notify($dbo);
        $notifications->setRequestFrom(auth::getCurrentUserId());

        $notifications_count = $notifications->getNewCount($profile->getLastNotifyView());

        echo $notifications_count;
        exit;
    }

    $profile->setLastActive();

    $profile->setLastNotifyView();

    $notifications = new notify($dbo);
    $notifications->setRequestFrom(auth::getCurrentUserId());

    $items_all = $notifications->getAllCount();
    $items_loaded = 0;

    if (!empty($_POST)) {

        $notifyId = isset($_POST['notifyId']) ? $_POST['notifyId'] : 0;
        $loaded = isset($_POST['loaded']) ? $_POST['loaded'] : 0;

        $notifyId = helper::clearInt($notifyId);
        $loaded = helper::clearInt($loaded);

        $result = $notifications->getAll($notifyId);

        $items_loaded = count($result['notifications']);

        $result['items_loaded'] = $items_loaded + $loaded;
        $result['items_all'] = $items_all;

        if ($items_loaded != 0) {

            ob_start();

            foreach ($result['notifications'] as $key => $value) {

                draw($value, $LANG, $helper);
            }

            if ($result['items_loaded'] < $items_all) {

                ?>

                <div class="row more_cont">
                    <div class="col s12">
                        <a href="javascript:void(0)" onclick="Notifications.moreItems('<?php echo $result['notifyId']; ?>'); return false;">
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

    $page_id = "notifications";

    $css_files = array("my.css", "account.css");
    $page_title = $LANG['page-notifications-likes']." | ".APP_TITLE;

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

                <h2 class="header"><?php echo $LANG['page-notifications-likes']; ?></h2>

                                <?php

                                    $result = $notifications->getAll(0);

                                    $items_loaded = count($result['notifications']);

                                    if ($items_loaded != 0) {

                                        ?>

                                            <ul class="collection">

                                        <?php

                                        foreach ($result['notifications'] as $key => $value) {

                                            draw($value, $LANG, $helper);
                                        }

                                        ?>

                                            </ul>

                                        <?php

                                        if ($items_all > 20) {

                                            ?>

                                            <div class="row more_cont">
                                                <div class="col s12">
                                                    <a href="javascript:void(0)" onclick="Notifications.moreItems('<?php echo $result['notifyId']; ?>'); return false;">
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
</main>

        <?php

            include_once($_SERVER['DOCUMENT_ROOT']."/common/site_footer.inc.php");
        ?>

        <script type="text/javascript">

            var items_all = <?php echo $items_all; ?>;
            var items_loaded = <?php echo $items_loaded; ?>;

            window.Notifications || ( window.Notifications = {} );

            Notifications.moreItems = function (offset) {

                $.ajax({
                    type: 'POST',
                    url: '/notifications.php',
                    data: 'itemId=' + offset + "&loaded=" + items_loaded,
                    dataType: 'json',
                    timeout: 30000,
                    success: function(response){

                        $('div.more_cont').remove();

                        if (response.hasOwnProperty('html')){

                            $("ul.collection").append(response.html);
                        }

                        items_loaded = response.items_loaded;
                        items_all = response.items_all;
                    },
                    error: function(xhr, type){

                    }
                });
            };

            window.Friends || ( window.Friends = {} );

            Friends.acceptRequest = function (id, friend_id, access_token) {

                $.ajax({
                    type: 'POST',
                    url: '/ajax/friends/method/acceptRequest.php',
                    data: 'friend_id=' + friend_id + "&access_token=" + access_token,
                    dataType: 'json',
                    timeout: 30000,
                    success: function(response){

                        $('li.collection-item[data-id=' + id + ']').remove();
                    },
                    error: function(xhr, type){

                    }
                });
            };

            Friends.rejectRequest = function (id, friend_id, access_token) {

                $.ajax({
                    type: 'POST',
                    url: '/ajax/friends/method/rejectRequest.php',
                    data: 'friend_id=' + friend_id + "&access_token=" + access_token,
                    dataType: 'json',
                    timeout: 30000,
                    success: function(response){

                        $('li.collection-item[data-id=' + id + ']').remove();
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

    function draw($notify, $LANG, $helper) {

        $profilePhotoUrl = "/img/profile_default_photo.png";

        if (strlen($notify['fromUserPhotoUrl']) != 0) {

            $profilePhotoUrl = $notify['fromUserPhotoUrl'];
        }

        ?>

        <li class="collection-item avatar" data-id="<?php echo $notify['id']; ?>">
            <a href="/profile.php?id=<?php echo $notify['fromUserId']; ?>"><img src="<?php echo $profilePhotoUrl; ?>" alt="" class="circle"></a>

            <?php

                switch ($notify['type']) {

                    case NOTIFY_TYPE_LIKE: {

                        ?>

                        <span class="title"><?php echo $notify['fromUserFullname']." ".$LANG['label-notify-profile-like']; ?></span>
                        <p>
                            <?php echo $notify['timeAgo']; ?>
                        </p>

                        <?php

                        break;
                    }

                    case NOTIFY_TYPE_GIFT: {

                        ?>

                        <span class="title"><?php echo $notify['fromUserFullname']." ".$LANG['label-notify-gift']; ?></span>
                        <p>
                            <?php echo $notify['timeAgo']; ?>
                        </p>

                        <?php

                        break;
                    }

                    default: {

                        ?>

                        <span class="title"><?php echo $notify['fromUserFullname']." ".$LANG['label-notify-request-to-friends']; ?></span>
                        <p>
                            <?php echo $notify['timeAgo']; ?>
                            <br>
                            <a onclick="Friends.acceptRequest('<?php echo $notify['id']; ?>', '<?php echo $notify['fromUserId']; ?>', '<?php echo auth::getAccessToken(); ?>'); return false;" class="" href=""><?php echo $LANG['action-accept']; ?></a>
                            <span class=""> | </span>
                            <a onclick="Friends.rejectRequest('<?php echo $notify['id']; ?>', '<?php echo $notify['fromUserId']; ?>', '<?php echo auth::getAccessToken(); ?>'); return false;" class="" href=""><?php echo $LANG['action-reject']; ?></a>
                            <br>
                        </p>

                        <?php

                        break;
                    }
                }
            ?>
        </li>

        <?php
    }

?>