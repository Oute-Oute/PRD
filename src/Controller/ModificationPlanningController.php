<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ModificationPlanningController extends AbstractController
{
    public function modificationPlanningGet(): Response
    {
        return $this->render('planning/modification-planning.html.twig');
    }
}