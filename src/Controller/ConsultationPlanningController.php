<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ScheduledActivityRepository;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;
use Symfony\Component\HttpFoundation\JsonResponse;

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
     * @var $scheduledActivities
     * @brief This variable contains the list of scheduled activities used in several function of the Controller.
     * */
    public $scheduledActivities;
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
        $listeAppointmentJSON = $this->listeAppointmentJSON($doctrine); //Récupération des données pathway-patient de la base de données
        $listeScheduledActivitiesJSON = $this->listeScheduledActivitiesJSON($doctrine, $SAR); //Récupération des données activités programmées de la base de données
        $listeMaterialResourceScheduledJSON = $this->listeMaterialResourceScheduledJSON($doctrine); //Récupération des données mrsa de la base de données
        $listeHumanResourceScheduledJSON = $this->listeHumanResourceScheduledJSON($doctrine); //Récupération des données HR-activité programmée de la base de données
        $listeMaterialResourcesUnavailables= $this->listeMaterialResourcesUnavailables($doctrine); //Récupération des données mr indisponibles de la base de données
        $listeHumanResourcesUnavailables = $this->listeHumanResourceUnavailables($doctrine); //Récupération des données HR indisponibles de la base de données
        //envoi sous forme de JSON
        return $this->render(
            'planning/consultation-planning.html.twig',
            [
                'datetoday' => $date,
                'listeScheduledActivitiesJSON' => $listeScheduledActivitiesJSON,
                'listeAppointmentJSON' => $listeAppointmentJSON,
                'listeMaterialResourceScheduledJSON' => $listeMaterialResourceScheduledJSON,
                'listeHumanResourceScheduledJSON' => $listeHumanResourceScheduledJSON,
                'listeMaterialResourcesUnavailables' => $listeMaterialResourcesUnavailables,
                'listeHumanResourcesUnavailables' => $listeHumanResourcesUnavailables,
            ]
        );
    }

    /*
     * @brief This function is the getter of the Scheduled Activities from the database.
     * @param ManagerRegistry $doctrine
     * @return array all the data of the activites to display
     */
    public function listeScheduledActivitiesJSON(ManagerRegistry $doctrine, ScheduledActivityRepository $SAR)
    {
        global $date;
        global $scheduledActivities;
        $scheduledActivities = $SAR->findSchedulerActivitiesByDate($date);
        $scheduledActivitiesArray = array();
        foreach ($scheduledActivities as $scheduledActivity) {
            //recuperation des id des ressources pour fullcalendar
            $patientId = $scheduledActivity->getAppointment()->getPatient()->getId();
            $patientId = "patient_" . $patientId; //nommage particulier pour séparer les types de ressources
            $pathwayId = $scheduledActivity->getAppointment()->getPathway()->getId();
            $pathwayId = "pathway_" . $pathwayId;
            //récuperations des id rh et rm dans une table différente
            $MaterialResourceScheduleds = $doctrine->getRepository("App\Entity\MaterialResourceScheduled")
                ->findBy(array("scheduledactivity" => $scheduledActivity));
            $humanResourceScheduleds = $doctrine->getRepository("App\Entity\HumanResourceScheduled")
                ->findBy(array("scheduledactivity" => $scheduledActivity));
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

            //récupération des données de l'activité programmée
            //jour de l'activité
            $day = $scheduledActivity->getAppointment()->getDayappointment();
            $day = $day->format('Y-m-d');
            //heure de début de l'activité programmée
            $start = $scheduledActivity->getStarttime();
            $start = $start->format('H:i:s');
            $start = $day . "T" . $start; //formatage sous forme de DateTime pour fullcalendar
            //heure de fin de l'activité programmée
            $end = $scheduledActivity->getEndtime();
            $end = $end->format('H:i:s');
            $end = $day . "T" . $end; //formatage sous forme de DateTime pour fullcalendar
            $patientLastName = $scheduledActivity->getAppointment()->getPatient()->getLastname();
            $patientFirstName = $scheduledActivity->getAppointment()->getPatient()->getFirstname();
            $patient = $patientLastName . " " . $patientFirstName;
            //ajout des données de l'activité programmée dans un tableau pour etre converti en JSON
            $scheduledActivitiesArray[] = array(
                'id' => (str_replace(" ", "3aZt3r", $scheduledActivity->getId())),
                'start' => $start,
                'end' => $end,
                'title' => ($scheduledActivity->getActivity()->getActivityname()),
                'appointment' => ($scheduledActivity->getAppointment()->getId()),
                'resourceIds' => ($resourceArray[0]),
                'description' => ($scheduledActivity->getActivity()->getActivityname()),
                'extendedProps' => array(
                    'patient' => $patient,
                    'pathway' => ($scheduledActivity->getAppointment()->getPathway()->getPathwayname()),
                    'materialResources' => ($MaterialResourceScheduledArray),
                    'humanResources' => ($HumanResourceScheduledArray),
                ),


            );
            //reset des tableaux de stockage temporaire des données
            unset($MaterialResourceScheduledArray);
            unset($HumanResourceScheduledArray);
            unset($resourceArray);
        }

        //Conversion des données ressources en json
        $scheduledActivitiesArrayJSON = new JsonResponse($scheduledActivitiesArray);
        return $scheduledActivitiesArrayJSON;
    }

    /*
     * @brief This function is the getter of the Appointments from the database.
     * @param ManagerRegistry $doctrine
     * @return array of the Appointments's data
     */
    public function listeAppointmentJSON(ManagerRegistry $doctrine)
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
    public function listeMaterialResourceScheduledJSON(ManagerRegistry $doctrine)
    {
        //tilisation de la variable globale $scheduledActivities pour recuperer les activites programmées du jour
        global $scheduledActivities;
        $MaterialResourceScheduledArray = array();

        if ($scheduledActivities != null) { //si il y a des données dans la base de données
            for ($i = 0; $i < count($scheduledActivities); $i++) {
                //recuperation des ressources de l'activité programmée
                $MaterialResourceScheduleds = $doctrine->getRepository("App\Entity\MaterialResourceScheduled")
                    ->findBy(array("scheduledactivity" => $scheduledActivities[$i]));
                //ajout des données des ressources de l'activité programmée dans un tableau pour etre converti en JSON
                foreach ($MaterialResourceScheduleds as $MaterialResourceScheduled) {
                    $materialCategories = $this->getMaterialCategory($doctrine, $MaterialResourceScheduled->getMaterialresource());
                    $materialCategoryArray = array();
                    foreach ($materialCategories as $materialCategory) {
                        $materialCategoryArray[] = array(
                            'category' => ($materialCategory),
                        );
                    }
                    $id = $MaterialResourceScheduled->getMaterialresource()->getId();
                    $id = "materialresource_" . $id;
                    $MaterialResourceScheduledArray[] = array(
                        'id' => $id,
                        'title' => ($MaterialResourceScheduled->getMaterialresource()->getMaterialresourcename()),
                        'extendedProps' => array(
                            'Categories' => ($materialCategoryArray),
                        ),

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
    public function listeHumanResourceScheduledJSON(ManagerRegistry $doctrine)
    {
        //tilisation de la variable globale $scheduledActivities pour recuperer les activites programmées du jour
        global $scheduledActivities;
        $HumanResourceScheduledArray = array();
        if ($scheduledActivities != null) { //si il y a des données dans la base de données
            for ($i = 0; $i < count($scheduledActivities); $i++) {
                //recuperation des ressources de l'activité programmée
                $HumanResourceScheduleds = $doctrine->getRepository("App\Entity\HumanResourceScheduled")
                    ->findBy(array("scheduledactivity" => $scheduledActivities[$i]));
                //ajout des données des ressources de l'activité programmée dans un tableau pour etre converti en JSON
                foreach ($HumanResourceScheduleds as $HumanResourceScheduled) {
                    $humanCategories = $this->getHumanCategory($doctrine, $HumanResourceScheduled->getHumanresource());
                    $HumanResourceArray = array();
                    foreach ($humanCategories as $humanCategory) {
                        $HumanResourceArray[] = array(
                            'category' => ($humanCategory),
                        );
                    }
                    $id = $HumanResourceScheduled->getHumanresource()->getId();
                    $id = "humanresource_" . $id;
                    $HumanResourceScheduledArray[] = array(
                        'id' => $id,
                        'title' => ($HumanResourceScheduled->getHumanresource()->getHumanresourcename()),
                        'categories' => ($HumanResourceArray),
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

    public function listeMaterialResourcesUnavailables(ManagerRegistry $doctrine)
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
        //Conversion des données ressources en json 
        $materialResourcesUnavailableArrayJSON = new JsonResponse($materialResourcesUnavailableArray);
        return $materialResourcesUnavailableArrayJSON;
    }
    
    public function listeHumanResourceUnavailables(ManagerRegistry $doctrine)
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
        //Conversion des données ressources en json 
        $humanResourcesUnavailableArrayJSON = new JsonResponse($humanResourcesUnavailableArray);
        return $humanResourcesUnavailableArrayJSON;
    }
}
