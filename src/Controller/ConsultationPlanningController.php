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
        $listeCategoryMRJSON=$this->listeCategoriesMRJSON($doctrine); //Récupération des données ressources matérielles de la base de données
        $listePatientsJSON=$this->listePatientsJSON($doctrine); //Récupération des données patients de la base de données
        $listePathwaysJSON=$this->listePathwaysJSON($doctrine); //Récupération des données parcours de la base de données
        $listeActivitiesJSON=$this->listeActivitiesJSON($doctrine); //Récupération des données activités de la base de données
        $listeScheduledActivitiesJSON=$this->listeScheduledActivitiesJSON($doctrine); //Récupération des données activités programmées de la base de données
        $listeAppointmentJSON=$this->listeAppointmentJSON($doctrine); //Récupération des données pathway-patient de la base de données
        $listeActivityHumanResourceJSON=$this->listeActivityHumanResourceJSON($doctrine); //Récupération des données activité-chr de la base de données
        $listeActivityMaterialResourceJSON=$this->listeActivityMaterialResourceJSON($doctrine); //Récupération des données activité-cmr de la base de données
        $listeMaterialResourceScheduledJSON=$this->listeMaterialResourceScheduledJSON($doctrine); //Récupération des données mrsa de la base de données
        $listeCategoryHRJSON=$this->listeCategoryHRJSON($doctrine); //Récupération des données catégorie ressource humaine de la base de données
        $listeCategoryOfHumanResourceJSON=$this->listeCategoryOfHumanResourceJSON($doctrine); //Récupération des données catégorie ressource categoryHR-HR de la base de données
        $listeUnavailabilitiesMaterialResourceJSON=$this->listeUnavailabilitiesMaterialResourceJSON($doctrine); //Récupération des données catégorie ressource indisponibilités-MR de la base de données
        $listeUnavailabilitiesJSON=$this->listeUnavailabilitiesJSON($doctrine); //Récupération des données indisponibilités de la base de données
        $listeUnavailabilitiesHumanResourceJSON=$this->listeUnavailabilitiesHumanResourceJSON($doctrine); //Récupération des données indisponibilité-HR de la base de données
        $listeHumanResourceScheduledJSON=$this->listeHumanResourceScheduledJSON($doctrine); //Récupération des données HR-activité programmée de la base de données
        $listeWorkingHoursJSON=$this->listeWorkingHoursJSON($doctrine); //Récupération des données horaires de travail de la base de données
        $listeHumanResourcesJSON=$this->listeHumanResourceJSON($doctrine); //Récupération des données ressources humaines de la base de données
        $listeMaterialResourceJSON=$this->listeMaterialResourceJSON($doctrine); //Récupération des données ressources matérielles de la base de données

        return $this->render('planning/consultation-planning.html.twig', 
            [
            'datetoday' => $date_today,
            'listePatientsJSON'=>$listePatientsJSON,
            'listePathwaysJSON'=>$listePathwaysJSON,
            'listeActivitiesJSON'=>$listeActivitiesJSON,
            'listeScheduledActivitiesJSON'=>$listeScheduledActivitiesJSON,
            'listeAppointmentJSON'=>$listeAppointmentJSON,
            'listeActivityHumanResourceJSON'=>$listeActivityHumanResourceJSON,
            'listeActivityMaterialResourceJSON'=>$listeActivityMaterialResourceJSON,
            'listeMaterialResourceScheduledJSON'=>$listeMaterialResourceScheduledJSON,
            'listeCategoryMRJSON'=>$listeCategoryMRJSON,
            'listeCategoryHRJSON'=>$listeCategoryHRJSON,
            'listeCategoryOfHumanResourceJSON'=>$listeCategoryOfHumanResourceJSON,
            'listeUnavailabilitiesMaterialResourceJSON'=>$listeUnavailabilitiesMaterialResourceJSON,
            'listeUnavailabilitiesJSON'=>$listeUnavailabilitiesJSON,
            'listeUnavailabilitiesHumanResourceJSON'=>$listeUnavailabilitiesHumanResourceJSON,
            'listeHumanResourceScheduledJSON'=>$listeHumanResourceScheduledJSON,
            'listeWorkingHoursJSON'=>$listeWorkingHoursJSON,
            'listeHumanResourcesJSON'=>$listeHumanResourcesJSON,
            'listeMaterialResourceJSON'=>$listeMaterialResourceJSON,
            

        
            ]);
                    
    }
   

    public function listePatientsJSON(ManagerRegistry $doctrine){
        $patients = $doctrine->getRepository("App\Entity\Patient")->findAll();  
        $patientArray=array(); 
        foreach($patients as $patient){
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
        }   
        //Conversion des données ressources en json
        $patientArrayJSON= new JsonResponse($patientArray); 
        return $patientArrayJSON; 
    }

    public function listepathwaysJSON(ManagerRegistry $doctrine){
        $pathways = $doctrine->getRepository("App\Entity\Pathway")->findAll();  
        $pathwayArray=array(); 
        foreach($pathways as $pathway){
            $idpath=$pathway->getId();
            $idpath="pathway_".$idpath;
            $pathwayArray[]=array(
                'id' =>$idpath,
                'target' =>(str_replace(" ", "3aZt3r", $pathway->getTarget())),
                'title'=>(str_replace(" ", "3aZt3r", $pathway->getPathwayname()))
            ); 
        }   
        //Conversion des données ressources en json
        $pathwayArrayJSON= new JsonResponse($pathwayArray); 
        return $pathwayArrayJSON; 
    }
    public function listeAppointmentJSON(ManagerRegistry $doctrine){
        $pathwaysPatients = $doctrine->getRepository("App\Entity\Appointment")->findAll();  
        $pathwayPatientArray=array(); 
        foreach($pathwaysPatients as $pathwayPatient){
            $pathwayPatientArray[]=array(
                'id' =>(str_replace(" ", "3aZt3r", $pathwayPatient->getId())),
                'patient_id'=>($pathwayPatient->getPatient()),
                'pathway_id' =>($pathwayPatient->getPathway()),
            ); 
        }   
        //Conversion des données ressources en json
        $pathwayPatientArrayJSON= new JsonResponse($pathwayPatientArray); 
        return $pathwayPatientArrayJSON; 
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
    public function listeAPJSON(ManagerRegistry $doctrine){
        $activitiesPathways = $doctrine->getRepository("App\Entity\AP")->findAll();  
        $activitiesPathwaysArray=array(); 
        foreach($activitiesPathways as $activityPathway){
            $activitiesPathwaysArray[]=array(
                'id' =>(str_replace(" ", "3aZt3r", $activityPathway->getId())),
                'activity'=>($activityPathway->getActivity()),
                'pathway' =>($activityPathway->getPathway()),
                'activityorder' =>($activityPathway->getActivityorder()),
                'delayminafter' =>($activityPathway->getDelayminafter()),
                'delaymaxafter' =>($activityPathway->getDelaymaxafter()),
            ); 
        }   
        //Conversion des données ressources en json
        $activitiesPathwaysArrayJSON= new JsonResponse($activitiesPathwaysArray); 
        return $activitiesPathwaysArrayJSON; 
    }

    public function listeCategoryHRJSON(ManagerRegistry $doctrine){
        $categogiesHR = $doctrine->getRepository("App\Entity\CategoryOfHumanResource")->findAll();  
        $categoriesHRArray=array(); 
        foreach($categogiesHR as $categoryHR){
            $categoriesHRArray[]=array(
                'id' =>(str_replace(" ", "3aZt3r", $categoryHR->getId())),
                'category'=>(str_replace(" ", "3aZt3r", $categoryHR->getCategory()))
            ); 
        }   
        //Conversion des données ressources en json
        $categoriesHRArrayJSON= new JsonResponse($categoriesHRArray); 
        return $categoriesHRArrayJSON; 
    }
    
    public function listeCategoriesMRJSON(ManagerRegistry $doctrine){
        $categogiesMR = $doctrine->getRepository("App\Entity\CategoryOfMaterialResource")->findAll();  
        $categoriesMRArray=array(); 
        foreach($categogiesMR as $categoryMR){
            $categoriesMRArray[]=array(
                'id' =>(str_replace(" ", "3aZt3r", $categoryMR->getId())),
                'category'=>(str_replace(" ", "3aZt3r", $categoryMR->getCategory()))
            ); 
        }   
        //Conversion des données ressources en json
        $categoriesMRArrayJSON= new JsonResponse($categoriesMRArray); 
        return $categoriesMRArrayJSON; 
    }

    public function listeActivityHumanResourceJSON(ManagerRegistry $doctrine){
        $activitiesHR = $doctrine->getRepository("App\Entity\ActivityHumanResource")->findAll();  
        $activitiesHRArray=array(); 
        foreach($activitiesHR as $activityHR){
            $activitiesHRArray[]=array(
                'id' =>(str_replace(" ", "3aZt3r", $activityHR->getId())),
                'categoryHumanResource' =>($activityHR->getCategoryhumanresource()),
            ); 
        }   
        //Conversion des données ressources en json
        $activitiesHRArrayJSON= new JsonResponse($activitiesHRArray); 
        return $activitiesHRArrayJSON; 
    }

    public function listeActivityMaterialResourceJSON(ManagerRegistry $doctrine){
        $activitiesMR = $doctrine->getRepository("App\Entity\ActivityMaterialResource")->findAll();  
        $activitiesMRArray=array(); 
        foreach($activitiesMR as $activityMR){
            $activitiesMRArray[]=array(
                'id' =>(str_replace(" ", "3aZt3r", $activityMR->getId())),
                'categoryMaterialResource' =>($activityMR->getCategorymaterialresource()),
            ); 
        }   
        //Conversion des données ressources en json
        $activitiesMRArrayJSON= new JsonResponse($activitiesMRArray); 
        return $activitiesMRArrayJSON; 
    }

    public function listeScheduledActivitiesJSON(ManagerRegistry $doctrine){
        $scheduledActivities = $doctrine->getRepository("App\Entity\ScheduledActivity")->findAll();  
        $scheduledActivitiesArray=array(); 
        foreach($scheduledActivities as $scheduledActivity){
            $patientId=$scheduledActivity->getPatient()->getId();
            $patientId="patient_".$patientId;
            $patientName=$scheduledActivity->getPatient()->getLastname();
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
                'title'=>($scheduledActivity->getActivity()->getActivityname()),
                'resourceId'=>$patientId,
                'patient'=>$patientName,
                
            ); 
        }   
        //Conversion des données ressources en json
        $scheduledActivitiesArrayJSON= new JsonResponse($scheduledActivitiesArray); 
        return $scheduledActivitiesArrayJSON; 
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

    public function listeCategoryOfHumanResourceJSON(ManagerRegistry $doctrine){
        $categoriesHR = $doctrine->getRepository("App\Entity\CategoryOfHumanResource")->findAll();  
        $categoryHRArray=array(); 
        foreach($categoriesHR as $categoryHR){
            $categoryHRArray[]=array(
                'id' =>(str_replace(" ", "3aZt3r", $categoryHR->getId())),
                'categoryHumanResource' =>($categoryHR->getCategoryhumanresource()),
                'humanResource'=>($categoryHR->getHumanresource())
            ); 
        }   
        //Conversion des données ressources en json
        $categoriesHRArrayJSON= new JsonResponse($categoryHRArray); 
        return $categoriesHRArrayJSON; 
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

    public function listeWorkingHoursJSON(ManagerRegistry $doctrine){
        $workingHours = $doctrine->getRepository("App\Entity\WorkingHours")->findAll();  
        $workingHoursArray=array(); 
        foreach($workingHours as $workingHour){
            $workingHoursArray[]=array(
                'id' =>(str_replace(" ", "3aZt3r", $workingHour->getId())),
                'startdatetime'=>($workingHour->getStartdatetime()),
                'enddatetime'=>($workingHour->getEnddatetime()),
                'humanResource'=>($workingHour->getHumanresource()),
                
            ); 
        }   
        //Conversion des données ressources en json
        $workingHoursArrayJSON= new JsonResponse($workingHoursArray); 
        return $workingHoursArrayJSON; 
    }

    public function listeUnavailabilitiesJSON(ManagerRegistry $doctrine){
        $unavailabilities = $doctrine->getRepository("App\Entity\Unavailabilities")->findAll();  
        $unavailabilitiesArray=array(); 
        foreach($unavailabilities as $unavailability){
            $unavailabilitiesArray[]=array(
                'id' =>(str_replace(" ", "3aZt3r", $unavailability->getId())),
                'startdatetime'=>($unavailability->getStartdatetime()),
                'enddatetime'=>($unavailability->getEnddatetime())
                
            ); 
        }   
        //Conversion des données ressources en json
        $unavailabilitiesArrayJSON= new JsonResponse($unavailabilitiesArray); 
        return $unavailabilitiesArrayJSON; 
    }

    public function listeUnavailabilitiesMaterialResourceJSON(ManagerRegistry $doctrine){
        $UnavailabilitiesMaterialResources = $doctrine->getRepository("App\Entity\UnavailabilitiesMaterialResource")->findAll();  
        $UnavailabilitiesMaterialResourceArray=array(); 
        foreach($UnavailabilitiesMaterialResources as $UnavailabilitiesMaterialResource){
            $UnavailabilitiesMaterialResourceArray[]=array(
                'id' =>(str_replace(" ", "3aZt3r", $UnavailabilitiesMaterialResource->getId())),
                'unavailability'=>($UnavailabilitiesMaterialResource->getUnavailability()),
                'materialResource' =>($UnavailabilitiesMaterialResource->getMaterialresource()),
                
            ); 
        }   
        //Conversion des données ressources en json
        $UnavailabilitiesMaterialResourceArrayJSON= new JsonResponse($UnavailabilitiesMaterialResourceArray); 
        return $UnavailabilitiesMaterialResourceArrayJSON; 
    }

    public function listeUnavailabilitiesHumanResourceJSON(ManagerRegistry $doctrine){
        $IHRs = $doctrine->getRepository("App\Entity\UnavailabilitiesHumanResource")->findAll();  
        $IHRArray=array(); 
        foreach($IHRs as $IHR){
            $IHRArray[]=array(
                'id' =>(str_replace(" ", "3aZt3r", $IHR->getId())),
                'unavailability'=>($IHR->getUnavailability()),
                'humanResource' =>($IHR->getHumanresource()),
                
            ); 
        }   
        //Conversion des données ressources en json
        $IHRArrayJSON= new JsonResponse($IHRArray); 
        return $IHRArrayJSON; 
    }
}


