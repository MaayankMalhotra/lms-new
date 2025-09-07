<?php

namespace App\Http\Middleware;
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventLoginLogout
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            // Redirect based on user role
            switch ($user->role) {
                case 1:
                    return redirect()->route('admin.dash');
                case 2:
                    return redirect()->route('trainer.dashboard');
                case 3:
                    return redirect()->route('student.dashboard');
                default:
                    auth()->logout();
                    return redirect()->route('login')->withErrors(['error' => 'Invalid user role']);
            }
        }

        return $next($request);
    }
}
