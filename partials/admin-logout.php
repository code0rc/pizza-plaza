<?php

unset($_SESSION['isLoggedIn']);
header('Location: ?site=admin&logout=1');