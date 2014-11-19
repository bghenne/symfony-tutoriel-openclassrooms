<?php

// src/Sdz/BlogBundle/Controller/BlogController.php

namespace Sdz\BlogBundle\Controller;

use Sdz\BlogBundle\Bigbrother\BigbrotherEvents;
use Sdz\BlogBundle\Bigbrother\MessagePostEvent;
use Sdz\BlogBundle\Form\ArticleEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sdz\BlogBundle\Entity\Article;
use Sdz\BlogBundle\Form\ArticleType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use JMS\SecurityExtraBundle\Annotation\Secure;

class BlogController extends Controller
{
    public function indexAction($page)
    {
        // On ne sait pas combien de pages il y a
        // Mais on sait qu'une page doit être supérieure ou égale à 1
        // Bien sûr pour le moment on ne se sert pas (encore !) de cette variable
        if ($page < 1) {
            // On déclenche une exception NotFoundHttpException
            // Cela va afficher la page d'erreur 404
            // On pourra la personnaliser plus tard
            throw $this->createNotFoundException('Page inexistante (page = '.$page.')');
        }

        // Pour récupérer la liste de tous les articles : on utilise findAll()
        $articles = $this->getDoctrine()
            ->getManager()
            ->getRepository('SdzBlogBundle:Article')
            ->getArticles(1, $page);

        // L'appel de la vue ne change pas
        return $this->render('SdzBlogBundle:Blog:index.html.twig', array(
            'articles' => $articles,
            'page' => $page,
            'nombrePage' => ceil(count($articles))
        ));
    }

    public function voirAction(Article $article)
    {
        // On récupère les articleCompetence pour l'article $article
        $listeArticleCompetence = $this->getDoctrine()->getManager()->getRepository('SdzBlogBundle:ArticleCompetence')
            ->findByArticle($article->getId());

        // Puis modifiez la ligne du render comme ceci, pour prendre en compte les variables :
        return $this->render('SdzBlogBundle:Blog:voir.html.twig', array(
            'article'                 => $article,
            'liste_articleCompetence' => $listeArticleCompetence
        ));
    }

    /**
     * @Secure(roles="ROLE_USER")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    public function ajouterAction()
    {
        // La gestion d'un formulaire est particulière, mais l'idée est la suivante :
        //$article = $this->getDoctrine()->getManager()->find('SdzBlogBundle:Article', 2);
        $article = new Article;
        $form = $this->createForm(new ArticleType, $article);

        $request = $this->get('request');

        if ($request->getMethod() == 'POST') {
            // Ici, on s'occupera de la création et de la gestion du formulaire

            $form->submit($request);

            if ($form->isValid()) {

                $event = new MessagePostEvent($article->getContenu(), $this->getUser());

                $this->get('event_dispatcher')
                    ->dispatch(BigbrotherEvents::onMessagePost, $event);

                $article->setContenu($event->getMessage());

                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();

                $this->get('session')->getFlashBag()->add('info', 'Article bien enregistré');

                // Puis on redirige vers la page de visualisation de cet article
                return $this->redirect( $this->generateUrl('sdzblog_voir', array('id' => $article->getId())) );

            }
        }

        // Si on n'est pas en POST, alors on affiche le formulaire
        return $this->render('SdzBlogBundle:Blog:ajouter.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function modifierAction(Article $article, Request $request)
    {
        $form = $this->createForm(New ArticleEditType(), $article);

        if ($request->getMethod() == 'POST') {

            $form->submit($request);

            if ($form->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();

                $this->get('session')->getFlashBag()->add('info', 'Article bien modifié');

                return $this->redirect($this->generateUrl('sdbblog_voir', array('id' => $article->getId())));
            }

        }

        // Ici, on s'occupera de la création et de la gestion du formulaire

        return $this->render('SdzBlogBundle:Blog:modifier.html.twig', array(
            'article' => $article,
            'form' => $form->createView()
        ));
    }

    public function supprimerAction(Article $article)
    {
        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        // Cela permet de protéger la suppression d'article contre cette faille
        $form = $this->createFormBuilder()->getForm();

        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                // On supprime l'article
                $em = $this->getDoctrine()->getManager();
                $em->remove($article);
                $em->flush();

                // On définit un message flash
                $this->get('session')->getFlashBag()->add('info', 'Article bien supprimé');

                // Puis on redirige vers l'accueil
                return $this->redirect($this->generateUrl('sdzblog_accueil'));
            }
        }

        // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
        return $this->render('SdzBlogBundle:Blog:supprimer.html.twig', array(
            'article' => $article,
            'form'    => $form->createView()
        ));
    }

    public function menuAction($nombre)
    {
        $liste = $this->getDoctrine()
            ->getManager()
            ->getRepository('SdzBlogBundle:Article')
            ->findBy(
                array(),          // Pas de critère
                array('date' => 'desc'), // On trie par date décroissante
                $nombre,         // On sélectionne $nombre articles
                0                // À partir du premier
            );

        return $this->render('SdzBlogBundle:Blog:menu.html.twig', array(
            'liste_articles' => $liste // C'est ici tout l'intérêt : le contrôleur passe les variables nécessaires au template !
        ));
    }

    public function traductionAction($name)
    {
        $articleRepository = $this->getDoctrine()->getManager()->getRepository('SdzBlogBundle:Article');
        return $this->render('SdzBlogBundle:Blog:traduction.html.twig', array(
            'total_articles' => count($articleRepository->findAll()),
            'name' => $name
        ));
    }
}