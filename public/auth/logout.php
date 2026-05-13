<?php
declare(strict_types=1);
require_once '../../config/app.php';
require_once HELPERS_PATH . 'Session.php';
require_once HELPERS_PATH . 'Response.php';

Session::start();
Session::destroy();
Response::redirectTo(BASE_URL . '/auth/login.php');
