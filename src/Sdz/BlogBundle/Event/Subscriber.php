<?php
/**
 * Created by PhpStorm.
 * User: ben
 * Date: 19/08/14
 * Time: 13:33
 */

namespace Sdz\BlogBundle\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;


class Subscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'kernel.response' => 'onKernelResponse',
        );
    }

    public function onKernelResponse (FilterResponseEvent $event)
    {

    }

}
