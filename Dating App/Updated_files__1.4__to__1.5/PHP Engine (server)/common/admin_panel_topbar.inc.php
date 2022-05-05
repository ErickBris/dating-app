<?php

/*!
 * ifsoft.co.uk engine v1.0
 *
 * http://ifsoft.com.ua, http://ifsoft.co.uk
 * qascript@ifsoft.co.uk
 *
 * Copyright 2012-2016 Demyanchuk Dmitry (https://vk.com/dmitry.demyanchuk)
 */


    if (!admin::isSession()) {

        ?>

        <nav class="light-blue lighten-1" role="navigation">
            <div class="nav-wrapper container">
                <a href="/" class="brand-logo"><?php echo APP_NAME; ?></a>
                <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
                <ul class="right hide-on-med-and-down">
                    <li><a href="/admin/login.php">Log In</a></li>
                </ul>
                <ul class="side-nav" id="mobile-demo">
                    <li><a href="/admin/login.php">Log In</a></li>
                </ul>
            </div>
        </nav>

        <?php

    } else {

        ?>

        <header class="content">

            <div class="navbar-fixed">

                <ul id="dropdown1" class="dropdown-content">
                    <li><a href="/admin/support.php">Support</a></li>
                    <li><a href="/admin/settings.php">Settings</a></li>
                    <li class="divider"></li>
                    <li><a href="/admin/logout.php/?access_token=<?php echo admin::getAccessToken(); ?>&continue=/" class="waves-effect waves-teal">Logout</a></li>
                </ul>

                <nav class="top-nav light-blue">
                    <div class="nav-wrapper">
                        <a href="#" data-activates="nav-mobile" class="button-collapse top-nav full">
                            <i class="large material-icons">reorder</i>
                        </a>
                        <a href="#" class="page-title">Admin Panel</a>

                        <ul class="right hide-on-med-and-down" style="margin-right: 250px;">
                            <li><a href="/admin/profile_reports.php">Profile Reports</a></li>
                            <li><a href="/admin/photo_reports.php">Photo Reports</a></li>
                            <li><a href="/admin/stream_messages.php">Messages Stream</a></li>
                            <li><a class="dropdown-button" href="#!" data-activates="dropdown1"><i style="padding-left: 0px;" class="material-icons right">more_vert</i>Administrator</a></li>
                        </ul>

                    </div>
                </nav>
            </div>

            <ul id="nav-mobile" class="side-nav fixed" style="left: 0px;">

                <li class="collection-header grey lighten-5 center-align" style="line-height: normal"><br>
                    <img class="responsive-img" style="max-width: 60%" src="/img/panel_logo.png"><br>
                    <h4><?php echo APP_NAME; ?></h4>
                    <br>
                </li>

                <li class="bold <?php if (isset($page_id) && $page_id === "main") { echo "active grey lighten-3";} ?>">
                    <a href="/admin/main.php" class="waves-effect waves-ripple"><b>General</b></a>
                </li>

                <li class="bold <?php if (isset($page_id) && $page_id === "gifts") { echo "active grey lighten-3";} ?>">
                    <a href="/admin/gifts.php" class="waves-effect waves-ripple"><b>Gifts</b></a>
                </li>

                <li class="bold <?php if (isset($page_id) && $page_id === "users") { echo "active grey lighten-3";} ?>">
                    <a href="/admin/users.php" class="waves-effect waves-ripple"><b>Users</b></a>
                </li>

                <li class="bold <?php if (isset($page_id) && $page_id === "admob") { echo "active grey lighten-3";} ?>">
                    <a href="/admin/admob.php" class="waves-effect waves-ripple"><b>AdMob</b></a>
                </li>

                <li class="bold <?php if (isset($page_id) && $page_id === "gcm") { echo "active grey lighten-3";} ?>">
                    <a href="/admin/gcm.php" class="waves-effect waves-ripple"><b>Push Notifications</b></a>
                </li>

                <li class="bold <?php if (isset($page_id) && $page_id === "support") { echo "active grey lighten-3";} ?>">
                    <a href="/admin/support.php" class="waves-effect waves-ripple"><b>Support</b></a>
                </li>

                <li class="bold <?php if (isset($page_id) && $page_id === "settings") { echo "active grey lighten-3";} ?>">
                    <a href="/admin/settings.php" class="waves-effect waves-ripple"><b>Settings</b></a>
                </li>

                <li class="bold">
                    <a href="/admin/logout.php/?access_token=<?php echo admin::getAccessToken(); ?>&continue=/" class="waves-effect waves-ripple"><b>Logout</b></a>
                </li>

            </ul>

        </header>

        <?php
    }
?>