<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Commentaire;
use App\Form\AjoutArticleFromType;
use App\Form\CommentaireFormType;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

//Pour filtrer l'accès à cette route en fonction des droits des utilisateurs
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class ArticleController
 * @package App\Controller
 * @Route("/actualites",name="actualites_")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="articles")
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

        //Savoir si un utilisateur est connecté et récupèrer ses informations pour un message
        $user_connectee = null;

        if ($this->isGranted('ROLE_USER') == true) {
            $user_connectee = explode('@', $this->getUser()->getUsername());
            $user_connectee = 'Bonjour ' . $user_connectee[0] . ', devenez rédacteur d\'article sur notre blog.';
        } else {
            $user_connectee = 'Bonjour, inscrivez-vous et devenez rédacteur d\'article sur notre blog.';
        }

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'user_connectee' => $user_connectee
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/article/nouveau", name="ajout_article")
     */
    public function ajoutArticle(Request $request)
    {
        $articles = new Article();
        $form = $this->createForm(AjoutArticleFromType::class, $articles);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //On recuperer l'utilisateur connecter et on le sauvegarde pour l'article
            $articles->setUser($this->getUser());
            //On instancies Doctrine
            $doctrine = $this->getDoctrine()->getManager();
            //On hydrate $commentaire
            $doctrine->persist($articles);
            //On écrit dans la base de données
            $doctrine->flush();

            //Message flash
            $this->addFlash('message_publie', 'Votre article a bien été publié !');

            //Retour à l'accueil
            return $this->redirectToRoute('actualites_articles');
        }

        return $this->render('article/ajout_article.html.twig', [
            'articleForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/article/{slug}", name="article")
     */
    public function article($slug, Request $request)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->findOneBy([
            'slug' => $slug
        ]);

        if (!$article) {
            throw $this->createNotFoundException("L'article recherché n'existe pas !");
        }

        //On instancie l'entité Commentaires
        $commentaires = new Commentaire();

        //On crée l'object formulaire
        $form = $this->createForm(CommentaireFormType::class, $commentaires);

        //On récupère les données saisies au Submit
        $form->handleRequest($request);
        //On vérifie si le formulaire a été envoyé et si les données sont valides
        if ($form->isSubmitted() && $form->isValid()) {
            //Ici le formulaire a été envoyé et les données sont valides
            //On passe l'object $article pour joindre le commentaire à l'articke
            $commentaires->setArticle($article);
            $commentaires->setCreatedAt(new \DateTime('now'));
            //On instancies Doctrine
            $doctrine = $this->getDoctrine()->getManager();
            //On hydrate $commentaire
            $doctrine->persist($commentaires);
            //On écrit dans la base de données
            $doctrine->flush();
        }

        return $this->render('article/article.html.twig', [
            'article' => $article,
            'commentaires' => $commentaires,
            'commentaireForm' => $form->createView()
        ]);
    }
}
