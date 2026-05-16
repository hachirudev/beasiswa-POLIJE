<?php
declare(strict_types=1);
require_once '../../../config/app.php';
require_once HELPERS_PATH . 'Session.php';
require_once CONFIG_PATH  . 'Database.php';
require_once CLASSES_PATH . 'Beasiswa.php';

Session::start();
Session::requireLogin();

header('Content-Type: application/json');

try {
    $db = Database::getInstance()->getConnection();
    $beasiswaObj = new Beasiswa($db);

    $filters = [
        'q'        => $_GET['q'] ?? '',
        'year'     => (int) ($_GET['year'] ?? 0),
        'month'    => (int) ($_GET['month'] ?? 0),
        'tags'     => isset($_GET['tags']) && is_array($_GET['tags']) ? $_GET['tags'] : [],
        'ipk'      => $_GET['ipk'] ?? '',
        'semester' => $_GET['semester'] ?? ''
    ];
    $page = max(1, (int) ($_GET['page'] ?? 1));

    $result = $beasiswaObj->searchAdvanced($filters, $page, 6);

    echo json_encode([
        'status' => 'success',
        'data' => $result['data'],
        'pagination' => [
            'current_page' => $result['current_page'],
            'total_pages'  => $result['total_pages'],
            'total_rows'   => $result['total_rows']
        ]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Terjadi kesalahan pada server.'
    ]);
}
