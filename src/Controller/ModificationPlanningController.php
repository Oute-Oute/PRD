<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;

class ModificationPlanningController extends AbstractController
{
    public function modificationPlanningGet(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository("App\Entity\ResourceType");
        $resourcestypes = $repository->findAll();

        return $this->render('planning/modification-planning.html.twig', ['resourcestypes' => $resourcestypes ]);
    }
}