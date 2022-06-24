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
        $listePatientsJSON=$this->listePatientsJSON($doctrine);
        $listeCircuitsJSON=$this->listeCircuitsJSON($doctrine);
        $listeCircuitsPatientsJSON=$this->listeCircuitsPatientsJSON($doctrine);
        $listeActivitiesJSON=$this->listeActivitiesJSON($doctrine);
        $listeActivitiesCircuitsJSON=$this->listeActivitiesCircuitsJSON($doctrine);
        $listeActivitiesResourceTypeJSON=$this->listeActivitiesResourceTypeJSON($doctrine);
        $listeCompleteActivitiesJSON=$this->listeCompleteActivitiesJSON($doctrine);
        return $this->render('planning/consultation-planning.html.twig', 
            ['resourcestypes' => $listeResourceTypes, 
            'listeresources'=>$listeResource, 
            'listeResourcesJSON'=>$listeResourceJSON, 
            'datetoday' => $date_today 
            ,'listePatientsJSON'=>$listePatientsJSON
            ,'listeCircuitsJSON'=>$listeCircuitsJSON
            ,'listeCircuitsPatientsJSON'=>$listeCircuitsPatientsJSON
            ,'listeActivitiesJSON'=>$listeActivitiesJSON
            ,'listeActivitiesCircuitsJSON'=>$listeActivitiesCircuitsJSON
            ,'listeActivitiesResourceTypeJSON'=>$listeActivitiesResourceTypeJSON
            ,'listeCompleteActivitiesJSON'=>$listeCompleteActivitiesJSON
            ]);
                    
    }

    public function listeResources(ManagerRegistry $doctrine){
        return $doctrine->getRepository("App\Entity\Resource")->findBy(['able'=>True]);  
    }

    public function listeResourcesJSON(ManagerRegistry $doctrine){
        $resources = $doctrine->getRepository("App\Entity\Resource")->findBy(['able'=>True]);  
        $resourcesArray=array(); 
        foreach($resources as $resource){
            $resourcesArray[]=array(
                'id' =>(str_replace(" ", "3aZt3r", $resource->getId())),
                'title'=>(str_replace(" ", "3aZt3r", $resource->getResourcename())),
                'able' =>(str_replace(" ", "3aZt3r", $resource->isAble())),
            ); 
        }   
        //Conversion des données ressources en json
        $resourcesArrayJSON= new JsonResponse($resourcesArray); 
        return $resourcesArrayJSON; 
    }

    public function listeResourcesTypes(ManagerRegistry $doctrine)
    {
        $listeResourceTypes = $doctrine->getRepository("App\Entity\ResourceType")->findAll();

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

    public function listePatientsJSON(ManagerRegistry $doctrine){
        $patients = $doctrine->getRepository("App\Entity\Patient")->findAll();  
        $patientArray=array(); 
        foreach($patients as $patient){
            $patientArray[]=array(
                'id' =>(str_replace(" ", "3aZt3r", $patient->getId())),
                'lastname'=>(str_replace(" ", "3aZt3r", $patient->getLastname())),
                'firstname' =>(str_replace(" ", "3aZt3r", $patient->getFirstname())),
            ); 
        }   
        //Conversion des données ressources en json
        $patientArrayJSON= new JsonResponse($patientArray); 
        return $patientArrayJSON; 
    }

    public function listecircuitsJSON(ManagerRegistry $doctrine){
        $circuits = $doctrine->getRepository("App\Entity\Circuit")->findAll();  
        $circuitArray=array(); 
        foreach($circuits as $circuit){
            $circuitArray[]=array(
                'id' =>(str_replace(" ", "3aZt3r", $circuit->getId())),
                'target' =>(str_replace(" ", "3aZt3r", $circuit->getTarget())),
                'circuitname'=>(str_replace(" ", "3aZt3r", $circuit->getCircuitname())),
                'circuittype' =>(str_replace(" ", "3aZt3r", $circuit->getCircuittype())),
            ); 
        }   
        //Conversion des données ressources en json
        $circuitArrayJSON= new JsonResponse($circuitArray); 
        return $circuitArrayJSON; 
    }
    public function listecircuitsPatientsJSON(ManagerRegistry $doctrine){
        $circuitsPatients = $doctrine->getRepository("App\Entity\CircuitPatient")->findAll();  
        $circuitPatientArray=array(); 
        foreach($circuitsPatients as $circuitPatient){
            $circuitPatientArray[]=array(
                'id' =>(str_replace(" ", "3aZt3r", $circuitPatient->getId())),
                'patient_id'=>($circuitPatient->getPatient()),
                'circuit_id' =>($circuitPatient->getCircuit()),
            ); 
        }   
        //Conversion des données ressources en json
        $circuitPatientArrayJSON= new JsonResponse($circuitPatientArray); 
        return $circuitPatientArrayJSON; 
    }
    public function listeActivitiesJSON(ManagerRegistry $doctrine){
        $activities = $doctrine->getRepository("App\Entity\Activity")->findAll();  
        $activitiesArray=array(); 
        foreach($activities as $activity){
            $activitiesArray[]=array(
                'id' =>(str_replace(" ", "3aZt3r", $activity->getId())),
                'activityname'=>(str_replace(" ", "3aZt3r", $activity->getActivityname())),
                'duration' =>($activity->getDuration()),
            ); 
        }   
        //Conversion des données ressources en json
        $activitiesArrayJSON= new JsonResponse($activitiesArray); 
        return $activitiesArrayJSON; 
    }
    public function listeActivitiesCircuitsJSON(ManagerRegistry $doctrine){
        $activitiesCircuits = $doctrine->getRepository("App\Entity\ActivityCircuit")->findAll();  
        $activitiesCircuitsArray=array(); 
        foreach($activitiesCircuits as $activityCircuit){
            $activitiesCircuitsArray[]=array(
                'id' =>(str_replace(" ", "3aZt3r", $activityCircuit->getId())),
                'activity'=>($activityCircuit->getActivity()),
                'circuit' =>($activityCircuit->getCircuit()),
            ); 
        }   
        //Conversion des données ressources en json
        $activitiesCircuitsArrayJSON= new JsonResponse($activitiesCircuitsArray); 
        return $activitiesCircuitsArrayJSON; 
    }
    public function listeActivitiesResourceTypeJSON(ManagerRegistry $doctrine){
        $activitiesResources = $doctrine->getRepository("App\Entity\ActivityResourceType")->findAll();  
        $activitiesResourcessArray=array(); 
        foreach($activitiesResources as $activityResource){
            $activitiesResourcessArray[]=array(
                'id' =>(str_replace(" ", "3aZt3r", $activityResource->getId())),
                'activity'=>($activityResource->getActivity()),
                'resourceType' =>($activityResource->getResourceType()),
            ); 
        }   
        //Conversion des données ressources en json
        $activitiesResourcesArrayJSON= new JsonResponse($activitiesResourcessArray); 
        return $activitiesResourcesArrayJSON; 
    }
    public function listeCompleteActivitiesJSON(ManagerRegistry $doctrine){
        $completeActivities = $doctrine->getRepository("App\Entity\CompleteActivity")->findAll();  
        $completeActivitiesArray=array(); 
        foreach($completeActivities as $completeActivity){
            $completeActivitiesArray[]=array(
                'id' =>(str_replace(" ", "3aZt3r", $completeActivity->getId())),
                'startdate'=>($completeActivity->getStartdate()),
                'enddate'=>($completeActivity->getEnddate()),
                'activity'=>($completeActivity->getActivity()),
                'patient'=>($completeActivity->getPatient()),
                
            ); 
        }   
        //Conversion des données ressources en json
        $activitiesCircuitsArrayJSON= new JsonResponse($completeActivitiesArray); 
        return $activitiesCircuitsArrayJSON; 
    }
    public function listeCompleteActivitiesResourcesJSON(ManagerRegistry $doctrine){
        $completeActivitiesResources = $doctrine->getRepository("App\Entity\CompleteActivityResource")->findAll();  
        $completeActivitiesResourcesArray=array(); 
        foreach($completeActivitiesResources as $completeActivityResource){
            $completeActivitiesResourcesArray[]=array(
                'id' =>(str_replace(" ", "3aZt3r", $completeActivityResource->getId())),
                'completeActivity'=>($completeActivityResource->getCompleteactivity()),
                'resource' =>($completeActivityResource->getResource()),
                
            ); 
        }   
        //Conversion des données ressources en json
        $completeActivitiesResourcesJSON= new JsonResponse($completeActivitiesResourcesArray); 
        return $completeActivitiesResourcesJSON; 
    }
}


