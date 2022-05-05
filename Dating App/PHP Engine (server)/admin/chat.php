<?php

    /*!
     * ifsoft.co.uk engine v1.0
     *
     * http://ifsoft.com.ua, http://ifsoft.co.uk
     * qascript@ifsoft.co.uk
     *
     * Copyright 2012-2016 Demyanchuk Dmitry (https://vk.com/dmitry.demyanchuk)
     */

    include_once($_SERVER['DOCUMENT_ROOT']."/core/init.inc.php");

    if (!admin::isSession()) {

        header("Location: /admin/login.php");
    }

    $stats = new stats($dbo);
    $admin = new admin($dbo);

    $postId = 0;
    $chatInfo = array();

    if (isset($_GET['id'])) {

        $chatId = isset($_GET['id']) ? $_GET['id'] : 0;
        $accessToken = isset($_GET['access_token']) ? $_GET['access_token'] : 0;
        $act = isset($_GET['act']) ? $_GET['act'] : '';

        $chatId = helper::clearInt($chatId);

        $messages = new messages($dbo);
        $chatInfo = $messages->getFull($chatId);

        if ($chatInfo['error'] === true) {

            header("Location: /admin/main.php");
            exit;
        }

    } else {

        header("Location: /admin/main.php");
        exit;
    }

    $page_id = "chat";

    $css_files = array("my.css");
    $page_title = "Chat";

    include_once($_SERVER['DOCUMENT_ROOT']."/common/header.inc.php");

?>

<body>

    <?php

        include_once($_SERVER['DOCUMENT_ROOT']."/common/admin_panel_topbar.inc.php");
    ?>

<main class="content">
    <div class="row">
        <div class="col s12 m12 l12">

            <?php

                include_once($_SERVER['DOCUMENT_ROOT']."/common/admin_panel_banner.inc.php");
            ?>

            <div class="card">
                <div class="card-content">
                    <div class="row">
                        <div class="col s12">

                        <div class="row">
                            <div class="col s6">
                                <h4>Chat</h4>
                            </div>
                        </div>

                        <div class="col s12" id="items-content">

                            <?php

                                foreach ($chatInfo['messages'] as $key => $value) {

                                    draw($value, $helper);
                                }
                            ?>
                        </div>

			</div>
		  </div>
		</div>
	  </div>
	</div>
</div>
</main>

        <?php

            include_once($_SERVER['DOCUMENT_ROOT']."/common/admin_panel_footer.inc.php");
        ?>

        <script type="text/javascript">

            window.Messages || ( window.Messages = {} );

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

        </script>

</body>
</html>

<?php

    function draw($item, $helper)
    {
        ?>

            <div class="row item" data-id="<?php echo $item['id']; ?>">
                <div class="col s8">
                    <div class="card">
                        <div class="card-content">
                            <p>
                                <a href="/admin/profile.php/?id=<?php echo $item['fromUserId']; ?>"><?php echo $item['fromUserFullname']; ?></a>
                                </br>
                                <br>
                                <?php echo $item['message']; ?>
                                </br>

                                <?php

                                if (strlen($item['imgUrl'])) {

                                    ?>

                                    <span class="post_img">
                                        <img style="width: 100%" src="<?php echo $item['imgUrl']; ?>"/>
                                    </span>
                                    <?php
                                }
                                ?>

                                <br>
                                <span class="text-lighten-2"><?php echo $item['timeAgo']; ?></span>
                            </p>
                        </div>
                        <div class="card-action">
                            <a href="javascript: void(0)" onclick="Messages.remove('<?php echo $item['id']; ?>', '<?php echo admin::getAccessToken(); ?>'); return false;">Delete</a>
                        </div>
                    </div>
                </div>
            </div>

        <?php
    }