<?php

namespace Blog\Bundle\Controller;

use Blog\Bundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends Controller
{
	public function viewAction($id)
  {
    $advert = array(
      'title'   => 'Recherche développpeur Symfony2',
      'id'      => $id,
      'author'  => 'Alexandre',
      'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…',
      'date'    => new \Datetime()
    );

    return $this->render('BlogBundle:Article:view.html.twig', array(
      'article' => $article
    ));
  }
	public function menuAction($limit)
  {
    // On fixe en dur une liste ici, bien entendu par la suite
    // on la récupérera depuis la BDD !
    $listArticles = array(
      array('id' => 2, 'title' => 'Recherche développeur Symfony2'),
      array('id' => 5, 'title' => 'Mission de webmaster'),
      array('id' => 9, 'title' => 'Offre de stage webdesigner')
    );

    return $this->render('BlogBundle:Article:menu.html.twig', array(
      // Tout l'intérêt est ici : le contrôleur passe
      // les variables nécessaires au template !
      'listArticles' => $listArticles
    ));
  }
	
    public function indexAction()
    {
        return $this->render('BlogBundle:Home:index.html.twig', array(
  'listArticles' => array()
));
    }
	public function addAction(Request $request)
  {
	$em = $this->getDoctrine()->getManager();
	$article = new Article();
	$article->setDate(new \Datetime());
	// On crée le FormBuilder grâce au service form factory
    $formBuilder = $this->get('form.factory')->createBuilder('form', $article);

    // On ajoute les champs de l'entité que l'on veut à notre formulaire
    $formBuilder
      ->add('date',      'date')
      ->add('title',     'text')
      ->add('content',   'textarea')
      ->add('author',    'text')
      ->add('published', 'checkbox')
      ->add('save',      'submit')
    ;
    // Pour l'instant, pas de candidatures, catégories, etc., on les gérera plus tard

    // À partir du formBuilder, on génère le formulaire
    $form = $formBuilder->getForm();
	$formBuilder->add('published', 'checkbox', array('required' => false));
	$form->handleRequest($request);
	
	if ($form->isValid()) {
		
      $em->persist($article);
      $em->flush();

      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

      // On redirige vers la page de visualisation de l'annonce nouvellement créée
      return $this->redirect($this->generateUrl('blog_homepage', array('id' => $article->getId())));
    }
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
    //return $this->render('BlogBundle:Article:add.html.twig');
	
	return $this->render('BlogBundle:Article:add.html.twig', array(
      'form' => $form->createView(),
    ));
  }
}
