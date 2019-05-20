<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



/**
 * Class CategoryController
 * @package App\Controller
 * @Route("/category")
 */
Class CategoryController extends AbstractController
{
    /**
     * @return Response
     * @Route ("/category", name="add_index")
     */
    public function index():Response
    {

        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return $this->render('blog/add_index.html.twig', [
            //'form' => $form->createView(),
            'categories' => $categories
        ]);
    }

    /**
     *
     * @param $request Request
     * @Route("/", name="add_category")
     * @return Response
     */
    public function add(Request $request) : Response
    {

        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('add_index');
        }

        return $this->render('blog/add_category.html.twig',[
            'form' => $form->createView()
            ]);
    }
}