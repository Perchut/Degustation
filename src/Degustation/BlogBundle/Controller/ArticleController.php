<?php

namespace Degustation\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ArticleController extends Controller
{
    public function indexAction()
    {
        if ($page < 1)
        {
        	throw new Exception('Page "'.$page.'" inexistante.');
        }
    }

    public function viewAction($id)
	{
		return $this->render('DegustationBlogBundle:Article:view.html.twig', array(
			'id' => $id
		));
	}

	public function addAction(Request $request)
	{
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
}
