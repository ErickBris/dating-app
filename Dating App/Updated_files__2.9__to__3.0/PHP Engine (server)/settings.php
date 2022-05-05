<?php

    /*!
     * ifsoft.co.uk v1.0
     *
     * http://ifsoft.com.ua, http://ifsoft.co.uk
     * qascript@ifsoft.co.uk
     *
     * Copyright 2012-2018 Demyanchuk Dmitry (https://vk.com/dmitry.demyanchuk)
     */

    include_once($_SERVER['DOCUMENT_ROOT']."/core/init.inc.php");

    if (!$auth->authorize(auth::getCurrentUserId(), auth::getAccessToken())) {

        header('Location: /');
    }

    $accountId = auth::getCurrentUserId();

    $account = new account($dbo, $accountId);

    $error = false;
    $send_status = false;
    $fullname = "";

    if (auth::isSession()) {

        $ticket_email = "";
    }

    if (!empty($_POST)) {

        $token = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';

        $allowMessages = isset($_POST['allowMessages']) ? $_POST['allowMessages'] : '';

        $gender = isset($_POST['gender']) ? $_POST['gender'] : 0;

        $day = isset($_POST['day']) ? $_POST['day'] : 0;
        $month = isset($_POST['month']) ? $_POST['month'] : 0;
        $year = isset($_POST['year']) ? $_POST['year'] : 0;

        $fullname = isset($_POST['fullname']) ? $_POST['fullname'] : '';
        $status = isset($_POST['status']) ? $_POST['status'] : '';
        $location = isset($_POST['location']) ? $_POST['location'] : '';
        $facebook_page = isset($_POST['facebook_page']) ? $_POST['facebook_page'] : '';
        $instagram_page = isset($_POST['instagram_page']) ? $_POST['instagram_page'] : '';

        $iStatus = isset($_POST['iStatus']) ? $_POST['iStatus'] : 0;
        $politicalViews = isset($_POST['politicalViews']) ? $_POST['politicalViews'] : 0;
        $worldViews = isset($_POST['worldViews']) ? $_POST['worldViews'] : 0;
        $personalPriority = isset($_POST['personalPriority']) ? $_POST['personalPriority'] : 0;
        $importantInOthers = isset($_POST['importantInOthers']) ? $_POST['importantInOthers'] : 0;
        $smokingViews = isset($_POST['smokingViews']) ? $_POST['smokingViews'] : 0;
        $alcoholViews = isset($_POST['alcoholViews']) ? $_POST['alcoholViews'] : 0;
        $lookingViews = isset($_POST['lookingViews']) ? $_POST['lookingViews'] : 0;
        $interestedViews = isset($_POST['interestedViews']) ? $_POST['interestedViews'] : 0;

        $allowMessages = helper::clearText($allowMessages);
        $allowMessages = helper::escapeText($allowMessages);

        $gender = helper::clearInt($gender);

        $day = helper::clearInt($day);
        $month = helper::clearInt($month);
        $year = helper::clearInt($year);

        $fullname = helper::clearText($fullname);
        $fullname = helper::escapeText($fullname);

        $status = helper::clearText($status);
        $status = helper::escapeText($status);

        $location = helper::clearText($location);
        $location = helper::escapeText($location);

        $facebook_page = helper::clearText($facebook_page);
        $facebook_page = helper::escapeText($facebook_page);

        $instagram_page = helper::clearText($instagram_page);
        $instagram_page = helper::escapeText($instagram_page);

        $iStatus = helper::clearInt($iStatus);
        $politicalViews = helper::clearInt($politicalViews);
        $worldViews = helper::clearInt($worldViews);
        $personalPriority = helper::clearInt($personalPriority);
        $importantInOthers = helper::clearInt($importantInOthers);
        $smokingViews = helper::clearInt($smokingViews);
        $alcoholViews = helper::clearInt($alcoholViews);
        $lookingViews = helper::clearInt($lookingViews);
        $interestedViews = helper::clearInt($interestedViews);

        if (auth::getAuthenticityToken() !== $token) {

            $error = true;
        }

        if (!$error) {

            if ($allowMessages === "on") {

                $account->setAllowMessages(1);

            } else {

                $account->setAllowMessages(0);
            }

            if (helper::isCorrectFullname($fullname)) {

                $account->edit($fullname);
            }

            $account->setSex($gender);
            $account->setBirth($year, $month, $day);
            $account->setStatus($status);
            $account->setLocation($location);

            $account->set_iStatus($iStatus);
            $account->set_iPoliticalViews($politicalViews);
            $account->set_iWorldView($worldViews);
            $account->set_iPersonalPriority($personalPriority);
            $account->set_iImportantInOthers($importantInOthers);
            $account->set_iSmokingViews($smokingViews);
            $account->set_iAlcoholViews($alcoholViews);
            $account->set_iLooking($lookingViews);
            $account->set_iInterested($interestedViews);

            if (helper::isValidURL($facebook_page)) {

                $account->setFacebookPage($facebook_page);

            } else {

                $account->setFacebookPage("");
            }

            if (helper::isValidURL($instagram_page)) {

                $account->setInstagramPage($instagram_page);

            } else {

                $account->setInstagramPage("");
            }

            header("Location: /settings.php/?error=false");
            exit;
        }

        header("Location: /settings.php/?error=true");
        exit;
    }

    $account->setLastActive();

    $accountInfo = $account->get();

    auth::newAuthenticityToken();

    $page_id = "settings_profile";

    $css_files = array("my.css", "account.css");
    $page_title = $LANG['page-settings']." | ".APP_TITLE;

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

                <h2 class="header"><?php echo $LANG['page-settings']; ?></h2>

                <div class="row msg-form">

                    <form class="" action="/settings.php" method="POST">

                        <?php

                        if ( isset($_GET['error']) ) {

                            switch ($_GET['error']) {

                                case "true" : {

                                    ?>

                                    <div class="input-field col s12">
                                        <div class="card red lighten-2">
                                            <div class="card-content white-text">
                                                <span class="card-title"><?php echo $LANG['msg-error-unknown']; ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <?php

                                    break;
                                }

                                default: {

                                    ?>

                                    <div class="input-field col s12">
                                        <div class="card teal lighten-2">
                                            <div class="card-content white-text">
                                                <span class="card-title"><?php echo $LANG['label-thanks']; ?></span>
                                                <br>
                                                <?php echo $LANG['label-settings-saved']; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <?php

                                    break;
                                }
                            }
                        }
                        ?>

                        <input autocomplete="off" type="hidden" name="authenticity_token" value="<?php echo auth::getAuthenticityToken(); ?>">

                        <p class="col s12">
                            <input type="checkbox" id="allowMessages" name="allowMessages" <?php if ($accountInfo['allowMessages'] == 1) echo "checked=\"checked\""; ?> />
                            <label for="allowMessages"><?php echo $LANG['label-messages-allow']; ?><br><?php echo $LANG['label-messages-allow-desc']; ?></label>
                        </p>

                        <div class="input-field col s12">
                            <input type="text" class="validate" id="fullname" name="fullname" value="<?php echo $accountInfo['fullname']; ?>">
                            <label for="fullname" class="active"><?php echo $LANG['label-fullname']; ?></label>
                        </div>

                        <div class="input-field col s12">
                            <input type="text" class="validate" id="location" name="location" value="<?php echo $accountInfo['location']; ?>">
                            <label for="location" class="active"><?php echo $LANG['label-location']; ?></label>
                        </div>

                        <div class="input-field col s12">
                            <input type="text" class="validate" id="facebook_page" name="facebook_page" value="<?php echo $accountInfo['fb_page']; ?>">
                            <label for="facebook_page" class="active"><?php echo $LANG['label-facebook-link']; ?></label>
                        </div>

                        <div class="input-field col s12">
                            <input type="text" class="validate" id="instagram_page" name="instagram_page" value="<?php echo $accountInfo['instagram_page']; ?>">
                            <label for="instagram_page" class="active"><?php echo $LANG['label-instagram-link']; ?></label>
                        </div>

                        <div class="input-field col s12">
                            <textarea id="status" class="materialize-textarea" name="status" rows="10" cols="80"><?php echo $accountInfo['status']; ?></textarea>
                            <label for="status"><?php echo $LANG['label-status']; ?></label>

                            <script type="text/javascript">
                                $('#textarea1').trigger('autoresize');

                                $(document).ready(function() {

                                    $('select').material_select();
                                });
                            </script>

                        </div>

                        <div class="input-field col s12">
                            <select name="gender">
                                <option value="2" <?php if ($accountInfo['sex'] != SEX_FEMALE && $accountInfo['sex'] != SEX_MALE) echo "selected=\"selected\""; ?>><?php echo $LANG['gender-unknown']; ?></option>
                                <option value="0" <?php if ($accountInfo['sex'] == SEX_MALE) echo "selected=\"selected\""; ?>><?php echo $LANG['gender-male']; ?></option>
                                <option value="1" <?php if ($accountInfo['sex'] == SEX_FEMALE) echo "selected=\"selected\""; ?>><?php echo $LANG['gender-female']; ?></option>
                            </select>
                            <label><?php echo $LANG['label-gender']; ?></label>
                        </div>

                        <div class="input-field col s12">
                            <select name="iStatus">
                                <option value="0" <?php if ($accountInfo['iStatus'] == 0) echo "selected=\"selected\""; ?>><?php echo $LANG['label-relationship-status-0']; ?></option>
                                <option value="1" <?php if ($accountInfo['iStatus'] == 1) echo "selected=\"selected\""; ?>><?php echo $LANG['label-relationship-status-1']; ?></option>
                                <option value="2" <?php if ($accountInfo['iStatus'] == 2) echo "selected=\"selected\""; ?>><?php echo $LANG['label-relationship-status-2']; ?></option>
                                <option value="3" <?php if ($accountInfo['iStatus'] == 3) echo "selected=\"selected\""; ?>><?php echo $LANG['label-relationship-status-3']; ?></option>
                                <option value="4" <?php if ($accountInfo['iStatus'] == 4) echo "selected=\"selected\""; ?>><?php echo $LANG['label-relationship-status-4']; ?></option>
                                <option value="5" <?php if ($accountInfo['iStatus'] == 5) echo "selected=\"selected\""; ?>><?php echo $LANG['label-relationship-status-5']; ?></option>
                                <option value="6" <?php if ($accountInfo['iStatus'] == 6) echo "selected=\"selected\""; ?>><?php echo $LANG['label-relationship-status-6']; ?></option>
                                <option value="7" <?php if ($accountInfo['iStatus'] == 7) echo "selected=\"selected\""; ?>><?php echo $LANG['label-relationship-status-7']; ?></option>

                            </select>
                            <label><?php echo $LANG['label-relationship-status']; ?></label>
                        </div>

                        <div class="input-field col s12">
                            <select name="politicalViews">
                                <option value="0" <?php if ($accountInfo['iPoliticalViews'] == 0) echo "selected=\"selected\""; ?>><?php echo $LANG['label-political-views-0']; ?></option>
                                <option value="1" <?php if ($accountInfo['iPoliticalViews'] == 1) echo "selected=\"selected\""; ?>><?php echo $LANG['label-political-views-1']; ?></option>
                                <option value="2" <?php if ($accountInfo['iPoliticalViews'] == 2) echo "selected=\"selected\""; ?>><?php echo $LANG['label-political-views-2']; ?></option>
                                <option value="3" <?php if ($accountInfo['iPoliticalViews'] == 3) echo "selected=\"selected\""; ?>><?php echo $LANG['label-political-views-3']; ?></option>
                                <option value="4" <?php if ($accountInfo['iPoliticalViews'] == 4) echo "selected=\"selected\""; ?>><?php echo $LANG['label-political-views-4']; ?></option>
                                <option value="5" <?php if ($accountInfo['iPoliticalViews'] == 5) echo "selected=\"selected\""; ?>><?php echo $LANG['label-political-views-5']; ?></option>
                                <option value="6" <?php if ($accountInfo['iPoliticalViews'] == 6) echo "selected=\"selected\""; ?>><?php echo $LANG['label-political-views-6']; ?></option>
                                <option value="7" <?php if ($accountInfo['iPoliticalViews'] == 7) echo "selected=\"selected\""; ?>><?php echo $LANG['label-political-views-7']; ?></option>
                                <option value="8" <?php if ($accountInfo['iPoliticalViews'] == 8) echo "selected=\"selected\""; ?>><?php echo $LANG['label-political-views-8']; ?></option>
                                <option value="9" <?php if ($accountInfo['iPoliticalViews'] == 9) echo "selected=\"selected\""; ?>><?php echo $LANG['label-political-views-9']; ?></option>

                            </select>
                            <label><?php echo $LANG['label-political-views']; ?></label>
                        </div>

                        <div class="input-field col s12">
                            <select name="worldViews">
                                <option value="0" <?php if ($accountInfo['iWorldView'] == 0) echo "selected=\"selected\""; ?>><?php echo $LANG['label-world-view-0']; ?></option>
                                <option value="1" <?php if ($accountInfo['iWorldView'] == 1) echo "selected=\"selected\""; ?>><?php echo $LANG['label-world-view-1']; ?></option>
                                <option value="2" <?php if ($accountInfo['iWorldView'] == 2) echo "selected=\"selected\""; ?>><?php echo $LANG['label-world-view-2']; ?></option>
                                <option value="3" <?php if ($accountInfo['iWorldView'] == 3) echo "selected=\"selected\""; ?>><?php echo $LANG['label-world-view-3']; ?></option>
                                <option value="4" <?php if ($accountInfo['iWorldView'] == 4) echo "selected=\"selected\""; ?>><?php echo $LANG['label-world-view-4']; ?></option>
                                <option value="5" <?php if ($accountInfo['iWorldView'] == 5) echo "selected=\"selected\""; ?>><?php echo $LANG['label-world-view-5']; ?></option>
                                <option value="6" <?php if ($accountInfo['iWorldView'] == 6) echo "selected=\"selected\""; ?>><?php echo $LANG['label-world-view-6']; ?></option>
                                <option value="7" <?php if ($accountInfo['iWorldView'] == 7) echo "selected=\"selected\""; ?>><?php echo $LANG['label-world-view-7']; ?></option>
                                <option value="8" <?php if ($accountInfo['iWorldView'] == 8) echo "selected=\"selected\""; ?>><?php echo $LANG['label-world-view-8']; ?></option>
                                <option value="9" <?php if ($accountInfo['iWorldView'] == 9) echo "selected=\"selected\""; ?>><?php echo $LANG['label-world-view-9']; ?></option>

                            </select>
                            <label><?php echo $LANG['label-world-view']; ?></label>
                        </div>

                        <div class="input-field col s12">
                            <select name="personalPriority">
                                <option value="0" <?php if ($accountInfo['iPersonalPriority'] == 0) echo "selected=\"selected\""; ?>><?php echo $LANG['label-personal-priority-0']; ?></option>
                                <option value="1" <?php if ($accountInfo['iPersonalPriority'] == 1) echo "selected=\"selected\""; ?>><?php echo $LANG['label-personal-priority-1']; ?></option>
                                <option value="2" <?php if ($accountInfo['iPersonalPriority'] == 2) echo "selected=\"selected\""; ?>><?php echo $LANG['label-personal-priority-2']; ?></option>
                                <option value="3" <?php if ($accountInfo['iPersonalPriority'] == 3) echo "selected=\"selected\""; ?>><?php echo $LANG['label-personal-priority-3']; ?></option>
                                <option value="4" <?php if ($accountInfo['iPersonalPriority'] == 4) echo "selected=\"selected\""; ?>><?php echo $LANG['label-personal-priority-4']; ?></option>
                                <option value="5" <?php if ($accountInfo['iPersonalPriority'] == 5) echo "selected=\"selected\""; ?>><?php echo $LANG['label-personal-priority-5']; ?></option>
                                <option value="6" <?php if ($accountInfo['iPersonalPriority'] == 6) echo "selected=\"selected\""; ?>><?php echo $LANG['label-personal-priority-6']; ?></option>
                                <option value="7" <?php if ($accountInfo['iPersonalPriority'] == 7) echo "selected=\"selected\""; ?>><?php echo $LANG['label-personal-priority-7']; ?></option>
                                <option value="8" <?php if ($accountInfo['iPersonalPriority'] == 8) echo "selected=\"selected\""; ?>><?php echo $LANG['label-personal-priority-8']; ?></option>

                            </select>
                            <label><?php echo $LANG['label-personal-priority']; ?></label>
                        </div>

                        <div class="input-field col s12">
                            <select name="importantInOthers">
                                <option value="0" <?php if ($accountInfo['iImportantInOthers'] == 0) echo "selected=\"selected\""; ?>><?php echo $LANG['label-important-in-others-0']; ?></option>
                                <option value="1" <?php if ($accountInfo['iImportantInOthers'] == 1) echo "selected=\"selected\""; ?>><?php echo $LANG['label-important-in-others-1']; ?></option>
                                <option value="2" <?php if ($accountInfo['iImportantInOthers'] == 2) echo "selected=\"selected\""; ?>><?php echo $LANG['label-important-in-others-2']; ?></option>
                                <option value="3" <?php if ($accountInfo['iImportantInOthers'] == 3) echo "selected=\"selected\""; ?>><?php echo $LANG['label-important-in-others-3']; ?></option>
                                <option value="4" <?php if ($accountInfo['iImportantInOthers'] == 4) echo "selected=\"selected\""; ?>><?php echo $LANG['label-important-in-others-4']; ?></option>
                                <option value="5" <?php if ($accountInfo['iImportantInOthers'] == 5) echo "selected=\"selected\""; ?>><?php echo $LANG['label-important-in-others-5']; ?></option>
                                <option value="6" <?php if ($accountInfo['iImportantInOthers'] == 6) echo "selected=\"selected\""; ?>><?php echo $LANG['label-important-in-others-6']; ?></option>

                            </select>
                            <label><?php echo $LANG['label-important-in-others']; ?></label>
                        </div>

                        <div class="input-field col s12">
                            <select name="smokingViews">
                                <option value="0" <?php if ($accountInfo['iSmokingViews'] == 0) echo "selected=\"selected\""; ?>><?php echo $LANG['label-smoking-views-0']; ?></option>
                                <option value="1" <?php if ($accountInfo['iSmokingViews'] == 1) echo "selected=\"selected\""; ?>><?php echo $LANG['label-smoking-views-1']; ?></option>
                                <option value="2" <?php if ($accountInfo['iSmokingViews'] == 2) echo "selected=\"selected\""; ?>><?php echo $LANG['label-smoking-views-2']; ?></option>
                                <option value="3" <?php if ($accountInfo['iSmokingViews'] == 3) echo "selected=\"selected\""; ?>><?php echo $LANG['label-smoking-views-3']; ?></option>
                                <option value="4" <?php if ($accountInfo['iSmokingViews'] == 4) echo "selected=\"selected\""; ?>><?php echo $LANG['label-smoking-views-4']; ?></option>
                                <option value="5" <?php if ($accountInfo['iSmokingViews'] == 5) echo "selected=\"selected\""; ?>><?php echo $LANG['label-smoking-views-5']; ?></option>

                            </select>
                            <label><?php echo $LANG['label-smoking-views']; ?></label>
                        </div>

                        <div class="input-field col s12">
                            <select name="alcoholViews">
                                <option value="0" <?php if ($accountInfo['iAlcoholViews'] == 0) echo "selected=\"selected\""; ?>><?php echo $LANG['label-alcohol-views-0']; ?></option>
                                <option value="1" <?php if ($accountInfo['iAlcoholViews'] == 1) echo "selected=\"selected\""; ?>><?php echo $LANG['label-alcohol-views-1']; ?></option>
                                <option value="2" <?php if ($accountInfo['iAlcoholViews'] == 2) echo "selected=\"selected\""; ?>><?php echo $LANG['label-alcohol-views-2']; ?></option>
                                <option value="3" <?php if ($accountInfo['iAlcoholViews'] == 3) echo "selected=\"selected\""; ?>><?php echo $LANG['label-alcohol-views-3']; ?></option>
                                <option value="4" <?php if ($accountInfo['iAlcoholViews'] == 4) echo "selected=\"selected\""; ?>><?php echo $LANG['label-alcohol-views-4']; ?></option>
                                <option value="5" <?php if ($accountInfo['iAlcoholViews'] == 5) echo "selected=\"selected\""; ?>><?php echo $LANG['label-alcohol-views-5']; ?></option>

                            </select>
                            <label><?php echo $LANG['label-alcohol-views']; ?></label>
                        </div>

                        <div class="input-field col s12">
                            <select name="lookingViews">
                                <option value="0" <?php if ($accountInfo['iLooking'] == 0) echo "selected=\"selected\""; ?>><?php echo $LANG['label-you-looking-0']; ?></option>
                                <option value="1" <?php if ($accountInfo['iLooking'] == 1) echo "selected=\"selected\""; ?>><?php echo $LANG['label-you-looking-1']; ?></option>
                                <option value="2" <?php if ($accountInfo['iLooking'] == 2) echo "selected=\"selected\""; ?>><?php echo $LANG['label-you-looking-2']; ?></option>
                                <option value="3" <?php if ($accountInfo['iLooking'] == 3) echo "selected=\"selected\""; ?>><?php echo $LANG['label-you-looking-3']; ?></option>

                            </select>
                            <label><?php echo $LANG['label-you-looking']; ?></label>
                        </div>

                        <div class="input-field col s12">
                            <select name="interestedViews">
                                <option value="0" <?php if ($accountInfo['iInterested'] == 0) echo "selected=\"selected\""; ?>><?php echo $LANG['label-you-like-0']; ?></option>
                                <option value="1" <?php if ($accountInfo['iInterested'] == 1) echo "selected=\"selected\""; ?>><?php echo $LANG['label-you-like-1']; ?></option>
                                <option value="2" <?php if ($accountInfo['iInterested'] == 2) echo "selected=\"selected\""; ?>><?php echo $LANG['label-you-like-2']; ?></option>

                            </select>
                            <label><?php echo $LANG['label-you-like']; ?></label>
                        </div>

                        <div class="input-field col s12" style="padding-left: 0;">

                            <select name="day" class="col s4">
                                <?php

                                    for ($day = 1; $day <= 31; $day++) {

                                        if ($day == $accountInfo['day']) {

                                            echo "<option value=\"$day\" selected=\"selected\">$day</option>";

                                        } else {

                                            echo "<option value=\"$day\">$day</option>";
                                        }
                                    }
                                ?>
                            </select>

                            <select name="month" class="col s4">
                                <option value="0" <?php if ($accountInfo['month'] == 0) echo "selected=\"selected\""; ?>><?php echo $LANG['month-jan']; ?></option>
                                <option value="1" <?php if ($accountInfo['month'] == 1) echo "selected=\"selected\""; ?>><?php echo $LANG['month-feb']; ?></option>
                                <option value="2" <?php if ($accountInfo['month'] == 2) echo "selected=\"selected\""; ?>><?php echo $LANG['month-mar']; ?></option>
                                <option value="3" <?php if ($accountInfo['month'] == 3) echo "selected=\"selected\""; ?>><?php echo $LANG['month-apr']; ?></option>
                                <option value="4" <?php if ($accountInfo['month'] == 4) echo "selected=\"selected\""; ?>><?php echo $LANG['month-may']; ?></option>
                                <option value="5" <?php if ($accountInfo['month'] == 5) echo "selected=\"selected\""; ?>><?php echo $LANG['month-june']; ?></option>
                                <option value="6" <?php if ($accountInfo['month'] == 6) echo "selected=\"selected\""; ?>><?php echo $LANG['month-july']; ?></option>
                                <option value="7" <?php if ($accountInfo['month'] == 7) echo "selected=\"selected\""; ?>><?php echo $LANG['month-aug']; ?></option>
                                <option value="8" <?php if ($accountInfo['month'] == 8) echo "selected=\"selected\""; ?>><?php echo $LANG['month-sept']; ?></option>
                                <option value="9" <?php if ($accountInfo['month'] == 9) echo "selected=\"selected\""; ?>><?php echo $LANG['month-oct']; ?></option>
                                <option value="10" <?php if ($accountInfo['month'] == 10) echo "selected=\"selected\""; ?>><?php echo $LANG['month-nov']; ?></option>
                                <option value="11" <?php if ($accountInfo['month'] == 11) echo "selected=\"selected\""; ?>><?php echo $LANG['month-dec']; ?></option>
                            </select>

                            <select name="year" class="col s4" style="padding-right: 0;">
                                <?php

                                    $current_year = date("Y");

                                    for ($year = 1915; $year <= $current_year; $year++) {

                                        if ($year == $accountInfo['year']) {

                                            echo "<option value=\"$year\" selected=\"selected\">$year</option>";

                                        } else {

                                            echo "<option value=\"$year\">$year</option>";
                                        }
                                    }
                                ?>
                            </select>

                            <label><?php echo $LANG['label-birth-date']; ?></label>

                        </div>

                        <div class="input-field col s12">
                            <button type="submit" class="btn waves-effect waves-light <?php echo SITE_THEME; ?> btn-large <?php echo SITE_THEME; ?>" name=""><?php echo $LANG['action-save']; ?></button>
                        </div>

                        <?php

                            if (FACEBOOK_AUTHORIZATION) {

                                ?>

                                    <div class="input-field col s12" style="margin-top: 60px;">
                                        <div class="card teal lighten-2">
                                            <div class="card-content white-text">
                                                <span class="card-title"><?php echo $LANG['label-services']; ?></span>
                                                <p><?php echo $LANG['action-connect-profile']; ?></p>
                                            </div>
                                            <div class="card-action">
                                                <a href="/settings/services"><?php echo $LANG['action-next']; ?></a>
                                            </div>
                                        </div>
                                    </div>

                                <?php
                            }
                        ?>

                        <div class="input-field col s12">
                            <div class="card teal lighten-2">
                                <div class="card-content white-text">
                                    <span class="card-title"><?php echo $LANG['page-referrals']; ?></span>
                                    <p><?php echo $LANG['label-referrals-list']; ?></p>
                                </div>
                                <div class="card-action">
                                    <a href="/settings/referrals"><?php echo $LANG['action-next']; ?></a>
                                </div>
                            </div>
                        </div>

                        <div class="input-field col s12">
                            <div class="card teal lighten-2">
                                <div class="card-content white-text">
                                    <span class="card-title"><?php echo $LANG['label-password']; ?></span>
                                    <p><?php echo $LANG['action-change-password']; ?></p>
                                </div>
                                <div class="card-action">
                                    <a href="/settings/password"><?php echo $LANG['action-next']; ?></a>
                                </div>
                            </div>
                        </div>

                        <div class="input-field col s12">
                            <div class="card teal lighten-2">
                                <div class="card-content white-text">
                                    <span class="card-title"><?php echo $LANG['label-profile']; ?></span>
                                    <p><?php echo $LANG['action-deactivation-profile']; ?></p>
                                </div>
                                <div class="card-action">
                                    <a href="/settings/deactivation"><?php echo $LANG['action-next']; ?></a>
                                </div>
                            </div>
                        </div>

                        <div class="input-field col s12">
                            <div class="card teal lighten-2">
                                <div class="card-content white-text">
                                    <span class="card-title"><?php echo $LANG['label-blacklist']; ?></span>
                                    <p><?php echo $LANG['label-blacklist-desc']; ?></p>
                                </div>
                                <div class="card-action">
                                    <a href="/settings/blacklist"><?php echo $LANG['action-next']; ?></a>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>

	        </div>
        </div>
    </div>
</main>

        <?php

            include_once($_SERVER['DOCUMENT_ROOT']."/common/site_footer.inc.php");
        ?>

</body>
</html>