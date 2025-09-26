<?php

return [
    
    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains security-related configuration options for your
    | TripMate application. These settings help protect against common
    | web security vulnerabilities.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | HTTPS Enforcement
    |--------------------------------------------------------------------------
    |
    | When enabled, this will force all requests to use HTTPS in production.
    | This should be enabled when you have SSL certificates configured.
    |
    */
    'force_https' => env('FORCE_HTTPS', false),

    /*
    |--------------------------------------------------------------------------
    | Password Security Settings
    |--------------------------------------------------------------------------
    |
    | Configure password requirements and security measures.
    |
    */
    'password' => [
        'min_length' => 8,
        'require_uppercase' => true,
        'require_lowercase' => true,
        'require_numbers' => true,
        'require_special_chars' => true,
        'max_attempts' => 5,
        'lockout_duration' => 15, // minutes
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Configuration
    |--------------------------------------------------------------------------
    |
    | Configure rate limits for different endpoints and actions.
    |
    */
    'rate_limits' => [
        'login' => [
            'max_attempts' => 5,
            'decay_minutes' => 15,
        ],
        'api' => [
            'max_attempts' => 60,
            'decay_minutes' => 1,
        ],
        'password_reset' => [
            'max_attempts' => 3,
            'decay_minutes' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | File Upload Security
    |--------------------------------------------------------------------------
    |
    | Configure allowed file types, sizes, and security measures for uploads.
    |
    */
    'uploads' => [
        'max_file_size' => 5 * 1024 * 1024, // 5MB
        'allowed_mime_types' => [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'application/pdf',
        ],
        'allowed_extensions' => [
            'jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf'
        ],
        'scan_for_malware' => true,
        'quarantine_suspicious' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Headers Configuration
    |--------------------------------------------------------------------------
    |
    | Configure security headers that will be sent with responses.
    |
    */
    'headers' => [
        'x_frame_options' => 'DENY',
        'x_content_type_options' => 'nosniff',
        'x_xss_protection' => '1; mode=block',
        'referrer_policy' => 'strict-origin-when-cross-origin',
        'permissions_policy' => 'camera=(), microphone=(), geolocation=()',
    ],

    /*
    |--------------------------------------------------------------------------
    | Input Validation Security
    |--------------------------------------------------------------------------
    |
    | Configure input validation and sanitization settings.
    |
    */
    'input_validation' => [
        'max_input_length' => 10000,
        'strip_html_tags' => true,
        'prevent_xss' => true,
        'detect_sql_injection' => true,
        'log_suspicious_input' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Session Security
    |--------------------------------------------------------------------------
    |
    | Additional session security settings.
    |
    */
    'session' => [
        'regenerate_on_login' => true,
        'invalidate_on_logout' => true,
        'check_ip_address' => env('SESSION_CHECK_IP', false),
        'check_user_agent' => env('SESSION_CHECK_UA', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Security
    |--------------------------------------------------------------------------
    |
    | Database security configuration.
    |
    */
    'database' => [
        'enable_query_log' => env('DB_QUERY_LOG', false),
        'log_slow_queries' => env('DB_LOG_SLOW_QUERIES', true),
        'slow_query_threshold' => 1000, // milliseconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Monitoring and Logging
    |--------------------------------------------------------------------------
    |
    | Security monitoring and logging configuration.
    |
    */
    'monitoring' => [
        'log_failed_logins' => true,
        'log_suspicious_activity' => true,
        'alert_on_multiple_failures' => true,
        'max_failures_before_alert' => 10,
        'enable_security_scanner' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Two-Factor Authentication
    |--------------------------------------------------------------------------
    |
    | Configuration for 2FA implementation.
    |
    */
    'two_factor' => [
        'enabled' => env('TWO_FACTOR_ENABLED', false),
        'issuer' => env('APP_NAME', 'TripMate'),
        'backup_codes_count' => 8,
        'window' => 1, // Time window tolerance
    ],

    /*
    |--------------------------------------------------------------------------
    | API Security
    |--------------------------------------------------------------------------
    |
    | Security settings for API endpoints.
    |
    */
    'api' => [
        'enable_cors' => true,
        'allowed_origins' => ['http://localhost:3000'], // Add your frontend URLs
        'require_authentication' => true,
        'enable_rate_limiting' => true,
    ],

];