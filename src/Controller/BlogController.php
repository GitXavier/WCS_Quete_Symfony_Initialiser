<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class BlogController
 * @package App\Controller
 * @Route("/blog")
 */

class BlogController extends AbstractController
{
    /**
     * @Route("/", name="blog_index")
     */
    public function index()
    {
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();

        if (!$articles) {
            throw $this->createNotFoundException(
                'No article found in article\'s table.'
            );
        }

        return $this->render(
            'blog/index.html.twig',
            ['articles' => $articles]
        );
    }

    /**
     * Getting a article with a formatted slug for title
     *
     * @param string $slug The slugger
     *
     * @Route("/{slug<^[a-z0-9-]+$>}",
     *     defaults={"slug" = null},
     *     name="blog_show")
     *  @return Response A response instance
     */
    public function show(?string $slug) : Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find an article in article\'s table.');
        }

        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );

        $article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);

        if (!$article) {
            throw $this->createNotFoundException(
                'No article with '.$slug.' title, found in article\'s table.'
            );
        }

        return $this->render(
            'blog/show.html.twig',
            [
                'article' => $article,
                'slug' => $slug,
            ]
        );
    }


    /**
     *
     * @return Response
     * @Route("/category/{name}", name="show_category")
     */
    public function showByCategory(Category $categoryName)
    {

        $articles = $categoryName->getArticles();

        return $this->render(
            'blog/category.html.twig', [
            'articles' => $articles,
            'categories' => $categoryName
        ]);

 /*
        /**
         * @param string $categoryName
         * @return Response
         * @Route("/category/{categoryName}", name="show_category")
         *
    public function showByCategory(string $categoryName)
    {

     * Bidirectionnelle
             $category = $this->getDoctrine()
                 ->getRepository(Category::class)
                 ->findOneBy(['name' => $categoryName]);

             $articles = $category->getArticles();


      * unidirectionnelle
      *
             $category = $this->getDoctrine()
                 ->getRepository(Category::class)
                 ->findOneBy(['name' => $categoryName]);

             $categoryArticle = $this->getDoctrine()
                 ->getRepository(Article::class)
                 ->findBy(['category' => $category], ['id' => 'DESC'], 3);


        return $this->render(
            'blog/category.html.twig', [
                'articles' => $articles,
                'categories' => $category
            ]);
 */

    }


    /**
     * @Route("/pages/{slug}", name="blog_pages", requirements={"slug"="[a-z0-9-]+"})
     */
    public function pages($slug = 'article-sans-titre')
    {
        $slug = ucwords(implode(' ', explode('-', $slug)));
        return $this->render('blog/pages.html.twig', ['pages_slug' => $slug]);
    }

}