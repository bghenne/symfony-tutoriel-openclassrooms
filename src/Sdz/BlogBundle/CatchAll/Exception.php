<?php
/**
 * Created by PhpStorm.
 * User: ben
 * Date: 18/08/14
 * Time: 16:53
 */

namespace Sdz\BlogBundle\CatchAll;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;


class Exception
{
    protected function treatException(\Exception $exception)
    {
    }

    public function onResponseException(GetResponseForExceptionEvent $event)
    {
    }
} 