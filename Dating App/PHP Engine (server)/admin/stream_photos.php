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

    $accountInfo = array();

    $stats = new stats($dbo);

    $page_id = "stream_photos";

    $stream = new stream($dbo);

    $inbox_all = $stream->getAllCount();
    $inbox_loaded = 0;

    if (!empty($_POST)) {

        $itemId = isset($_POST['itemId']) ? $_POST['itemId'] : 0;
        $loaded = isset($_POST['loaded']) ? $_POST['loaded'] : '';

        $itemId = helper::clearInt($itemId);
        $loaded = helper::clearInt($loaded);

        $result = $stream->get($itemId);

        $inbox_loaded = count($result['items']);

        $result['inbox_loaded'] = $inbox_loaded + $loaded;
        $result['inbox_all'] = $inbox_all;

        if ($inbox_loaded != 0) {

            ob_start();

            foreach ($result['items'] as $key => $value) {

                draw($value, $helper);
            }

            if ($result['inbox_loaded'] < $inbox_all) {

                ?>

                <div class="row more_cont">
                    <div class="col s12">
                        <a href="javascript:void(0)" onclick="Stream.moreItems('<?php echo $result['itemId']; ?>'); return false;">
                            <button class="btn waves-effect waves-light teal more_link">View more</button>
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

    $css_files = array("my.css");
    $page_title = "Photos Stream";

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
                                <h4>Stream Photos</h4>
                            </div>
                        </div>

                        <div class="col s12" id="items-content">

                            <?php

                                $result = $stream->get(0);

                                $inbox_loaded = count($result['items']);

                                if ($inbox_loaded != 0) {

                                    foreach ($result['items'] as $key => $value) {

                                        draw($value, $helper);
                                    }


                                } else {

                                    ?>

                                        <div class="row">
                                            <div class="col s12">
                                                <div class="card blue-grey darken-1">
                                                    <div class="card-content white-text">
                                                        <span class="card-title">List is empty.</span>
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
                                            <a href="javascript:void(0)" onclick="Stream.moreItems('<?php echo $result['itemId']; ?>'); return false;">
                                                <button class="btn waves-effect waves-light teal more_link">View more</button>
                                            </a>
                                        </div>
                                    </div>

                                    <?php
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

            var inbox_all = <?php echo $inbox_all; ?>;
            var inbox_loaded = <?php echo $inbox_loaded; ?>;

            window.Stream || ( window.Stream = {} );

            Stream.remove = function (offset, accessToken) {

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

            Stream.moreItems = function (offset) {

                $('a.more_link').hide();
                $('a.loading_link').show();

                $.ajax({
                    type: 'POST',
                    url: '/admin/stream_photos.php',
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

</body>
</html>

<?php

    function draw($post, $helper = null) {

        ?>

        <div class="row item" data-id="<?php echo $post['id']; ?>">
            <div class="col s8">
                <div class="card">
                    <div class="card-image">
                        <img src="<?php echo $post['previewImgUrl']; ?>">
                        <span class="card-title">Photo</span>
                    </div>
                    <div class="card-action">
                        <a href="/admin/profile.php/?id=<?php echo $post['fromUserId']; ?>">Photo Author</a>
                        <a href="/admin/photo.php/?id=<?php echo $post['id']; ?>">Photo Preview</a>
                    </div>
                </div>
            </div>
        </div>

    <?php
    }