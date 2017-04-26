<?php

namespace Degustation\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Degustation\BlogBundle\Entity\Article;

class ArticleController extends Controller
{
    public function indexAction($page)
    {
        if ($page < 1)
        {
        	throw new Exception('Page "'.$page.'" inexistante.');
        }

        $listAdverts = array(
			array('id' => 2, 'title' => 'Recherche développeur Symfony', 'author' => 'Perchut'),
			array('id' => 5, 'title' => 'Mission de webmaster', 'author' => 'Perchut', 'pic' => 'https://www.google.fr/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png'),
			array('id' => 9, 'title' => 'Offre de stage webdesigner', 'author' => 'Perchut')
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
		$article->setTitle('Recherche développeur Symfony.');
		$article->setAuthor('Alexandre');
		$article->setContent("Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…");
		$article->setStatus('New');
		// On peut ne pas définir ni la date ni la publication,
		// car ces attributs sont définis automatiquement dans le constructeur

		// On récupère l'EntityManager
		$em = $this->getDoctrine()->getManager();

		// Étape 1 : On « persiste » l'entité
		$em->persist($article);

		// Étape 2 : On « flush » tout ce qui a été persisté avant
		$em->flush();


		if($request->isMethod('POST'))
		{
			$request->getSession()->getFlashBag()->add('notice', 'Article bien enregistré.');
			return $this->redirectToRoute('degustation_blog_view', array('id' => 5));
		}

		return $this->render('DegustationBlogBundle:Article:add.html.twig');
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
			array('id' => 2, 'title' => 'Recherche développeur Symfony', 'author' => 'Perchut'),
			array('id' => 5, 'title' => 'Mission de webmaster', 'author' => 'Perchut'),
			array('id' => 9, 'title' => 'Offre de stage webdesigner', 'author' => 'Perchut')
		);

		return $this->render('DegustationBlogBundle:Article:menu.html.twig', array(
			'listAdverts' => $listAdverts
		));
	}
}
