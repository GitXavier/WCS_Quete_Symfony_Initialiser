<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/",name="app_index")
     */
    public function index()
    {
        return $this->render('default.html.twig');
    }

    public function dispatch(Request $request)
    {

        $locale = $request->getLocale();

        return $this->redirectToRoute('app_index', ['_locale' => $locale]);
    }
}