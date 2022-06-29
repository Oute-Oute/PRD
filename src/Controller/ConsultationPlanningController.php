<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;
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
        $listeScheduledActivitiesJSON=$this->listeScheduledActivitiesJSON($doctrine); //Récupération des données activités programmées de la base de données
        $listeAppointmentJSON=$this->listeAppointmentJSON($doctrine); //Récupération des données pathway-patient de la base de données
        $listeMaterialResourceScheduledJSON=$this->listeMaterialResourceScheduledJSON($doctrine); //Récupération des données mrsa de la base de données
        $listeHumanResourceScheduledJSON=$this->listeHumanResourceScheduledJSON($doctrine); //Récupération des données HR-activité programmée de la base de données
        $listeHumanResourcesJSON=$this->listeHumanResourceJSON($doctrine); //Récupération des données ressources humaines de la base de données
        $listeMaterialResourceJSON=$this->listeMaterialResourceJSON($doctrine); //Récupération des données ressources matérielles de la base de données

        return $this->render('planning/consultation-planning.html.twig', 
            [
            'datetoday' => $date_today,
            'listeScheduledActivitiesJSON'=>$listeScheduledActivitiesJSON,
            'listeAppointmentJSON'=>$listeAppointmentJSON,
            'listeMaterialResourceScheduledJSON'=>$listeMaterialResourceScheduledJSON,
            'listeHumanResourceScheduledJSON'=>$listeHumanResourceScheduledJSON,

            

        
            ]);
                    
    }
   

    public function listeScheduledActivitiesJSON(ManagerRegistry $doctrine){
        /*$scheduledActivities = $doctrine->getRepository("App\Entity\ScheduledActivity")->findAll();  
        $scheduledActivitiesArray=array(); 
        foreach($scheduledActivities as $scheduledActivity){
            //$patientId=$scheduledActivity->getPatient()->getId();
            //$patientId="patient_".$patientId;
            //$patientName=$scheduledActivity->getPatient()->getLastname();
            $start=$scheduledActivity->getStarttime();
            $day=$scheduledActivity->getDay();
            $day=$day->format('Y-m-d');
            $start=$start->format('H:i:s');
            $start=$day."T".$start;
            $end=$scheduledActivity->getEndtime();
            $end=$end->format('H:i:s');
            $end=$day."T".$end;
            $scheduledActivitiesArray[]=array(
                'id' =>(str_replace(" ", "3aZt3r", $scheduledActivity->getId())),
                'start'=>$start,
                'end'=>$end,
                'title'=>($scheduledActivity->getActivity()->getActivityname())
                
                
            ); 
        }   
        //Conversion des données ressources en json
        $scheduledActivitiesArrayJSON= new JsonResponse($scheduledActivitiesArray); 
        return $scheduledActivitiesArrayJSON; */
    }

    public function listeAppointmentJSON(ManagerRegistry $doctrine){

        $date_today=date('Y-m-d');
        if(isset($_GET["date"])){
            $date_today = $_GET["date"];
            $date_today = str_replace('T12:00:00', '', $date_today);
        }
        $date = new \DateTime($date_today);

        $Appointments = $doctrine   ->getRepository("App\Entity\Appointment")
                                    ->findBy(array('dayappointment'=>$date));  
        $AppointmentArray=array(); 
        foreach($Appointments as $Appointment){
            $AppointmentArray[]=array(
                'id' =>(str_replace(" ", "3aZt3r", $Appointment->getId())),
                'day'=>( $Appointment->getDayappointment()->format('Y-m-d')),
                'earliestappointmenttime'=>(str_replace(" ", "3aZt3r", $Appointment->getEarliestappointmenttime())),
                'latestappointmenttime'=>(str_replace(" ", "3aZt3r", $Appointment->getLatestappointmenttime())),
                'scheduled'=>$Appointment->isScheduled(),
                'patient'=>$this->getPatient($doctrine,$Appointment->getPatient()->getId()),
                'pathway'=>$this->getPathway($doctrine,$Appointment->getPathway()->getId()),
            ); 
        }   
        //Conversion des données ressources en json
        $AppointmentArrayJSON= new JsonResponse($AppointmentArray); 
        return $AppointmentArrayJSON; 
    }
    public function getPathway(ManagerRegistry $doctrine, $id){
        $pathway = $doctrine->getRepository("App\Entity\Pathway")->findOneBy(array('id'=>$id));  
        $pathwayArray=array(); 
            $idpath=$pathway->getId();
            $idpath="pathway_".$idpath;
            $pathwayArray[]=array(
                'id' =>$idpath,
                'title'=>(str_replace(" ", "3aZt3r", $pathway->getPathwayname()))
            );         
        return $pathwayArray; 
    }

    public function listeMaterialResourceScheduledJSON(ManagerRegistry $doctrine){
        $MaterialResourceScheduleds = $doctrine->getRepository("App\Entity\MaterialResourceScheduled")->findAll();  
        $MaterialResourceScheduledArray=array(); 
        foreach($MaterialResourceScheduleds as $MaterialResourceScheduled){
            $MaterialResourceScheduledArray[]=array(
                'id' =>(str_replace(" ", "3aZt3r", $MaterialResourceScheduled->getId())),
                'scheduledActivity'=>($MaterialResourceScheduled->getScheduledactivity()),
                'materialResource' =>($MaterialResourceScheduled->getMaterialresource()),
                
            ); 
        }   
        //Conversion des données ressources en json
        $MaterialResourceScheduledArrayJSON= new JsonResponse($MaterialResourceScheduledArray); 
        return $MaterialResourceScheduledArrayJSON; 
    }
    public function listeHumanResourceScheduledJSON(ManagerRegistry $doctrine){
        $HumanResourceScheduleds = $doctrine->getRepository("App\Entity\HumanResourceScheduled")->findAll();  
        $HumanResourceScheduledArray=array(); 
        foreach($HumanResourceScheduleds as $HumanResourceScheduled){
            $HumanResourceScheduledArray[]=array(
                'id' =>(str_replace(" ", "3aZt3r", $HumanResourceScheduled->getId())),
                'scheduledActivity'=>($HumanResourceScheduled->getScheduledactivity()),
                'humanResource' =>($HumanResourceScheduled->getHumanresource()),
                
            ); 
        }   
        //Conversion des données ressources en json
        $HumanResourceScheduledArrayJSON= new JsonResponse($HumanResourceScheduledArray); 
        return $HumanResourceScheduledArrayJSON; 
    }

    public function getPatient(ManagerRegistry $doctrine, $id){
        $patient = $doctrine->getRepository("App\Entity\Patient")->findOneBy(array('id'=>$id));  
        $patientArray=array(); 
            $lastname=$patient->getLastname();
            $firstname=$patient->getFirstname();
            $title=$lastname." ".$firstname;
            $id=$patient->getId();
            $id="patient_".$id;
            $patientArray[]=array(
                'id' =>$id,
                'lastname'=>(str_replace(" ", "3aZt3r", $lastname)),
                'firstname' =>(str_replace(" ", "3aZt3r", $firstname)),
                'title'=>$title
            ); 
        
        //Conversion des données ressources en json 
        return $patientArray; 
    }

    
    public function listeMaterialResourceJSON(ManagerRegistry $doctrine){
        $materialResources = $doctrine->getRepository("App\Entity\MaterialResource")->findAll();  
        $materialResourceArray=array(); 
        foreach($materialResources as $materialResource){
            $idmr=$materialResource->getId();
            $idmr="material_".$idmr;
            $materialResourceArray[]=array(
                'id' =>$idmr,
                'title'=>($materialResource->getMaterialResourcename()),
                'available'=>($materialResource->isAvailable())
                
            ); 
        }   
        //Conversion des données ressources en json
        $materialResourceArrayJSON= new JsonResponse($materialResourceArray); 
        return $materialResourceArrayJSON; 
    }

    public function listeHumanResourceJSON(ManagerRegistry $doctrine){
        $humanResources = $doctrine->getRepository("App\Entity\HumanResource")->findAll();  
        $humanResourceArray=array(); 
        foreach($humanResources as $humanResource){
            $idhr=$humanResource->getId();
            $idhr="human_".$idhr;
            $humanResourceArray[]=array(
                'id' =>$idhr,
                'title'=>($humanResource->getHumanResourcename()),
                'available'=>($humanResource->isAvailable())
                
            ); 
        }   
        //Conversion des données ressources en json
        $humanResourceArrayJSON= new JsonResponse($humanResourceArray); 
        return $humanResourceArrayJSON; 
    }

}


