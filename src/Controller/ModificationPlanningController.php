<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\MaterialResourceScheduled;
use App\Entity\HumanResourceScheduled;
use App\Entity\ScheduledActivity;
use App\Repository\MaterialResourceScheduledRepository;
use App\Repository\HumanResourceScheduledRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ScheduledActivityRepository;
use DateInterval;
use Symfony\Component\Validator\Constraints\Length;

/**
 * Controller de la page modification du planning
 */
class ModificationPlanningController extends AbstractController
{
    /**
     * Fonction pour l'affichage de la page modification planning par la méthode GET
     */
    public function modificationPlanningGet(Request $request,ManagerRegistry $doctrine, ScheduledActivityRepository $SAR, EntityManagerInterface $entityManager): Response
    {
        $date_today=array(); 
        if(isset($_POST['form'])){
            $this->modificationPlanningPost($request,$doctrine,$entityManager); 
            $date_today=$_POST['date']; 
        }
        if(isset($_GET['date'])){
            $date_today = $_GET["date"];
        }
        //Récupération des données nécessaires
        $listHumanResources = $doctrine->getRepository("App\Entity\HumanResource")->findBy(['available' => true]);
        $listMaterialResources=$doctrine->getRepository("App\Entity\MaterialResource")->findBy(['available'=>true]); 
        $listePatients = $doctrine->getRepository("App\Entity\Patient")->findAll();
        $listePathWayPatients = $doctrine->getRepository("App\Entity\Appointment")->findAll();
        $listeAppointment=$this->listAppointment($doctrine); 
        $listescheduledActivity= $this->listScehduledActivity($doctrine,$SAR,$date_today);  
        $listesuccessionJSON=$this->listSuccessorJSON($doctrine); 
        $listeActivitiesJSON=$this->listActivityJSON($doctrine);  
        $listeAppointmentJSON=$this->listAppointmentJSON($doctrine); 
        
        $listeResourceJSON=$this->listResourcesJSON($doctrine); 

    return $this->render('planning/modification-planning.html.twig', [
        'listepatients'=>$listePatients, 
        'listePathWaypatients' => $listePathWayPatients, 
        'listeResourceJSON'=>$listeResourceJSON,
        'listHumanResources'=>$listHumanResources,
        'listMaterialResources'=>$listMaterialResources, 
        'datetoday' => $date_today,
        'listeScheduledActivitiesJSON'=>$listescheduledActivity,
        'listeAppointments'=>$listeAppointment,
        'listeSuccessorsJSON'=>$listesuccessionJSON,
        'listeActivitiesJSON'=>$listeActivitiesJSON,
        'listeAppointmentsJSON'=>$listeAppointmentJSON
    ]);
    }

    public function modificationPlanningPost(Request $request, ManagerRegistry $doctrine, EntityManagerInterface $entityManager)
    {
        $form = $request->get('form'); 
        
        if($form == 'add')
        {
            $idAppointment=$request->get('select-appointment'); 
            $AppointmentBegin=$request->get('time_begin'); 
            $Appointment=$doctrine->getRepository('App\Entity\Appointment')->findOneBy(['id'=>$idAppointment]); 
            $activities=$doctrine->getRepository('App\Entity\Activity')->findBy(['pathway'=>$Appointment->getPathway()->getId()]);
            foreach($activities as $activity){
              $findfirst=$doctrine->getRepository('App\Entity\Successor')->findBy(['activityb'=>$activity->getId()]); 
              if($findfirst ==null){
                $idfirst=$activity->getId(); 
              }
            }
            $activitya=$doctrine->getRepository('App\Entity\Successor')->findOneBy(['id'=>$idfirst])->getActivitya(); 
            
           do{
            $succesor=$doctrine->getRepository('App\Entity\Successor')->findOneBy(['activitya'=>$activitya->getId()]); 
            dd($succesor); 
            $activityB=$succesor->getActivityb();  
           }while($activityB!=null); 
        }

         

    }
    public function listSuccessorJSON(ManagerRegistry $doctrine){
        $successors=$doctrine->getRepository('App\Entity\Successor')->findAll(); 
        $successorsArray=array(); 
        foreach($successors as $succesor){
            $successorsArray[]=array(
                'id'=>$succesor->getId(),
                'idactivitya'=>$succesor->getActivitya()->getId(),
                'idactivityb'=>$succesor->getActivityb()->getId(), 
                'delaymin'=>$succesor->getDelaymin(), 
                'delaymax'=>$succesor->getDelaymax(),
            ); 
        }

        $successorsArrayJSON=new JsonResponse($successorsArray); 
        return $successorsArrayJSON; 
    }

    public function listActivityJSON(ManagerRegistry $doctrine){
        $activities=$doctrine->getRepository('App\Entity\Activity')->findAll(); 
        $activitiesArray=array(); 
        foreach($activities as $activity){
            $activitiesArray[]=array(
                'id'=>$activity->getId(),
                'name'=>(str_replace(" ", "3aZt3r", $activity->getActivityname())),
                'duration'=>$activity->getDuration(),
                'idPathway'=>$activity->getPathway()->getId()
            );
        }
        $activitiesArrayJSON=new JsonResponse($activitiesArray); 
        return $activitiesArrayJSON; 
    }
    public function modificationPlanninEventsgGet(Request $request, ManagerRegistry $doctrine, EntityManagerInterface $entityManager)
    {
        

    }


    public function listAppointment(ManagerRegistry $doctrine){
        $appointments=$doctrine->getRepository("App\Entity\Appointment")->findAll();
        /*$appointmentsArray=array(); 
        foreach($appointments as $appointment){
            $appointmentsArray[]=array(
                'id'=>$appointment->getId(),
                'earliestappointmenttime'=>$appointment->getEarliestappointmenttime(), 
                'lastestappointmenttime'=>$appointment->getLatestappointmenttime(),
                'dayappointment'=>$appointment->getDayappointment(),
                'idPatient'=>$appointment->getPatient()->getId(),
                'idPathway'=>$appointment->getPathway()->getId(),
            );
        } */
        return $appointments; 
    }

    public function listAppointmentJSON(ManagerRegistry $doctrine){
        $appointments=$doctrine->getRepository("App\Entity\Appointment")->findAll();
        $appointmentsArray=array(); 
        foreach($appointments as $appointment){
            $appointmentsArray[]=array(
                'id'=>$appointment->getId(),
                'earliestappointmenttime'=>$appointment->getEarliestappointmenttime()->format('H:i:s'), 
                'lastestappointmenttime'=>$appointment->getLatestappointmenttime()->format('H:i:s'),
                'dayappointment'=>$appointment->getDayappointment()->format('Y:m:d'),
                'idPatient'=>$appointment->getPatient()->getId(),
                'idPathway'=>$appointment->getPathway()->getId(),
            );
        } 
        $appointmentsArrayJSON=new JsonResponse($appointmentsArray); 
        return $appointmentsArrayJSON; 
    }
  
    public function listResourcesJSON(ManagerRegistry $doctrine){
        $materialResources = $doctrine->getRepository("App\Entity\MaterialResource")->findAll();  
        $humanResources = $doctrine->getRepository("App\Entity\HumanResource")->findAll();   
        $resourcesArray=array(); 

        if($materialResources != null)
        {
            foreach($materialResources as $materialResource){
                $resourcesArray[]=array(
                    'id' =>("material-".str_replace(" ", "3aZt3r", $materialResource->getId())),
                    'title'=>(str_replace(" ", "3aZt3r", $materialResource->getMaterialresourcename())),
                ); 
            }   
        }

        if($humanResources != null)
        {
            foreach($humanResources as $humanResource){
                $resourcesArray[]=array(
                    'id' =>("human-".str_replace(" ", "3aZt3r", $humanResource->getId())),
                    'title'=>(str_replace(" ", "3aZt3r", $humanResource->getHumanresourcename())),
                ); 
            }   
        }

        //Conversion des données ressources en json
        $resourcesArrayJson = new JsonResponse($resourcesArray); 
        return $resourcesArrayJson; 
    }

    public function listScehduledActivity(ManagerRegistry $doctrine, ScheduledActivityRepository $SAR,$date){
        $TodayDate=substr($date,0,10); 
        
        
        $scheduledActivities=$SAR->findSchedulerActivitiesByDate($TodayDate); 
        $scheduledActivitiesArray=array();  
        foreach($scheduledActivities as $scheduledActivity)
        {
            $scheduledActivitiesHumanResources=$doctrine->getRepository("App\Entity\HumanResourceScheduled")->findBy((['scheduledactivity'=>$scheduledActivity->getId()]));  
            $scheduledActivitiesResourcesArray=array(); 
            foreach($scheduledActivitiesHumanResources as $scheduledActivitiesHumanResource){
                array_push($scheduledActivitiesResourcesArray,"human-".$scheduledActivitiesHumanResource->getHumanresource()->getId()); 
            }

            $scheduledActivitiesMaterialResources=$doctrine->getRepository("App\Entity\MaterialResourceScheduled")->findBy((['scheduledactivity'=>$scheduledActivity->getId()]));   
            foreach($scheduledActivitiesMaterialResources as $scheduledActivitiesMaterialResource){
                array_push($scheduledActivitiesResourcesArray,"material-".$scheduledActivitiesMaterialResource->getMaterialresource()->getId()); 
            }

            $patientId=$scheduledActivity->getAppointment()->getPatient()->getId();
            $start=$scheduledActivity->getStarttime();
            $day=$scheduledActivity->getDayscheduled();
            $day=$day->format('Y-m-d');
            $start=$start->format('H:i:s');
            $start=$day."T".$start;
            $end=$scheduledActivity->getEndtime();
            $end=$end->format('H:i:s');
            $end=$day."T".$end;
            $idAppointment = $scheduledActivity->getAppointment()->getId();
            $idActivity = $scheduledActivity->getActivity()->getId();
            $scheduledActivitiesArray[]=array(
                'id' =>(str_replace(" ", "3aZt3r", $scheduledActivity->getId())),
                'start'=>$start,
                'end'=>$end,
                'title'=>($scheduledActivity->getActivity()->getActivityname()),
                'resourceIds'=>$scheduledActivitiesResourcesArray,
                'patient'=>$patientId,
                'appointment'=>$idAppointment,
                'activity'=>$idActivity,
            ); 
        }
        $scheduledActivitiesArrayJson= new JsonResponse($scheduledActivitiesArray); 
        return $scheduledActivitiesArrayJson; 
    }

    public function modificationPlanningValidation(Request $request, ScheduledActivityRepository $scheduledActivityRepository, HumanResourceScheduledRepository $humanResourceScheduledRepository, MaterialResourceScheduledRepository $materialResourceScheduledRepository, ManagerRegistry $doctrine)
    {
        $listeEvent = json_decode($request->request->get("events"));
        $listeResource = json_decode($request->request->get("list-resource"));
        $listeScheduledEvent = array();
        for($index = 0; $index < sizeof($listeEvent); $index++)
        {
            $newScheduledEvent = array();
            array_push($newScheduledEvent, $listeEvent[$index]);
            array_push($newScheduledEvent, $listeResource[$index]);
            array_push($listeScheduledEvent, $newScheduledEvent);
        }
        $date = $request->request->get("validation-date");

        $listeScheduledActivity = $scheduledActivityRepository->findBy(['dayscheduled' => \DateTime::createFromFormat('Y-m-d', substr($date,0,10))]);
        foreach($listeScheduledEvent as $event)
        {
            $scheduledActivityExist = false;

            foreach($listeScheduledActivity as $scheduledActivity)
            {
                if($scheduledActivity->getId() == $event[0]->id)
                {
                    $scheduledActivityExist = true;

                    $scheduledActivity->setStarttime(\DateTime::createFromFormat('H:i:s', substr($event[0]->start,11,16)));
                    $scheduledActivity->setEndtime(\DateTime::createFromFormat('H:i:s', substr($event[0]->end,11,16)));
                    $scheduledActivity->setDayscheduled(\DateTime::createFromFormat('Y-m-d', substr($event[0]->start,0,10)));

                    $scheduledActivityRepository->add($scheduledActivity, true);

                    $listeMaterialResourceScheduled = $materialResourceScheduledRepository->findBy((['scheduledactivity'=>$scheduledActivity->getId()]));
                    
                    $listeHumanResourceScheduled = $humanResourceScheduledRepository->findBy((['scheduledactivity'=>$scheduledActivity->getId()]));

                    foreach($event[1] as $resourceId)
                    {
                        if(substr($resourceId->id, 0, 5) == "human")
                        {
                            $humanResource = $doctrine->getRepository("App\Entity\HumanResource")->findOneBy(["id" => substr($resourceId->id, 6)]);
                            $humanResourceExist = false;
                            foreach($listeHumanResourceScheduled as $humanResourceScheduled)
                            {
                                if($humanResourceScheduled->getHumanresource() == $humanResource)
                                {
                                    $humanResourceExist = true;
                                }
                            }
                            if(!$humanResourceExist)
                            {
                                $newHumanResourceScheduled = new HumanResourceScheduled();
                                $newHumanResourceScheduled->setHumanresource($humanResource);
                                $newHumanResourceScheduled->setScheduledactivity($scheduledActivity);

                                $humanResourceScheduledRepository->add($newHumanResourceScheduled, true);
                            }
                        }
                        else
                        {
                            $materialResource = $doctrine->getRepository("App\Entity\MaterialResource")->findOneBy(["id" => substr($resourceId->id, 9)]);
                            $materialResourceExist = false;
                            foreach($listeMaterialResourceScheduled as $materialResourceScheduled)
                            {
                                if($materialResourceScheduled->getMaterialresource() == $materialResource)
                                {
                                    $materialResourceExist = true;
                                }
                            }
                            if(!$materialResourceExist)
                            {
                                $newMaterialResourceScheduled = new MaterialResourceScheduled();
                                $newMaterialResourceScheduled->setMaterialresource($materialResource);
                                $newMaterialResourceScheduled->setScheduledactivity($scheduledActivity);

                                $materialResourceScheduledRepository->add($newMaterialResourceScheduled, true);
                            }
                        }
                    }

                    foreach($listeHumanResourceScheduled as $humanResourceScheduled)
                    {
                        $humanResourceExist = false;
                        foreach($event[1] as $resourceId)
                        {
                            if(substr($resourceId->id, 0, 5) == "human")
                            {
                                $humanResource = $doctrine->getRepository("App\Entity\HumanResource")->findOneBy(["id" => substr($resourceId->id, 6)]);
                                if($humanResourceScheduled->getHumanresource() == $humanResource)
                                {
                                    $humanResourceExist = true;
                                }
                            }
                        }
                        if(!$humanResourceExist)
                        {
                            $humanResourceScheduledRepository->remove($humanResourceScheduled, true);
                        }
                    }

                    foreach($listeMaterialResourceScheduled as $materialResourceScheduled)
                    {
                        $materialResourceExist = false;
                        foreach($event[1] as $resourceId)
                        {
                            if(substr($resourceId->id, 0, 8) == "material")
                            {
                                $materialResource = $doctrine->getRepository("App\Entity\MaterialResource")->findOneBy(["id" => substr($resourceId->id, 9)]);
                                if($materialResourceScheduled->getMaterialresource() == $materialResource)
                                {
                                    $materialResourceExist = true;
                                }
                            }
                        }
                        if(!$materialResourceExist)
                        {
                            $materialResourceScheduledRepository->remove($materialResourceScheduled, true);
                        }
                    }
                }
            }

            if($scheduledActivityExist == false)
            {
                $newScheduledActivity = new ScheduledActivity();
                $newScheduledActivity->setStarttime(\DateTime::createFromFormat('H:i:s', substr($event[0]->start,11,16)));
                $newScheduledActivity->setEndtime(\DateTime::createFromFormat('H:i:s', substr($event[0]->end,11,16)));
                $newScheduledActivity->setDayscheduled(\DateTime::createFromFormat('Y-m-d', substr($event[0]->start,0,10)));
                $newScheduledActivity->setActivity($event[0]->extendedProps->activity);
                $newScheduledActivity->setAppointment($event[0]->extendedProps->appointment);

                $scheduledActivityRepository->add($newScheduledActivity, true);

                foreach($event[1] as $resourceId)
                {
                    if(substr($resourceId->id, 0, 5) == "human")
                    {
                        $humanResource = $doctrine->getRepository("App\Entity\HumanResource")->findOneBy(["id" => substr($resourceId->id, 6)]);
                        $newHumanResourceScheduled = new HumanResourceScheduled();
                        $newHumanResourceScheduled->setHumanresource($humanResource);
                        $newHumanResourceScheduled->setScheduledactivity($newScheduledActivity);

                        $humanResourceScheduledRepository->add($newHumanResourceScheduled, true);
                    }
                    else
                    {
                        $materialResource = $doctrine->getRepository("App\Entity\MaterialResource")->findOneBy(["id" => substr($resourceId->id, 9)]);

                        $newMaterialResourceScheduled = new MaterialResourceScheduled();
                        $newMaterialResourceScheduled->setMaterialresource($materialResource);
                        $newMaterialResourceScheduled->setScheduledactivity($newScheduledActivity);

                        $materialResourceScheduledRepository->add($newMaterialResourceScheduled, true);
                    }
                }
            }
        }
        return $this->redirectToRoute('ConsultationPlanning', [], Response::HTTP_SEE_OTHER);
    }

    /*public function writeModifDB(Modification $modification, ModificationRepository $modificationRepository, ManagerRegistry $doctrine)
    {
       
    }*/
    

    // A faire quand l'écriture dans la BDD de la modification sera implémentée
    /*
    public function ModificationDelete(Patient $patient, PatientRepository $patientRepository): Response
    {
        //suppression du patient dans la table Patient
        $patientRepository->remove($patient, true);

        return $this->redirectToRoute('Patients', [], Response::HTTP_SEE_OTHER);
    }
    */
}
