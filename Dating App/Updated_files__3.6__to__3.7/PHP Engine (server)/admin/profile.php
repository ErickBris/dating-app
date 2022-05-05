<?php

    /*!
     * ifsoft.co.uk engine v1.0
     *
     * http://ifsoft.com.ua, http://ifsoft.co.uk
     * raccoonsquare@gmail.com
     *
     * Copyright 2012-2018 Demyanchuk Dmitry (raccoonsquare@gmail.com)
     */

    include_once($_SERVER['DOCUMENT_ROOT']."/core/init.inc.php");

    if (!admin::isSession()) {

        header("Location: /admin/login.php");
    }

    $accountInfo = array();

    if (isset($_GET['id'])) {

        $accountId = isset($_GET['id']) ? $_GET['id'] : 0;
        $accessToken = isset($_GET['access_token']) ? $_GET['access_token'] : 0;
        $act = isset($_GET['act']) ? $_GET['act'] : '';

        $accountId = helper::clearInt($accountId);

        $account = new account($dbo, $accountId);
        $accountInfo = $account->get();

        $messages = new messages($dbo);
        $messages->setRequestFrom($accountId);

        if ($accessToken === admin::getAccessToken() && !APP_DEMO) {

            switch ($act) {

                case "disconnect": {

                    $account->setFacebookId('');

                    header("Location: /admin/profile.php/?id=".$accountInfo['id']);
                    break;
                }

                case "showAdmob": {

                    $account->setAdmob(1);

                    header("Location: /admin/profile.php/?id=".$accountInfo['id']);
                    break;
                }

                case "hideAdmob": {

                    $account->setAdmob(0);

                    header("Location: /admin/profile.php/?id=".$accountInfo['id']);
                    break;
                }

                case "close": {

                    $auth->removeAll($accountId);

                    header("Location: /admin/profile.php/?id=".$accountInfo['id']);
                    break;
                }

                case "block": {

                    $account->setState(ACCOUNT_STATE_BLOCKED);

                    header("Location: /admin/profile.php/?id=".$accountInfo['id']);
                    break;
                }

                case "unblock": {

                    $account->setState(ACCOUNT_STATE_ENABLED);

                    header("Location: /admin/profile.php/?id=".$accountInfo['id']);
                    break;
                }

                case "verify": {

                    $account->setVerify(1);

                    header("Location: /admin/profile.php/?id=".$accountInfo['id']);
                    break;
                }

                case "unverify": {

                    $account->setVerify(0);

                    header("Location: /admin/profile.php/?id=".$accountInfo['id']);
                    break;
                }

                case "ghost_set": {

                    $account->setGhost(1);

                    header("Location: /admin/profile.php/?id=".$accountInfo['id']);
                    break;
                }

                case "ghost_unset": {

                    $account->setGhost(0);

                    header("Location: /admin/profile.php/?id=".$accountInfo['id']);
                    break;
                }

                case "promode_set": {

                    $account->setPro(1);

                    header("Location: /admin/profile.php/?id=".$accountInfo['id']);
                    break;
                }

                case "promode_unset": {

                    $account->setPro(0);

                    header("Location: /admin/profile.php/?id=".$accountInfo['id']);
                    break;
                }

                case "delete-cover": {

                    $data = array("originCoverUrl" => '',
                                  "normalCoverUrl" => '');

                    $account->setCover($data);

                    header("Location: /admin/profile.php/?id=".$accountInfo['id']);
                    break;
                }

                case "delete-photo": {

                    $data = array("originPhotoUrl" => '',
                                  "normalPhotoUrl" => '',
                                  "lowPhotoUrl" => '');

                    $account->setPhoto($data);

                    header("Location: /admin/profile.php/?id=".$accountInfo['id']);
                    break;
                }

                default: {

                    if (!empty($_POST)) {

                        $authToken = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';
                        $username = isset($_POST['username']) ? $_POST['username'] : '';
                        $fullname = isset($_POST['fullname']) ? $_POST['fullname'] : '';
                        $location = isset($_POST['location']) ? $_POST['location'] : '';
                        $balance = isset($_POST['balance']) ? $_POST['balance'] : 0;
                        $fb_page = isset($_POST['fb_page']) ? $_POST['fb_page'] : '';
                        $instagram_page = isset($_POST['instagram_page']) ? $_POST['instagram_page'] : '';
                        $email = isset($_POST['email']) ? $_POST['email'] : '';

                        $username = helper::clearText($username);
                        $username = helper::escapeText($username);

                        $fullname = helper::clearText($fullname);
                        $fullname = helper::escapeText($fullname);

                        $location = helper::clearText($location);
                        $location = helper::escapeText($location);

                        $balance = helper::clearInt($balance);

                        $fb_page = helper::clearText($fb_page);
                        $fb_page = helper::escapeText($fb_page);

                        $instagram_page = helper::clearText($instagram_page);
                        $instagram_page = helper::escapeText($instagram_page);

                        $email = helper::clearText($email);
                        $email = helper::escapeText($email);

                         if ($authToken === helper::getAuthenticityToken()) {

                            $account->setUsername($username);
                            $account->setFullname($fullname);
                            $account->setLocation($location);
                            $account->setBalance($balance);
                            $account->setFacebookPage($fb_page);
                            $account->setInstagramPage($instagram_page);
                            $account->setEmail($email);
                         }
                    }

                    header("Location: /admin/profile.php/?id=".$accountInfo['id']);
                    exit;
                }
            }
        }

    } else {

        header("Location: /admin/main.php");
    }

    if ($accountInfo['error'] === true) {

        header("Location: /admin/main.php");
    }

    $stats = new stats($dbo);

    $page_id = "account";

    $error = false;
    $error_message = '';

    helper::newAuthenticityToken();

    $css_files = array("my.css");
    $page_title = $accountInfo['username']." | Account info";

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
                                <h4>Account Info</h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col s12">
                                <a href="/admin/personal_gcm.php/?id=<?php echo $accountInfo['id']; ?>">
							        <button class="btn waves-effect waves-light teal">Send Personal Message (GCM)<i class="material-icons right">send</i></button>
						        </a>
                            </div>
                        </div>

                        <div class="col s12">
                            <table class="striped responsive-table">
                                    <tbody>
                                        <tr>
                                            <th class="text-left">Name</th>
                                            <th>Value/Count</th>
                                            <th>Action</th>
                                        </tr>
                                        <tr>
                                            <td class="text-left">Username:</td>
                                            <td><?php echo $accountInfo['username']; ?></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">Fullname:</td>
                                            <td><?php echo $accountInfo['fullname']; ?></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">Email:</td>
                                            <td><?php echo $accountInfo['email']; ?></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">Facebook account:</td>
                                            <td><?php if (strlen($accountInfo['fb_id']) == 0) {echo "Not connected to facebook.";} else {echo "<a target=\"_blank\" href=\"https://www.facebook.com/app_scoped_user_id/{$accountInfo['fb_id']}\">Facebook account link</a>";} ?></td>
                                            <td><?php if (strlen($accountInfo['fb_id']) == 0) {echo "";} else {echo "<a href=\"/admin/profile.php/?id={$accountInfo['id']}&access_token=".admin::getAccessToken()."&act=disconnect\">Remove connection</a>";} ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">SignUp Ip address:</td>
                                            <td><?php if (!APP_DEMO) {echo $accountInfo['ip_addr'];} else {echo "It is not available in the demo version";} ?></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">SignUp Date:</td>
                                            <td><?php echo date("Y-m-d H:i:s", $accountInfo['regtime']); ?></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">AdMob (on/off AdMob in account):</td>
                                            <td>
                                                <?php

                                                    if ($accountInfo['admob'] == 1) {

                                                        echo "<span>On (AdMob is active in account)</span>";

                                                    } else {

                                                        echo "<span>Off (AdMob is not active in account)</span>";
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php

                                                    if ($accountInfo['admob'] == 1) {

                                                        ?>
                                                            <a class="" href="/admin/profile.php/?id=<?php echo $accountInfo['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=hideAdmob">Turn Off AdMob in this account</a>
                                                        <?php

                                                    } else {

                                                        ?>
                                                            <a class="" href="/admin/profile.php/?id=<?php echo $accountInfo['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=showAdmob">Turn On AdMob in this account </a>
                                                        <?php
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">Account state:</td>
                                            <td>
                                                <?php

                                                    if ($accountInfo['state'] == ACCOUNT_STATE_ENABLED) {

                                                        echo "<span>Account is active</span>";

                                                    } else {

                                                        echo "<span>Account is blocked</span>";
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php

                                                    if ($accountInfo['state'] == ACCOUNT_STATE_ENABLED) {

                                                        ?>
                                                            <a class="" href="/admin/profile.php/?id=<?php echo $accountInfo['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=block">Block account</a>
                                                        <?php

                                                    } else {

                                                        ?>
                                                            <a class="" href="/admin/profile.php/?id=<?php echo $accountInfo['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=unblock">Unblock account</a>
                                                        <?php
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">Account verified:</td>
                                            <td>
                                                <?php

                                                    if ($accountInfo['verify'] == 1) {

                                                        echo "<span>Account is verified.</span>";

                                                    } else {

                                                        echo "<span>Account is not verified.</span>";
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php

                                                    if ($accountInfo['verify'] == 1) {

                                                        ?>
                                                            <a class="" href="/admin/profile.php/?id=<?php echo $accountInfo['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=unverify">Unset verified</a>
                                                        <?php

                                                    } else {

                                                        ?>
                                                            <a class="" href="/admin/profile.php/?id=<?php echo $accountInfo['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=verify">Set account as verified</a>
                                                        <?php
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">Ghost Mode:</td>
                                            <td>
                                                <?php

                                                    if ($accountInfo['ghost'] == 1) {

                                                        echo "<span>Ghost Mode Activated.</span>";

                                                    } else {

                                                        echo "<span>Ghost Mode Not Active.</span>";
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php

                                                    if ($accountInfo['ghost'] == 1) {

                                                        ?>
                                                            <a class="" href="/admin/profile.php/?id=<?php echo $accountInfo['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=ghost_unset">Off Ghost Mode</a>
                                                        <?php

                                                    } else {

                                                        ?>
                                                            <a class="" href="/admin/profile.php/?id=<?php echo $accountInfo['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=ghost_set">On Ghost Mode</a>
                                                        <?php
                                                    }
                                                ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="text-left">Pro Mode:</td>
                                            <td>
                                                <?php

                                                    if ($accountInfo['pro'] == 1) {

                                                        echo "<span>Pro Mode Activated.</span>";

                                                    } else {

                                                        echo "<span>Pro Mode Not Active.</span>";
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php

                                                    if ($accountInfo['pro'] == 1) {

                                                        ?>
                                                            <a class="" href="/admin/profile.php/?id=<?php echo $accountInfo['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=promode_unset">Off Pro Mode</a>
                                                        <?php

                                                    } else {

                                                        ?>
                                                            <a class="" href="/admin/profile.php/?id=<?php echo $accountInfo['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=promode_set">On Pro Mode</a>
                                                        <?php
                                                    }
                                                ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="text-left">User active chats (not removed):</td>
                                            <td>
                                                <?php
                                                    $active_chats = $messages->myActiveChatsCount();

                                                    echo $active_chats;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    if ($active_chats > 0) {

                                                        ?>
                                                            <a href="/admin/profile_chats.php/?id=<?php echo $accountInfo['id']; ?>" >View</a></td>
                                                        <?php
                                                    }
                                                ?>
                                        </tr>
                                    </tbody>
                                </table>
                        </div>

                        <div class="row">
                            <div class="col s12">
                                <h4>Edit Account</h4>
                            </div>
                        </div>

                        <?php

                            if (strlen($accountInfo['lowPhotoUrl']) != 0) {

                                ?>
                                    <div class="row">
                                        <div class="col s12 m4">
                                            <div class="card">
                                                <div class="card-image">
                                                    <img src="<?php echo $accountInfo['normalPhotoUrl'] ?>">
                                                    <span class="card-title">Photo</span>
                                                </div>
                                                <div class="card-action">
                                                    <a href="/admin/profile.php/?id=<?php echo $accountInfo['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=delete-photo">Delete</a>
                                                    <a target="_blank" href="<?php echo $accountInfo['bigPhotoUrl'] ?>">View full size</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                            }

                            if (strlen($accountInfo['coverUrl']) != 0) {

                                ?>
                                    <div class="row">
                                        <div class="col s12 m4">
                                            <div class="card">
                                                <div class="card-image">
                                                    <img src="<?php echo $accountInfo['coverUrl'] ?>">
                                                    <span class="card-title">Cover</span>
                                                </div>
                                                <div class="card-action">
                                                    <a href="/admin/profile.php/?id=<?php echo $accountInfo['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=delete-cover">Delete</a>
                                                    <a target="_blank" href="<?php echo $accountInfo['coverUrl'] ?>">View full size</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                            }
                        ?>

                        <form method="post" action="/admin/profile.php/?id=<?php echo $accountInfo['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>">

                                <input type="hidden" name="authenticity_token" value="<?php echo helper::getAuthenticityToken(); ?>">

                                <div class="row">

                                    <div class="input-field col s12">
                                        <input placeholder="Username" id="username" type="text" name="username" maxlength="255" class="validate" value="<?php echo $accountInfo['username']; ?>">
                                        <label for="username">Username</label>
                                    </div>

                                    <div class="input-field col s12">
                                        <input placeholder="Fullname" id="fullname" type="text" name="fullname" maxlength="255" class="validate" value="<?php echo $accountInfo['fullname']; ?>">
                                        <label for="fullname">Fullname</label>
                                    </div>

                                    <div class="input-field col s12">
                                        <input placeholder="Location" id="location" type="text" name="location" maxlength="255" class="validate" value="<?php echo $accountInfo['location']; ?>">
                                        <label for="location">Location</label>
                                    </div>

                                    <div class="input-field col s12">
                                        <input placeholder="Facebook page" id="fb_page" type="text" name="fb_page" maxlength="255" class="validate" value="<?php echo $accountInfo['fb_page']; ?>">
                                        <label for="fb_page">Facebook page</label>
                                    </div>

                                    <div class="input-field col s12">
                                        <input placeholder="Instagram page" id="instagram_page" type="text" name="instagram_page" maxlength="255" class="validate" value="<?php echo $accountInfo['instagram_page']; ?>">
                                        <label for="instagram_page">Instagram page</label>
                                    </div>

                                    <div class="input-field col s12">
                                        <input placeholder="Email" id="email" type="text" name="email" maxlength="255" class="validate" value="<?php echo $accountInfo['email']; ?>">
                                        <label for="email">Email</label>
                                    </div>

                                    <div class="input-field col s12">
                                        <input placeholder="Balance" id="balance" type="text" name="balance" maxlength="255" class="validate" value="<?php echo $accountInfo['balance']; ?>">
                                        <label for="balance">Balance</label>
                                    </div>

                                    <div class="input-field col s12">
                                        <button type="submit" class="btn waves-effect waves-light" name="" >Save</button>
                                    </div>

                                </div>

                            </form>

                            <div class="row">
                                <div class="col s12">
                                    <h4>Authorizations</h4>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col s12">
                                    <a href="/admin/profile.php/?id=<?php echo $accountInfo['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=close">
                                        <button class="btn waves-effect waves-light teal">Close all authorizations<i class="material-icons right">delete</i></button>
                                    </a>
                                </div>
                            </div>

                            <div class="col s12">

                                <?php

                                    $result = $stats->getAuthData($accountInfo['id'], 0);

                                    $inbox_loaded = count($result['data']);

                                    if ($inbox_loaded != 0) {

                                    ?>

                                    <table class="bordered data-tables responsive-table">
                                        <tbody>
                                            <tr>
                                                <th class="text-left">Id</th>
                                                <th>Access token</th>
                                                <th>Client Id</th>
                                                <th>Create At</th>
                                                <th>Close At</th>
                                                <th>User agent</th>
                                                <th>Ip address</th>
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
            <td><?php echo $authObj['accessToken']; ?></td>
            <td><?php echo $authObj['clientId']; ?></td>
            <td><?php echo date("Y-m-d H:i:s", $authObj['createAt']); ?></td>
            <td><?php if ($authObj['removeAt'] == 0) {echo "-";} else {echo date("Y-m-d H:i:s", $authObj['removeAt']);} ?></td>
            <td><?php echo $authObj['u_agent']; ?></td>
            <td><?php if (!APP_DEMO) {echo $authObj['ip_addr'];} else {echo "It is not available in the demo version";} ?></td>
        </tr>

        <?php
    }