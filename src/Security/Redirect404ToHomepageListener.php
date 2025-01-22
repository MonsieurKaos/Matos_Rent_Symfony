<?php

namespace App\Security;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Routing\RouterInterface;

class Redirect404ToHomepageListener
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @var ExceptionEvent $event
     * @return null
     */
    public function onKernelException(ExceptionEvent $event)
    {
// If not a HttpNotFoundException ignore
        $exception=$event->getThrowable();
        if (!$exception instanceof NotFoundHttpException) {
            return;
        }

// Create redirect response with url for the home page
        $response = new RedirectResponse($this->router->generate('index'));

// Set the response to be processed
        $event->setResponse($response);
    }
}
