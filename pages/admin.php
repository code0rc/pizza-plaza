<?php

if(empty($_SESSION['isLoggedIn'])) {
    include APP_ROOT . '/partials/admin-login.php';
} else {
    if(!empty(filter_input(INPUT_GET, 'logout', FILTER_SANITIZE_NUMBER_INT))) {
        include APP_ROOT . '/partials/admin-logout.php';
    } else {
        include APP_ROOT . '/partials/admin.php';
    }
}