<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Controller de la page modification du planning
 */
class ModificationPlanningController extends AbstractController
{
    /**
     * Fonction pour l'affichage de la page modification planning par la méthode GET
     */
    public function modificationPlanningGet(ManagerRegistry $doctrine): Response
    {
        $date_today = $_GET["date"];
        
        //Récupération des données ressources de la base de données
        $resources = $doctrine->getRepository("App\Entity\Resource")->findAll();  
        $listeResourceTypes = $this->listeTypeResources($doctrine);
        $resourcesArray=array(); 
        foreach($resources as $resource){
            $resourcesCollection[]=array(
                'id' =>(str_replace(" ", "3aZt3r", $resource->getId())),
                'title'=>(str_replace(" ", "3aZt3r", $resource->getName())),
            ); 
        }   
        //Conversion des données ressources en json
        $resourcesArrayJson= new JsonResponse($resourcesArray); 

        //Récupération des données Activity de la base de données
        //A tester
        /*
        $activities=$doctrine->getRepository("App/Entity/PatientActivityResource")->findBy(); 
        $activitiesArray=array(); 
       
        foreach($activities as $activity){
             $activityClass=new \stdClass(); 
             $activityClass->idActivity = $activity->getActivityId()->getId();
            $activityClass->idPatient = $activity->getPatientId()->getId();
            $activityClass->date_debut=$activity->getDate()->getDate_debut(); 
            $activityClass->date_fin=$activity->getDate()->getDate_fin(); 
            $activityClass->idRessource = $activity->getResourceId()->getId();
            array_push($activitiesArray, $activityClass);
        }  */

        return $this->render('planning/modification-planning.html.twig', ['resourcestypes' => $listeResourceTypes, 'listeresources'=>$resources, 'resources'=>$resourcesArrayJson, 'datetoday' => $date_today ]);
    }

    public function modificationPlanningPost(Request $request, ManagerRegistry $doctrine, EntityManagerInterface $entityManager)
    {
        $form = $request->request->get('form');
        
        dd($request);

        if($form == 'modify')
        {
            $title = $request->request->get('title');
            $start = $request->request->get('start');
            $length = $request->request->get('length');
            $id = $request->request->get('id');

            $repositoryPCR = $doctrine->getRepository('\App\Entity\PatientCircuitResource');
            
            if(isset($title) && isset($start) && isset($length) && isset($id)){
                $PCR = $repositoryPCR->find($id);
                $date_start = \DateTime::createFromFormat('Y-m-d H:i', str_replace("T", "", $start));
                $PCR->setStartDateTime($date_start);
                $entityManager->flush();
            }
        }
        else if($form == 'add')
        {
            echo "</br>" . "j'ajoute" . "</br>";
        }
    }

    public function listeTypeResources(ManagerRegistry $doctrine)
    {
        $listeTypeResources = array();

        $listeActivitiesResourceTypes = $doctrine->getRepository("App\Entity\ActivityResourceType")->findAll();


        foreach($listeActivitiesResourceTypes as $activityResourceType)
        {
            $result = new \stdClass();
            
            $result->idActivity = $activityResourceType->getActivityId()->getId();
            $result->idResourceType = $activityResourceType->getResourceTypeId()->getId();
            $result->categoryResourceType = $activityResourceType->getResourceTypeId()->getCategory();

            array_push($listeTypeResources, $result);
        }
        return $listeTypeResources;
    }

    public function listeResourceTypes(ManagerRegistry $doctrine)
    {
        $listeResourceTypes = array();

        $listeActivitiesResourceTypes = $doctrine->getRepository("App\Entity\ActivityResourceType")->findAll();


        foreach($listeActivitiesResourceTypes as $activityResourceType)
        {
            $result = new \stdClass();
            
            $result->idActivity = $activityResourceType->getActivityId()->getId();
            $result->idResourceType = $activityResourceType->getResourceTypeId()->getId();
            $result->categoryResourceType = $activityResourceType->getResourceTypeId()->getCategory();

            array_push($listeResourceTypes, $result);
        }

        dd($listeResourceTypes);
        return $listeResourceTypes;
    }
}

