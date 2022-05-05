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

    if (!empty($_POST)) {

        $authToken = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';
        $message = isset($_POST['message']) ? $_POST['message'] : '';
        $type = isset($_POST['type']) ? $_POST['type'] : 1;

        $message = helper::clearText($message);
        $message = helper::escapeText($message);

        $type = helper::clearInt($type);

        if ($authToken === helper::getAuthenticityToken() && !APP_DEMO) {

            if (strlen($message) != 0) {

                $gcm = new gcm($dbo, 0);
                $gcm->setData($type, $message, 0);
                $gcm->forAll();
                $gcm->sendToAll();
            }
        }

        header("Location: /admin/gcm.php");
    }

    $stats = new stats($dbo);

    $page_id = "gcm";

    $error = false;
    $error_message = '';

    helper::newAuthenticityToken();

    $css_files = array("my.css");
    $page_title = "Google Cloud Messages";

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
                                <h4>Send message (GCM) for all users</h4>
                            </div>
                        </div>

                        <?php
                            if (APP_DEMO) {

                                ?>
                                    <div class="row">
                                        <div class="col s12">
                                            <div class="card blue-grey lighten-2">
                                                <div class="card-content white-text">
                                                    <span class="card-title">Sending push notifications (GCM) is not available in the demo version mode. That we turned off the sending push notifications (GCM) in the demo version mode to protect users from spam and of foul language.</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                            }
                        ?>

                        <form method="post" action="/admin/gcm.php">

                            <input type="hidden" name="authenticity_token" value="<?php echo helper::getAuthenticityToken(); ?>">

                            <div class="row">

                                <div class="input-field col s12">
                                    <select name="type">
                                        <option selected="selected" selected value="<?php echo GCM_NOTIFY_SYSTEM; ?>">For all users</option>
                                           <option value="<?php echo GCM_NOTIFY_CUSTOM; ?>">Only for authorized users</option>
                                    </select>
                                    <label>Message type</label>
                                </div>

                                <script type="text/javascript">

                                    $(document).ready(function() {

                                        $('select').material_select();
                                    });

                                </script>

                                <div class="input-field col s12">
                                    <input type="text" class="validate" name="message" id="message" maxlength="100">
                                    <label for="message">Message text</label>
                                </div>

                            </div>

                            <button type="submit" class="btn waves-effect waves-light" name="" >Send</button>
                        </form>

                        <div class="row">
                            <div class="col s12">
                                <h4>Recently sent messages</h4>
                            </div>
                        </div>

                        <div class="col s12">

                            <?php

                                $result = $stats->getGcmHistory();

                                $inbox_loaded = count($result['data']);

                                if ($inbox_loaded != 0) {

                                ?>

                                <table class="bordered data-tables responsive-table">
                                    <tbody>
                                        <tr>
                                            <th class="text-left">Id</th>
                                            <th>Message</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Delivered</th>
                                            <th>Create At</th>
                                        </tr>

                                    <?php

                                    foreach ($result['data'] as $key => $value) {

                                        draw($value);
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
                                                        <span class="card-title">History is empty.</span>
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

        <script type="text/javascript">


        </script>

</body>
</html>

<?php

    function draw($authObj)
    {
        ?>

        <tr>
            <td class="text-left"><?php echo $authObj['id']; ?></td>
            <td><?php echo $authObj['msg']; ?></td>
            <td>
                <?php

                    switch ($authObj['msgType']) {

                        case GCM_NOTIFY_SYSTEM: {

                            echo "For all users";
                            break;
                        }

                        case GCM_NOTIFY_CUSTOM: {

                            echo "Only for authorized users";
                            break;
                        }

                        default: {

                            break;
                        }
                    }
                ?>
            </td>
            <td><?php if ($authObj['status'] == 1) {echo "success";} else {echo "failure";} ?></td>
            <td><?php echo $authObj['success']; ?></td>
            <td><?php echo date("Y-m-d H:i:s", $authObj['createAt']); ?></td>
        </tr>

        <?php
    }