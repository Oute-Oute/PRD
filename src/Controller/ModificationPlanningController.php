<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\ScheduledActivity;
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
        $listeHumanResourceJSON=$this->listHumanResourcesJSON($doctrine); 
        $listeAppointmentJSON=$this->listAppointmentJSON($doctrine); 

    return $this->render('planning/modification-planning.html.twig', [
        'listepatients'=>$listePatients, 
        'listePathWaypatients' => $listePathWayPatients, 
        'listeHumanResourcesJSON'=>$listeHumanResourceJSON,
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

    public function listScehduledActivity(ManagerRegistry $doctrine, ScheduledActivityRepository $SAR,$date){
        $TodayDate=substr($date,0,10); 
        
        
        $scheduledActivities=$SAR->findSchedulerActivitiesByDate($TodayDate); 
        $scheduledActivitiesArray=array();  
        foreach($scheduledActivities as $scheduledActivity){
            $scheduledActivitiesHumanResources=$doctrine->getRepository("App\Entity\HumanResourceScheduled")->findBy((['scheduledactivity'=>$scheduledActivity->getId()]));  
            $scheduledActivitiesHumanResourcesArray=array(); 
            foreach($scheduledActivitiesHumanResources as $scheduledActivitiesHumanResource){
                array_push($scheduledActivitiesHumanResourcesArray,$scheduledActivitiesHumanResource->getHumanresource()->getId()); 
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
                'resourceIds'=>$scheduledActivitiesHumanResourcesArray,
                'patient'=>$patientId,
                'appointment'=>$idAppointment,
                'activity'=>$idActivity,
            ); 
        }
        $scheduledActivitiesArrayJson= new JsonResponse($scheduledActivitiesArray); 
        return $scheduledActivitiesArrayJson; 
    }

    public function modificationPlanningValidation(Request $request, ScheduledActivityRepository $scheduledActivityRepository)
    {
        $listeEvent = json_decode($request->request->get("events"));
        $date = $request->request->get("validation-date");

        $listeScheduledActivity = $scheduledActivityRepository->findBy(['dayscheduled' => \DateTime::createFromFormat('Y-m-d', substr($date,0,10))]);
        foreach($listeEvent as $event)
        {
            $scheduledActivityExist = false;

            foreach($listeScheduledActivity as $scheduledActivity)
            {
                if($scheduledActivity->getId() == $event->id)
                {
                    $scheduledActivityExist = true;

                    $scheduledActivity->setStarttime(\DateTime::createFromFormat('H:i:s', substr($event->start,11,16)));
                    $scheduledActivity->setEndtime(\DateTime::createFromFormat('H:i:s', substr($event->end,11,16)));
                    $scheduledActivity->setDayscheduled(\DateTime::createFromFormat('Y-m-d', substr($event->start,0,10)));

                    $scheduledActivityRepository->add($scheduledActivity, true);
                }
            }

            if($scheduledActivityExist == false)
            {
                $scheduledActivity = new ScheduledActivity();
                $scheduledActivity->setStarttime(\DateTime::createFromFormat('H:i:s', substr($event->start,11,16)));
                $scheduledActivity->setEndtime(\DateTime::createFromFormat('H:i:s', substr($event->end,11,16)));
                $scheduledActivity->setDayscheduled(\DateTime::createFromFormat('Y-m-d', substr($event->start,0,10)));
                $scheduledActivity->setActivity($event->extendedProps->activity);
                $scheduledActivity->setAppointment($event->extendedProps->appointment);

                $scheduledActivityRepository->add($scheduledActivity, true);
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
