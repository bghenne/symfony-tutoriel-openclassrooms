<?php

namespace Sdz\BlogBundle\Beta;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class BetaListener
{
    protected $dateFin;

    public function __construct($dateFin)
    {
        $this->dateFin = new \DateTime($dateFin);
    }

    protected function displayBeta(Response $response, $joursRestants)
    {
        $content = $response->getContent();

        // Code à rajouter
        $html = '<span style="color: red; font-size: 0.5em;"> - Beta J-'.(int) $joursRestants.' !</span>';

        // Insertion du code dans la page, dans le <h1> du header
        $content = preg_replace('#<h1>(.*?)</h1>#iU',
            '<h1>$1'.$html.'</h1>',
            $content,
            1);

        return $response->setContent($content);
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST != $event->getRequestType()) {
            return;
        }

        $response = $event->getResponse();

        $joursRestants = $this->dateFin->diff(new \DateTime())->days;

        if ($joursRestants > 0) {
            $response = $this->displayBeta($event->getResponse(), $joursRestants);
        }

        $event->setResponse($response);
    }
} 