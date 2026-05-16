<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Beasiswa POLIJE' ?></title>
    <meta name="description" content="<?= $pageDescription ?? 'Sistem Informasi Beasiswa Politeknik Negeri Jember' ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets/css/style.css?v=<?= filemtime(__DIR__ . '/../../assets/css/style.css') ?>"
        rel="stylesheet">
    <script>
        window.BASE_URL = '<?= BASE_URL ?>';
    </script>
</head>

<body>