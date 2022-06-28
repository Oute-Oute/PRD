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
        
        $listeHumanResourceJSON=$this->listHumanResourcesJSON($doctrine); 

        return $this->render('planning/modification-planning.html.twig', ['listepatients'=>$listePatients, 'listePathWaypatients' => $listePathWayPatients, 'listeHumanResourcesJSON'=>$listeHumanResourceJSON,'listHumanResources'=>$listHumanResources,'listMaterialResources'=>$listMaterialResources, 'datetoday' => $date_today,'listeScheduledActivitiesJSON'=>$listescheduledActivity ]);
    }

    public function modificationPlanningPost(Request $request, ManagerRegistry $doctrine, EntityManagerInterface $entityManager,ScheduledActivityRepository $SAR)
    {
        echo 'alo'; 
        $form = $request->request->get('form');
        
        

        if($form == 'modify')
        {
            $title = $request->request->get('title');
            $start = $request->request->get('date');
            $length = $request->request->get('length');
            $id = $request->request->get('id');

            $repositoryPCR = $doctrine->getRepository('\App\Entity\ScheduledActivity');
            dd($title,$start,$length,$id); 
            if(isset($title) && isset($start) && isset($length) && isset($id)){
                $SA = $repositoryPCR->find($id);
                $date_start = \DateTime::createFromFormat('Y-m-d H:i', str_replace("T", "", $start));
                $SA->setStartdate($date_start);
                $SA->setEnddate(strtotime($date_start)+$length*60); 
                dd($SA->getendDate()); 
                $entityManager->flush();
            }
        }
        else if($form == 'add')
        {
            echo "</br>" . "j'ajoute" . "</br>";
        }

        return $this->modificationPlanningGet($doctrine, $SAR); 


    }

    public function listHumanResourcesJSON(ManagerRegistry $doctrine){
        $resources = $doctrine->getRepository("App\Entity\HumanResource")->findAll();  
        $resourcesArray=array();  
        foreach($resources as $resource){
            $resourcesArray[]=array(
                'id' =>(str_replace(" ", "3aZt3r", $resource->getId())),
                'title'=>(str_replace(" ", "3aZt3r", $resource->getHumanresourcename())),
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
            $scheduledActivitiesHumanResources=$doctrine->getRepository("App\Entity\HRSA")->findBy((['scheduledactivity'=>$scheduledActivity->getId()]));  
            $scheduledActivitiesHumanResourcesArray=array(); 
            foreach($scheduledActivitiesHumanResources as $scheduledActivitiesHumanResource){
                array_push($scheduledActivitiesHumanResourcesArray,$scheduledActivitiesHumanResource->getHumanresource()->getId()); 
            }
                $id=$scheduledActivity->getId();
                $id="patient_".$id;
                $start=$scheduledActivity->getStartDate();
                $start=$start->format('Y-m-d H:i:s');
                $start=str_replace(" ", "T", $start);
                $end=$scheduledActivity->getEndDate();
                $end=$end->format('Y-m-d H:i:s');
                $end=str_replace(" ", "T", $end);
                $scheduledActivitiesArray[]=array(
                    'id' =>(str_replace(" ", "3aZt3r", $scheduledActivity->getId())),
                    'start'=>$start,
                    'end'=>$end,
                    'title'=>($scheduledActivity->getActivity()->getActivityname()),
                    'resourceIds'=>$scheduledActivitiesHumanResourcesArray,
            );
        }
        $scheduledActivitiesArrayJson= new JsonResponse($scheduledActivitiesArray); 
        return $scheduledActivitiesArrayJson; 
    }
}
