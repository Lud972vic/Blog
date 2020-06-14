<?php

namespace App\Controller;

use App\Entity\Article;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArticleController
 * @package App\Controller
 * @Route("/actualites",name="actualites_")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="article")
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {
        //On récupere la liste de tous les articles
        $donnees = $this->getDoctrine()->getRepository(Article::class)->findBy([], ['created_at' => 'desc']);
        /*Numéro de la page en cours, 1 par defaut (page 1), 4 nombre d element par page*/
        $articles = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1), 3
        );

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }
}
