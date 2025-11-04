<?php

session_start();

session_destroy();


header("Location: /restaurante/admin/login.php");
exit;
?>