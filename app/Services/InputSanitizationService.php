<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class InputSanitizationService
{
    /**
     * Sanitize and validate user input
     */
    public static function sanitizeInput(array $data, array $rules): array
    {
        // First, sanitize the data
        $sanitized = self::sanitizeData($data);
        
        // Then validate it
        $validator = Validator::make($sanitized, $rules);
        
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        
        return $sanitized;
    }

    /**
     * Sanitize data array
     */
    protected static function sanitizeData(array $data): array
    {
        $sanitized = [];
        
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = self::sanitizeData($value);
            } elseif (is_string($value)) {
                $sanitized[$key] = self::sanitizeString($value);
            } else {
                $sanitized[$key] = $value;
            }
        }
        
        return $sanitized;
    }

    /**
     * Sanitize string input
     */
    protected static function sanitizeString(string $input): string
    {
        // Remove null bytes
        $input = str_replace(chr(0), '', $input);
        
        // Strip HTML tags (except allowed ones)
        $input = strip_tags($input, '<b><i><u><em><strong>');
        
        // Convert special characters to HTML entities
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        
        // Remove potential XSS patterns
        $xssPatterns = [
            '/javascript:/i',
            '/vbscript:/i',
            '/onload/i',
            '/onerror/i',
            '/onclick/i',
            '/onmouseover/i',
        ];
        
        foreach ($xssPatterns as $pattern) {
            $input = preg_replace($pattern, '', $input);
        }
        
        // Trim whitespace
        return trim($input);
    }

    /**
     * Sanitize email input
     */
    public static function sanitizeEmail(string $email): string
    {
        return filter_var(trim(strtolower($email)), FILTER_SANITIZE_EMAIL);
    }

    /**
     * Sanitize URL input
     */
    public static function sanitizeUrl(string $url): string
    {
        return filter_var(trim($url), FILTER_SANITIZE_URL);
    }

    /**
     * Sanitize filename for file uploads
     */
    public static function sanitizeFilename(string $filename): string
    {
        // Remove path characters
        $filename = basename($filename);
        
        // Remove dangerous characters
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $filename);
        
        // Limit length
        if (strlen($filename) > 100) {
            $filename = substr($filename, 0, 100);
        }
        
        return $filename;
    }

    /**
     * Common validation rules for different input types
     */
    public static function getCommonRules(): array
    {
        return [
            'name_rules' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'email_rules' => ['required', 'email', 'max:255'],
            'password_rules' => ['required', 'string', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/'],
            'mobile_rules' => ['required', 'string', 'regex:/^[0-9+\-\s()]+$/', 'max:20'],
            'text_rules' => ['required', 'string', 'max:1000'],
            'url_rules' => ['nullable', 'url', 'max:255'],
        ];
    }

    /**
     * Validate password strength
     */
    public static function validatePasswordStrength(string $password): bool
    {
        // At least 8 characters, one uppercase, one lowercase, one digit, one special character
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
        return preg_match($pattern, $password) === 1;
    }

    /**
     * Check for SQL injection patterns
     */
    public static function containsSqlInjection(string $input): bool
    {
        $patterns = [
            '/(\b(select|insert|update|delete|drop|create|alter|exec|union)\b)/i',
            '/(\b(or|and)\b\s*\d+\s*=\s*\d+)/i',
            '/(\'\s*(or|and)\s*\'[^\']*\'\s*=\s*\'[^\']*\')/i',
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }
        
        return false;
    }
}