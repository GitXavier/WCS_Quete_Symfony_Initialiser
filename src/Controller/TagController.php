<?php

namespace App\Controller;

use App\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class TagController
 * @package App\Controller
 * @Route("tag")
 */
Class TagController extends AbstractController
{

    /**
     * @return Response
     * @Route("/", name="show_tag")
     */
    public function showTags():Response
    {
        $tags = $this->getDoctrine()
            ->getRepository(Tag::class)
            ->findAll();

        if (!$tags) {
            throw $this->createNotFoundException(
                'No article found in article\'s table.'
            );
        }

        return $this->render(
            'blog/show_tag.html.twig', [
            'tags' => $tags,
        ]);
    }

        /**
         * @param Tag $tag
         * @return Response
         * @Route("/{name}", name="tag_article")
         */
        public function showByTag(Tag $tag):Response
        {
            $articles = $tag->getArticles();

            return $this->render(
                'blog/tag_article.html.twig', [
                'articles' => $articles,
                'tags' => $tag
            ]);
        }
}