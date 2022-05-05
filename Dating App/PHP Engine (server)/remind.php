<?php

    /*!
	 * ifsoft engine v1.0
	 *
	 * http://ifsoft.com.ua, http://ifsoft.co.uk
	 * qascript@ifsoft.co.uk, qascript@mail.ru
	 *
	 * Copyright 2012-2016 Demyanchuk Dmitry (https://vk.com/dmitry.demyanchuk)
	 */

    include_once($_SERVER['DOCUMENT_ROOT']."/core/init.inc.php");

    if (auth::isSession()) {

        header("Location: /messages.php");
    }

    $email = '';

    $error = false;
    $error_message = '';
    $sent = false;

    if ( isset($_GET['sent']) ) {

        $sent = isset($_GET['sent']) ? $_GET['sent'] : 'false';

        if ($sent === 'success') {

            $sent = true;

        } else {

            $sent = false;
        }
    }

    if (!empty($_POST)) {

        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $token = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';

        $email = helper::clearText($email);
        $email = helper::escapeText($email);

        if (auth::getAuthenticityToken() !== $token) {

            $error = true;
            $error_message[] = $LANG['msg-error-unknown'];
        }

        if (!helper::isCorrectEmail($email)) {

            $error = true;
            $error_message[] = $LANG['msg-email-incorrect'];
        }

        if ( !$error && !$helper->isEmailExists($email) ) {

            $error = true;
            $error_message[] = $LANG['msg-email-not-found'];
        }

        if (!$error) {

            $accountId = $helper->getUserIdByEmail($email);

            if ($accountId != 0) {

                $account = new account($dbo, $accountId);

                $accountInfo = $account->get();

                if ($accountInfo['error'] === false && $accountInfo['state'] != ACCOUNT_STATE_BLOCKED) {

                    $clientId = 0; // Desktop version

                    $restorePointInfo = $account->restorePointCreate($email, $clientId);

                    ob_start();

                    ?>

                    <html>
                    <body>
                    This is link <a href="<?php echo APP_URL;  ?>/restore/?hash=<?php echo $restorePointInfo['hash']; ?>"><?php echo APP_URL;  ?>/restore/?hash=<?php echo $restorePointInfo['hash']; ?></a> to reset your password.
                    </body>
                    </html>

                    <?php

                    $from = SMTP_EMAIL;

                    $to = $email;

                    $html_text = ob_get_clean();

                    $subject = APP_TITLE." | Password reset";

                    $mail = new phpmailer();

                    $mail->isSMTP();                                      // Set mailer to use SMTP
                    $mail->Host = SMTP_HOST;                               // Specify main and backup SMTP servers
                    $mail->SMTPAuth = SMTP_AUTH;                               // Enable SMTP authentication
                    $mail->Username = SMTP_USERNAME;                      // SMTP username
                    $mail->Password = SMTP_PASSWORD;                      // SMTP password
                    $mail->SMTPSecure = SMTP_SECURE;                            // Enable TLS encryption, `ssl` also accepted
                    $mail->Port = SMTP_PORT;                                    // TCP port to connect to

                    $mail->From = $from;
                    $mail->FromName = APP_TITLE;
                    $mail->addAddress($to);                               // Name is optional

                    $mail->isHTML(true);                                  // Set email format to HTML

                    $mail->Subject = $subject;
                    $mail->Body    = $html_text;

                    $mail->send();
                }
            }

            $sent = true;
            header("Location: /remind.php/?sent=success");
        }
    }

    auth::newAuthenticityToken();

    $page_id = "restore";

    $css_files = array("my.css");
    $page_title = $LANG['page-restore']." | ".APP_TITLE;

    include_once($_SERVER['DOCUMENT_ROOT'] . "/common/header.inc.php");
?>

<body>

<?php

    include_once($_SERVER['DOCUMENT_ROOT'] . "/common/site_topbar.inc.php");
?>

<div class="section no-pad-bot" id="index-banner">
    <div class="container">

        <?php

            if ($sent) {

                ?>

                    <div class="row">
                        <div class="col s12 m6" style="margin: 0 auto; float: none; margin-top: 100px;">
                            <div class="card teal lighten-2">
                                <div class="card-content white-text">
                                <span class="card-title">
                                    <?php echo $LANG['msg-reset-password-sent']; ?>
                                </span>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php

            } else {

                ?>

                    <div class="row">
                        <form class="col s12 m6" action="/remind.php" method="post" style="margin: 0 auto; float: none; margin-top: 100px;">

                            <input autocomplete="off" type="hidden" name="authenticity_token" value="<?php echo helper::getAuthenticityToken(); ?>">

                            <div class="card ">
                                <div class="card-content black-text">
                                    <span class="card-title"><?php echo $LANG['page-restore']; ?></span>
                                    <p class="red-text" style="margin-top: 10px; margin-bottom: 10px; <?php if (!$error) echo "display: none"; ?>"><?php echo $LANG['msg-email-not-found']; ?></p>
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <input id="email" type="text" class="validate valid" name="email" value="<?php echo $email; ?>">
                                            <label for="email" class="active"><?php echo $LANG['label-email']; ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-action">
                                    <button style="margin-right: 10px;" class="waves-effect waves-light btn <?php echo SITE_THEME; ?>"><?php echo $LANG['action-next']; ?></button>
                                </div>
                            </div>
                        </form>
                    </div>

                <?php

            }
        ?>

    </div>
</div>

<script src="/js/init.js"></script>

</body>
</html>