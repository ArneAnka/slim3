<?php

namespace App\Middleware;

class SubscriberMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if (!$this->container->auth->check()) {
            /** 
            * Render with signed_in as false.
            * You could easely change this so it look like AuthMiddleware,
            * that the user is redirected to sign-in. But maybe you want to
            * show the user some small information about you content? This could be
            * one way of doing it.
            * 
            * @return $signed_in false
            */
			return $this->container->view->render($response, 'subscriber/index.twig', ['signed_in' => false]);
        }

        if(!(new \App\Models\User)->hasSubscription()){
        	/**
        	* Render with subscription as false.
        	* The user is signed-in, but for the moment hasent any subscription.
        	* You now have the opportunity to show the user a credit-card form, or
        	* some other easy way of adding a subscription to the account.
        	*
        	* @return $subscription false
        	*/
			return $this->container->view->render($response, 'subscriber/index.twig', ['subscription' => false]);
        }

        $response = $next($request, $response);
        return $response;
    }
}
