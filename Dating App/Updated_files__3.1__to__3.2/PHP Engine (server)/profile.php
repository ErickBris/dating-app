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
        exit;
    }

    if (isset($_GET['id'])) {

        $profile_id = isset($_GET['id']) ? $_GET['id'] : 0;

        $profile_id = helper::clearInt($profile_id);

        $profile = new profile($dbo, $profile_id);

        $profile->setRequestFrom(auth::getCurrentUserId());
        $profileInfo = $profile->get();

    } else {

        header("Location: /");
        exit;
    }

    if ($profileInfo['error'] === true) {

        header("Location: /");
        exit;
    }

    if ($profileInfo['id'] == auth::getCurrentUserId()) {

        $page_id = "my-profile";

        $account = new account($dbo, $profileInfo['id']);
        $account->setLastActive();
        unset($account);

    } else {

        $page_id = "profile";

        if ($profileInfo['ghost'] == 0) {

            $guests = new guests($dbo, $profileInfo['id']);
            $guests->setRequestFrom(auth::getCurrentUserId());

            $guests->add(auth::getCurrentUserId());
        }


    }

    if ($profileInfo['state'] != ACCOUNT_STATE_ENABLED) {

        include_once("stubs/profile.php");
        exit;
    }

    $css_files = array("my.css", "account.css");
    $page_title = $profileInfo['fullname']." | ".APP_TITLE;

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

                <div class="row">
                    <div class="col s12">
                        <div class="card white">

                            <ul class="collection" style="border: none; border-bottom: 1px solid #e0e0e0">

                                <li class="collection-item avatar" style="padding-left: 94px">
                                    <img style="height: 64px; width: 64px;" src="<?php if ( strlen($profileInfo['bigPhotoUrl']) != 0 ) { echo $profileInfo['bigPhotoUrl']; } else { echo "/img/profile_default_photo.png"; } ?>" alt="" class="circle profile-img">
                                    <span style="font-size: 1.44rem;" class="title"><?php echo $profileInfo['fullname']; ?></span>
                                    <p>
                                        <span>@<?php echo $profileInfo['username']; ?></span>
                                        <br>

                                        <?php

                                            if ($profileInfo['online']) {

                                                echo "<span class=\"teal-text\">Online</span>";

                                            } else {

                                                if ($profileInfo['lastAuthorize'] == 0) {

                                                    echo "Offline";

                                                } else {

                                                    echo $profileInfo['lastAuthorizeTimeAgo'];
                                                }
                                            }
                                        ?>

                                    <br>
                                    </p>

                                    <?php

                                        if ($profileInfo['id'] == auth::getCurrentUserId()) {

                                            ?>
                                                <a href="/settings.php" class="secondary-content <?php echo SITE_TEXT_COLOR; ?>"><i class="material-icons">mode edit</i></a>
                                            <?php
                                        }

                                    ?>

                                </li>
                            </ul>

                            <div class="row">
                                <div class="col s12 m12 l12 left">

                                <div class="col s12  m7">
                                    <div class="card white" style="box-shadow: none">

                                    <ul class="collection" style="border: none;">

                                        <li class="collection-item">
                                            <h5 class="title"><?php echo $LANG['label-join-date']; ?></h5>
                                            <p><?php echo $profileInfo['createDate'];; ?></p>
                                        </li>

                                        <?php

                                            if (strlen($profileInfo['sex']) < 2) {

                                                ?>

                                                <li class="collection-item">
                                                    <h5 class="title"><?php echo $LANG['label-gender']; ?></h5>
                                                    <p><?php if ($profileInfo['sex'] == 0) { echo $LANG['gender-male']; } else echo $LANG['gender-female']; ?></p>
                                                </li>

                                                <?php
                                            }

                                        ?>

                                        <?php

                                            if (strlen($profileInfo['location']) > 0) {

                                                ?>

                                                    <li class="collection-item">
                                                        <h5 class="title"><?php echo $LANG['label-location']; ?></h5>
                                                        <p><?php echo $profileInfo['location']; ?></p>
                                                    </li>

                                                <?php
                                            }

                                        ?>

                                        <?php

                                            if (strlen($profileInfo['fb_page']) > 0) {

                                                ?>

                                                <li class="collection-item">
                                                    <h5 class="title"><?php echo $LANG['label-facebook-link']; ?></h5>
                                                    <a href="<?php echo $profileInfo['fb_page']; ?>"><?php echo $profileInfo['fb_page']; ?></a>
                                                </li>

                                                <?php
                                            }

                                        ?>

                                        <?php

                                            if (strlen($profileInfo['instagram_page']) > 0) {

                                                ?>

                                                <li class="collection-item">
                                                    <h5 class="title"><?php echo $LANG['label-instagram-link']; ?></h5>
                                                    <a href="<?php echo $profileInfo['instagram_page']; ?>"><?php echo $profileInfo['instagram_page']; ?></a>
                                                </li>

                                                <?php
                                            }

                                        ?>

                                        <?php

                                            if (strlen($profileInfo['status']) > 0) {

                                                ?>

                                                    <li class="collection-item">
                                                        <h5 class="title"><?php echo $LANG['label-status']; ?></h5>
                                                        <p><?php echo $profileInfo['status']; ?></p>
                                                    </li>

                                                <?php
                                            }

                                        ?>

                                        <?php

                                            if ($profileInfo['iStatus'] != 0) {

                                                ?>

                                                <li class="collection-item">
                                                    <h5 class="title"><?php echo $LANG['label-relationship-status']; ?></h5>
                                                    <p>
                                                        <?php

                                                            switch ($profileInfo['iStatus']) {

                                                                case 1: {

                                                                    echo $LANG['label-relationship-status-1'];

                                                                    break;
                                                                }

                                                                case 2: {

                                                                    echo $LANG['label-relationship-status-2'];

                                                                    break;
                                                                }

                                                                case 3: {

                                                                    echo $LANG['label-relationship-status-3'];

                                                                    break;
                                                                }

                                                                case 4: {

                                                                    echo $LANG['label-relationship-status-4'];

                                                                    break;
                                                                }

                                                                case 5: {

                                                                    echo $LANG['label-relationship-status-5'];

                                                                    break;
                                                                }

                                                                case 6: {

                                                                    echo $LANG['label-relationship-status-6'];

                                                                    break;
                                                                }

                                                                case 7: {

                                                                    echo $LANG['label-relationship-status-7'];

                                                                    break;
                                                                }

                                                                default: {

                                                                    echo $LANG['label-relationship-status-0'];

                                                                    break;
                                                                }
                                                            }

                                                        ?>
                                                    </p>
                                                </li>

                                                <?php
                                            }

                                        ?>

                                        <?php

                                        if ($profileInfo['iPoliticalViews'] != 0) {

                                            ?>

                                            <li class="collection-item">
                                                <h5 class="title"><?php echo $LANG['label-political-views']; ?></h5>
                                                <p>
                                                    <?php

                                                    switch ($profileInfo['iPoliticalViews']) {

                                                        case 1: {

                                                            echo $LANG['label-political-views-1'];

                                                            break;
                                                        }

                                                        case 2: {

                                                            echo $LANG['label-political-views-2'];

                                                            break;
                                                        }

                                                        case 3: {

                                                            echo $LANG['label-political-views-3'];

                                                            break;
                                                        }

                                                        case 4: {

                                                            echo $LANG['label-political-views-4'];

                                                            break;
                                                        }

                                                        case 5: {

                                                            echo $LANG['label-political-views-5'];

                                                            break;
                                                        }

                                                        case 6: {

                                                            echo $LANG['label-political-views-6'];

                                                            break;
                                                        }

                                                        case 7: {

                                                            echo $LANG['label-political-views-7'];

                                                            break;
                                                        }

                                                        case 8: {

                                                            echo $LANG['label-political-views-8'];

                                                            break;
                                                        }

                                                        case 9: {

                                                            echo $LANG['label-political-views-9'];

                                                            break;
                                                        }

                                                        default: {

                                                            echo $LANG['label-political-views-0'];

                                                            break;
                                                        }
                                                    }

                                                    ?>
                                                </p>
                                            </li>

                                            <?php
                                        }

                                        ?>

                                        <?php

                                        if ($profileInfo['iWorldView'] != 0) {

                                            ?>

                                            <li class="collection-item">
                                                <h5 class="title"><?php echo $LANG['label-world-view']; ?></h5>
                                                <p>
                                                    <?php

                                                    switch ($profileInfo['iWorldView']) {

                                                        case 1: {

                                                            echo $LANG['label-world-view-1'];

                                                            break;
                                                        }

                                                        case 2: {

                                                            echo $LANG['label-world-view-2'];

                                                            break;
                                                        }

                                                        case 3: {

                                                            echo $LANG['label-world-view-3'];

                                                            break;
                                                        }

                                                        case 4: {

                                                            echo $LANG['label-world-view-4'];

                                                            break;
                                                        }

                                                        case 5: {

                                                            echo $LANG['label-world-view-5'];

                                                            break;
                                                        }

                                                        case 6: {

                                                            echo $LANG['label-world-view-6'];

                                                            break;
                                                        }

                                                        case 7: {

                                                            echo $LANG['label-world-view-7'];

                                                            break;
                                                        }

                                                        case 8: {

                                                            echo $LANG['label-world-view-8'];

                                                            break;
                                                        }

                                                        case 9: {

                                                            echo $LANG['label-world-view-9'];

                                                            break;
                                                        }

                                                        default: {

                                                            echo $LANG['label-world-view-0'];

                                                            break;
                                                        }
                                                    }

                                                    ?>
                                                </p>
                                            </li>

                                            <?php
                                        }

                                        ?>

                                        <?php

                                        if ($profileInfo['iPersonalPriority'] != 0) {

                                            ?>

                                            <li class="collection-item">
                                                <h5 class="title"><?php echo $LANG['label-personal-priority']; ?></h5>
                                                <p>
                                                    <?php

                                                    switch ($profileInfo['iPersonalPriority']) {

                                                        case 1: {

                                                            echo $LANG['label-personal-priority-1'];

                                                            break;
                                                        }

                                                        case 2: {

                                                            echo $LANG['label-personal-priority-2'];

                                                            break;
                                                        }

                                                        case 3: {

                                                            echo $LANG['label-personal-priority-3'];

                                                            break;
                                                        }

                                                        case 4: {

                                                            echo $LANG['label-personal-priority-4'];

                                                            break;
                                                        }

                                                        case 5: {

                                                            echo $LANG['label-personal-priority-5'];

                                                            break;
                                                        }

                                                        case 6: {

                                                            echo $LANG['label-personal-priority-6'];

                                                            break;
                                                        }

                                                        case 7: {

                                                            echo $LANG['label-personal-priority-7'];

                                                            break;
                                                        }

                                                        case 8: {

                                                            echo $LANG['label-personal-priority-8'];

                                                            break;
                                                        }

                                                        default: {

                                                            echo $LANG['label-personal-priority-0'];

                                                            break;
                                                        }
                                                    }

                                                    ?>
                                                </p>
                                            </li>

                                            <?php
                                        }

                                        ?>

                                        <?php

                                        if ($profileInfo['iImportantInOthers'] != 0) {

                                            ?>

                                            <li class="collection-item">
                                                <h5 class="title"><?php echo $LANG['label-important-in-others']; ?></h5>
                                                <p>
                                                    <?php

                                                    switch ($profileInfo['iImportantInOthers']) {

                                                        case 1: {

                                                            echo $LANG['label-important-in-others-1'];

                                                            break;
                                                        }

                                                        case 2: {

                                                            echo $LANG['label-important-in-others-2'];

                                                            break;
                                                        }

                                                        case 3: {

                                                            echo $LANG['label-important-in-others-3'];

                                                            break;
                                                        }

                                                        case 4: {

                                                            echo $LANG['label-important-in-others-4'];

                                                            break;
                                                        }

                                                        case 5: {

                                                            echo $LANG['label-important-in-others-5'];

                                                            break;
                                                        }

                                                        case 6: {

                                                            echo $LANG['label-important-in-others-6'];

                                                            break;
                                                        }

                                                        default: {

                                                            echo $LANG['label-important-in-others-0'];

                                                            break;
                                                        }
                                                    }

                                                    ?>
                                                </p>
                                            </li>

                                            <?php
                                        }

                                        ?>

                                        <?php

                                        if ($profileInfo['iSmokingViews'] != 0) {

                                            ?>

                                            <li class="collection-item">
                                                <h5 class="title"><?php echo $LANG['label-smoking-views']; ?></h5>
                                                <p>
                                                    <?php

                                                    switch ($profileInfo['iSmokingViews']) {

                                                        case 1: {

                                                            echo $LANG['label-smoking-views-1'];

                                                            break;
                                                        }

                                                        case 2: {

                                                            echo $LANG['label-smoking-views-2'];

                                                            break;
                                                        }

                                                        case 3: {

                                                            echo $LANG['label-smoking-views-3'];

                                                            break;
                                                        }

                                                        case 4: {

                                                            echo $LANG['label-smoking-views-4'];

                                                            break;
                                                        }

                                                        case 5: {

                                                            echo $LANG['label-smoking-views-5'];

                                                            break;
                                                        }

                                                        default: {

                                                            echo $LANG['label-smoking-views-0'];

                                                            break;
                                                        }
                                                    }

                                                    ?>
                                                </p>
                                            </li>

                                            <?php
                                        }

                                        ?>

                                        <?php

                                        if ($profileInfo['iAlcoholViews'] != 0) {

                                            ?>

                                            <li class="collection-item">
                                                <h5 class="title"><?php echo $LANG['label-alcohol-views']; ?></h5>
                                                <p>
                                                    <?php

                                                    switch ($profileInfo['iAlcoholViews']) {

                                                        case 1: {

                                                            echo $LANG['label-alcohol-views-1'];

                                                            break;
                                                        }

                                                        case 2: {

                                                            echo $LANG['label-alcohol-views-2'];

                                                            break;
                                                        }

                                                        case 3: {

                                                            echo $LANG['label-alcohol-views-3'];

                                                            break;
                                                        }

                                                        case 4: {

                                                            echo $LANG['label-alcohol-views-4'];

                                                            break;
                                                        }

                                                        case 5: {

                                                            echo $LANG['label-alcohol-views-5'];

                                                            break;
                                                        }

                                                        default: {

                                                            echo $LANG['label-alcohol-views-0'];

                                                            break;
                                                        }
                                                    }

                                                    ?>
                                                </p>
                                            </li>

                                            <?php
                                        }

                                        ?>

                                        <?php

                                        if ($profileInfo['iLooking'] != 0) {

                                            ?>

                                            <li class="collection-item">
                                                <h5 class="title"><?php echo $LANG['label-profile-looking']; ?></h5>
                                                <p>
                                                    <?php

                                                    switch ($profileInfo['iLooking']) {

                                                        case 1: {

                                                            echo $LANG['label-you-looking-1'];

                                                            break;
                                                        }

                                                        case 2: {

                                                            echo $LANG['label-you-looking-2'];

                                                            break;
                                                        }

                                                        case 3: {

                                                            echo $LANG['label-you-looking-3'];

                                                            break;
                                                        }

                                                        default: {

                                                            echo $LANG['label-you-looking-0'];

                                                            break;
                                                        }
                                                    }

                                                    ?>
                                                </p>
                                            </li>

                                            <?php
                                        }

                                        ?>

                                        <?php

                                        if ($profileInfo['iInterested'] != 0) {

                                            ?>

                                            <li class="collection-item">
                                                <h5 class="title"><?php echo $LANG['label-profile-like']; ?></h5>
                                                <p>
                                                    <?php

                                                    switch ($profileInfo['iInterested']) {

                                                        case 1: {

                                                            echo $LANG['label-profile-you-like-1'];

                                                            break;
                                                        }

                                                        case 2: {

                                                            echo $LANG['label-profile-you-like-2'];

                                                            break;
                                                        }

                                                        default: {

                                                            echo $LANG['label-profile-you-like-0'];

                                                            break;
                                                        }
                                                    }

                                                    ?>
                                                </p>
                                            </li>

                                            <?php
                                        }

                                        ?>

                                    </ul>
                                    </div>
                                </div>

                                <?php

                                    if ($profileInfo['id'] != auth::getCurrentUserId()) {

                                        ?>

                                        <div class="col s12  m5">
                                            <div class="card white" style="box-shadow: none">

                                                <div class="row">
                                                    <div class="input-field col s12 friends_button_container" style="margin-top: 0">

                                                        <?php

                                                            if ($profileInfo['friend']) {

                                                                ?>
                                                                    <a onclick="Friends.remove('<?php echo $profileInfo['id']; ?>', '<?php echo auth::getAccessToken(); ?>'); return false;" style="width: 100%; min-height: 36px; height: auto" class="btn waves-effect waves-light <?php echo SITE_THEME; ?>"><?php echo $LANG['action-remove-from-friends']; ?></a>
                                                                <?php

                                                            } else {

                                                                if ($profileInfo['follow']) {

                                                                    ?>
                                                                        <a onclick="Profile.sendRequest('<?php echo $profileInfo['id']; ?>', '<?php echo auth::getAccessToken(); ?>'); return false;" style="width: 100%; min-height: 36px; height: auto" class="btn waves-effect waves-light <?php echo SITE_THEME; ?>"><?php echo $LANG['action-cancel-friend-request']; ?></a>
                                                                    <?php

                                                                } else {

                                                                    ?>
                                                                        <a onclick="Profile.sendRequest('<?php echo $profileInfo['id']; ?>', '<?php echo auth::getAccessToken(); ?>'); return false;" style="width: 100%; min-height: 36px; height: auto" class="btn waves-effect waves-light <?php echo SITE_THEME; ?>" ><?php echo $LANG['action-add-to-friends']; ?></a>
                                                                    <?php
                                                                }
                                                            }
                                                        ?>

                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="input-field col s12 report_button_container" style="margin-top: 0">

                                                        <a onclick="Profile.getReportBox(); return false;" style="width: 100%" class="btn waves-effect waves-light <?php echo SITE_THEME; ?>"><?php echo $LANG['action-report']; ?></a>

                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="input-field col s12 block_button_container" style="margin-top: 0">

                                                        <?php

                                                        if ($profileInfo['blocked']) {

                                                            ?>
                                                                <a onclick="Profile.unblock('<?php echo $profileInfo['id']; ?>', '<?php echo auth::getAccessToken(); ?>'); return false;" style="width: 100%" class="btn waves-effect waves-light <?php echo SITE_THEME; ?>"><?php echo $LANG['action-unblock']; ?></a>
                                                            <?php

                                                        } else {

                                                            ?>
                                                                <a onclick="Profile.block('<?php echo $profileInfo['id']; ?>', '<?php echo auth::getAccessToken(); ?>'); return false;" style="width: 100%" class="btn waves-effect waves-light <?php echo SITE_THEME; ?>"><?php echo $LANG['action-block']; ?></a>
                                                            <?php
                                                        }
                                                        ?>

                                                    </div>
                                                </div>

                                                <?php

                                                    if (auth::getCurrentProMode() == 0 && auth::getCurrentFreeMessagesCount() < 1) {

                                                        ?>
                                                            <div class="row">
                                                                <div class="input-field col s12" style="margin-top: 0">
                                                                    <a href="javascript: void(0)" style="width: 100%; min-height: 36px; height: auto" class="btn <?php echo SITE_THEME; ?>"><?php echo $LANG['label-pro-mode-alert']; ?></a>
                                                                </div>
                                                            </div>
                                                        <?php

                                                    } else {

                                                        if ($profileInfo['allowMessages'] == 1 || ($profileInfo['allowMessages'] == 0 && $profileInfo['friend'])) {

                                                            ?>

                                                            <div class="row">
                                                                <div class="input-field col s12" style="margin-top: 0">
                                                                    <a href="/chat.php/?chat_id=0&user_id=<?php echo $profileInfo['id']; ?>" style="width: 100%; min-height: 36px; height: auto" class="btn waves-effect waves-light <?php echo SITE_THEME; ?>"><?php echo $LANG['action-send-message']; ?></a>
                                                                </div>
                                                            </div>

                                                            <?php
                                                        }
                                                    }

                                                ?>

                                                <?php

                                                    if (!$profileInfo['myLike']) {

                                                        ?>

                                                        <div class="fixed-action-btn" style="bottom: 65px; right: 24px;">
                                                            <a onclick="Profile.like('<?php echo $profileInfo['id']; ?>', '<?php echo auth::getAccessToken(); ?>'); return false;" class="btn-floating btn-large <?php echo SITE_THEME; ?>">
                                                                <i class="material-icons">favorite</i>
                                                            </a>
                                                        </div>

                                                        <?php

                                                    }
                                                ?>

                                            </div>
                                        </div>

                                        <?php

                                    } else {

                                        ?>

                                            <div class="col s12  m5">
                                                <div class="card white" style="box-shadow: none">

                                                    <div class="row">
                                                        <div class="input-field col s12" style="margin-top: 0">
                                                            <a onclick="Profile.changePhoto(); return false;" style="width: 100%; min-height: 36px; height: auto" class="btn waves-effect waves-light <?php echo SITE_THEME; ?>"><?php echo $LANG['action-change-photo']; ?></a>
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
    </div>
</main>

        <?php

            include_once($_SERVER['DOCUMENT_ROOT']."/common/site_footer.inc.php");
        ?>

        <script type="text/javascript" src="/js/jquery.ocupload-1.1.2.js"></script>

        <script type="text/javascript">

            window.Friends || ( window.Friends = {} );

            Friends.remove = function (friend_id, access_token) {

                $.ajax({
                    type: 'POST',
                    url: '/ajax/friends/method/remove.php',
                    data: 'friend_id=' + friend_id + "&access_token=" + access_token,
                    dataType: 'json',
                    timeout: 30000,
                    success: function(response){

                        if (response.hasOwnProperty('html')) {

                            $("div.friends_button_container").html(response.html);
                        }
                    },
                    error: function(xhr, type){

                    }
                });
            };

            window.Profile || ( window.Profile = {} );

            Profile.changePhoto = function() {

                $('#img-box').openModal();
            };

            Profile.getReportBox = function() {

                $('#report-box').openModal();
            };

            Profile.sendReport = function (profile_id, reason, access_token) {

                $.ajax({
                    type: 'POST',
                    url: '/ajax/profile/method/report.php',
                    data: 'profile_id=' + profile_id + "&reason=" + reason + "&access_token=" + access_token,
                    dataType: 'json',
                    timeout: 30000,
                    success: function(response) {

                        $('#report-box').closeModal();

                        if (response.hasOwnProperty('error')) {

                            Materialize.toast('<?php echo $LANG['label-profile-reported']; ?>', 3000);
                        }
                    },
                    error: function(xhr, type){

                    }
                });
            };

            Profile.sendRequest = function (profile_id, access_token) {

                $.ajax({
                    type: 'POST',
                    url: '/ajax/friends/method/sendRequest.php',
                    data: 'profile_id=' + profile_id + "&access_token=" + access_token,
                    dataType: 'json',
                    timeout: 30000,
                    success: function(response){

                        if (response.hasOwnProperty('html')) {

                            $("div.friends_button_container").html(response.html);
                        }
                    },
                    error: function(xhr, type){

                    }
                });
            };

            Profile.block = function (profile_id, access_token) {

                $.ajax({
                    type: 'POST',
                    url: '/ajax/profile/method/block.php',
                    data: 'profile_id=' + profile_id + "&access_token=" + access_token,
                    dataType: 'json',
                    timeout: 30000,
                    success: function(response){

                        if (response.hasOwnProperty('html')) {

                            $("div.block_button_container").html(response.html);
                        }
                    },
                    error: function(xhr, type){

                    }
                });
            };

            Profile.unblock = function (profile_id, access_token) {

                $.ajax({
                    type: 'POST',
                    url: '/ajax/profile/method/unblock.php',
                    data: 'profile_id=' + profile_id + "&access_token=" + access_token,
                    dataType: 'json',
                    timeout: 30000,
                    success: function(response){

                        if (response.hasOwnProperty('html')) {

                            $("div.block_button_container").html(response.html);
                        }
                    },
                    error: function(xhr, type){

                    }
                });
            };

            Profile.like = function (profile_id, access_token) {

                $.ajax({
                    type: 'POST',
                    url: '/ajax/profile/method/like.php',
                    data: 'profile_id=' + profile_id + "&access_token=" + access_token,
                    dataType: 'json',
                    timeout: 30000,
                    success: function(response){

                        $("div.fixed-action-btn").hide();
                    },
                    error: function(xhr, type){

                    }
                });
            };

        </script>

    <div id="img-box" class="modal">
        <div class="modal-content">
            <h4><?php echo $LANG['label-image-upload-description']; ?></h4>
            <div class="file_select_btn_container">
                <div class="file_select_btn btn <?php echo SITE_THEME; ?>" style="width: 220px"><?php echo $LANG['action-add-img']; ?></div>
            </div>

            <div class="file_select_btn_description" style="display: none">
                <?php echo $LANG['msg-loading']; ?>
            </div>
        </div>
        <div class="modal-footer">
            <a href="#!" class=" modal-action modal-close waves-effect waves-ripple btn-flat"><?php echo $LANG['action-close']; ?></a>
        </div>
    </div>

    <?php

        if (auth::getCurrentUserId() != $profileInfo['id']) {

            ?>

                <div id="report-box" class="modal">
                    <div class="modal-content">
                        <h5><?php echo $LANG['page-profile-report-sub-title']; ?></h5>
                        <a onclick="Profile.sendReport('<?php echo $profileInfo['id']; ?>', '0', '<?php echo auth::getAccessToken(); ?>'); return false;" class="waves-effect waves-ripple btn-flat" style="display: block" href="javascript:void(0)"><?php echo $LANG['label-profile-report-reason-1']; ?></a>
                        <a onclick="Profile.sendReport('<?php echo $profileInfo['id']; ?>', '1', '<?php echo auth::getAccessToken(); ?>'); return false;" class="waves-effect waves-ripple btn-flat" style="display: block" href="javascript:void(0)"><?php echo $LANG['label-profile-report-reason-2']; ?></a>
                        <a onclick="Profile.sendReport('<?php echo $profileInfo['id']; ?>', '2', '<?php echo auth::getAccessToken(); ?>'); return false;" class="waves-effect waves-ripple btn-flat" style="display: block" href="javascript:void(0)"><?php echo $LANG['label-profile-report-reason-3']; ?></a>
                        <a onclick="Profile.sendReport('<?php echo $profileInfo['id']; ?>', '3', '<?php echo auth::getAccessToken(); ?>'); return false;" class="waves-effect waves-ripple btn-flat" style="display: block" href="javascript:void(0)"><?php echo $LANG['label-profile-report-reason-4']; ?></a>
                    </div>
                    <div class="modal-footer">
                        <a href="#!" class=" modal-action modal-close waves-effect waves-ripple btn-flat"><?php echo $LANG['action-cancel']; ?></a>
                    </div>
                </div>

            <?php
        }
    ?>

    <script type="text/javascript">

        $('.file_select_btn').upload({
            name: 'uploaded_file',
            method: 'post',
            enctype: 'multipart/form-data',
            action: '/ajax/profile/method/uploadPhoto.php',
            onComplete: function(text) {

                var response = JSON.parse(text);

                if (response.hasOwnProperty('error')) {

                    if (response.error === false) {

                        $('#img-box').closeModal();

                        if (response.hasOwnProperty('lowPhotoUrl')) {

                            $("img.profile-img").attr("src", response.lowPhotoUrl);
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