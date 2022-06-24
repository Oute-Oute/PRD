<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;

class ConsultationPlanningController extends AbstractController
{
     /**
     * Fonction pour l'affichage de la page consultation planning par la méthode GET
     */
    public function consultationPlanningGet(ManagerRegistry $doctrine): Response
    {
        $date_today=date(('Y-m-d'));
        if(isset($_GET["date"])){
            $date_today = $_GET["date"];
            $date_today = str_replace('T12:00:00', '', $date_today);
        }
        //Récupération des données ressources de la base de données
        $listeResourceTypes=$this->listeResourcesTypes($doctrine); 
        $listeResourceJSON=$this->listeResourcesJSON($doctrine); 
        $listeResource=$this->listeResources($doctrine); 

        return $this->render('planning/consultation-planning.html.twig', ['resourcestypes' => $listeResourceTypes, 'listeresources'=>$listeResource, 'listeResourcesJSON'=>$listeResourceJSON, 'datetoday' => $date_today ]);
    }

    public function listeResources(ManagerRegistry $doctrine){
        return $doctrine->getRepository("App\Entity\Resource")->findAll();  
    }

    public function listeResourcesJSON(ManagerRegistry $doctrine){
        $resources = $doctrine->getRepository("App\Entity\Resource")->findAll();  
        $resourcesArray=array(); 
        foreach($resources as $resource){
            $resourcesArray[]=array(
                'id' =>(str_replace(" ", "3aZt3r", $resource->getId())),
                'title'=>(str_replace(" ", "3aZt3r", $resource->getName())),
            ); 
        }   
        //Conversion des données ressources en json
        $resourcesArrayJson= new JsonResponse($resourcesArray); 
        return $resourcesArrayJson; 
    }

    public function listeResourcesTypes(ManagerRegistry $doctrine)
    {
        $listeResourceTypes = array();

        $listeActivitiesResourceTypes = $doctrine->getRepository("App\Entity\ResourceType")->findAll();

        /*
        foreach($listeActivitiesResourceTypes as $activityResourceType)
        {
            $result = new \stdClass();
            
            $result->idActivity = $activityResourceType->getActivities()->getId();
            $result->idResourceType = $activityResourceType->getResourceTypeId()->getId();
            $result->categoryResourceType = $activityResourceType->getResourceTypeId()->getCategory();

            array_push($listeResourceTypes, $result);
        }

        //dd($listeResourceTypes);
        */
        return $listeResourceTypes;
    }

}

