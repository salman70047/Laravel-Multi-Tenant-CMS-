<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Tenant;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $subdomain = $this->extractSubdomain($host);
        
        if (!$subdomain) {
            abort(404, 'Tenant not found');
        }

        $tenant = Tenant::findBySubdomain($subdomain);
        
        if (!$tenant) {
            abort(404, 'Tenant not found');
        }

        // Bind the current tenant to the service container
        app()->instance('current_tenant', $tenant);
        
        // Set tenant context for the request
        $request->attributes->set('tenant', $tenant);

        return $next($request);
    }

    /**
     * Extract subdomain from host
     */
    private function extractSubdomain(string $host): ?string
    {
        // Handle localhost and IP addresses for development
        if ($host === 'localhost' || filter_var($host, FILTER_VALIDATE_IP)) {
            return 'demo'; // Default tenant for local development
        }

        $parts = explode('.', $host);
        
        // For single domain multi-tenancy like tenant.app.domain.com
        if (count($parts) >= 3) {
            return $parts[0];
        }

        // For development with custom hosts like tenant.test
        if (count($parts) === 2 && $parts[1] === 'test') {
            return $parts[0];
        }

        return null;
    }
}
