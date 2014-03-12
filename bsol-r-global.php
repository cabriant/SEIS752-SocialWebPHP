<?php
    function renderHead() {
        ?>
        <meta name='viewport' content='width=device-width,initial-scale=1,user-scalable=no'>
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
        <!-- <link rel="stylesheet" type="text/css" href="css/jquery-ui-1.10.3.custom.min.css"> -->
        <link rel='stylesheet' type='text/css' href='css/site.min.css'>
        <!-- <link rel='shortcut icon' type='image/x-icon' href='favicon.ico'> -->
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
        <?php
    }

    function renderProfileNavBar() {
        renderNavBar(true, "profile");
    }

    function renderAllUsersNavBar() {
        renderNavBar(true, "all");
    }

    function renderMyFriendsNavBar() {
        renderNavBar(true, "myfriends");
    }

    function renderSearchNavBar() {
        renderNavBar(true, "search");
    }

    function renderSearchAjaxNavBar() {
        renderNavBar(true, "searchAjax");
    }

    function renderEmptyNavBar() {
        renderNavBar(false, "");
    }

    function renderNavBar($show_tabs, $active_tab)
    {
        ?>
        <!-- Fixed navbar -->
        <div class='navbar navbar-default navbar-fixed-top bottom-box-shadow'>
            <div class="container">
                <div class='navbar-header'>
                    <?php if ($show_tabs)
                    {
                        ?>
                        <button type='button' class='navbar-toggle' data-toggle='collapse' data-target='.navbar-collapse'>
                            <span class='icon-bar'></span>
                            <span class='icon-bar'></span>
                            <span class='icon-bar'></span>
                        </button>
                        <?php
                    }
                    ?>
                    <a class='navbar-brand hidden-xs' href='http://www.briantsolutions.com/profile.php'>Briant Solutions | Social Network</a>
                    <a class='navbar-brand visible-xs' href='http://www.briantsolutions.com/profile.php'>Briant Solutions</a>
                </div>
                <div class='navbar-collapse collapse'>
                    <?php if ($show_tabs)
                    {
                        ?>
                        <ul class='nav navbar-nav'>
                            <?php

                            renderSimpleNavBarItem("profile.php", "Profile", (strcasecmp($active_tab, "profile") == 0));
                            renderSimpleNavBarItem("allprofiles.php", "All Users", (strcasecmp($active_tab, "all") == 0));
                            renderSimpleNavBarItem("myfriends.php", "My Friends", (strcasecmp($active_tab, "myfriends") == 0));
                            renderSimpleNavBarItem("search.php", "Search", (strcasecmp($active_tab, "search") == 0));
                            renderSimpleNavBarItem("searchAjax.php", "Search AJAX", (strcasecmp($active_tab, "searchAjax") == 0));

                            ?>
                        </ul>
                        <?php
                    }

                    $user = getUserFromSession();
                    if (!empty($user))
                    {
                        ?>
                        <a href="logoff.php" class="logoff-btn pull-right"> Log Off</a>
                        <div class="pull-right">
                            <?php echo $user['name']; ?> | 
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
    }

    function renderSimpleNavBarItem($href, $name, $active)
    {
        ?>
        <li <?php if ($active) { echo "class='active'"; } ?>>
            <a href="<?php echo $href; ?>"><?php echo $name; ?></a>
        </li>
        <?php
    }

    function renderFooter() {
        ?>
        <div class="footer">
            <div class="container">
                <a class='emblem' href='http://www.briantsolutions.edu/profile.php'>Briant Solutions</a>
                <a class='footer-link' href='#'>&copy; 2014 Briant Solutions</a>
            </div>
        </div>
        <?php
    }

    function renderJSLinks()
    {
        ?>
        <script src='http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>
        <script>window.jQuery || document.write('<script src="js/jquery-1.10.2.min.js"><\/script>')</script>
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
        <!--[if lt IE 9]>
        <script src='js/html5shiv.js'></script>
        <script src='js/respond.min.js'></script>
        <![endif]-->
    <?php
    }

    function getMainTitle() {
        echo "Briant Solutions - ";
    }

    function renderErrorMessage($key) {
        $message = getFromSession($key, false);
        if (!empty($message)) {
            // let's show our message to the user
            ?>
            <div class="error-box">
                <?php echo $message; ?>
            </div>
            <?php
        }
    }

    function renderInfoMessage($key) {
        $message = getFromSession($key, false);
        if (!empty($message)) {
            // let's show our message to the user
            ?>
            <div class="info-box">
                <?php echo $message; ?>
            </div>
            <?php
        }
    }
?>