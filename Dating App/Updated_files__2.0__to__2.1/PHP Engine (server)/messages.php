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

    $profile = new profile($dbo, auth::getCurrentUserId());

    $messages = new msg($dbo);
    $messages->setRequestFrom(auth::getCurrentUserId());

    if (isset($_GET['action'])) {

        $messages_count = $messages->getNewMessagesCount();

        echo $messages_count;
        exit;
    }

    $account = new account($dbo, auth::getCurrentUserId());
    $account->setLastActive();
    unset($account);

    $inbox_all = $messages->myActiveChatsCount();
    $inbox_loaded = 0;

    if (!empty($_POST)) {

        $messageCreateAt = isset($_POST['messageCreateAt']) ? $_POST['messageCreateAt'] : 0;
        $loaded = isset($_POST['loaded']) ? $_POST['loaded'] : 0;

        $messageCreateAt = helper::clearInt($messageCreateAt);
        $loaded = helper::clearInt($loaded);

        $result = $messages->getDialogs_new($messageCreateAt);

        $inbox_loaded = count($result['chats']);

        $result['chats_loaded'] = $inbox_loaded + $loaded;
        $result['chats_all'] = $inbox_all;

        if ($inbox_all != 0) {

            ob_start();

            foreach ($result['chats'] as $key => $value) {

                draw($value, $LANG, $helper);
            }

            if ($result['chats_loaded'] < $inbox_all) {

                ?>

                <div class="more_cont">
                    <a class="more_link" href="#" onclick="Messages.moreItems('<?php echo $result['messageCreateAt']; ?>'); return false;"><?php echo $LANG['action-more']; ?></a>
                    <a class="loading_link" href="#" style="display: none">&nbsp;</a>
                </div>

            <?php
            }

            $result['html'] = ob_get_clean();
        }

        echo json_encode($result);
        exit;
    }

    $page_id = "messages";

    $css_files = array("my.css", "account.css");
    $page_title = $LANG['page-messages']." | ".APP_TITLE;

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

                <h2 class="header"><?php echo $LANG['page-messages']; ?></h2>

                            <?php

                                $result = $messages->getDialogs_new(0);

                                $inbox_loaded = count($result['chats']);

                                if ($inbox_loaded != 0) {

                                    ?>

                                    <ul class="collection">

                                    <?php

                                    foreach ($result['chats'] as $key => $value) {

                                            draw($value, $LANG, $helper);
                                    }

                                    ?>

                                    </ul>

                                    <?php


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

                                if ($inbox_all > 20) {

                                    ?>

                                    <div class="row more_cont">
                                        <div class="col s12">
                                            <a href="javascript:void(0)" onclick="Messages.moreItems('<?php echo $result['messageCreateAt']; ?>'); return false;">
                                                <button class="btn waves-effect waves-light teal more_link"><?php echo $LANG['action-more']; ?></button>
                                            </a>
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

            var inbox_all = <?php echo $inbox_all; ?>;
            var inbox_loaded = <?php echo $inbox_loaded; ?>;

            window.Messages || ( window.Messages = {} );

            Messages.moreItems = function (offset) {

                $.ajax({
                    type: 'POST',
                    url: '/messages.php',
                    data: 'messageCreateAt=' + offset + "&loaded=" + inbox_loaded,
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

                    }
                });
            };

        </script>

        <script type="text/javascript" src="/js/chat.js"></script>

</body>
</html>

<?php

    function draw($chat, $LANG, $helper) {

        $time = new language(NULL, $LANG['lang-code']);

        ?>

        <li class="collection-item avatar" data-id="<?php echo $chat['id']; ?>">
            <a href="/profile.php?id=<?php echo $chat['withUserId']; ?>"><img src="<?php if ( strlen($chat['withUserPhotoUrl']) != 0 ) { echo $chat['withUserPhotoUrl']; } else { echo "/img/profile_default_photo.png"; } ?>" alt="" class="circle"></a>
            <span class="title dialogs-title"><?php echo $chat['withUserFullname']; ?></span>
            <p>
            <?php

                if (strlen($chat['lastMessage']) == 0) {

                    echo "Image";

                } else {

                    echo $chat['lastMessage'];
                }

            ?>
            <br>
            <?php echo $time->timeAgo($chat['lastMessageCreateAt']); ?>
            <br>
            <a class="left" href="/chat.php/?chat_id=<?php echo $chat['id']; ?>&user_id=<?php echo $chat['withUserId']; ?>"><?php echo $LANG['action-go-to-conversation']; ?></a>
            <?php
                                if ($chat['newMessagesCount'] != 0) {

                                    ?>
                                        <a class="right messages-counter" href="javascript: void(0)"><?php echo $chat['newMessagesCount']; ?></a>

                                    <?php
                                }
            ?>
            <br>
            </p>
            <a href="#!" onclick="Messages.removeChat('<?php echo $chat['id']; ?>', '<?php echo $chat['withUserId']; ?>', '<?php echo auth::getAccessToken(); ?>'); return false;" class="secondary-content <?php echo SITE_TEXT_COLOR; ?>"><i class="material-icons">delete</i></a>
        </li>

        <?php
    }

?>