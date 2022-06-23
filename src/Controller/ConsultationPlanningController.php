<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;

class ConsultationPlanningController extends AbstractController
{
    public function consultationPlanningGet(ManagerRegistry $doctrine): Response
    {
        
        //Récupération des données ressources de la base de données
        $repository = $doctrine->getRepository("App\Entity\ResourceType");
        $resourcestypes = $repository->findAll();

        $resourcesCollection=array();
        $resources = $doctrine->getRepository("App\Entity\Resource")->findAll();  
        foreach($resources as $resources){
            $resourcesCollection[]=array(
                'id' =>(str_replace(" ", "3aZt3r", $resources->getId())),
                'title'=>(str_replace(" ", "3aZt3r", $resources->getName())),
            ); 
        }   
        $jsonresponse= new JsonResponse($resourcesCollection); 
        return $this->render('planning/consultation-planning.html.twig', ['resourcestypes' => $resourcestypes, 'resources'=>$jsonresponse ]);

    }



}

