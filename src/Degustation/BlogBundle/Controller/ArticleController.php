<?php

namespace Degustation\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Degustation\BlogBundle\Entity\Article;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ArticleController extends Controller
{
    public function indexAction($page)
    {
        if ($page < 1)
        {
        	throw new Exception('Page "'.$page.'" inexistante.');
        }

        $listAdverts = array(
			array('id' => 2, 'title' => "Les grancs blancs d'Alsace", 'author' => 'Perchut'),
			array('id' => 5, 'title' => 'Les vins blancs de la vallée du Rhône', 'author' => 'Perchut', 'pic' => 'http://www.lechai.fr/media/wysiwyg/edv-portfolio.jpg'),
			array('id' => 9, 'title' => 'Les vins rouges de la vallée du Rhône', 'author' => 'Perchut')
		);

        return $this->render('DegustationBlogBundle:Article:index.html.twig', array(
        	'listAdverts' => $listAdverts
        ));
    }

    public function viewAction($id)
	{

		// On récupère le repository
	    $repository = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('DegustationBlogBundle:Article')
	    ;

	    // On récupère l'entité correspondante à l'id $id
	    $article = $repository->find($id);

	    // $advert est donc une instance de OC\PlatformBundle\Entity\Advert
	    // ou null si l'id $id  n'existe pas, d'où ce if :
	    if (null === $article) {
	      throw new NotFoundHttpException("L'article d'id ".$id." n'existe pas.");
	    }

		return $this->render('DegustationBlogBundle:Article:view.html.twig', array(
			'article' => $article
		));
	}

	public function addAction(Request $request)
	{

		// Création de l'entité
		$article = new Article();

		// On crée le FormBuilder grâce au service form factory
		$formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $article);

		// On ajoute les champs de l'entité que l'on veut à notre formulaire
	    $formBuilder
	      ->add('date',      DateType::class)
	      ->add('title',     TextType::class)
	      ->add('content',   TextareaType::class)
	      ->add('author',    TextType::class)
	      ->add('status',	 TextType::class)
	      ->add('save',      SubmitType::class)
	    ;

	    // À partir du formBuilder, on génère le formulaire
    	$form = $formBuilder->getForm();

		if($request->isMethod('POST'))
		{
			$form->handleRequest($request);

			if ($request->isMethod('POST'))
			{
				// On récupère l'EntityManager
				$em = $this->getDoctrine()->getManager();

				// Étape 1 : On « persiste » l'entité
				$em->persist($article);

				// Étape 2 : On « flush » tout ce qui a été persisté avant
				$em->flush();

				$request->getSession()->getFlashBag()->add('notice', 'Article bien enregistré.');

				return $this->redirectToRoute('degustation_blog_view', array('id' => $advert->getId()));
			}
		}

		// On passe la méthode createView() du formulaire à la vue
	    // afin qu'elle puisse afficher le formulaire toute seule
	    return $this->render('DegustationBlogBundle:Article:add.html.twig', array(
	      'form' => $form->createView(),
	    ));
	}

	public function editAction($id, Request $request)
	{
		if ($request->isMethod('POST'))
		{
			$request->getSession()->getFlashBag()->add('notice', 'Article bien modifié.');
			return $this->redirectToRoute('degustation_blog_view', array('id' => 5));
		}

		return $this->render('DegustationBlogBundle:Article:edit.html.twig');
	}

	public function deleteAction($id)
	{
		return $this->render('DegustationBlogBundle:Article:delete.html.twig');
	}


	public function menuAction()
	{
		$listAdverts = array(
			array('id' => 2, 'title' => "Les grancs blancs d'Alsace", 'author' => 'Perchut'),
			array('id' => 5, 'title' => 'Les vins blancs de la vallée du Rhône', 'author' => 'Perchut'),
			array('id' => 9, 'title' => 'Les vins rouges de la vallée du Rhône', 'author' => 'Perchut')
		);

		return $this->render('DegustationBlogBundle:Article:menu.html.twig', array(
			'listAdverts' => $listAdverts
		));
	}
}
