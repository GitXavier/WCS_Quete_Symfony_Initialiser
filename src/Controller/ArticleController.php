<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Slugify;

/**
 * @Route({
 *     "fr": "/article",
 *     "en": "/article",
 *     "es": "/biene",
 * })
 */
class ArticleController extends AbstractController
{
    /**
     * @Route({
     *     "fr": "/",
     *     "en": "/",
     *     "es": "/",
     * }, name="article_index", methods={"GET"})
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    /**
     * @Route({
     *     "fr": "/ajouter",
     *     "en": "/new",
     *     "es": "/crear",
     * }, name="article_new", methods={"GET","POST"})
     *
     */
    public function new(Request $request, Slugify $slugify, \Swift_Mailer $mailer): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $slug = $slugify->generate($article->getTitle());
            $article->setSlug($slug);

            $author = $this->getUser();
            $article->setAuthor($author);

            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', 'l\'article vient d\'être ajouté');

            $message = (new \Swift_Message('Un nouvel article vient d\'être publié sur ton blog !'))
                ->setFrom($this->getParameter('mailer_from'))
                ->setTo($this->getParameter('mailer_from'))
                ->setBody(
                    $this->renderView(
                        'article/email/notification.html.twig',
                        ['article'=>$article]
                    ),
                    'text/html'
                );
            $mailer->send($message);
            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="article_show", methods={"GET"})
     */
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
            'isFavorite' => $this->getUser()->isFavorite($article)
        ]);
    }

    /**
     * @Route({
     *     "fr": "/editer",
     *     "en": "/edit",
     *     "es": "/editar",
     * }, name="article_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Article $article, Slugify $slugify): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugify->generate($article->getTitle());
            $article->setSlug($slug);

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'l\'article vient d\'être modifié');

            return $this->redirectToRoute('article_index', [
                'id' => $article->getId(),
            ]);
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="article_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();

            $this->addFlash('danger', 'l\'article vient d\'être supprimé');
        }

        return $this->redirectToRoute('article_index');
    }

    /**
     * @Route("/{id}/favorite", name="article_favorite", methods={"GET","POST"})
     *
     */
    public function  favorite(Article $article, EntityManagerInterface $manager)
    {
/*        $user = $this->getUser();
        $user->addFavorite($article);

        $this->getDoctrine()->getManager()->flush();

        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);*/

        if ($this->getUser()->getFavorite()->contains($article)) {
            $this->getUser()->removeFavorite($article)   ;
        } else {
            $this->getUser()->favorite->addFavorite($article);
        }

        $manager->flush();

        return $this->json([
            'isFavorite' => $this->getUser()->isFavorite($article)
        ]);
    }

}
