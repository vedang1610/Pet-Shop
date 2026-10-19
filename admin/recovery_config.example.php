<?php
// recovery_config.example.php - copy this file to recovery_config.php and set your own key.
// The key lets you reset the ADMIN password from "Forgot password?" on the admin login page.
// Keep recovery_config.php secret (it is listed in .gitignore). Use at least 12 random characters.
return array(
    'admin_recovery_key' => 'CHANGE-ME-TO-A-LONG-RANDOM-KEY',
);
