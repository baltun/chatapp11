<?php

namespace App\Http\Middleware;

use App\Exceptions\AppLogicException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionsMiddleware
{

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->route()->originalParameters()['user'] != \Auth::user()->id) {
            throw new AppLogicException(
                'You do not have permission to perform this action for another user',
                Response::HTTP_FORBIDDEN
            );
        }
        return $next($request);
    }
}
