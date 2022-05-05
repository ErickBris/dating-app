<?php

    /*!
     * ifsoft.co.uk v1.0
     *
     * http://ifsoft.com.ua, http://ifsoft.co.uk
     * raccoonsquare@gmail.com
     *
     * Copyright 2012-2018 Demyanchuk Dmitry (raccoonsquare@gmail.com)
     */

    include_once($_SERVER['DOCUMENT_ROOT']."/core/init.inc.php");

    if (!$auth->authorize(auth::getCurrentUserId(), auth::getAccessToken())) {

        header('Location: /');
    }

    $showForm = true;

    $chat_id = 0;
    $user_id = 0;

    $chat_info = array("messages" => array());
    $user_info = array();

    $messages = new messages($dbo);
    $messages->setRequestFrom(auth::getCurrentUserId());

    if (!isset($_GET['chat_id']) && !isset($_GET['user_id'])) {

        header('Location: /');
        exit;

    } else {

        $chat_id = isset($_GET['chat_id']) ? $_GET['chat_id'] : 0;
        $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : 0;

        $chat_id = helper::clearInt($chat_id);
        $user_id = helper::clearInt($user_id);

        $user = new profile($dbo, $user_id);
        $user->setRequestFrom(auth::getCurrentUserId());
        $user_info = $user->get();
        unset($user);

        if ($user_info['error'] === true) {

            header('Location: /');
            exit;
        }

        $chat_id_test = $messages->getChatId(auth::getCurrentUserId(), $user_id);

        if ($chat_id != 0 && $chat_id_test != $chat_id) {

            header('Location: /');
            exit;
        }

        if ($chat_id == 0) {

            $chat_id = $messages->getChatId(auth::getCurrentUserId(), $user_id);

            if ($chat_id != 0) {

                header('Location: /chat.php/?chat_id='.$chat_id.'&user_id='.$user_id);
                exit;
            }
        }

        if ($chat_id != 0) {

            $chat_info = $messages->get($chat_id, 0);
        }
    }

    if ($user_info['state'] != ACCOUNT_STATE_ENABLED) {

        $showForm = false;
    }

    if ($user_info['allowMessages'] == 0 && $user_info['friend'] === false) {

        $showForm = false;
    }

    $blacklist = new blacklist($dbo);
    $blacklist->setRequestFrom($user_info['id']);

    if ($blacklist->isExists(auth::getCurrentUserId())) {

        $showForm = false;
    }

    $items_all = $messages->messagesCountByChat($chat_id);
    $items_loaded = 0;

    $page_id = "chat";

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

                <h2 class="header"><?php echo $user_info['fullname']; ?></h2>

                <div class="chat-content">

                            <?php

                                $result = $chat_info;

                                    $items_loaded = count($result['messages']);

                                    if ($items_loaded != 0) {

                                        if ($items_all > 20) {

                                            ?>

                                            <div class="messages_cont">

                                                <div class="row more_cont">
                                                    <div class="col s12">
                                                        <a href="javascript:void(0)" onclick="Messages.more('<?php echo $chat_id ?>', '<?php echo $user_id ?>'); return false;">
                                                            <button class="btn waves-effect waves-light <?php echo SITE_THEME; ?> more_link"><?php echo $LANG['action-more']; ?></button>
                                                        </a>
                                                    </div>
                                                </div>

                                            </div>

                                            <?php
                                        }

                                        ?>
                                            <ul class="collection">
                                        <?php

                                            foreach (array_reverse($result['messages']) as $key => $value) {

                                                draw::messageItem($value, $LANG, $helper);
                                            }

                                        ?>
                                            </ul>

                                            <script type="text/javascript">

                                                $(window).load(function() {

                                                    $("html, body").animate({ scrollTop: $(document).height() }, 1000);
                                                });

                                            </script>
                                        <?php

                                        ?>

                                        <?php


                                        ?>

                                        <?php

                                    } else {

                                        ?>

                                            <div class="row empty-list">
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

                    <div class="row msg-form">
                        <form class="" onsubmit="

                        <?php

                            if (auth::getCurrentProMode() == 0) {

                                ?>
                                    Messages.showProAlert(); return false;
                                <?php

                            } else {

                                ?>
                                    Messages.create('<?php echo $chat_id; ?>', '<?php echo $user_id; ?>', '<?php echo auth::getAccessToken(); ?>'); return false;
                                <?php
                            }
                        ?>

                        ">
                            <input type="hidden" name="message_image" value="">

                            <div class="input-field col s9">
                                <input type="text" class="validate" id="msg-text" name="message_text" value="">
                                <label for="msg-text" class=""><?php echo $LANG['label-placeholder-message']; ?></label>
                            </div>

                            <div class="input-field col s1">
                                <button type="submit" class="btn waves-effect waves-light <?php echo SITE_THEME; ?> btn-large" name=""><i class="material-icons">send</i></button>
                            </div>
                        </form>
                    </div>

                    <div class="row">
                        <div class="input-field col s6 msg-image-preview-container" style="display: none">
                             <img class="msg-image-preview" style="max-width: 100%;" src="">
                        </div>
                        <div class="input-field col s2">
                            <a onclick="Messages.changeImage(); return false;" class="btn waves-effect waves-light <?php echo SITE_THEME; ?> btn-large" name=""><i class="material-icons msg-image-change">image</i></a>
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

        <script type="text/javascript" src="/js/jquery.ocupload-1.1.2.js"></script>
        <script type="text/javascript" src="/js/chat.js"></script>

        <script type="text/javascript">

            var items_all = <?php echo $items_all; ?>;
            var items_loaded = <?php echo $items_loaded; ?>;
            var chat_id = <?php echo $chat_id; ?>;

            if (chat_id != 0) {

                App.chatInit('<?php echo $chat_id; ?>', '<?php echo $user_id; ?>', '<?php echo auth::getAccessToken(); ?>');
            }

            window.Messages || ( window.Messages = {} );

            Messages.showProAlert = function() {

                $('#pro-alert-box').openModal();
            };

            Messages.changeImage = function() {

                var message_img = $('input[name=message_image]').val();

                if (message_img.length > 0) {

                    $('input[name=message_image]').val("");
                    $("div.msg-image-preview-container").hide();
                    $("img.msg-image-preview").attr("src", "");
                    $("i.msg-image-change").html("image");

                } else {

                    $('#img-box').openModal();
                }
            };

            Messages.remove = function (offset, accessToken) {

                $.ajax({
                    type: 'GET',
                    url: '/admin/msg.php/?id=' + offset  + '&access_token=' + accessToken,
                    data: 'itemId=' + offset + "&access_token=" + accessToken,
                    timeout: 30000,
                    success: function(response) {

                        $('div.item[data-id=' + offset + ']').remove();
                    },
                    error: function(xhr, type){

                    }
                });
            };

            Messages.moreItems = function (offset) {

                $('a.more_link').hide();
                $('a.loading_link').show();

                $.ajax({
                    type: 'POST',
                    url: '/admin/stream_messages.php',
                    data: 'itemId=' + offset + "&loaded=" + inbox_loaded,
                    dataType: 'json',
                    timeout: 30000,
                    success: function(response){

                        $('div.more_cont').remove();

                        if (response.hasOwnProperty('html')){

                            $("div#items-content").append(response.html);
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

        <div id="img-box" class="modal">
            <div class="modal-content">
                <h4><?php echo $LANG['label-image-upload-description']; ?></h4>
                <div class="file_select_btn_container">
                    <div class="file_select_btn btn" style="width: 220px"><?php echo $LANG['action-add-img']; ?></div>
                </div>

                <div class="file_select_btn_description" style="display: none">
                    <?php echo $LANG['msg-loading']; ?>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#!" class=" modal-action modal-close waves-effect waves-light btn-flat"><?php echo $LANG['action-close']; ?></a>
            </div>
        </div>

        <div id="pro-alert-box" class="modal">
            <div class="modal-content">
                <h4><?php echo $LANG['label-pro-mode-alert']; ?></h4>
            </div>
            <div class="modal-footer">
                <a href="#!" class=" modal-action modal-close waves-effect waves-light btn-flat"><?php echo $LANG['action-close']; ?></a>
            </div>
        </div>

        <script type="text/javascript">

            $('.file_select_btn').upload({
              name: 'userfile',
              method: 'post',
              enctype: 'multipart/form-data',
              action: '/ajax/msg/method/uploadImg.php',
              onComplete: function(text) {

                var response = JSON.parse(text);

                if (response.hasOwnProperty('error')) {

                  if (response.error === false) {

                    $('#img-box').closeModal();

                    if (response.hasOwnProperty('imgUrl')) {

                      $("input[name=message_image]").val(response.imgUrl);
                      $("img.msg-image-preview").attr("src", response.imgUrl);
                      $("div.msg-image-preview-container").show();
                      $("i.msg-image-change").html("delete");
//                      $("img.msg_img_preview").attr("src", response.imgUrl);
                    }
                  }
                }

                $("div.file_select_btn_description").hide();
                $("div.file_select_btn_container").show();
              },
              onSubmit: function() {

                $("div.file_select_btn_container").hide();
                $("div.file_select_btn_description").show();
              }
            });

        </script>

</body>
</html>

<?php

    function draw($message, $LANG, $helper) {

        $time = new language(NULL, $LANG['lang-code']);

        $message['message'] = helper::processMsgText($message['message']);

        ?>

            <li class="collection-item avatar" data-id="<?php echo $message['id']; ?>">
                <a href="/profile.php?id=<?php echo $message['fromUserId']; ?>"><img src="<?php if (strlen($message['fromUserPhotoUrl']) != 0 ) { echo $message['fromUserPhotoUrl']; } else { echo "/img/profile_default_photo.png"; } ?>" alt="" class="circle"></a>
                <span class="title dialogs-title"><?php echo $message['fromUserUsername']; ?></span>
                <p>
                <?php

                            if (strlen($message['message']) > 0) {

                                ?>
                                    <?php echo $message['message']; ?></br>
                                <?php
                            }

                            if (strlen($message['imgUrl']) > 0) {

                                ?>
                                    <img style="max-width: 80%; margin-top: 10px;" src="<?php echo $message['imgUrl']; ?>"></br>
                                <?php
                            }

                ?>

                </p>
                <a href="javascript:void(0)" class="secondary-content"><?php echo $time->timeAgo($message['createAt']); ?></a>
            </li>

        <?php
    }

?>