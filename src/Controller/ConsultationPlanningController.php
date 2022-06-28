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
        $listePPJSON=$this->listePPJSON($doctrine); //Récupération des données pathway-patient de la base de données
        $listeAPJSON=$this->listeAPJSON($doctrine); //Récupération des données activité-patient de la base de données
        $listeACHRJSON=$this->listeACHRJSON($doctrine); //Récupération des données activité-chr de la base de données
        $listeACMRJSON=$this->listeACMRJSON($doctrine); //Récupération des données activité-cmr de la base de données
        $listeMRSAJSON=$this->listeMRSAJSON($doctrine); //Récupération des données mrsa de la base de données
        $listeCategoryHRJSON=$this->listeCategoryHRJSON($doctrine); //Récupération des données catégorie ressource humaine de la base de données
        $listeCHRJSON=$this->listeCHRJSON($doctrine); //Récupération des données catégorie ressource categoryHR-HR de la base de données
        $listeIMRJSON=$this->listeIMRJSON($doctrine); //Récupération des données catégorie ressource indisponibilités-MR de la base de données
        $listeIndisponibilitiesJSON=$this->listeIndisponibilitiesJSON($doctrine); //Récupération des données indisponibilités de la base de données
        $listeHRIJSON=$this->listeHRIJSON($doctrine); //Récupération des données indisponibilité-HR de la base de données
        $listeHRSAJSON=$this->listeHRSAJSON($doctrine); //Récupération des données HR-activité programmée de la base de données
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
            'listePPJSON'=>$listePPJSON,
            'listeAPJSON'=>$listeAPJSON,
            'listeACHRJSON'=>$listeACHRJSON,
            'listeACMRJSON'=>$listeACMRJSON,
            'listeMRSAJSON'=>$listeMRSAJSON,
            'listeCategoryMRJSON'=>$listeCategoryMRJSON,
            'listeCategoryHRJSON'=>$listeCategoryHRJSON,
            'listeCHRJSON'=>$listeCHRJSON,
            'listeIMRJSON'=>$listeIMRJSON,
            'listeIndisponibilitiesJSON'=>$listeIndisponibilitiesJSON,
            'listeHRIJSON'=>$listeHRIJSON,
            'listeHRSAJSON'=>$listeHRSAJSON,
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
                'title'=>(str_replace(" ", "3aZt3r", $pathway->getPathwayname())),
                'type' =>(str_replace(" ", "3aZt3r", $pathway->getPathwaytype())),
            ); 
        }   
        //Conversion des données ressources en json
        $pathwayArrayJSON= new JsonResponse($pathwayArray); 
        return $pathwayArrayJSON; 
    }
    public function listePPJSON(ManagerRegistry $doctrine){
        $pathwaysPatients = $doctrine->getRepository("App\Entity\PP")->findAll();  
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
        $categogiesHR = $doctrine->getRepository("App\Entity\CategoryHumanResource")->findAll();  
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
        $categogiesMR = $doctrine->getRepository("App\Entity\CategoryMaterialResource")->findAll();  
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

    public function listeACHRJSON(ManagerRegistry $doctrine){
        $activitiesHR = $doctrine->getRepository("App\Entity\ACHR")->findAll();  
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

    public function listeACMRJSON(ManagerRegistry $doctrine){
        $activitiesMR = $doctrine->getRepository("App\Entity\ACMR")->findAll();  
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
        $activities=$doctrine->getRepository("App\Entity\Activity")->findAll();
        $activityArray=array();
        $scheduledActivitiesArray=array(); 
        foreach($scheduledActivities as $scheduledActivity){
            $patientId=$scheduledActivity->getPatient()->getId();
            $patientId="patient_".$patientId;
            $patientName=$scheduledActivity->getPatient()->getLastname();
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
                'available'=>($materialResource->isAvailable()),
                'categoryMaterialResource'=>($materialResource->getCategorymaterialresource())
                
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

    public function listeCHRJSON(ManagerRegistry $doctrine){
        $categoriesHR = $doctrine->getRepository("App\Entity\CHR")->findAll();  
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

    public function listeMRSAJSON(ManagerRegistry $doctrine){
        $MRSAs = $doctrine->getRepository("App\Entity\MRSA")->findAll();  
        $MRSAArray=array(); 
        foreach($MRSAs as $MRSA){
            $MRSAArray[]=array(
                'id' =>(str_replace(" ", "3aZt3r", $MRSA->getId())),
                'scheduledActivity'=>($MRSA->getScheduledactivity()),
                'materialResource' =>($MRSA->getMaterialresource()),
                
            ); 
        }   
        //Conversion des données ressources en json
        $MRSAArrayJSON= new JsonResponse($MRSAArray); 
        return $MRSAArrayJSON; 
    }
    public function listeHRSAJSON(ManagerRegistry $doctrine){
        $HRSAs = $doctrine->getRepository("App\Entity\HRSA")->findAll();  
        $HRSAArray=array(); 
        foreach($HRSAs as $HRSA){
            $HRSAArray[]=array(
                'id' =>(str_replace(" ", "3aZt3r", $HRSA->getId())),
                'scheduledActivity'=>($HRSA->getScheduledactivity()),
                'humanResource' =>($HRSA->getHumanresource()),
                
            ); 
        }   
        //Conversion des données ressources en json
        $HRSAArrayJSON= new JsonResponse($HRSAArray); 
        return $HRSAArrayJSON; 
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

    public function listeIndisponibilitiesJSON(ManagerRegistry $doctrine){
        $indisponibilities = $doctrine->getRepository("App\Entity\Indisponibilities")->findAll();  
        $indisponibilitiesArray=array(); 
        foreach($indisponibilities as $indisponibility){
            $indisponibilitiesArray[]=array(
                'id' =>(str_replace(" ", "3aZt3r", $indisponibility->getId())),
                'startdatetime'=>($indisponibility->getStartdatetime()),
                'enddatetime'=>($indisponibility->getEnddatetime())
                
            ); 
        }   
        //Conversion des données ressources en json
        $indisponibilitiesArrayJSON= new JsonResponse($indisponibilitiesArray); 
        return $indisponibilitiesArrayJSON; 
    }

    public function listeIMRJSON(ManagerRegistry $doctrine){
        $IMRs = $doctrine->getRepository("App\Entity\IMR")->findAll();  
        $IMRArray=array(); 
        foreach($IMRs as $IMR){
            $IMRArray[]=array(
                'id' =>(str_replace(" ", "3aZt3r", $IMR->getId())),
                'indisponibility'=>($IMR->getIndisponibility()),
                'materialResource' =>($IMR->getMaterialresource()),
                
            ); 
        }   
        //Conversion des données ressources en json
        $IMRArrayJSON= new JsonResponse($IMRArray); 
        return $IMRArrayJSON; 
    }

    public function listeHRIJSON(ManagerRegistry $doctrine){
        $IHRs = $doctrine->getRepository("App\Entity\HRI")->findAll();  
        $IHRArray=array(); 
        foreach($IHRs as $IHR){
            $IHRArray[]=array(
                'id' =>(str_replace(" ", "3aZt3r", $IHR->getId())),
                'indisponibility'=>($IHR->getIndisponibility()),
                'humanResource' =>($IHR->getHumanresource()),
                
            ); 
        }   
        //Conversion des données ressources en json
        $IHRArrayJSON= new JsonResponse($IHRArray); 
        return $IHRArrayJSON; 
    }
}


