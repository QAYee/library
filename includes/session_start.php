<?php
// Check if session has already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
