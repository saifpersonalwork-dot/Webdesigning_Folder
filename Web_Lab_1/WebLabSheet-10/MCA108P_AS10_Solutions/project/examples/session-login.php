<?php
// Include this after validating the user's password.
session_regenerate_id(true);
$_SESSION['user_id'] = $userId;
