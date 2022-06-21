<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ConsultationPlanningController extends AbstractController
{
    public function consultationPlanningGet(): Response
    {
        return $this->render('planning/consultation-planning.html.twig');
    }
}