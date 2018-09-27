<?php

include_once '../library.php';

session_start();
unset ( $_SESSION [KSCONFIG_DB_NAME] );

header("Location: login.php?msg=logout");
