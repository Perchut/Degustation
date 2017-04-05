<?php

namespace Degustation\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('DegustationBlogBundle:Default:index.html.twig');
    }
}
