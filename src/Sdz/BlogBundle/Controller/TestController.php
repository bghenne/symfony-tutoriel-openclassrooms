<?php
// src/Sdz/BlogBundle/Controller/TestController.php

namespace Sdz\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Httpfoundation\Response;
use Sdz\BlogBundle\Entity;
use Sdz\BlogBundle\Beta\BetaListener;

class TestController extends Controller
{
    public function testAction()
    {
        $service = $this->get('sdz_blog.mailer_password');
        $articles = $this->getDoctrine()->getManager()->getRepository('SdzBlogBundle:Article')->getArticles(50, 1);

//        $betaListener = new BetaListener('2014-08-31');
//
//        $dispatcher = $this->get('event_dispatcher');
//        $dispatcher->addListener('kernel.response', array($betaListener, 'onKernelResponse'));

        return $this->render('SdzBlogBundle:Test:test.html.twig', array(
            'articles' => $articles
        ));
    }
} 