<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog_index")
     */
    public function index()
    {
        return $this->render('blog/index.html.twig', [
            'owner' => 'Thomas',
        ]);
    }

    /**
     * @Route("/blog/show/{slug}", name="blog_show", requirements={"slug"="[a-z0-9-]+"})
     */
    public function show($slug = 'article-sans-titre')
    {
        $slug = ucwords(implode(' ', explode('-', $slug)));
        return $this->render('blog/show.html.twig', ['show_slug' => $slug]);
    }

    /**
     * @Route("/blog/pages/{slug}", name="blog_pages", requirements={"slug"="[a-z0-9-]+"})
     */
    public function pages($slug = 'article-sans-titre')
    {
        $slug = ucwords(implode(' ', explode('-', $slug)));
        return $this->render('blog/pages.html.twig', ['pages_slug' => $slug]);
    }
}