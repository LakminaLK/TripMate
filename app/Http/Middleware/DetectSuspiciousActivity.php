<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DetectSuspiciousActivity
{
    protected $suspiciousPatterns = [
        // SQL Injection patterns
        '/(\b(union|select|insert|update|delete|drop|create|alter|exec|script)\b)/i',
        // XSS patterns
        '/(<script|javascript:|on\w+\s*=)/i',
        // Path traversal
        '/(\.\.[\/\\]|\.\.%2f|\.\.%5c)/i',
        // Command injection
        '/(\b(system|exec|shell_exec|passthru|eval)\s*\()/i',
    ];

    protected $suspiciousUserAgents = [
        'sqlmap',
        'nikto',
        'nessus',
        'burp',
        'zap',
        'w3af',
        'havij',
        'pangolin'
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $suspiciousScore = $this->calculateSuspiciousScore($request);
        
        if ($suspiciousScore > 5) {
            $this->logSuspiciousActivity($request, $suspiciousScore);
            
            // Block if score is very high
            if ($suspiciousScore > 10) {
                return response()->json(['error' => 'Request blocked for security reasons'], 403);
            }
        }

        return $next($request);
    }

    /**
     * Calculate a suspicious score for the request.
     */
    protected function calculateSuspiciousScore(Request $request): int
    {
        $score = 0;
        
        // Check all input parameters
        $allInput = array_merge(
            $request->all(),
            $request->headers->all()
        );
        
        foreach ($allInput as $key => $value) {
            if (is_string($value)) {
                foreach ($this->suspiciousPatterns as $pattern) {
                    if (preg_match($pattern, $value)) {
                        $score += 3;
                    }
                }
            }
        }
        
        // Check user agent
        $userAgent = strtolower($request->userAgent() ?? '');
        foreach ($this->suspiciousUserAgents as $suspiciousAgent) {
            if (strpos($userAgent, $suspiciousAgent) !== false) {
                $score += 5;
            }
        }
        
        // Check for rapid requests from same IP
        $ip = $request->ip();
        $requestCount = Cache::get("requests_count_{$ip}", 0);
        Cache::put("requests_count_{$ip}", $requestCount + 1, 60); // 1 minute
        
        if ($requestCount > 100) { // More than 100 requests per minute
            $score += 4;
        }
        
        // Check for suspicious file extensions in requests
        if (preg_match('/\.(php|asp|jsp|exe|bat|sh)$/i', $request->path())) {
            $score += 2;
        }
        
        return $score;
    }

    /**
     * Log suspicious activity for monitoring.
     */
    protected function logSuspiciousActivity(Request $request, int $score): void
    {
        Log::warning('Suspicious activity detected', [
            'ip' => $request->ip(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'user_agent' => $request->userAgent(),
            'input' => $request->all(),
            'headers' => $request->headers->all(),
            'suspicious_score' => $score,
            'timestamp' => now()
        ]);
        
        // Also log to a specific security log file
        $logData = [
            'timestamp' => now()->toISOString(),
            'ip' => $request->ip(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'user_agent' => $request->userAgent(),
            'score' => $score
        ];
        
        file_put_contents(
            storage_path('logs/security.log'),
            json_encode($logData) . PHP_EOL,
            FILE_APPEND | LOCK_EX
        );
    }
}