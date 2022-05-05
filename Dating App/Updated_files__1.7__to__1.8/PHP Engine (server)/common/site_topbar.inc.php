<?php

    /*!
     * ifsoft.co.uk v1.0
     *
     * http://ifsoft.com.ua, http://ifsoft.co.uk
     * qascript@ifsoft.co.uk
     *
     * Copyright 2012-2016 Demyanchuk Dmitry (https://vk.com/dmitry.demyanchuk)
     */


    if (!auth::isSession()) {

        ?>

            <nav class="<?php echo SITE_THEME; ?>" role="navigation">
                <div class="nav-wrapper container">
                    <a href="/" class="brand-logo"><?php echo APP_NAME; ?></a>
                    <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
                    <ul class="right hide-on-med-and-down">
                        <li><a href="/login.php"><?php echo $LANG['action-login']; ?></a></li>
                        <li><a href="/signup.php"><?php echo $LANG['action-signup']; ?></a></li>
                    </ul>
                    <ul class="side-nav" id="mobile-demo">
                        <li><a href="/login.php"><?php echo $LANG['action-login']; ?></a></li>
                        <li><a href="/signup.php"><?php echo $LANG['action-signup']; ?></a></li>
                    </ul>
                </div>
            </nav>

        <?php

    } else {

        ?>


            <header class="content">

                <div class="navbar-fixed">

                    <ul id="dropdown1" class="dropdown-content">
                        <li><a href="/profile.php/?id=<?php echo auth::getCurrentUserId(); ?>" class="waves-effect waves-ripple <?php echo SITE_TEXT_COLOR; ?>"><?php echo $LANG['topbar-profile']; ?></a></li>
                        <li><a href="/settings.php" class="waves-effect waves-ripple <?php echo SITE_TEXT_COLOR; ?>"><?php echo $LANG['topbar-settings']; ?></a></li>
                        <li class="divider"></li>
                        <li><a href="/logout.php/?access_token=<?php echo auth::getAccessToken(); ?>&continue=/" class="waves-effect waves-ripple <?php echo SITE_TEXT_COLOR; ?>"><?php echo $LANG['topbar-logout']; ?></a></li>
                    </ul>

                    <nav class="top-nav <?php echo SITE_THEME; ?>">
                        <div class="nav-wrapper">
                            <a href="#" data-activates="nav-mobile" class="button-collapse top-nav full">
                                <i class="large material-icons">reorder</i>
                            </a>

                            <a href="javascript:void(0)" class="page-title"><?php echo APP_TITLE; ?></a>

                            <ul class="right hide-on-med-and-down" style="margin-right: 250px;">
                                <li><a class="dropdown-button" href="#!" data-activates="dropdown1"><i style="padding-left: 0px;" class="material-icons right">more_vert</i><?php echo auth::getCurrentUserFullname(); ?></a></li>
                            </ul>

                        </div>
                    </nav>
                </div>

                <ul id="nav-mobile" class="side-nav fixed" style="left: 0px;">

                    <li class="collection-header grey lighten-5 center-align" style="line-height: normal"><br>
                        <img class="responsive-img circle profile-img" style="max-width: 60%" src="<?php echo auth::getCurrentUserPhotoUrl(); ?>"><br>
                        <h4><?php echo auth::getCurrentUserFullname(); ?></h4>
                        <br>
                    </li>

                    <li class="bold <?php if (isset($page_id) && $page_id === "my-profile") { echo "active grey lighten-3";} ?>">
                        <a href="/profile.php/?id=<?php echo auth::getCurrentUserId(); ?>" class="waves-effect waves-ripple"><b><?php echo $LANG['topbar-profile']; ?></b></a>
                    </li>

                    <li class="bold <?php if (isset($page_id) && $page_id === "messages") { echo "active grey lighten-3";} ?>">
                        <a href="/messages.php" class="waves-effect waves-ripple"><b><?php echo $LANG['topbar-messages']; ?> <span class="badge" style="display: none" id="messages_counter_cont">(<span id="messages_counter">0</span>)</span></b></a>
                    </li>

                    <li class="bold <?php if (isset($page_id) && $page_id === "notifications") { echo "active grey lighten-3";} ?>">
                        <a href="/notifications.php" class="waves-effect waves-ripple"><b><?php echo $LANG['topbar-likes']; ?> <span class="badge" style="display: none" id="notifications_counter_cont">(<span id="notifications_counter">0</span>)</span></b></a>
                    </li>

                    <li class="bold <?php if (isset($page_id) && $page_id === "friends") { echo "active grey lighten-3";} ?>">
                        <a href="/friends.php" class="waves-effect waves-ripple"><b><?php echo $LANG['topbar-friends']; ?> <span class="badge" style="display: none" id="friends_counter_cont">(<span id="friends_counter">0</span>)</span></b></a>
                    </li>

                    <li class="bold <?php if (isset($page_id) && $page_id === "guests") { echo "active grey lighten-3";} ?>">
                        <a href="/guests.php" class="waves-effect waves-ripple"><b><?php echo $LANG['topbar-guests']; ?> <span class="badge" style="display: none" id="guests_counter_cont">(<span id="guests_counter">0</span>)</span></b></a>
                    </li>

                    <li class="bold <?php if (isset($page_id) && $page_id === "likes") { echo "active grey lighten-3";} ?>">
                        <a href="/likes.php" class="waves-effect waves-ripple"><b><?php echo $LANG['topbar-profile-likes']; ?></b></a>
                    </li>

                    <li class="bold <?php if (isset($page_id) && $page_id === "liked") { echo "active grey lighten-3";} ?>">
                        <a href="/liked.php" class="waves-effect waves-ripple"><b><?php echo $LANG['topbar-i-liked']; ?></b></a>
                    </li>

                    <li class="bold <?php if (isset($page_id) && $page_id === "search") { echo "active grey lighten-3";} ?>">
                        <a href="/search.php" class="waves-effect waves-ripple"><b><?php echo $LANG['topbar-search']; ?></b></a>
                    </li>

                    <li class="bold <?php if (isset($page_id) && $page_id === "settings") { echo "active grey lighten-3";} ?>">
                        <a href="/settings.php" class="waves-effect waves-ripple"><b><?php echo $LANG['topbar-settings']; ?></b></a>
                    </li>

                    <li class="bold">
                        <a href="/logout.php/?access_token=<?php echo auth::getAccessToken(); ?>&continue=/" class="waves-effect waves-ripple"><b><?php echo $LANG['topbar-logout']; ?></b></a>
                    </li>

                </ul>

            </header>
        <?php
    }
?>