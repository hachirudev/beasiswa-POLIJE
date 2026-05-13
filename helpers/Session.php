<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/config/app.php';

class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key): mixed
    {
        return $_SESSION[$key] ?? null;
    }

    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public static function destroy(): void
    {
        session_unset();
        session_destroy();
    }

    public static function isLoggedIn(): bool
    {
        return self::has('id') && self::has('role');
    }

    public static function getRole(): ?string
    {
        return self::get('role');
    }

    public static function getId(): ?int
    {
        $id = self::get('id');
        return $id !== null ? (int) $id : null;
    }

    public static function getNama(): ?string
    {
        return self::get('nama');
    }

    public static function requireLogin(): void
    {
        if (!self::isLoggedIn()) {
            header('Location: ' . BASE_URL . '/auth/login-mahasiswa.php');
            exit;
        }
    }

    public static function requireRole(string $role): void
    {
        if (self::getRole() !== $role) {
            $loginPages = [
                'mahasiswa' => '/auth/login-mahasiswa.php',
                'mitra'     => '/auth/login-mitra.php',
                'admin'     => '/auth/login-admin.php',
            ];

            $page = $loginPages[$role] ?? '/auth/login-mahasiswa.php';
            header('Location: ' . BASE_URL . $page);
            exit;
        }
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
}
