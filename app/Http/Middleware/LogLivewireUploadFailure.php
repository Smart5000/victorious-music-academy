<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogLivewireUploadFailure
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response->getStatusCode() === 401 && str_contains($request->path(), 'upload-file')) {
            Log::error('Livewire upload returned 401 Unauthorized.', [
                'path' => $request->path(),
                'scheme' => $request->getScheme(),
                'host' => $request->getHost(),
                'is_secure' => $request->isSecure(),
                'has_valid_signature' => $request->hasValidSignature(),
                'app_url' => config('app.url'),
                'asset_url' => config('app.asset_url'),
                'session_driver' => config('session.driver'),
                'session_secure' => config('session.secure'),
                'session_same_site' => config('session.same_site'),
                'livewire_temp_disk' => config('livewire.temporary_file_upload.disk'),
                'forwarded_proto' => $request->headers->get('x-forwarded-proto'),
                'forwarded_host' => $request->headers->get('x-forwarded-host'),
                'forwarded_port' => $request->headers->get('x-forwarded-port'),
                'forwarded_for_present' => $request->headers->has('x-forwarded-for'),
            ]);
        }

        return $response;
    }
}
