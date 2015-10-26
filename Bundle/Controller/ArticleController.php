<?php

namespace Blog\Bundle\Controller;

use Blog\Bundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends Controller
{
    public function indexAction()
    {
        return $this->render('BlogBundle:Home:index.html.twig');
    }
	public function addAction(/*Request $request*/)
  {
	  $em = $this->getDoctrine()->getManager();
	  /*
    // Création de l'entité
    $article = new Article();
    $article->setTitle('Recherche développeur Symfony2.');
    $article->setAuthor('Alexandre');
    $article->setContent("Nous recherchons un développeur Symfony2 débutant sur Paris. Blabla…");
    // On peut ne pas définir ni la date ni la publication,
    // car ces attributs sont définis automatiquement dans le constructeur

    // On récupère l'EntityManager
    $em = $this->getDoctrine()->getManager();

    // Étape 1 : On « persiste » l'entité
    $em->persist($article);

    // Étape 2 : On « flush » tout ce qui a été persisté avant
    $em->flush();

    // Reste de la méthode qu'on avait déjà écrit
    if ($request->isMethod('POST')) {
      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
      return $this->redirect($this->generateUrl('oc_platform_view', array('id' => $article->getId())));
    }
	*/
    return $this->render('BlogBundle:Article:add.html.twig');
  }
}
