<?php

namespace App\Controller;
use App\Repository\ScheduledActivityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


class StatisticsController extends AbstractController
{

    public $date;
    /*
     * @brief Allows to get stats
     * 
     */
    public function index(ManagerRegistry $doctrine, ScheduledActivityRepository $SAR): Response
    {

        $english_months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
        $french_months = array('janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');
        
        global $date;
        $date = date(('Y-m-d'));
        if (isset($_GET["date"])) {
            $date = $_GET["date"];
            $date = str_replace('T12:00:00', '', $date);
        }
        $header="";
        if (isset($_GET["headerResources"])) {
            $header = $_GET["headerResources"];
        }

        $dateFormatted=date_create($date);
        $dateFormatted->format('Y-F-d');
        $dateStr=str_replace($english_months, $french_months,$dateFormatted->format('d F Y'));
        $this->getDisplayedActivitiesJSON($doctrine,$SAR);
        $humanResourcesSheduledJSON=$this->getHumanResourceScheduledJSON($doctrine);
        $materialResourcesSheduledJSON=$this->getMaterialResourceScheduledJSON($doctrine);
        return $this->render('statistics/index.html.twig', [
            'controller_name' => 'StatisticsController',
            'currentdate' => $date,
            'dateFormatted' => $dateStr,
            'getHumanResourceScheduledJSON' => $humanResourcesSheduledJSON,
            'getMaterialResourceScheduledJSON' => $materialResourcesSheduledJSON,
        ]);
    }


    public function getResourcesScheduled(ManagerRegistry $doctrine,$displayedActivity){
        //recuperation des id des ressources pour fullcalendar
        $patientId = $displayedActivity->getAppointment()->getPatient()->getId();
        $patientId = "patient_" . $patientId; //nommage particulier pour séparer les types de ressources
        $pathwayId = $displayedActivity->getAppointment()->getPathway()->getId();
        $pathwayId = "pathway_" . $pathwayId;
        //récuperations des id rh et rm dans une table différente
       $MaterialResourceScheduleds = $doctrine->getRepository("App\Entity\MaterialResourceScheduled")
       ->findBy(array("scheduledactivity" => $displayedActivity));
   $humanResourceScheduleds = $doctrine->getRepository("App\Entity\HumanResourceScheduled")
       ->findBy(array("scheduledactivity" => $displayedActivity));
   $HumanResourceScheduledArray[] = array();
   $MaterialResourceScheduledArray[] = array();

   /*  Sinon : 
       plusieurs ressources peuvent être associées à une activité programmée
       on récupère les ressources associées à une activité programmée
       on les met dans un tableau pour les affecter à l'activité programmée */
       if($MaterialResourceScheduleds != null){
           foreach ($MaterialResourceScheduleds as $MaterialResourceScheduled) {
               $id = $MaterialResourceScheduled->getMaterialresource()->getId();
               $id = "materialresource_" . $id;
               $MaterialResourceScheduledArray[] = array(
                   $id,
                   $MaterialResourceScheduled->getMaterialresource()->getMaterialresourcename(),
               );
           }
       }
       else if($MaterialResourceScheduleds == null){
           $MaterialResourceScheduledArray[]=array(
               "materialresource_noResource",
               "Pas de ressource allouée"
           );
       }
       if($humanResourceScheduleds != null){
           foreach ($humanResourceScheduleds as $humanResourceScheduled) {
               $id = $humanResourceScheduled->getHumanresource()->getId();
               $id = "humanresource_" . $id;
               $HumanResourceScheduledArray[] = array(
                   $id,
                   $humanResourceScheduled->getHumanresource()->getHumanresourcename(),
               );
           }
       }
       else{
       $HumanResourceScheduledArray[]=array(
           "humanresource_noResource",
           "Pas de ressource allouée"
       );
   }
   //factorisation de toutes les ressources en un unique tableau
   $resourceArray[] = array(
       $patientId,
       $pathwayId,
   );
   for ($i = 1; $i < count($MaterialResourceScheduledArray); $i++) {

       array_push($resourceArray[0], $MaterialResourceScheduledArray[$i][0]);
   }
   for ($i = 1; $i < count($HumanResourceScheduledArray); $i++) {
       array_push($resourceArray[0], $HumanResourceScheduledArray[$i][0]);
   }
   //stockage également des ressources dans un autre champ du tableau pour récupérerer uniquement les ressources humaines  OU matérielles
   
   if(count($MaterialResourceScheduledArray) > 0){
       $resourceArray[1]=[];
       for ($i = 1; $i < count($MaterialResourceScheduledArray); $i++) {
           array_push($resourceArray[1], $MaterialResourceScheduledArray[$i][1]);
       }
   }
   else{
       $resourceArray[1] = "Aucune Ressource Matérielle";
   }
   if(count($HumanResourceScheduledArray) > 0){
       $resourceArray[2]=[];
       for ($i = 1; $i < count($HumanResourceScheduledArray); $i++) {
           array_push($resourceArray[2], $HumanResourceScheduledArray[$i][1]);
       }
   }
   else{
       $resourceArray[2] = "Aucune Ressource Humaine";
   }
   
   return $resourceArray;
   }


    /*
     * @brief This function is the getter of the Scheduled Activities from the database.
     * @param ManagerRegistry $doctrine
     * @return array all the data of the activites to display
     */
    public function getDisplayedActivitiesJSON(ManagerRegistry $doctrine, ScheduledActivityRepository $SAR)
    {
        global $date;
        global $displayedActivities;
        $displayedActivities = $SAR->findSchedulerActivitiesByDate($date);
        $displayedActivitiesArray = array();
        foreach ($displayedActivities as $displayedActivity) {
           
           //recuperation des ressources associées à une activité programmée
           $resourceArray=$this->getResourcesScheduled($doctrine,$displayedActivity);  
            //récupération des données de l'activité programmée
            //jour de l'activité
            $day = $displayedActivity->getAppointment()->getDayappointment();
            $day = $day->format('Y-m-d');
            //heure de début de l'activité programmée
            $start = $displayedActivity->getStarttime();
            $start = $start->format('H:i:s');
            $start = $day . "T" . $start; //formatage sous forme de DateTime pour fullcalendar
            //heure de fin de l'activité programmée
            $end = $displayedActivity->getEndtime();
            $end = $end->format('H:i:s');
            $end = $day . "T" . $end; //formatage sous forme de DateTime pour fullcalendar
            $patientLastName = $displayedActivity->getAppointment()->getPatient()->getLastname();
            $patientFirstName = $displayedActivity->getAppointment()->getPatient()->getFirstname();
            $patient = $patientLastName . " " . $patientFirstName;
            
            //ajout des données de l'activité programmée dans un tableau pour etre converti en JSON
            $displayedActivitiesArray[] = array(
                'id' => (str_replace(" ", "3aZt3r", $displayedActivity->getId())),
                'start' => $start,
                'end' => $end,
                'title' => ($displayedActivity->getActivity()->getActivityname()),
                'appointment' => ($displayedActivity->getAppointment()->getId()),
                'resourceIds' => ($resourceArray[0]),
                'description' => ($displayedActivity->getActivity()->getActivityname()),
                'extendedProps' => array(
                    'patient' => $patient,
                    'pathway' => ($displayedActivity->getAppointment()->getPathway()->getPathwayname()),
                    'materialResources' => ($resourceArray[1]),
                    'humanResources' => ($resourceArray[2]),
                ),
            );
            //reset des tableaux de stockage temporaire des données
            unset($resourceArray);
    }
    //$unavailabityArray = $this->getUnavailabity($doctrine);
    //$displayedActivitiesArray = array_merge($displayedActivitiesArray, $unavailabityArray);

        //Conversion des données ressources en json
        $displayedActivitiesArrayJSON = new JsonResponse($displayedActivitiesArray);
        return $displayedActivitiesArrayJSON;
    }


    public function getHumanResourceScheduledJSON(ManagerRegistry $doctrine)
    {
        //tilisation de la variable globale $displayedActivities pour recuperer les activites programmées du jour
        global $displayedActivities;
        $HumanResourceScheduledArray = array();
        $arrayId = array();
        if ($displayedActivities != null) { //si il y a des données dans la base de données
            for ($i = 0; $i < count($displayedActivities); $i++) {
                //recuperation des ressources de l'activité programmée
                $HumanResourceScheduleds = $doctrine->getRepository("App\Entity\HumanResourceScheduled")
                    ->findBy(array("scheduledactivity" => $displayedActivities[$i]));
                //ajout des données des ressources de l'activité programmée dans un tableau pour etre converti en JSON
                foreach ($HumanResourceScheduleds as $HumanResourceScheduled) {
                    if(!in_array($HumanResourceScheduled->getHumanresource()->getId(),$arrayId)){
                    
                        $categories=$doctrine->getRepository("App\Entity\CategoryOfHumanResource")->findBy(array('humanresource' => $HumanResourceScheduled->getHumanresource()));
                        $categoriesArray=array();
                        foreach ($categories as $category) {
                            $categoriesArray[]=array(
                                'id' => $category->getId(),
                                'name' => $category->getHumanResourceCategory()->getCategoryname()
                            );
                        }
                        $id = $HumanResourceScheduled->getHumanresource()->getId();
                        $id = "humanresource_" . $id;
                        $HumanResourceScheduledArray[] = array(
                            'id' => $id,
                            'title' => ($HumanResourceScheduled->getHumanresource()->getHumanresourcename()),
                            'categories' => ($categoriesArray),
                            //'workingHours' => ($this->getWorkingHours($doctrine, $HumanResourceScheduled->getHumanresource())),
                            'type' => 0
                        );
                        unset($HumanResourceArray);
                        $arrayId[]=$HumanResourceScheduled->getHumanresource()->getId();
                    }
                }
                
            }
        }
        $workingHoursEmpty=array();
        for($i=0;$i<7;$i++){
            $workingHoursEmpty[]=array(
                'day' => $i,
                'startTime' => "00:00",
                'endTime' => "23:59",

            );
        }
        $HumanResourceScheduledArray[] = array(
            'id' => 'humanresource_noResource',
            'title' => 'Aucune ressource',
            'categories' => array(
                array(
                    'id' => '0',
                    'name' => 'Aucune catégorie',
                ),
            ),
            'workingHours' => $workingHoursEmpty,
            'type' => 1
        );
        //Conversion des données ressources en json
        $HumanResourceScheduledArrayJSON = new JsonResponse($HumanResourceScheduledArray);
        return $HumanResourceScheduledArrayJSON;
    }

    public function getMaterialResourceScheduledJSON(ManagerRegistry $doctrine)
    {
        //tilisation de la variable globale $displayedActivities pour recuperer les activites programmées du jour
        global $displayedActivities;
        $MaterialResourceScheduledArray = array();
        $arrayId = array();
        if ($displayedActivities != null) { //si il y a des données dans la base de données
            for ($i = 0; $i < count($displayedActivities); $i++) {
                //recuperation des ressources de l'activité programmée
                $MaterialResourceScheduleds = $doctrine->getRepository("App\Entity\MaterialResourceScheduled")
                    ->findBy(array("scheduledactivity" => $displayedActivities[$i]));
                //ajout des données des ressources de l'activité programmée dans un tableau pour etre converti en JSON
                foreach ($MaterialResourceScheduleds as $MaterialResourceScheduled) {
                    if(!in_array($MaterialResourceScheduled->getMAterialresource()->getId(),$arrayId)){
                    $materialCategories=$doctrine->getRepository("App\Entity\CategoryOfMaterialResource")->findBy(array('materialresource' => $MaterialResourceScheduled->getMaterialresource()));
                    $materialCategoryArray = array();
                    foreach ($materialCategories as $materialCategory) {
                        $materialCategoryArray[] = array(
                            'id' => $materialCategory->getId(),
                            'name' => $materialCategory->getMaterialResourceCategory()->getCategoryname(),
                        );
                    }
                    $id = $MaterialResourceScheduled->getMaterialresource()->getId();
                    $id = "materialresource_" . $id;
                    $MaterialResourceScheduledArray[] = array(
                        'id' => $id,
                        'title' => ($MaterialResourceScheduled->getMaterialresource()->getMaterialresourcename()),
                        'categories' => ($materialCategoryArray),
                        'type' => 0


                    );
                    unset($materialCategoryArray);
                    $arrayId[]=$MaterialResourceScheduled->getMaterialresource()->getId();
                }
            }
            }
        }
        $MaterialResourceScheduledArray[] = array(
            'id' => 'humanresource_noResource',
            'title' => 'Aucune ressource',
            'categories' => array(
                array(
                    'id' => '0',
                    'name' => 'Aucune catégorie',
                ),
            ),
            'type' => 1
        );
        //var_dump($MaterialResourceScheduledArray);
        unset($materialCategoryArray);
        //Conversion des données ressources en json
        $MaterialResourceScheduledArrayJSON = new JsonResponse($MaterialResourceScheduledArray);
        return $MaterialResourceScheduledArrayJSON;
    }

}