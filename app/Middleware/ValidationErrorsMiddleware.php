<?php

namespace App\Middleware;

class ValidationErrorsMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
    	if(!empty($_SESSION['errors']))
    	{
	        $this->container->view->getEnvironment()->addGlobal('errors', $_SESSION['errors']);
	        unset($_SESSION['errors']);
        }

        if(false === $request->getAttribute('csrf_status')){
            return $response->withStatus(500)
                            ->withHeader('Content-Type', 'text/html')
                            ->write('Something went wrong!');
        }

        $response = $next($request, $response);
        return $response;

    }
}