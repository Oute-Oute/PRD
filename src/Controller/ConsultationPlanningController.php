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
        $listeResourceJSON=$this->listeResourcesJSON($doctrine); 
        return $this->render('planning/consultation-planning.html.twig',['listeResourcesJSON'=>$listeResourceJSON]);
        
    }

    public function listeResourcesJSON(ManagerRegistry $doctrine) {
        $resources = $doctrine->getRepository("App\Entity\Resource")->findAll();  
        $resourcesArray=array(); 
        foreach($resources as $resource) {
            $resourcesArray[]=array(
                'id' =>(str_replace(" ", "3aZt3r", $resource->getId())),
                'title'=>(str_replace(" ", "3aZt3r", $resource->getName())),
            ); 
        }   
        //Conversion des données ressources en json
        $resourcesArrayJson= new JsonResponse($resourcesArray); 
        return $resourcesArrayJson; 
    }


}

