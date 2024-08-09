<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class ExceptionListener
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        // On récupère l'objet exception qui a causé l'événement.
        $exception = $event->getThrowable();

        // On vérifie si l'exception est de type NotFoundHttpException.
        if ($exception instanceof NotFoundHttpException) {
            // On crée une réponse de redirection vers la page d'accueil.
            $response = new RedirectResponse($this->router->generate('app_home'));

            // On remplace la réponse par défaut (page 404) avec la réponse créée (la redirection).
            $event->setResponse($response);
        }
    }
}
