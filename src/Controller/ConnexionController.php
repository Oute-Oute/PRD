<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ConnexionController extends AbstractController
{
    /**
     * @Route("/connexion", name="app_connexion")
     */
    public function connexionGet(Request $request)
    {
        return $this->render('connexion/connexion.html.twig');
    }
}
