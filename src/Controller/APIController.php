<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use App\MesFonctions;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 ***Sécurité à venir : Validation des données, l'authentification...***
 */

/**
 * @Route("/api",name="api_")
 */
class APIController extends AbstractController
{
    /**
     * Récupération des articles
     * @Route("/articles/liste", name="articles_liste", methods={"GET"})
     */
    public function getLesArticles(ArticleRepository $repository)
    {
        return MesFonctions::apiGet($repository, 'apiFindAll', '');
    }

    /**
     * Récupération d'un article
     * @Route("/article/lire/{id}", name="article_lire", methods={"GET"})
     */
    public function getUnArticle(ArticleRepository $repository, Request $request)
    {
        $id = $request->attributes->get('id');
        return MesFonctions::apiGet($repository, 'apiFindOneBy', $id);
    }

    /**
     * Ajout d'un article
     * @Route("/article/ajout", name="article_ajout", methods={"POST"})
     */
    public function apiAddArticle(Request $request)
        //***Avec JavaScript
    {
        //On vérifie si on a une reqûete XMLHttpRequest
        //***if ($request->isXmlHttpRequest()) {
        //On vérifie les données apèrs les avoir décodées à faire...
        $donnees = json_decode($request->getContent());

        //On instancie un nouvel article
        $article = new Article();

        //On hydrate notre article
        $article->setTitre($donnees->titre);
        $article->setContenu($donnees->contenu);
        $article->setImage($donnees->image);
        //Faire une authentification, à faire
        $user = $this->getDoctrine()->getRepository(User::class)->find(2);
        $article->setUser($user);

        //On sauvegarde en base de données
        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();

        //On retourne la confirmation
        return new Response('L\'article est crée.', 201);
        //***}
        //***return new Response('L\'article n\'est pas crée.', 404);
    }

    /**
     * Modifier un article
     * @Route("/article/editer/{id}",name="article_editer", methods={"PUT"})
     * ?Article $article si on ne trouve pas l'article, on le crée d'où le '?'
     */
    public function apiEditArticle(?Article $article, Request $request)
    {
        //On vérifie si on a une reqûete XMLHttpRequest
        //***if ($request->isXmlHttpRequest()) {
        //On vérifie les données apèrs les avoir décodées à faire...
        $donnees = json_decode($request->getContent());

        $codeResponse = 200;

        //Si on n'a pas d'article
        if (!$article) {
            //On instancie un nouvel aeticle
            $article = new Article();

            //On met le code 201, création d'un article
            $codeResponse = 201;
        }

        //On hydrate notre article
        $article->setTitre($donnees->titre);
        $article->setContenu($donnees->contenu);
        $article->setImage($donnees->image);
        //Faire une authentification, à faire
        $user = $this->getDoctrine()->getRepository(User::class)->find(2);
        $article->setUser($user);

        //On sauvegarde en base de données
        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();

        //On retourne la confirmation
        return new Response('L\'article est crée/modifié.', $codeResponse);

        //***}
        //***return new Response('L\'article n\'est pas crée.', 404);
    }

    /**
     * Supprimer un article
     * @Route("/article/supprimer/{id}", name="article_supprimer", methods={"DELETE"})
     * @param Article $article
     * @return Response
     */
    public function apiRemoveArticle(Article $article)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($article);
        $em->flush();

        return new Response('L\'article est Supprimé', 200);
    }
}
