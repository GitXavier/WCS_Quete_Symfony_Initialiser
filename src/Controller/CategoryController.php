<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



/**
 * Class CategoryController
 * @package App\Controller
 * @Route("/category")
 * @IsGranted("ROLE_ADMIN")
 */
Class CategoryController extends AbstractController
{
    /**
     * @return Response
     * @Route ("/", name="show_category")
     */
    public function show():Response
    {

        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return $this->render('blog/show_catecory.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     *
     * @param $request Request
     * @Route("/add", name="add_category")
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

            return $this->redirectToRoute('show_category');
        }

        return $this->render('blog/add_category.html.twig',[
            'form' => $form->createView()
            ]);
    }
}