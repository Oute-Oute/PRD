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
        $listeResourceTypes=$this->listeResourcesTypes($doctrine); 
        $listeResourceJSON=$this->listeResourcesJSON($doctrine); 
        $listeResource=$this->listeResources($doctrine); 

        return $this->render('planning/modification-planning.html.twig', ['resourcestypes' => $listeResourceTypes, 'listeresources'=>$listeResource, 'listeResourcesJSON'=>$listeResourceJSON, 'datetoday' => $date_today ]);
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

    public function listeResources(ManagerRegistry $doctrine){
        return $doctrine->getRepository("App\Entity\Resource")->findAll();  
    }

    public function PatientActivityResource(ManagerRegistry $doctrine){
        //Récupération des données Activity de la base de données
        //A tester
        $str = str_replace("T", " ", $_GET['date']);
        $str=substr($str,0,16); 
        $date_start = \DateTime::createFromFormat('Y-m-d H:m', $str);
        //dd(str_replace("T", " ", $_GET['date']), $_GET['date'], $date_start); 
        //$activities=$doctrine->getRepository("App\Entity\PatientActivityResource")->findBy(array('start_datetime' => $date_start)); 
        $activities=$doctrine->getRepository("App\Entity\PatientActivityResource")->findAll(); 
        $activitiesArray=array(); 
        /*
        foreach($activities as $activity){
             $activityClass=new \stdClass(); 
             $activitiesArray[]=array(
                'idActivity' => $activity->getActivity(),
                'idPatient' => $activity->getPatient(),
                'idRessource' => $activity->getResource(),
                'date_debut'=>$activity->getStartDatetime(),
                'date_fin'=>$activity->getEndDatetime(),  
             );
             dd($activitiesArray);  
             $activityClass->idActivity = $activity->getActivity()->getId();
             $activityClass->idPatient = $activity->getPatient()->getId();
             $activityClass->idRessource = $activity->getResource()->getId();
             $activityClass->date_debut=$activity->getStartDatetime();  
             $activityClass->date_fin=$activity->getDate()->getEndDatetime();  
           
            array_push($activitiesArray, $activityClass);
        }  
        */
        return $activitiesArray; 
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

