<?php

namespace App\Controller;

use App\Entity\ScheduledActivity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ScheduledActivityRepository; 

/**
 * Controller de la page modification du planning
 */
class ModificationPlanningController extends AbstractController
{
    /**
     * Fonction pour l'affichage de la page modification planning par la méthode GET
     */
    public function modificationPlanningGet(ManagerRegistry $doctrine, ScheduledActivityRepository $SAR): Response
    {
        $date_today = $_GET["date"];
        //Récupération des données nécessaires
        $listHumanResources = $doctrine->getRepository("App\Entity\HumanResource")->findBy(['available' => true]);
        $listMaterialResources=$doctrine->getRepository("App\Entity\MaterialResource")->findBy(['available'=>true]); 
        $listePatients = $doctrine->getRepository("App\Entity\Patient")->findAll();
        $listePathWayPatients = $doctrine->getRepository("App\Entity\PP")->findAll();

        $listescheduledActivity=$this->listScehduledActivity($doctrine,$SAR);  
        
        $listeResourceJSON=$this->listHumanResourcesJSON($doctrine); 

        return $this->render('planning/modification-planning.html.twig', ['listepatients'=>$listePatients, 'listePathWaypatients' => $listePathWayPatients, 'listeResourcesJSON'=>$listeResourceJSON,'listHumanResources'=>$listHumanResources,'listMaterialResources'=>$listMaterialResources, 'datetoday' => $date_today ]);
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

            $repositoryPCR = $doctrine->getRepository('\App\Entity\PatientPathWayResource');
            
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

    public function listHumanResourcesJSON(ManagerRegistry $doctrine){
        $resources = $doctrine->getRepository("App\Entity\HumanResource")->findAll();  
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

    public function listMaterialResourcesJSON(ManagerRegistry $doctrine){
        $resources = $doctrine->getRepository("App\Entity\MaterialResource")->findAll();  
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

    public function listScehduledActivity(ManagerRegistry $doctrine, ScheduledActivityRepository $SAR){
        $TodayDate="%";
        $TodayDate .=(substr($_GET['date'],0,10)); 
        $TodayDate=$TodayDate; 
        $TodayDate .="%";
        
        
        $scheduledActivities=$SAR->findSchedulerActivitiesByDate($TodayDate); 
        $scheduledActivitiesArray=array();  
        foreach($scheduledActivities as $scheduledActivity){
            $scheduledActivitiesHumanResources=$doctrine->getRepository("App\Entity\HRSA")->findBy((['id'=>$scheduledActivity->getId()])); 
            $scheduledActivitiesHumanResourcesArray=array(); 
            foreach($scheduledActivitiesHumanResources as $scheduledActivitiesHumanResource){
                array_push($scheduledActivitiesHumanResourcesArray,$scheduledActivitiesHumanResource); 
            }
            
            $scheduledActivitiesArray[]=array(
                'id'=>$scheduledActivity->getId(), 
                'title'=>$scheduledActivity->getActivity()->GetActivityName(),
                'startDate'=>$scheduledActivity->getStartDate(),
                'endDate'=>$scheduledActivity->getEndDate(),
                'resourceIds'=>$scheduledActivitiesHumanResourcesArray,
                'PatientLastName'=>$scheduledActivity->getPatient()->getLastname()
            );
        }
        $scheduledActivitiesArrayJson= new JsonResponse($scheduledActivitiesArray); 
        return $scheduledActivitiesArrayJson; 
    }
}
