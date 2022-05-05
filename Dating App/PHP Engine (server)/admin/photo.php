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

    $photoId = 0;
    $photoInfo = array();

    if (isset($_GET['id'])) {

        $photoId = isset($_GET['id']) ? $_GET['id'] : 0;
        $accessToken = isset($_GET['access_token']) ? $_GET['access_token'] : 0;
        $act = isset($_GET['act']) ? $_GET['act'] : '';

        $photoId = helper::clearInt($photoId);

        $photos = new photos($dbo);
        $photoInfo = $photos->info($photoId);

        if ($photoInfo['error'] === true) {

            header("Location: /admin/main.php");
            exit;
        }

        if ($photoInfo['removeAt'] != 0) {

            header("Location: /admin/photo_reports.php");
            exit;
        }

    } else {

        header("Location: /admin/main.php");
        exit;
    }

    $page_id = "photo";

    $error = false;
    $error_message = '';

    $css_files = array("admin.css");
    $page_title = "Photo info";

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
                                <div class="col s12">
                                    <h4>Photo info</h4>
                                </div>
                            </div>

                            <div class="col s12">

                                <?php

                                    if ($photoInfo['removeAt'] > 0) {

                                        ?>

                                        <?php

                                    } else {

                                        draw($photoInfo, $helper);
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

            window.Photo || (window.Photo = {});

            Photo.remove = function (offset, fromUserId, accessToken) {

                $.ajax({
                    type: 'GET',
                    url: '/admin/photo_remove.php/?id=' + offset + '&fromUserId=' + fromUserId + '&access_token=' + accessToken,
                    data: 'itemId=' + offset + '&fromUserId=' + fromUserId + "&access_token=" + accessToken,
                    timeout: 30000,
                    success: function(response) {

                        $('div.item[data-id=' + offset + ']').remove();

                        window.location.href = "/admin/photo_reports.php";
                    },
                    error: function(xhr, type){

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
            <div class="col s12 m4">
                <div class="card">
                    <div class="card-image">
                        <img src="<?php echo $post['previewImgUrl']; ?>">
                        <span class="card-title">Photo</span>
                    </div>
                    <div class="card-action">
                        <a href="/admin/profile.php/?id=<?php echo $post['fromUserId']; ?>">Photo Author</a>
                        <a onclick="Photo.remove('<?php echo $post['id']; ?>', '<?php echo $post['fromUserId']; ?>', '<?php echo admin::getAccessToken(); ?>'); return false;" href="#">Delete</a>
                    </div>
                </div>
            </div>
        </div>

    <?php
    }