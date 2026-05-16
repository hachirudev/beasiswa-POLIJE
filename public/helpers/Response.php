<?php
declare(strict_types=1);

class Response
{
    public static function redirectTo(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }

    public static function sendJson(mixed $data, int $statusCode = 200): void
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($statusCode);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public static function setFlash(string $key, string $message): void
    {
        $_SESSION['_flash'][$key] = $message;
    }

    public static function getFlash(string $key): ?string
    {
        if (isset($_SESSION['_flash'][$key])) {
            $message = $_SESSION['_flash'][$key];
            unset($_SESSION['_flash'][$key]);
            return $message;
        }
        return null;
    }

    public static function abort(int $code = 404): void
    {
        http_response_code($code);
        $messages = [
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        ];
        $message = $messages[$code] ?? 'Error';
        echo '<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error ' . $code . '</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-color: #f8f9fa;
            color: #343a40;
        }
        .error-container {
            text-align: center;
            padding: 2rem;
        }
        .error-code {
            font-size: 5rem;
            font-weight: 700;
            color: #dc3545;
            margin: 0;
        }
        .error-message {
            font-size: 1.25rem;
            color: #6c757d;
            margin-top: 0.5rem;
        }
        a {
            color: #0d6efd;
            text-decoration: none;
            margin-top: 1rem;
            display: inline-block;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <p class="error-code">' . $code . '</p>
        <p class="error-message">' . htmlspecialchars($message) . '</p>
        <a href="javascript:history.back()">Kembali</a>
    </div>
</body>
</html>';
        exit;
    }
}
