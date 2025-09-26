<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SecureFileUploadService
{
    protected $allowedMimeTypes = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'application/pdf',
        'text/plain',
    ];

    protected $allowedExtensions = [
        'jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'txt'
    ];

    protected $maxFileSize = 5 * 1024 * 1024; // 5MB in bytes

    /**
     * Validate and store uploaded file securely
     */
    public function handleUpload(UploadedFile $file, string $directory = 'uploads'): array
    {
        try {
            // Validate file
            $this->validateFile($file);
            
            // Generate secure filename
            $filename = $this->generateSecureFilename($file);
            
            // Store file in secure location
            $path = $this->storeFile($file, $directory, $filename);
            
            // Scan for malware (basic check)
            $this->scanFileForMalware($path);
            
            return [
                'success' => true,
                'path' => $path,
                'filename' => $filename,
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Validate uploaded file
     */
    protected function validateFile(UploadedFile $file): void
    {
        // Check if file was uploaded without errors
        if (!$file->isValid()) {
            throw new \InvalidArgumentException('File upload failed');
        }

        // Check file size
        if ($file->getSize() > $this->maxFileSize) {
            throw new \InvalidArgumentException('File too large. Maximum size: ' . ($this->maxFileSize / 1024 / 1024) . 'MB');
        }

        // Check MIME type
        $mimeType = $file->getMimeType();
        if (!in_array($mimeType, $this->allowedMimeTypes)) {
            throw new \InvalidArgumentException('File type not allowed: ' . $mimeType);
        }

        // Check file extension
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $this->allowedExtensions)) {
            throw new \InvalidArgumentException('File extension not allowed: ' . $extension);
        }

        // Verify file content matches extension (basic check)
        $this->verifyFileContent($file);

        // Check for executable files
        $this->checkForExecutable($file);
    }

    /**
     * Verify file content matches its extension
     */
    protected function verifyFileContent(UploadedFile $file): void
    {
        $extension = strtolower($file->getClientOriginalExtension());
        
        // For images, verify it's actually an image
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            $imageInfo = @getimagesize($file->getPathname());
            if (!$imageInfo) {
                throw new \InvalidArgumentException('File is not a valid image');
            }
        }

        // For PDFs, check PDF signature
        if ($extension === 'pdf') {
            $handle = fopen($file->getPathname(), 'r');
            $header = fread($handle, 4);
            fclose($handle);
            
            if ($header !== '%PDF') {
                throw new \InvalidArgumentException('File is not a valid PDF');
            }
        }
    }

    /**
     * Check for executable content
     */
    protected function checkForExecutable(UploadedFile $file): void
    {
        $content = file_get_contents($file->getPathname());
        
        // Check for common executable signatures
        $executableSignatures = [
            'MZ',      // Windows PE
            '#!/bin/', // Unix shebang
            '<?php',   // PHP script
            '<script', // JavaScript
            'PK',      // ZIP/JAR files
        ];

        foreach ($executableSignatures as $signature) {
            if (strpos($content, $signature) === 0) {
                throw new \InvalidArgumentException('Executable files are not allowed');
            }
        }

        // Check for suspicious patterns in the content
        $suspiciousPatterns = [
            '/eval\s*\(/i',
            '/exec\s*\(/i',
            '/system\s*\(/i',
            '/shell_exec\s*\(/i',
            '/<\?php/i',
            '/<script/i',
        ];

        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                throw new \InvalidArgumentException('File contains suspicious content');
            }
        }
    }

    /**
     * Generate secure filename
     */
    protected function generateSecureFilename(UploadedFile $file): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $timestamp = now()->format('Y-m-d_H-i-s');
        $randomString = Str::random(16);
        
        return $timestamp . '_' . $randomString . '.' . $extension;
    }

    /**
     * Store file securely
     */
    protected function storeFile(UploadedFile $file, string $directory, string $filename): string
    {
        // Create directory structure based on date
        $datePath = now()->format('Y/m');
        $fullDirectory = $directory . '/' . $datePath;
        
        // Store file
        $path = $file->storeAs($fullDirectory, $filename, 'public');
        
        // Set proper permissions
        $fullPath = storage_path('app/public/' . $path);
        chmod($fullPath, 0644);
        
        return $path;
    }

    /**
     * Basic malware scanning (you might want to integrate with a proper antivirus)
     */
    protected function scanFileForMalware(string $path): void
    {
        $fullPath = storage_path('app/public/' . $path);
        $content = file_get_contents($fullPath);
        
        // Check for common malware signatures
        $malwarePatterns = [
            '/(\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*\s*=\s*["\'][^"\']*["\'];\s*eval\s*\()/i',
            '/(base64_decode\s*\(\s*["\'][A-Za-z0-9+\/=]+["\']\s*\))/i',
            '/(gzinflate\s*\(\s*base64_decode)/i',
            '/(eval\s*\(\s*gzinflate)/i',
        ];

        foreach ($malwarePatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                // Delete the file immediately
                unlink($fullPath);
                throw new \InvalidArgumentException('File contains malicious content and has been blocked');
            }
        }
    }

    /**
     * Delete file securely
     */
    public function deleteFile(string $path): bool
    {
        try {
            // Verify path is within allowed directories
            $allowedDirectories = ['uploads', 'images', 'documents'];
            $pathSegments = explode('/', $path);
            
            if (!in_array($pathSegments[0], $allowedDirectories)) {
                throw new \InvalidArgumentException('Invalid file path');
            }

            return Storage::disk('public')->delete($path);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get file info securely
     */
    public function getFileInfo(string $path): ?array
    {
        try {
            $fullPath = storage_path('app/public/' . $path);
            
            if (!file_exists($fullPath)) {
                return null;
            }

            return [
                'size' => filesize($fullPath),
                'mime_type' => mime_content_type($fullPath),
                'modified' => filemtime($fullPath),
                'path' => $path,
            ];
        } catch (\Exception $e) {
            return null;
        }
    }
}