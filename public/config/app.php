<?php
declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));

define('CLASSES_PATH', BASE_PATH . '/classes/');
define('HELPERS_PATH', BASE_PATH . '/helpers/');
define('CONFIG_PATH',  BASE_PATH . '/config/');

define('BASE_URL', '/beasiswa-polije-finale/public');

define('UPLOAD_PATH',   BASE_PATH . '/uploads/');
define('UPLOAD_URL',    BASE_URL  . '/uploads/');
define('MAX_FILE_SIZE', 5242880);
define('ALLOWED_TYPES', ['application/pdf']);

define('APP_NAME',    'Beasiswa POLIJE');
define('APP_VERSION', '1.0.0');
