<?php

namespace Degustation\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Degustation\BlogBundle\Entity\Article;
use Degustation\BlogBundle\Form\ArticleType;


class ArticleController extends Controller
{
    public function indexAction($page)
    {
        if ($page < 1)
        {
        	throw new Exception('Page "'.$page.'" inexistante.');
        }

	    $repository = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('DegustationBlogBundle:Article')
	    ;
	    $listArticles = $repository->findAll();

        return $this->render('DegustationBlogBundle:Article:index.html.twig', array(
        	'listArticles' => $listArticles
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

		$article = new Article();
    	$form = $this->get('form.factory')->create(ArticleType::class, $article);

		if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
		{
			$repository = $this->getDoctrine()->getManager()->getRepository('DegustationBlogBundle:Statut');

			if ($form->get('publish')->getData() == true)
			{
				$status = $repository->findOneByStatus('Soumis');
			}
			else 
			{
				$status = $repository->findOneByStatus('Brouillon');
			}

			$article->setStatus($status);

			$em = $this->getDoctrine()->getManager();
			$em->persist($article);
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', 'Article bien enregistré.');

			return $this->redirectToRoute('degustation_blog_view', array('id' => $article->getId()));
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
		$repository = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('DegustationBlogBundle:Article')
	    ;
	    $listArticles = $repository->findAll();

		return $this->render('DegustationBlogBundle:Article:menu.html.twig', array(
			'listArticles' => $listArticles
		));
	}
}
