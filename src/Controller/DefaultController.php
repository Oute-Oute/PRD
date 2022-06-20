<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="calendrier")
     */
    public function index()
    {
        return $this->render('calendrier/calendrier.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}
