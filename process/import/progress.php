<?php
session_start();
echo isset($_SESSION['progress']) ? $_SESSION['progress'] : 0;
?>
