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

    /*
     * @brief This function is the getter of the Controller.
     * @details It creates the data used by html an js files by collecting them from the database.
     * @param ManagerRegistry $doctrine
     * @return JSON File containing the data used by the html and js files.
     */
    public function consultationPlanningGet(ManagerRegistry $doctrine): Response
    {
        $dateGet = date(('Y-m-d'));
        if (isset($_GET["date"])) {
            $dateGet = $_GET["date"];
            $dateGet = str_replace('T12:00:00', '', $dateGet);
        }
        //Récupération des données ressources de la base de données
        $listeScheduledActivitiesJSON = $this->listeScheduledActivitiesJSON($doctrine); //Récupération des données activités programmées de la base de données
        $listeAppointmentJSON = $this->listeAppointmentJSON($doctrine); //Récupération des données pathway-patient de la base de données
        $listeMaterialResourceScheduledJSON = $this->listeMaterialResourceScheduledJSON($doctrine); //Récupération des données mrsa de la base de données
        $listeHumanResourceScheduledJSON = $this->listeHumanResourceScheduledJSON($doctrine); //Récupération des données HR-activité programmée de la base de données

        //envoi sous forme de JSON
        return $this->render(
            'planning/consultation-planning.html.twig',
            [
                'datetoday' => $dateGet,
                'listeScheduledActivitiesJSON' => $listeScheduledActivitiesJSON,
                'listeAppointmentJSON' => $listeAppointmentJSON,
                'listeMaterialResourceScheduledJSON' => $listeMaterialResourceScheduledJSON,
                'listeHumanResourceScheduledJSON' => $listeHumanResourceScheduledJSON,
            ]
        );
    }

    /*
     * @brief This function is the getter of the Scheduled Activities from the database.
     * @param ManagerRegistry $doctrine
     * @return array all the data of the activites to display
     */
    public function listeScheduledActivitiesJSON(ManagerRegistry $doctrine)
    {
        $scheduledActivities = $doctrine->getRepository("App\Entity\ScheduledActivity")->findAll();
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
                );
            }
            foreach ($humanResourceScheduleds as $humanResourceScheduled) {
                $id = $humanResourceScheduled->getHumanresource()->getId();
                $id = "humanresource_" . $id;
                $HumanResourceScheduledArray[] = array(
                    $id,
                );
            }
            //factorisation de toutes les ressources en un unique tableau
            $resourceArray[] = array(
                $patientId,
                $pathwayId,
            );
            for ($i = 0; $i < count($MaterialResourceScheduledArray); $i++) {
                array_push($resourceArray[0], $MaterialResourceScheduledArray[$i]);
            }
            for ($i = 0; $i < count($HumanResourceScheduledArray); $i++) {
                array_push($resourceArray[0], $HumanResourceScheduledArray[$i]);
            }

            //récupération des données de l'activité programmée
            //jour de l'activité
            $day = $scheduledActivity->getDayscheduled();
            $day = $day->format('Y-m-d');
            //heure de début de l'activité programmée
            $start = $scheduledActivity->getStarttime();
            $start = $start->format('H:i:s');
            $start = $day . "T" . $start; //formatage sous forme de DateTime pour fullcalendar
            //heure de fin de l'activité programmée
            $end = $scheduledActivity->getEndtime();
            $end = $end->format('H:i:s');
            $end = $day . "T" . $end; //formatage sous forme de DateTime pour fullcalendar
            /*  ajout de toutes les activités du jour dans un tableau global 
                pour les autres fonctions qui en ont besoin*/
            global $scheduledActivities;
            $scheduledActivities[] = $scheduledActivity->getActivity();
            //ajout des données de l'activité programmée dans un tableau pour etre converti en JSON
            $scheduledActivitiesArray[] = array(
                'id' => (str_replace(" ", "3aZt3r", $scheduledActivity->getId())),
                'start' => $start,
                'end' => $end,
                'title' => ($scheduledActivity->getActivity()->getActivityname()),
                'appointment' => ($scheduledActivity->getAppointment()->getId()),
                'resourceIds' => ($resourceArray[0]),


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
        $dateGet = date('Y-m-d');
        if (isset($_GET["date"])) {
            $dateGet = $_GET["date"];
            $dateGet = str_replace('T12:00:00', '', $dateGet); //formattage post full calendar pour correspondre à la base de données

        }
        $date = new \DateTime($dateGet);

        //recuperartion des appointments du jour depuis la base de données
        $Appointments = $doctrine->getRepository("App\Entity\Appointment")
            ->findBy(array('dayappointment' => $date));
        //Creation d'un tableau pour stocker les données des appointments
        $AppointmentArray = array();
        foreach ($Appointments as $Appointment) {
            $AppointmentArray[] = array(
                'id' => (str_replace(" ", "3aZt3r", $Appointment->getId())),
                'day' => ($Appointment->getDayappointment()->format('Y-m-d')),
                'earliestappointmenttime' => ($Appointment->getEarliestappointmenttime()),
                'latestappointmenttime' => ($Appointment->getLatestappointmenttime()),
                'scheduled' => $Appointment->isScheduled(),
                'patient' => $this->getPatient($doctrine, $Appointment->getPatient()->getId()),
                'pathway' => $this->getPathway($doctrine, $Appointment->getPathway()->getId()),
            );
        }
        //Conversion des données ressources en json
        $AppointmentArrayJSON = new JsonResponse($AppointmentArray);
        return $AppointmentArrayJSON;
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
                    $id = $MaterialResourceScheduled->getMaterialresource()->getId();
                    $id = "materialresource_" . $id;
                    $MaterialResourceScheduledArray[] = array(
                        'id' => $id,
                        'title' => ($MaterialResourceScheduled->getMaterialresource()->getMaterialresourcename()),

                    );
                }
            }
        }
        //Conversion des données ressources en json
        $MaterialResourceScheduledArrayJSON = new JsonResponse($MaterialResourceScheduledArray);
        return $MaterialResourceScheduledArrayJSON;
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
                    $id = $HumanResourceScheduled->getHumanresource()->getId();
                    $id = "humanresource_" . $id;
                    $HumanResourceScheduledArray[] = array(
                        'id' => $id,
                        'title' => ($HumanResourceScheduled->getHumanresource()->getHumanresourcename()),

                    );
                }
            }
        }
        //Conversion des données ressources en json
        $HumanResourceScheduledArrayJSON = new JsonResponse($HumanResourceScheduledArray);
        return $HumanResourceScheduledArrayJSON;
    }

    /*
     * @brief This function is the getter of the Patients from the database.
     * @param ManagerRegistry $doctrine
     * @param $id the id of the patient to get
     * @return array of the patient's data
     */
    public function getPatient(ManagerRegistry $doctrine, $id)
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
            'title' => $title
        );

        //Conversion des données ressources en json 
        return $patientArray;
    }
}
