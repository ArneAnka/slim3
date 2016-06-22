<?php

namespace App\Middleware;

class AdminMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
    	/** Check if user is admin, if not: return 404 */
        if (!$this->container->auth->checkIsAdmin()){
            // return $response->withStatus(404)->withHeader('Content-Type', 'text/html')->write('Page not found');
            throw new \Slim\Exception\NotFoundException($request, $response);
        }

        $response = $next($request, $response);
        return $response;
    }
}
