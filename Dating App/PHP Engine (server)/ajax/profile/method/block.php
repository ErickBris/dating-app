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

    if (!$auth->authorize(auth::getCurrentUserId(), auth::getAccessToken())) {

        header('Location: /');
    }

    if (!empty($_POST)) {

        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : '';

        $profile_id = isset($_POST['profile_id']) ? $_POST['profile_id'] : 0;
        $reason = isset($_POST['reason']) ? $_POST['reason'] : '';

        $profile_id = helper::clearInt($profile_id);

        $reason = preg_replace( "/[\r\n]+/", " ", $reason); //replace all new lines to one new line
        $reason  = preg_replace('/\s+/', ' ', $reason);        //replace all white spaces to one space

        $reason = helper::escapeText($reason);

        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        if (auth::getAccessToken() === $access_token) {

            $blacklist = new blacklist($dbo);
            $blacklist->setRequestFrom(auth::getCurrentUserId());

            $result = $blacklist->add($profile_id, $reason);

            ob_start();

            ?>
                <a onclick="Profile.unblock('<?php echo $profile_id; ?>', '<?php echo auth::getAccessToken(); ?>'); return false;" style="width: 100%" class="btn waves-effect waves-light <?php echo SITE_THEME; ?>"><?php echo $LANG['action-unblock']; ?></a>
            <?php

            $result['html'] = ob_get_clean();
        }

        echo json_encode($result);
        exit;
    }
