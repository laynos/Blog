<?php

namespace Blog\Bundle\Controller;

use Blog\Bundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends Controller
{

	    public function indexAction()
    {
		$repository = $this
			->getDoctrine()
			->getManager()
			->getRepository('BlogBundle:Article')
		;


		$listArticles = $repository->findAll();
/*
	foreach ($listArticles as $article) {
  // $advert est une instance de Advert
		//echo $article->getContent();

	}
  */      return $this->render('BlogBundle:Home:index.html.twig', array(
  'listArticles' => $listArticles
));
    }

	public function viewAction($id)
  {

	  // On récupère le repository
   // $repository = $this->getDoctrine()
     // ->getManager()
	  //->getRepository('BlogBundle:Article')
	 //;
	  //->find('BlogBundle:Article', $id);
	$article = $this->getDoctrine()
	->getManager()
	->find('BlogBundle:Article', $id)
	;

	//$article = $repository->find($id);

	 if (null === $article) {
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }


    return $this->render('BlogBundle:Article:view.html.twig', array(
      'article' => $article
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
      return $this->redirect($this->generateUrl('blog_view', array('id' => $article->getId())));
    }
	/*
    // Reste de la méthode qu'on avait déjà écrit
    if ($request->isMethod('POST')) {
      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
      return $this->redirect($this->generateUrl('blog_view', array('id' => $article->getId())));
    }
	*/
    //return $this->render('BlogBundle:Article:add.html.twig');

	return $this->render('BlogBundle:Article:add.html.twig', array(
      'form' => $form->createView(),
    ));
  }
	public function menuAction($limit = 3)
  {
	  		$repository = $this
			->getDoctrine()
			->getManager()
			->getRepository('BlogBundle:Article')
			;



	$listArticles = $repository->findBy(
  array(), // Critere
  array('date' => 'desc'),        // Tri
  $limit,                         // Limite
  0                            // Offset
);

    return $this->render('BlogBundle:Article:menu.html.twig', array(
      // Tout l'intérêt est ici : le contrôleur passe
      // les variables nécessaires au template !
      'listArticles' => $listArticles
    ));
  }





   public function editAction($id, Request $request)
  {
    $em = $this->getDoctrine()->getManager();

    // On récupère l'annonce $id
    $article = $em->getRepository('BlogBundle:Article')->find($id);

    if (null === $article) {
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }

	// formulaire

  return $this->render('BlogBundle:Article:edit.html.twig', array(
      'article' => $article
    ));
  }

  public function deleteAction($id, Request $request)
  {
    // On récupère l'EntityManager
    $em = $this->getDoctrine()->getManager();

    // On récupère l'entité correspondant à l'id $id
    $article = $em->getRepository('BlogBundle:Article')->find($id);

    // Si l'annonce n'existe pas, on affiche une erreur 404
    if ($article == null) {
      throw $this->createNotFoundException("L'annonce d'id ".$id." n'existe pas.");
    }

    if ($request->isMethod('POST')) {
      // Si la requête est en POST, on deletea l'article

      $request->getSession()->getFlashBag()->add('info', 'Annonce bien supprimée.');

      // Puis on redirige vers l'accueil
      return $this->redirect($this->generateUrl('blog_homepage'));
    }

    // Si la requête est en GET, on affiche une page de confirmation avant de delete
    return $this->render('BlogBundle:Article:delete.html.twig', array(
      'article' => $article
    ));
  }

}
