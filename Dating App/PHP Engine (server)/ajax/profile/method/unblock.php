<?php

    /*!
     * ifsoft.co.uk engine v1.0
     *
     * http://ifsoft.com.ua, http://ifsoft.co.uk
     * qascript@ifsoft.co.uk
     *
     * Copyright 2012-2016 Demyanchuk Dmitry (https://vk.com/dmitry.demyanchuk)
     */

    include_once($_SERVER['DOCUMENT_ROOT'] . "/core/init.inc.php");

    if (!$auth->authorize(auth::getCurrentUserId(), auth::getAccessToken())) {

        header('Location: /');
    }

    if (!empty($_POST)) {

        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : '';

        $profile_id = isset($_POST['profile_id']) ? $_POST['profile_id'] : 0;

        $profile_id = helper::clearInt($profile_id);

        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        if (auth::getAccessToken() === $access_token) {

            $blacklist = new blacklist($dbo);
            $blacklist->setRequestFrom(auth::getCurrentUserId());

            $result = $blacklist->remove($profile_id);

            ob_start();

            ?>
                <a onclick="Profile.block('<?php echo $profile_id; ?>', '<?php echo auth::getAccessToken(); ?>'); return false;" style="width: 100%" class="btn waves-effect waves-light <?php echo SITE_THEME; ?>"><?php echo $LANG['action-block']; ?></a>
            <?php

            $result['html'] = ob_get_clean();
        }

        echo json_encode($result);
        exit;
    }
