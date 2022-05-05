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
    $gift = new gift($dbo);

    $page_id = "gifts";

    $error = false;
    $error_message = '';

    if (isset($_GET['action'])) {

        $action = isset($_GET['action']) ? $_GET['action'] : '';
        $id = isset($_GET['id']) ? $_GET['id'] : 0;

        $action = helper::clearText($action);
        $action = helper::escapeText($action);

        $id = helper::clearInt($id);

        if (!APP_DEMO) {

            switch($action) {

                case 'remove': {

                    $gift->db_remove($id);

                    header("Location: /admin/gifts.php");

                    break;
                }

                default: {

                    header("Location: /admin/gifts.php");

                    break;
                }
            }
        }
    }

    if (!empty($_POST)) {

        $authToken = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';
        $cost = isset($_POST['cost']) ? $_POST['cost'] : 3;
        $category = isset($_POST['category']) ? $_POST['category'] : 0;

        $cost = helper::clearInt($cost);
        $category = helper::clearInt($category);

        if ($authToken === helper::getAuthenticityToken() && !APP_DEMO) {

            if (isset($_FILES['uploaded_file']['name'])) {

                $uploaded_file = $_FILES['uploaded_file']['tmp_name'];
                $uploaded_file_name = basename($_FILES['uploaded_file']['name']);
                $uploaded_file_ext = pathinfo($_FILES['uploaded_file']['name'], PATHINFO_EXTENSION);

                $gift_next_id = $gift->db_getMaxId();
                $gift_next_id++;

                if (move_uploaded_file($_FILES['uploaded_file']['tmp_name'], GIFTS_PATH.$gift_next_id.".".$uploaded_file_ext)) {

                    $gift->db_add($cost, $category, APP_URL."/".GIFTS_PATH.$gift_next_id.".".$uploaded_file_ext);
                }
            }
        }

        header("Location: /admin/gifts.php");
    }

    helper::newAuthenticityToken();

    $css_files = array("my.css");
    $page_title = "Gifts";

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
                                <h4>Add New Gift</h4>
                            </div>
                        </div>

                            <form method="post" action="/admin/gifts.php" enctype="multipart/form-data">

                                <input type="hidden" name="authenticity_token" value="<?php echo helper::getAuthenticityToken(); ?>">

                                <div class="row">

                                    <div class="input-field col s12">
                                        <input placeholder="Cost (In Credits)" id="cost" type="text" name="cost" maxlength="100" class="validate" value="3">
                                        <label for="cost">Gift Cost (In Credits)</label>
                                    </div>

                                    <div class="file-field input-field col s12">
                                        <div class="btn">
                                            <span>Gift Image</span>
                                            <input type="file" name="uploaded_file">
                                        </div>
                                        <div class="file-path-wrapper">
                                            <input class="file-path validate" type="text" placeholder="Image File (Attention! To view images correctly - we recommend using the image size of 256x256 pixels. Formats: JPG and PNG.)">
                                        </div>
                                    </div>

                                    <div class="input-field col s12">
                                        <button type="submit" class="btn waves-effect waves-light" name="" >Add</button>
                                    </div>

                                </div>

                            </form>

                            <div class="row">
                                <div class="col s6">
                                    <h4>Gifts</h4>
                                </div>
                            </div>

                            <div class="col s12" id="items-content">

                                <?php

                                    $result = $gift->db_get(0, 100);

                                    $inbox_loaded = count($result['items']);

                                    if ($inbox_loaded != 0) {

                                        ?>

                                        <table class="bordered data-tables responsive-table">
                                            <tbody>
                                                <tr>
                                                    <th class="text-left">Id</th>
                                                    <th>Gift Image</th>
                                                    <th>Cost</th>
                                                    <th>Category</th>
                                                    <th>Date</th>
                                                    <th>Action</th>
                                                </tr>

                                        <?php

                                        foreach ($result['items'] as $key => $value) {

                                            draw($value, $helper);
                                        }

                                        ?>

                                            </tbody>
                                        </table>

                                        <?php


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

</body>
</html>

<?php

    function draw($gift, $helper)
    {
        ?>

        <tr data-id="<?php echo $gift['id']; ?>">
            <td class="text-left"><?php echo $gift['id']; ?></td>
            <td style="text-align: left;"><img style="width: 64px; border: 1px solid #ccc" src="<?php echo $gift['imgUrl']; ?>"></td>
            <td><?php echo $gift['cost'] ?></td>
            <td><?php echo $gift['category'] ?></td>
            <td><?php echo $gift['date']; ?></td>
            <td><a href="/admin/gifts.php/?id=<?php echo $gift['id']; ?>&action=remove">Remove</a></td>
        </tr>

        <?php
    }