<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/home", name="home_")
 */
class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function mainIndex()
    {
        return $this->render('main/mainIndex.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * @Route("/legal-notices",name="legal_notices" )
     */
    public function legalNotices()
    {
        return $this->render('main/mainLegalNotices.html.twig');
    }

    /**
     * @Route("/change-locale/{locale}",name="change_locale" )
     */
    public function changeLocale($locale, Request $request)
    {
        //On stocke la langue demandée dans la session
        $request->getSession()->set('_locale', $locale);

        //On revient sur la page précédente
        return $this->redirect($request->headers->get('referer'));
    }
}
