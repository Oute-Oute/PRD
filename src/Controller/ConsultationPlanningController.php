<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\displayedActivityRepository;
use App\Repository\ScheduledActivityRepository;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\Length;

class ConsultationPlanningController extends AbstractController
{
    /*
     * @file ConsultationPlanningController.php
     * @brief This file contains the controller for planning's consultation.
     * @author Thomas Blumstein
     * @version 1.0
     * @date 2022/06
     */

    /*
     * @var $displayedActivities
     * @brief This variable contains the list of scheduled activities used in several function of the Controller.
     * */
    public $displayedActivities;
    public $date;

    /*
     * @brief This function is the getter of the Controller.
     * @details It creates the data used by html an js files by collecting them from the database.
     * @param ManagerRegistry $doctrine
     * @return JSON File containing the data used by the html and js files.
     */
    public function consultationPlanningGet(ManagerRegistry $doctrine, ScheduledActivityRepository $SAR): Response
    {
        global $date;
        $date = date(('Y-m-d'));
        if (isset($_GET["date"])) {
            $date = $_GET["date"];
            $date = str_replace('T12:00:00', '', $date);
        }
        //Récupération des données ressources de la base de données
        $getAppointmentJSON = $this->getAppointmentJSON($doctrine); //Récupération des données pathway-patient de la base de données
        $getDisplayedActivitiesJSON = $this->getDisplayedActivitiesJSON($doctrine, $SAR); //Récupération des données activités programmées de la base de données
        $getMaterialResourceScheduledJSON = $this->getMaterialResourceScheduledJSON($doctrine); //Récupération des données mrsa de la base de données
        $getHumanResourceScheduledJSON = $this->getHumanResourceScheduledJSON($doctrine); //Récupération des données HR-activité programmée de la base de données
        $settingsRepository = $doctrine->getRepository("App\Entity\Settings")->findAll();
        //envoi sous forme de JSON
        return $this->render(
            'planning/consultation-planning.html.twig',
            [
                'currentdate' => $date,
                'getDisplayedActivitiesJSON' => $getDisplayedActivitiesJSON,
                'getAppointmentJSON' => $getAppointmentJSON,
                'getMaterialResourceScheduledJSON' => $getMaterialResourceScheduledJSON,
                'getHumanResourceScheduledJSON' => $getHumanResourceScheduledJSON,
                'settingsRepository' => $settingsRepository,
                ]
        );
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
    /*  plusieurs ressources peuvent être associées à une activité programmée
        on récupère les ressources associées à une activité programmée
        on les met dans un tableau pour les affecter à l'activité programmée */
    foreach ($MaterialResourceScheduleds as $MaterialResourceScheduled) {
        $id = $MaterialResourceScheduled->getMaterialresource()->getId();
        $id = "materialresource_" . $id;
        $MaterialResourceScheduledArray[] = array(
            $id,
            $MaterialResourceScheduled->getMaterialresource()->getMaterialresourcename(),
        );
    }
    foreach ($humanResourceScheduleds as $humanResourceScheduled) {
        $id = $humanResourceScheduled->getHumanresource()->getId();
        $id = "humanresource_" . $id;
        $HumanResourceScheduledArray[] = array(
            $id,
            $humanResourceScheduled->getHumanresource()->getHumanresourcename(),
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
     * @brief This function get the unavailabitity of the material resources.
     * @param ManagerRegistry $doctrine
     * @return an array containing the unavailability of the material resources.
     */
    public function getMaterialResourcesUnavailables(ManagerRegistry $doctrine)
    {
        //recuperation du patient depuis la base de données
    $materialResourcesUnavailable = $doctrine->getRepository("App\Entity\UnavailabilityMaterialResource")->findAll();
        $materialResourcesUnavailableArray = array();
        foreach ($materialResourcesUnavailable as $materialResourceUnavailable) {
            $resource= $materialResourceUnavailable->getMaterialresource()->getId();
            $resource = "materialresource_" . $resource;
            $materialResourcesUnavailableArray[] = array(
                'description' =>'Ressource Indisponible',
                'resourceId' => ($resource),
                'start' => ($materialResourceUnavailable->getUnavailability()->getStartdatetime()->format('Y-m-d H:i:s')),
                'end' => ($materialResourceUnavailable->getUnavailability()->getEnddatetime()->format('Y-m-d H:i:s')),
                'display'=>'background',
                'color'=>'#ff0000',
            );
        }
        return $materialResourcesUnavailableArray;
    }
    
        /*
     * @brief This function get the unavailabitity of the human resources.
     * @param ManagerRegistry $doctrine
     * @return an array containing the unavailability of the human resources.
     */
    public function getHumanResourceUnavailables(ManagerRegistry $doctrine)
    {
        //recuperation du patient depuis la base de données
    $humanResourcesUnavailable = $doctrine->getRepository("App\Entity\UnavailabilityHumanResource")->findAll();
        $humanResourcesUnavailableArray = array();
        foreach ($humanResourcesUnavailable as $humanResourceUnavailable) {
            $resource= $humanResourceUnavailable->getHumanresource()->getId();
            $resource = "humanresource_" . $resource;
            $humanResourcesUnavailableArray[] = array(
                'description' =>'Employé Indisponible',
                'resourceId' => ($resource),
                'start' => ($humanResourceUnavailable->getUnavailability()->getStartdatetime()->format('Y-m-d H:i:s')),
                'end' => ($humanResourceUnavailable->getUnavailability()->getEnddatetime()->format('Y-m-d H:i:s')),
                'display'=>'background',
                'color'=>'#ff0000',
            );
        }
        return $humanResourcesUnavailableArray;
    }

    /*
     * @brief This function get all the unavailabitity of the material and human resources.
     * @param ManagerRegistry $doctrine
     * @return an array containing the unavailability
     */
    public function getUnavailabity(ManagerRegistry $doctrine){
        $humanUnavailabity = $this->getHumanResourceUnavailables($doctrine);
        $materialUnavailabity = $this->getMaterialResourcesUnavailables($doctrine);
        $unavailabityArray = array();
        foreach ($humanUnavailabity as $humanUnavailabity) {
            array_push($unavailabityArray, $humanUnavailabity);
        }
        foreach ($materialUnavailabity as $materialUnavailabity) {
            array_push($unavailabityArray, $materialUnavailabity);
        }
        return $unavailabityArray;
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
            if(count($resourceArray[0])>2){            
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
    }
    $unavailabityArray = $this->getUnavailabity($doctrine);
    $displayedActivitiesArray = array_merge($displayedActivitiesArray, $unavailabityArray);

        //Conversion des données ressources en json
        $displayedActivitiesArrayJSON = new JsonResponse($displayedActivitiesArray);
        return $displayedActivitiesArrayJSON;
    }

    /*
     * @brief This function is the getter of the Appointments from the database.
     * @param ManagerRegistry $doctrine
     * @return array of the Appointments's data
     */
    public function getAppointmentJSON(ManagerRegistry $doctrine)
    {
        //recuperation de la date dont on veut le planning
        global $date;
        $dateTime = new \DateTime($date);
        //recuperartion des appointments du jour depuis la base de données
        $appointments = $doctrine->getRepository("App\Entity\Appointment")
            ->findBy(array('dayappointment' => $dateTime));
        //Creation d'un tableau pour stocker les données des appointments

        $appointmentArray = array();
        foreach ($appointments as $appointment) {
            $businessHours = array(
                'startTime' => $appointment->getEarliestappointmenttime()->format('H:i'),
                'endTime' => $appointment->getLatestappointmenttime()->format('H:i'),
            );
            $appointmentArray[] = array(
                'id' => (str_replace(" ", "3aZt3r", $appointment->getId())),
                'day' => ($appointment->getDayappointment()->format('Y-m-d')),
                'earliestappointmenttime' => ($appointment->getEarliestappointmenttime()),
                'latestappointmenttime' => ($appointment->getLatestappointmenttime()),
                'scheduled' => $appointment->isScheduled(),
                'patient' => $this->getPatient($doctrine, $appointment->getPatient()->getId(), $businessHours),
                'pathway' => $this->getPathway($doctrine, $appointment->getPathway()->getId()),

            );
        }
        //Conversion des données ressources en json
        $appointmentArrayJSON = new JsonResponse($appointmentArray);
        return $appointmentArrayJSON;
    }

    /*
     * @brief This function is the getter of the Pathways from the database.
     * @param ManagerRegistry $doctrine
     * @return array of the pathways's data
     */
    public function getPathway(ManagerRegistry $doctrine, $id)
    {
        //recuperation du pathway depuis la base de données
        $pathway = $doctrine->getRepository("App\Entity\Pathway")->findOneBy(array('id' => $id));
        $pathwayArray = array();
        $idpath = $pathway->getId();
        $idpath = "pathway_" . $idpath; //formatage pour fullcalendar
        //ajout des données du pathway dans un tableau
        $pathwayArray[] = array(
            'id' => $idpath,
            'title' => (str_replace(" ", "3aZt3r", $pathway->getPathwayname()))
        );
        return $pathwayArray;
    }

    /*
     * @brief This function is the getter of the Material Resources to display from the database.
     * @param ManagerRegistry $doctrine
     * @return array of the resource's data
     */
    public function getMaterialResourceScheduledJSON(ManagerRegistry $doctrine)
    {
        //tilisation de la variable globale $displayedActivities pour recuperer les activites programmées du jour
        global $displayedActivities;
        $MaterialResourceScheduledArray = array();

        if ($displayedActivities != null) { //si il y a des données dans la base de données
            for ($i = 0; $i < count($displayedActivities); $i++) {
                //recuperation des ressources de l'activité programmée
                $MaterialResourceScheduleds = $doctrine->getRepository("App\Entity\MaterialResourceScheduled")
                    ->findBy(array("scheduledactivity" => $displayedActivities[$i]));
                //ajout des données des ressources de l'activité programmée dans un tableau pour etre converti en JSON
                foreach ($MaterialResourceScheduleds as $MaterialResourceScheduled) {
                    $materialCategories = $this->getMaterialCategory($doctrine, $MaterialResourceScheduled->getMaterialresource());
                    $materialCategoryArray = array();
                    foreach ($materialCategories as $materialCategory) {
                        $materialCategoryArray[] = array(
                            'name' => ($materialCategory),
                        );
                    }
                    $id = $MaterialResourceScheduled->getMaterialresource()->getId();
                    $id = "materialresource_" . $id;
                    $MaterialResourceScheduledArray[] = array(
                        'id' => $id,
                        'title' => ($MaterialResourceScheduled->getMaterialresource()->getMaterialresourcename()),
                        'categories' => ($materialCategoryArray)


                    );
                    unset($materialCategoryArray);
                }
            }
        }
        unset($materialCategoryArray);
        //Conversion des données ressources en json
        $MaterialResourceScheduledArrayJSON = new JsonResponse($MaterialResourceScheduledArray);
        return $MaterialResourceScheduledArrayJSON;
    }

    /*
     * @brief This function is the getter of the Material Categories to display from the database.
     * @param ManagerRegistry $doctrine
     * @parma MaterialResource $resource
     * @return array of the resource's data
     */
    public function getMaterialCategory(ManagerRegistry $doctrine, $resource)
    {
        //recuperation du pathway depuis la base de données
        //dd($resource,$doctrine->getRepository("App\Entity\MaterialResourceScheduled")->findAll());
        $materialCategories = $doctrine->getRepository("App\Entity\CategoryOfMaterialResource")->findBy(array('materialresource' => $resource));
        $materialCategoryArray = array();
        foreach ($materialCategories as $materialCategory) {
            $materialCategoryArray[] = array(
                'name' => ($materialCategory->getMaterialresourcecategory()->getCategoryname()),
            );
        }
        //Conversion des données ressources en json
        return $materialCategoryArray;
    }

    /*
     * @brief This function is the getter of the Human Resources to display from the database.
     * @param ManagerRegistry $doctrine
     * @return array of the resource's data
     */
    public function getHumanResourceScheduledJSON(ManagerRegistry $doctrine)
    {
        //tilisation de la variable globale $displayedActivities pour recuperer les activites programmées du jour
        global $displayedActivities;
        $HumanResourceScheduledArray = array();
        if ($displayedActivities != null) { //si il y a des données dans la base de données
            for ($i = 0; $i < count($displayedActivities); $i++) {
                //recuperation des ressources de l'activité programmée
                $HumanResourceScheduleds = $doctrine->getRepository("App\Entity\HumanResourceScheduled")
                    ->findBy(array("scheduledactivity" => $displayedActivities[$i]));
                //ajout des données des ressources de l'activité programmée dans un tableau pour etre converti en JSON
                foreach ($HumanResourceScheduleds as $HumanResourceScheduled) {
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
                        'workingHours' => ($this->getWorkingHours($doctrine, $HumanResourceScheduled->getHumanresource())),

                    );
                    unset($HumanResourceArray);
                }
            }
        }
        //Conversion des données ressources en json
        $HumanResourceScheduledArrayJSON = new JsonResponse($HumanResourceScheduledArray);
        return $HumanResourceScheduledArrayJSON;
    }


    /*
     * @brief This function is the getter of the working hours to display from the database.
     * @param ManagerRegistry $doctrine
     * @return array of the resource's data
     */
    public function getWorkingHours(ManagerRegistry $doctrine, $resource)
    {
        //recuperation du pathway depuis la base de données
        $setOfWorkingHours = $doctrine->getRepository("App\Entity\WorkingHours")->findBy(array('humanresource' => $resource));
        $workingHoursArray = array();
        foreach ($setOfWorkingHours as $workingHours) {
            $dayWorkingHours = $workingHours->getDayweek();
            //ajout des données du pathway dans un tableau
            $workingHoursArray[] = array(
                'day' => $dayWorkingHours,
                'startTime' => ($workingHours->getStarttime()->format('H:i')),
                'endTime' => ($workingHours->getEndtime()->format('H:i')),

            );
        }
        return $workingHoursArray;
    }

    /*
     * @brief This function is the getter of the Human Categories to display from the database.
     * @param ManagerRegistry $doctrine
     * @parma HumanResource $resource
     * @return array of the resource's data
     */
    public function getHumanCategory(ManagerRegistry $doctrine, $resource)
    {
        //recuperation du pathway depuis la base de données
        $humanCategories = $doctrine->getRepository("App\Entity\CategoryOfHumanResource")->findBy(array("humanresource" => $resource));
        $humanCategoryArray = array();
        foreach ($humanCategories as $humanCategory) {
            $humanCategoryArray[] = array(
                'name' => ($humanCategory->getHumanresourcecategory()->getCategoryname()),
            );
        }
        //Conversion des données ressources en json
        return $humanCategoryArray;
    }

    /*
     * @brief This function is the getter of the Patients from the database.
     * @param ManagerRegistry $doctrine
     * @param $id the id of the patient to get
     * @return array of the patient's data
     */
    public function getPatient(ManagerRegistry $doctrine, $id, $businessHours)
    {
        //recuperation du patient depuis la base de données
        $patient = $doctrine->getRepository("App\Entity\Patient")->findOneBy(array('id' => $id));
        $patientArray = array();
        $lastname = $patient->getLastname();
        $firstname = $patient->getFirstname();
        $title = $lastname . " " . $firstname; //utilisé pour l'affichage fullcalendar
        $id = $patient->getId();
        $id = "patient_" . $id;

        $patientArray[] = array(
            'id' => $id,
            'lastname' => (str_replace(" ", "3aZt3r", $lastname)),
            'firstname' => (str_replace(" ", "3aZt3r", $firstname)),
            'title' => $title,
            'businessHours' => $businessHours,
        );

        //Conversion des données ressources en json 
        return $patientArray;
    }

    }
    