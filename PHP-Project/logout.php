<?php
session_start();
session_destroy();
header("Location: /PHP-Project/index.php");
exit();
?>