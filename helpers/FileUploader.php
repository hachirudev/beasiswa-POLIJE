<?php
declare(strict_types=1);

class FileUploader
{
    private string $uploadDir;
    private array $allowedTypes = ['application/pdf'];
    private int $maxSize = 5242880; // 5MB dalam bytes

    public function __construct(string $uploadDir)
    {
        $this->uploadDir = rtrim($uploadDir, '/\\') . DIRECTORY_SEPARATOR;

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    public function upload(array $file): string
    {
        $this->validateFile($file);

        $sanitizedName = $this->sanitizeFileName($file['name']);
        $uniqueName = uniqid('', true) . '_' . $sanitizedName;
        $destination = $this->uploadDir . $uniqueName;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new Exception('Gagal memindahkan file yang diunggah.');
        }

        // Kembalikan path relatif dari public/
        $publicPos = strpos($destination, 'uploads' . DIRECTORY_SEPARATOR);
        if ($publicPos !== false) {
            return str_replace('\\', '/', substr($destination, $publicPos));
        }

        return str_replace('\\', '/', $uniqueName);
    }

    public function uploadMultiple(array $files): array
    {
        $paths = [];

        // Normalisasi struktur $_FILES jika multiple
        if (isset($files['name']) && is_array($files['name'])) {
            $fileCount = count($files['name']);
            for ($i = 0; $i < $fileCount; $i++) {
                $singleFile = [
                    'name'     => $files['name'][$i],
                    'type'     => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error'    => $files['error'][$i],
                    'size'     => $files['size'][$i],
                ];
                $paths[] = $this->upload($singleFile);
            }
        } else {
            // Jika sudah berupa array of single file
            foreach ($files as $file) {
                $paths[] = $this->upload($file);
            }
        }

        return $paths;
    }

    public function delete(string $filePath): bool
    {
        // Bangun path absolut jika path relatif diberikan
        if (!str_starts_with($filePath, '/') && !preg_match('/^[A-Za-z]:/', $filePath)) {
            $absolutePath = dirname($this->uploadDir, 2) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $filePath);
        } else {
            $absolutePath = $filePath;
        }

        if (file_exists($absolutePath)) {
            return unlink($absolutePath);
        }

        return false;
    }

    private function sanitizeFileName(string $fileName): string
    {
        // Ambil nama file dan ekstensi
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $baseName = pathinfo($fileName, PATHINFO_FILENAME);

        // Hapus karakter berbahaya, hanya izinkan alfanumerik, strip, underscore, titik
        $baseName = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $baseName);
        $baseName = preg_replace('/_+/', '_', $baseName);
        $baseName = trim($baseName, '_');

        if ($baseName === '') {
            $baseName = 'file';
        }

        return $baseName . '.' . strtolower($extension);
    }

    private function validateFile(array $file): void
    {
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Terjadi kesalahan saat mengunggah file.');
        }

        if (!in_array($file['type'], $this->allowedTypes, true)) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mimeType, $this->allowedTypes, true)) {
                throw new Exception('Tipe file tidak diizinkan. Hanya file PDF yang diterima.');
            }
        }

        if ($file['size'] > $this->maxSize) {
            throw new Exception('Ukuran file melebihi batas maksimal 5MB.');
        }
    }
}
