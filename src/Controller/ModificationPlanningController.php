<?php

namespace App\Controller;

use App\Entity\MaterialResourceScheduled;
use App\Entity\HumanResourceScheduled;
use App\Entity\ScheduledActivity;
use App\Entity\Modification;
use App\Entity\Unavailability;
use App\Entity\UnavailabilityHumanResource;
use App\Entity\UnavailabilityMaterialResource;
use App\Repository\UnavailabilityMaterialResourceRepository;
use App\Repository\MaterialResourceScheduledRepository;
use App\Repository\HumanResourceScheduledRepository;
use App\Repository\ModificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ScheduledActivityRepository;
use App\Repository\UnavailabilityHumanResourceRepository;
use App\Repository\UserRepository;
use DateInterval;
use DateTime;
use DateTimeZone;
use Symfony\Component\Validator\Constraints\Length;

/**
 * Controller de la page modification du planning
 */
class ModificationPlanningController extends AbstractController
{
    /**
     * Fonction pour l'affichage de la page modification planning par la méthode GET
     */
    public function modificationPlanningGet(Request $request, ManagerRegistry $doctrine, ScheduledActivityRepository $SAR, EntityManagerInterface $entityManager): Response
    {
        $dateModified = array();
        $idUser = 1;
        //Récupération de la date à laquelle on modifie le planning
        if (isset($_GET['date'])) {
            $dateModified = $_GET["date"];
        }
        //Récupération de l'id de l'utilisateur
        if (isset($_GET['id'])) {
            $idUser = $_GET["id"];
        }

        //Récupération des données via la base de donnée avec Doctrine
        $listHumanResources = $doctrine->getRepository("App\Entity\HumanResource")->findBy(['available' => true]);
        $listMaterialResources = $doctrine->getRepository("App\Entity\MaterialResource")->findBy(['available' => true]);
        $listePatients = $doctrine->getRepository("App\Entity\Patient")->findAll();
        $listePathWayPatients = $doctrine->getRepository("App\Entity\Appointment")->findAll();
        $listeAppointment = $this->getAppointment($doctrine, $dateModified);
        $listescheduledActivity = $this->getScehduledActivity($doctrine, $SAR, $dateModified);
        $listesuccessionJSON = $this->getSuccessorJSON($doctrine);
        $listeActivitiesJSON = $this->getActivityJSON($doctrine);
        $listAppointmentJSON = $this->getAppointmentJSON($doctrine, $dateModified);
        $listMaterialResourceJSON = $this->getMaterialResourcesJSON($doctrine);
        $listHumanResourceJSON = $this->getHumanResourcesJSON($doctrine);
        $listActivityHumanResourcesJSON = $this->getActivityHumanResourcesJSON($doctrine);
        $listActivityMaterialResourcesJSON = $this->getActivityMaterialResourcesJSON($doctrine);
        $listeMaterialResourcesUnavailables = $this->getMaterialResourcesUnavailables($doctrine); //Récupération des données mr indisponibles de la base de données
        $listeHumanResourcesUnavailables = $this->getHumanResourceUnavailables($doctrine); //Récupération des données HR indisponibles de la base de données

        //On redirige sur la page html modification planning et on envoie toutes les données dont on a besoin
        return $this->render('planning/modification-planning.html.twig', [
            'listepatients' => $listePatients,
            'listePathWaypatients' => $listePathWayPatients,
            'listMaterialResourceJSON' => $listMaterialResourceJSON,
            'listHumanResourceJSON' => $listHumanResourceJSON,
            'listHumanResources' => $listHumanResources,
            'listMaterialResources' => $listMaterialResources,
            'datetoday' => $dateModified,
            'listScheduledActivitiesJSON' => $listescheduledActivity,
            'listeAppointments' => $listeAppointment,
            'listeSuccessorsJSON' => $listesuccessionJSON,
            'listeActivitiesJSON' => $listeActivitiesJSON,
            'listAppointmentsJSON' => $listAppointmentJSON,
            'listeActivityHumanResourcesJSON' => $listActivityHumanResourcesJSON,
            'listeActivityMaterialResourcesJSON' => $listActivityMaterialResourcesJSON,
            'listeMaterialResourcesUnavailables' => $listeMaterialResourcesUnavailables,
            'listeHumanResourcesUnavailables' => $listeHumanResourcesUnavailables,

        ]);
    }

    //Fonction vérifiant si une modification a lieu ou non pour le jour souhaité, si c'est le cas l'utilisateur ne peut pas accéder à la page. 
    public function alertModif($dateModified, $idUser)
    {
        $modificationRepository = new ModificationRepository($this->getDoctrine());
        $modifications = $modificationRepository->findAll();

        $dateModified = str_replace('T12:00:00', '', $dateModified);
        $dateToday = new \DateTime('now', new DateTimeZone('Europe/Paris'));
        $dateToday = new \DateTime($dateToday->format('Y-m-d H:i:s'));

        $modifArray = array();
        $i = 0;
        foreach ($modifications as $modification) {
            $modifArray[] = array(
                'dateTimeModified' => ($modification->getDatetimemodification()->format('Y-m-d H:i:s')),
                'dateModified' => ($modification->getDatemodified()->format('Y-m-d')),
                'userId' => ($modification->getUser()->getId())
            );
            $datetimeModified = new \DateTime(date('Y-m-d H:i:s', strtotime($modifArray[$i]['dateTimeModified'])));
            $interval = $datetimeModified->diff($dateToday);

            $intervalHour = $interval->format('%h');
            $intervalMinutes = $interval->format('%i');

            if ($modifArray[$i]['dateModified'] == $dateModified) {
                // ATTENTION, le timer doit être supérieur à celui du popup
                if ($intervalHour * 60 + $intervalMinutes < 10) {
                    if ($idUser == $modifArray[$i]['userId']) { // Empeche d'envoyer une erreur si un user quitte et revient
                        $modificationRepository->remove($modification, true);
                    } else {
                        echo "<script> 
                            alert('Une modification pour le " . $dateModified . " est déjà en cours, vous allez être redirigé')
                            window.location.assign('/ConsultationPlanning');
                        </script>";
                        return false;
                    }
                } else {
                    // Supprimer la modif dans BDD car trop vieille
                    $modificationRepository->remove($modification, true);
                }
            }
            $i++;
        }
        return true;
    }

    

    //Renvoie la liste de tous les successors en format JSON
    public function getSuccessorJSON(ManagerRegistry $doctrine)
    {
        $successors = $doctrine->getRepository('App\Entity\Successor')->findAll();
        $successorsArray = array();
        foreach ($successors as $succesor) {
            $successorsArray[] = array(
                'id' => $succesor->getId(),
                'idactivitya' => $succesor->getActivitya()->getId(),
                'idactivityb' => $succesor->getActivityb()->getId(),
                'delaymin' => $succesor->getDelaymin(),
                'delaymax' => $succesor->getDelaymax(),
            );
        }

        $successorsArrayJSON = new JsonResponse($successorsArray);
        return $successorsArrayJSON;
    }

    //Renvoie a liste de toutes les Activity en format JSON
    public function getActivityJSON(ManagerRegistry $doctrine)
    {
        $activities = $doctrine->getRepository('App\Entity\Activity')->findAll();
        $activitiesArray = array();
        foreach ($activities as $activity) {
            $activitiesArray[] = array(
                'id' => $activity->getId(),
                'name' => (str_replace(" ", "3aZt3r", $activity->getActivityname())),
                'duration' => $activity->getDuration(),
                'idPathway' => $activity->getPathway()->getId()
            );
        }
        $activitiesArrayJSON = new JsonResponse($activitiesArray);
        return $activitiesArrayJSON;
    }
 
    //Renvoie la liste de tous les rendez-vous pour un jour donné
    public function getAppointment(ManagerRegistry $doctrine, $date)
    {

        $date = new \DateTime(date('Y-m-d', strtotime(substr($date, 0, 10))));
        $appointments = $doctrine->getRepository("App\Entity\Appointment")->findBy(['dayappointment' => $date]);
        return $appointments;
    }

    //Renvoie la liste de tous les rendez-vous pour un jour donné en JSON
    public function getAppointmentJSON(ManagerRegistry $doctrine, $date)
    {
        $date = new \DateTime(date('Y-m-d', strtotime(substr($date, 0, 10))));
        $appointments = $doctrine->getRepository("App\Entity\Appointment")->findBy(['dayappointment' => $date]);
        $appointmentsArray = array();
        foreach ($appointments as $appointment) {
            $earliestappointmenttime = "";
            if ($appointment->getEarliestappointmenttime() != null) {
                $earliestappointmenttime = str_replace(" ", "T", $appointment->getEarliestappointmenttime()->format('Y-m-d H:i:s'));
            }
            $latestappointmenttime = "";
            if ($appointment->getLatestappointmenttime() != null) {
                $latestappointmenttime = str_replace(" ", "T", $appointment->getLatestappointmenttime()->format('Y-m-d H:i:s'));
            }
            $appointmentsArray[] = array(
                'id' => $appointment->getId(),
                'earliestappointmenttime' => $earliestappointmenttime,
                'latestappointmenttime' => $latestappointmenttime,
                'dayappointment' => $appointment->getDayappointment()->format('Y:m:d'),
                'idPatient' => $this->getPatient($doctrine, $appointment->getPatient()->getId()),
                'idPathway' => $this->getPathway($doctrine, $appointment->getPathway()->getId()),
                'scheduled' => $appointment->isScheduled(),
            );
        }
        $appointmentsArrayJSON = new JsonResponse($appointmentsArray);
        return $appointmentsArrayJSON;
    }


    public function getPatient(ManagerRegistry $doctrine, $id)
    {
        //recuperation d'un patient depuis la base de données
        $patient = $doctrine->getRepository("App\Entity\Patient")->findOneBy(array('id' => $id));
        $patientArray = array();
        $lastname = $patient->getLastname();
        $firstname = $patient->getFirstname();
        $title = $lastname . " " . $firstname; //utilisé pour l'affichage fullcalendar
        $id = $patient->getId();
        $id = "patient_" . $id;
        $patientArray[] = array(
            'id' => (str_replace(" ", "3aZt3r", $id)),
            'lastname' => (str_replace(" ", "3aZt3r", $lastname)),
            'firstname' => (str_replace(" ", "3aZt3r", $firstname)),
            'title' => (str_replace(" ", "3aZt3r", $title))
        );

        //Conversion des données ressources en json 
        return $patientArray;
    }

    //Récupération d'un parcour dans la base dee donnée
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

    //Retourne la liste des HumanResources en format JSON. 
    public function getHumanResourcesJSON(ManagerRegistry $doctrine)
    {
        $humanResources = $doctrine->getRepository("App\Entity\HumanResource")->findAll();
        $humanResourcesArray = array();

        if ($humanResources != null) {
            foreach ($humanResources as $humanResource) {
                $humanResourcesArray[] = array(
                    'id' => ("human-" . str_replace(" ", "3aZt3r", $humanResource->getId())),
                    'title' => (str_replace(" ", "3aZt3r", $humanResource->getHumanresourcename())),
                    'workingHours' => ($this->getWorkingHours($doctrine, $humanResource)),
                );
            }
        }
        //Conversion des données ressources en json
        $humanResourcesArrayJson = new JsonResponse($humanResourcesArray);
        return $humanResourcesArrayJson;
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

    //Retourne la liste des MaterialResources en nformat JSON
    public function getMaterialResourcesJSON(ManagerRegistry $doctrine)
    {
        $materialResources = $doctrine->getRepository("App\Entity\MaterialResource")->findAll();
        $materialResourcesArray = array();

        if ($materialResources != null) {
            foreach ($materialResources as $materialResource) {
                $materialResourcesArray[] = array(
                    'id' => ("material-" . str_replace(" ", "3aZt3r", $materialResource->getId())),
                    'title' => (str_replace(" ", "3aZt3r", $materialResource->getMaterialresourcename())),
                );
            }
        }

        //Conversion des données ressources en json
        $materialResourcesArrayJson = new JsonResponse($materialResourcesArray);
        return $materialResourcesArrayJson;
    }

    //Retourne la liste des Scheduled Activity en format JSON pour un jour donné
    public function getScehduledActivity(ManagerRegistry $doctrine, ScheduledActivityRepository $SAR, $date)
    {
        $TodayDate = substr($date, 0, 10);


        $scheduledActivities = $SAR->findSchedulerActivitiesByDate($TodayDate);
        $scheduledActivitiesArray = array();
        foreach ($scheduledActivities as $scheduledActivity) {
            //Obtention du nombre de resources matérielles à renseigner pour cette activité
            $activitiesMaterialResourcesByActivityId = $doctrine->getRepository("App\Entity\ActivityMaterialResource")->findBy(['activity' => $scheduledActivity->getActivity()->getId()]);
            $quantityMaterialResources = 0;
            foreach ($activitiesMaterialResourcesByActivityId as $activityMaterialResourcesByActivityId) {
                $quantityMaterialResources = $quantityMaterialResources + $activityMaterialResourcesByActivityId->getQuantity();
            }

            //Obtention du nombre de resources Humaines à renseigner pour cette activité
            $activitiesHumanResourcesByActivityId = $doctrine->getRepository("App\Entity\ActivityHumanResource")->findBy(['activity' => $scheduledActivity->getActivity()->getId()]);
            $quantityHumanResources = 0;
            foreach ($activitiesHumanResourcesByActivityId as $activityHumanResourcesByActivityId) {
                $quantityHumanResources = $quantityHumanResources + $activityHumanResourcesByActivityId->getQuantity();
            }
            //Tableau contenant toutes les ressources déja plannifiées pour une activité
            $scheduledActivitiesResourcesArray = array();
            //Recherche des ressources Humaines déja plannifiées 
            $scheduledActivitiesHumanResources = $doctrine->getRepository("App\Entity\HumanResourceScheduled")->findBy((['scheduledactivity' => $scheduledActivity->getId()]));
            $scheduledActivitesHumanResourcesArray = array();
            foreach ($scheduledActivitiesHumanResources as $scheduledActivitiesHumanResource) {
                $scheduledActivitesHumanResourcesArray[] = array(
                    'id' => $scheduledActivitiesHumanResource->getHumanresource()->getId(),
                    'title' => $scheduledActivitiesHumanResource->getHumanresource()->getHumanresourcename(),
                );
                $quantityHumanResources = $quantityHumanResources - 1;
                array_push($scheduledActivitiesResourcesArray, "human-" . $scheduledActivitiesHumanResource->getHumanresource()->getId());
            }
            //Recherche des ressources matérielles déjà plannifiées. 
            $scheduledActivitiesMaterialResources = $doctrine->getRepository("App\Entity\MaterialResourceScheduled")->findBy((['scheduledactivity' => $scheduledActivity->getId()]));
            $scheduledActivitiesMaterialResourceArray = array();
            foreach ($scheduledActivitiesMaterialResources as $scheduledActivitiesMaterialResource) {
                $scheduledActivitiesMaterialResourceArray[] = array(
                    'id' => $scheduledActivitiesMaterialResource->getMaterialresource()->getId(),
                    'title' => $scheduledActivitiesMaterialResource->getMaterialresource()->getMaterialresourcename(),
                );
                $quantityMaterialResources = $quantityMaterialResources - 1;
                array_push($scheduledActivitiesResourcesArray, "material-" . $scheduledActivitiesMaterialResource->getMaterialresource()->getId());
            }



            //Put the number of undefined HumanResources in scheduledActivitiesResourcesArray
            for ($i = 0; $i < $quantityHumanResources; $i++) {
                array_push($scheduledActivitiesResourcesArray, "h-default");
            }

            //Put the number of undefined MaterialResources in scheduledActivitiesResourcesArray
            for ($i = 0; $i < $quantityMaterialResources; $i++) {
                array_push($scheduledActivitiesResourcesArray, "m-default");
            }
            //formatage des informations à rentrer dans le tableau
            $start = $scheduledActivity->getStarttime();
            $day = $scheduledActivity->getAppointment()->getDayappointment();
            $day = $day->format('Y-m-d');
            $start = $start->format('H:i:s');
            $start = $day . "T" . $start;
            $end = $scheduledActivity->getEndtime();
            $end = $end->format('H:i:s');
            $end = $day . "T" . $end;
            $idAppointment = $scheduledActivity->getAppointment()->getId();
            $idActivity = $scheduledActivity->getActivity()->getId();
            $scheduledActivitiesArray[] = array(
                'id' => (str_replace(" ", "3aZt3r", $scheduledActivity->getId())),
                'start' => $start,
                'end' => $end,
                'title' => ($scheduledActivity->getActivity()->getActivityname()),
                'resourceIds' => $scheduledActivitiesResourcesArray,
                'appointment' => $idAppointment,
                'activity' => $idActivity,
                'patient' => $scheduledActivity->getAppointment()->getPatient()->getLastname() . " " . $scheduledActivity->getAppointment()->getPatient()->getFirstname(),
                'pathway' => ($scheduledActivity->getAppointment()->getPathway()->getPathwayname()),
                'materialResources' => ($scheduledActivitiesMaterialResourceArray),
                'humanResources' => ($scheduledActivitesHumanResourcesArray),
                'type' => "activity",
                'description'=>''
            );
        }
        $scheduledActivitiesArrayJson = new JsonResponse($scheduledActivitiesArray);
        return $scheduledActivitiesArrayJson;
    }

    //Retourne les ActivityHuman Ressource en format JSON
    public function getActivityHumanResourcesJSON(ManagerRegistry $doctrine)
    {
        $activitiesHumanResources = $doctrine->getRepository('App\Entity\ActivityHumanResource')->findAll();
        $activitiesHumanResourcesArray = array();
        foreach ($activitiesHumanResources as $activityHumanResources) {
            $activitiesHumanResourcesArray[] = array(
                'id' => $activityHumanResources->getId(),
                'activityId' => $activityHumanResources->getActivity()->getId(),
                'humanResourceCategoryId' => $activityHumanResources->getHumanresourcecategory()->getId(),
                'quantity' => $activityHumanResources->getQuantity(),
            );
        }
        return new JsonResponse($activitiesHumanResourcesArray);
    }

    //Retouorne les ActivityMaterialResources en format JSON
    public function getActivityMaterialResourcesJSON(ManagerRegistry $doctrine)
    {
        $activitiesMaterialResources = $doctrine->getRepository("App\Entity\ActivityMaterialResource")->findAll();
        $activitiesMaterialResourcesArray = array();
        foreach ($activitiesMaterialResources as $activityMaterialResources) {
            $activitiesMaterialResourcesArray[] = array(
                'id' => $activityMaterialResources->getId(),
                'activityId' => $activityMaterialResources->getActivity()->getId(),
                'materialResourceCategoryId' => $activityMaterialResources->getMaterialresourcecategory()->getId(),
                'quantity' => $activityMaterialResources->getQuantity(),
            );
        }
        return new JsonResponse($activitiesMaterialResourcesArray);
    }

    //Appelée lors de l'appui du bouton valider
    //Sauvegarde en nBDD les modifications de l'utilisateur
    public function modificationPlanningValidation(Request $request, UnavailabilityMaterialResourceRepository $unavailabilityMaterialResourceRepository, UnavailabilityHumanResourceRepository $unavailabilityHumanResourceRepository, ScheduledActivityRepository $scheduledActivityRepository, HumanResourceScheduledRepository $humanResourceScheduledRepository, MaterialResourceScheduledRepository $materialResourceScheduledRepository, ManagerRegistry $doctrine, EntityManagerInterface $entityManager)
    {
        //récupération des events et des ressources depuis le twig
        $listEvent = json_decode($request->request->get("events"));
        $listResource = json_decode($request->request->get("list-resource"));

        //création d'une nouvelle liste fusionnant les deux listes précédentes : events et ressources
        $listScheduledEvent = array();
        for ($index = 0; $index < sizeof($listEvent); $index++) {
            $newScheduledEvent = array();
            array_push($newScheduledEvent, $listEvent[$index]);
            array_push($newScheduledEvent, $listResource[$index]);
            array_push($listScheduledEvent, $newScheduledEvent);
        }

        //récupération de toutes les activités programmées prévu le jour qui vient d'être plannifié
        $date = $request->request->get("validation-date");
        $listScheduledActivity = $scheduledActivityRepository->findSchedulerActivitiesByDate(substr($date, 0, 10));
        //on parcours la liste des évènement plannifié qui viennent d'être modifiés
        foreach ($listScheduledEvent as $event) {
            //on instancie un booléen pour savoir si l'évènement est déjà en bdd ou non
            $scheduledActivityExist = false;

            //on parcours la liste des évènement programmés déjà stocké en bdd
            foreach ($listScheduledActivity as $scheduledActivity) {
                if ($event[0]->extendedProps->type == "activity") {
                    //si l'évènement modifié correspond à un évènement déjà enregistré en bdd, alors on le met à jour
                    if ($scheduledActivity->getId() == $event[0]->id) {
                        //on précise au booléen que l'évènement modifié existe déjà et qu'on a pas à le créer
                        $scheduledActivityExist = true;

                        //on met à jour ses attributs dans sa table
                        $scheduledActivity->setStarttime(\DateTime::createFromFormat('H:i:s', substr($event[0]->start, 11, 16)));
                        $scheduledActivity->setEndtime(\DateTime::createFromFormat('H:i:s', substr($event[0]->end, 11, 16)));

                        $scheduledActivityRepository->add($scheduledActivity, true);

                        //on récupère la liste des ressources associées à l'évènement
                        $listeMaterialResourceScheduled = $materialResourceScheduledRepository->findBy((['scheduledactivity' => $scheduledActivity->getId()]));
                        $listeHumanResourceScheduled = $humanResourceScheduledRepository->findBy((['scheduledactivity' => $scheduledActivity->getId()]));
                        //on parcours la liste des ressources modifiés
                        foreach ($event[1] as $resource) {
                            //on regarde si la ressource modifié est de type Humaine
                            if (substr($resource, 0, 5) == "human") {
                                //on récupère l'objet ressource humaine correspondant
                                $idResource = explode("-", $resource);
                                $humanResource = $doctrine->getRepository("App\Entity\HumanResource")->findOneBy(["id" => $idResource[1]]);

                                //on instancie un booléen pour savoir si la relation est déjà en bdd ou non
                                $humanResourceExist = false;

                                //on parcours la liste des relation déjà présente en bdd
                                foreach ($listeHumanResourceScheduled as $humanResourceScheduled) {
                                    //on regarde si la relation est déjà présente en bdd
                                    if ($humanResourceScheduled->getHumanresource() == $humanResource) {
                                        //on précise au booléen que la relation existe déjà et qu'on a pas à le créer
                                        $humanResourceExist = true;
                                    }
                                }

                                //si la relation n'est pas déjà existante, on la créer
                                if (!$humanResourceExist) {
                                    //ajout de la nouvelle relation en bdd
                                    $newHumanResourceScheduled = new HumanResourceScheduled();
                                    $newHumanResourceScheduled->setHumanresource($humanResource);
                                    $newHumanResourceScheduled->setScheduledactivity($scheduledActivity);

                                    $humanResourceScheduledRepository->add($newHumanResourceScheduled, true);

                                    /*//ajout de unavailability
                                    $newUnavailability = new Unavailability();
                                    $strDate = substr($date, 0, 10);
                                    $strStart = $strDate . " " . $scheduledActivity->getStarttime()->format('H:i:s');
                                    $strEnd = $strDate . " " . $scheduledActivity->getEndtime()->format('H:i:s');
                                    $newUnavailability->setStartdatetime(\DateTime::createFromFormat('Y-m-d H:i:s', $strStart));
                                    $newUnavailability->setEnddatetime(\DateTime::createFromFormat('Y-m-d H:i:s', $strEnd));

                                    $entityManager->persist($newUnavailability);
                                    $entityManager->flush();

                                    //ajout de la relation entre les deux tables
                                    $newUnavailabilityHumanResource = new UnavailabilityHumanResource();
                                    $newUnavailabilityHumanResource->setHumanresource($humanResource);
                                    $newUnavailabilityHumanResource->setUnavailability($newUnavailability);

                                    $entityManager->persist($newUnavailabilityHumanResource);
                                    $entityManager->flush();*/
                                }
                            }

                            //sinon, la relation est donc de type matérielle
                            else if (substr($resource, 0, 8) == "material") {
                                //on récupère l'objet ressource matériel correspondant
                                $idResource = explode("-", $resource);
                                $materialResource = $doctrine->getRepository("App\Entity\MaterialResource")->findOneBy(["id" => $idResource[1]]);

                                //on instancie un booléen pour savoir si la relation est déjà en bdd ou non
                                $materialResourceExist = false;

                                //on parcours la liste des relation déjà présente en bdd
                                foreach ($listeMaterialResourceScheduled as $materialResourceScheduled) {
                                    //on regarde si la relation est déjà présente en bdd
                                    if ($materialResourceScheduled->getMaterialresource() == $materialResource) {
                                        //on précise au booléen que la relation existe déjà et qu'on a pas à le créer
                                        $materialResourceExist = true;
                                    }
                                }

                                //si la relation n'est pas déjà existante, on la créer
                                if (!$materialResourceExist) {
                                    //ajout de la nouvelle relation en bdd
                                    $newMaterialResourceScheduled = new MaterialResourceScheduled();
                                    $newMaterialResourceScheduled->setMaterialresource($materialResource);
                                    $newMaterialResourceScheduled->setScheduledactivity($scheduledActivity);

                                    $materialResourceScheduledRepository->add($newMaterialResourceScheduled, true);

                                    /*//ajout de unavailability
                                    $newUnavailability = new Unavailability();
                                    $strDate = substr($date, 0, 10);
                                    $strStart = $strDate . " " . $scheduledActivity->getStarttime()->format('H:i:s');
                                    $strEnd = $strDate . " " . $scheduledActivity->getEndtime()->format('H:i:s');
                                    $newUnavailability->setStartdatetime(\DateTime::createFromFormat('Y-m-d H:i:s', $strStart));
                                    $newUnavailability->setEnddatetime(\DateTime::createFromFormat('Y-m-d H:i:s', $strEnd));

                                    $entityManager->persist($newUnavailability);
                                    $entityManager->flush();

                                    //ajout de la relation entre les deux tables
                                    $newUnavailabilityMaterialResource = new UnavailabilityMaterialResource();
                                    $newUnavailabilityMaterialResource->setMaterialresource($materialResource);
                                    $newUnavailabilityMaterialResource->setUnavailability($newUnavailability);

                                    $entityManager->persist($newUnavailabilityMaterialResource);
                                    $entityManager->flush();*/
                                }
                            }
                        }

                        //on parcours la liste des relation entre ressource humaine et évènement programmé de la bdd
                        foreach ($listeHumanResourceScheduled as $humanResourceScheduled) {
                            //on instancie un booléen pour savoir si la ressource est toujours associé à l'évènement modifié
                            $humanResourceExist = false;

                            //on parcours la liste des ressources modifiés
                            foreach ($event[1] as $resource) {
                                //on ne compare que les ressource de type humaine
                                if (substr($resource, 0, 5) == "human") {
                                    $idResource = explode("-", $resource);
                                    //on récupère en bdd la ressource humaine correspondante
                                    $humanResource = $doctrine->getRepository("App\Entity\HumanResource")->findOneBy(["id" => $idResource[1]]);

                                    //on regarde si la ressource est toujours associé à l'évènement modifié
                                    if ($humanResourceScheduled->getHumanresource() == $humanResource) {
                                        //on précise au booléen que la relation est toujours présente
                                        $humanResourceExist = true;
                                    }
                                }
                            }

                            //si la relation n'est plus présente dans la liste des relations modifiés
                            if (!$humanResourceExist) {
                                //on supprime la relation entre l'évènement programmé et la ressource humaine
                                $humanResourceScheduledRepository->remove($humanResourceScheduled, true);

                                /*$strDate = substr($date, 0, 10);
                                $strStart = $strDate . " " . $scheduledActivity->getStarttime()->format('H:i:s');

                                $listUnavailabilityHumanResource = $unavailabilityHumanResourceRepository->findUnavailabilityHumanResourceByDate($strStart, $humanResourceScheduled->getHumanresource()->getId());

                                foreach ($listUnavailabilityHumanResource as $unavailabilityHumanResource) {
                                    $unavailability = $unavailabilityHumanResource->getUnavailability();
                                    $entityManager->remove($unavailabilityHumanResource);
                                    $entityManager->flush($unavailabilityHumanResource);
                                    $entityManager->remove($unavailability);
                                    $entityManager->flush($unavailability);
                                }*/
                            }
                            /*else 
                            {
                                $strDate = substr($date, 0, 10);
                                $strStart = $strDate . " " . $scheduledActivity->getStarttime()->format('H:i:s');

                                $listUnavailabilityHumanResource = $unavailabilityHumanResourceRepository->findUnavailabilityHumanResourceByDate($strStart, $humanResourceScheduled->getHumanresource()->getId());

                                foreach ($listUnavailabilityHumanResource as $unavailabilityHumanResource) {
                                    $unavailability = $unavailabilityHumanResource->getUnavailability();
                                    $strEnd = $strDate . " " . $scheduledActivity->getEndtime()->format('H:i:s');
                                    $newUnavailability->setStartdatetime(\DateTime::createFromFormat('Y-m-d H:i:s', $strStart));
                                    $newUnavailability->setEnddatetime(\DateTime::createFromFormat('Y-m-d H:i:s', $strEnd));
                                    $entityManager->persist($unavailability);
                                    $entityManager->flush();
                                }
                            }*/
                        }

                        //on parcours la liste des relation entre ressource matérielle et évènement programmé de la bdd
                        foreach ($listeMaterialResourceScheduled as $materialResourceScheduled) {
                            //on instancie un booléen pour savoir si la ressource est toujours associé à l'évènement modifié
                            $materialResourceExist = false;

                            //on parcours la liste des ressources associé à l'évènement
                            foreach ($event[1] as $resource) {
                                //on ne compare que les ressource de type matérielle
                                if (substr($resource, 0, 8) == "material") {
                                    $idResource = explode("-", $resource);
                                    //on récupère en bdd la ressource matérielle correspondante
                                    $materialResource = $doctrine->getRepository("App\Entity\MaterialResource")->findOneBy(["id" => $idResource[1]]);

                                    //on regarde si la ressource est toujours associé à l'évènement modifié
                                    if ($materialResourceScheduled->getMaterialresource() == $materialResource) {
                                        //on précise au booléen que la relation est toujours présente
                                        $materialResourceExist = true;
                                        
                                        /*$strDate = substr($date, 0, 10);
                                        $strStart = $strDate . " " . $scheduledActivity->getStarttime()->format('H:i:s');

                                        $listUnavailabilityMaterialResource = $unavailabilityMaterialResourceRepository->findUnavailabilityMaterialResourceByDate($strStart, $materialResourceScheduled->getMaterialresource()->getId());
                                        foreach ($listUnavailabilityMaterialResource as $unavailabilityMaterialResource) {
                                            $unavailability = $unavailabilityMaterialResource->getUnavailability();
                                            $strEnd = $strDate . " " . $scheduledActivity->getEndtime()->format('H:i:s');
                                            $unavailability->setStartdatetime(\DateTime::createFromFormat('Y-m-d H:i:s', $strStart));
                                            $unavailability->setEnddatetime(\DateTime::createFromFormat('Y-m-d H:i:s', $strEnd));
                                            $entityManager->persist($unavailability);
                                            $entityManager->flush();
                                        }*/
                                    }
                                }
                            }

                            //si la relation n'est plus présente dans la liste des relations modifiés
                            if (!$materialResourceExist) {
                                //on supprime la relation entre l'évènement programmé et la ressource matérielle
                                $materialResourceScheduledRepository->remove($materialResourceScheduled, true);

                                /*$strDate = substr($date, 0, 10);
                                $strStart = $strDate . " " . $scheduledActivity->getStarttime()->format('H:i:s');

                                $listUnavailabilityMaterialResource = $unavailabilityMaterialResourceRepository->findUnavailabilityMaterialResourceByDate($strStart, $materialResourceScheduled->getMaterialresource()->getId());
                                
                                foreach ($listUnavailabilityMaterialResource as $unavailabilityMaterialResource) {
                                    $unavailability = $unavailabilityMaterialResource->getUnavailability();
                                    $entityManager->remove($unavailabilityMaterialResource);
                                    $entityManager->flush($unavailabilityMaterialResource);
                                    $entityManager->remove($unavailability);
                                    $entityManager->flush($unavailability);
                                }*/
                            }
                        }
                    }
                }
            }

            //si l'évènement modifié n'est pas encore en bdd
            if ($event[0]->extendedProps->type == "activity") {
                if (!$scheduledActivityExist) {
                    //on créer un nouvelle évènement
                    $newScheduledActivity = new ScheduledActivity();
                    $newScheduledActivity->setStarttime(\DateTime::createFromFormat('H:i:s', substr($event[0]->start, 11, 16)));
                    $newScheduledActivity->setEndtime(\DateTime::createFromFormat('H:i:s', substr($event[0]->end, 11, 16)));

                    $activity = $doctrine->getRepository("App\Entity\Activity")->findOneBy(["id" => $event[0]->extendedProps->activity]);
                    $appointment = $doctrine->getRepository("App\Entity\Appointment")->findOneBy(["id" => $event[0]->extendedProps->appointment]);

                    $appointment->setScheduled(true);
                    $newScheduledActivity->setActivity($activity);
                    $newScheduledActivity->setAppointment($appointment);
                    $scheduledActivityRepository->add($newScheduledActivity, true);

                    //on parcours la liste de ses ressources associés
                    foreach ($event[1] as $resource) {
                        //on créer les relations avec les ressources de type humaine
                        if (substr($resource, 0, 5) == "human") {
                            $idResource = explode("-", $resource);
                            //on créer la nouvelle relation entre la ressource humaine et le nouvel évènement
                            $humanResource = $doctrine->getRepository("App\Entity\HumanResource")->findOneBy(["id" => $idResource[1]]);
                            $newHumanResourceScheduled = new HumanResourceScheduled();
                            $newHumanResourceScheduled->setHumanresource($humanResource);
                            $newHumanResourceScheduled->setScheduledactivity($newScheduledActivity);

                            $humanResourceScheduledRepository->add($newHumanResourceScheduled, true);

                            /*//ajout de unavailability
                            $newUnavailability = new Unavailability();
                            $strDate = substr($date, 0, 10);
                            $strStart = $strDate . " " . $newScheduledActivity->getStarttime()->format('H:i:s');
                            $strEnd = $strDate . " " . $newScheduledActivity->getEndtime()->format('H:i:s');
                            $newUnavailability->setStartdatetime(\DateTime::createFromFormat('Y-m-d H:i:s', $strStart));
                            $newUnavailability->setEnddatetime(\DateTime::createFromFormat('Y-m-d H:i:s', $strEnd));

                            $entityManager->persist($newUnavailability);
                            $entityManager->flush();

                            //ajout de la relation entre les deux tables
                            $newUnavailabilityHumanResource = new UnavailabilityHumanResource();
                            $newUnavailabilityHumanResource->setHumanresource($humanResource);
                            $newUnavailabilityHumanResource->setUnavailability($newUnavailability);

                            $entityManager->persist($newUnavailabilityHumanResource);
                            $entityManager->flush();*/
                        }

                        //on créer les relations avec les ressources de type matérielle
                        else if (substr($resource, 0, 8) == "material") {
                            $idResource = explode("-", $resource);
                            //on créer la nouvelle relation entre la ressource matérielle et le nouvel évènement
                            $materialResource = $doctrine->getRepository("App\Entity\MaterialResource")->findOneBy(["id" => $idResource[1]]);

                            $newMaterialResourceScheduled = new MaterialResourceScheduled();
                            $newMaterialResourceScheduled->setMaterialresource($materialResource);
                            $newMaterialResourceScheduled->setScheduledactivity($newScheduledActivity);

                            $materialResourceScheduledRepository->add($newMaterialResourceScheduled, true);

                            /*//ajout de unavailability
                            $newUnavailability = new Unavailability();
                            $strDate = substr($date, 0, 10);
                            $strStart = $strDate . " " . $newScheduledActivity->getStarttime()->format('H:i:s');
                            $strEnd = $strDate . " " . $newScheduledActivity->getEndtime()->format('H:i:s');
                            $newUnavailability->setStartdatetime(\DateTime::createFromFormat('Y-m-d H:i:s', $strStart));
                            $newUnavailability->setEnddatetime(\DateTime::createFromFormat('Y-m-d H:i:s', $strEnd));

                            $entityManager->persist($newUnavailability);
                            $entityManager->flush();

                            //ajout de la relation entre les deux tables
                            $newUnavailabilityMaterialResource = new UnavailabilityMaterialResource();
                            $newUnavailabilityMaterialResource->setMaterialresource($materialResource);
                            $newUnavailabilityMaterialResource->setUnavailability($newUnavailability);

                            $entityManager->persist($newUnavailabilityMaterialResource);
                            $entityManager->flush();*/
                        }
                    }
                }
            }
        }
        $this->modificationDeleteOnUnload($request, $doctrine);
        return $this->redirectToRoute('ConsultationPlanning', [], Response::HTTP_SEE_OTHER);
    }

    public function modificationDeleteOnUnload(Request $request, ManagerRegistry $doctrine)
    {
        $dateModified = $request->request->get("validation-date");
        if (isset($_GET['dateModified'])) {
            $dateModified = $_GET['dateModified'];
        }
        $dateModified = str_replace('T12:00:00', '', $dateModified);

        //$modificationRepository = $doctrine->getRepository("App\Entity\Modification");
        $modificationRepository = new ModificationRepository($doctrine);
        $modifications = $modificationRepository->findAll();
        $i = 0;
        foreach ($modifications as $modification) {
            if ($modification->getDatemodified()->format('Y-m-d') == $dateModified) {
                $modificationRepository->remove($modification, true);
            }
            $i++;
        }
        return $this->redirectToRoute('ConsultationPlanning', [], Response::HTTP_SEE_OTHER);
    }

    //Retouorne la liste des MaterialResourcesUnavailable en format JSON
    public function getMaterialResourcesUnavailables(ManagerRegistry $doctrine)
    {
        //recuperation du patient depuis la base de données
        $materialResourcesUnavailable = $doctrine->getRepository("App\Entity\UnavailabilityMaterialResource")->findAll();
        $materialResourcesUnavailableArray = array();
        foreach ($materialResourcesUnavailable as $materialResourceUnavailable) {
            $resource = $materialResourceUnavailable->getMaterialresource()->getId();
            $resource = "material-" . $resource;
            $materialResourcesUnavailableArray[] = array(
                'description' => 'Ressource Indisponible',
                'resourceId' => ($resource),
                'start' => ($materialResourceUnavailable->getUnavailability()->getStartdatetime()->format('Y-m-d H:i:s')),
                'end' => ($materialResourceUnavailable->getUnavailability()->getEnddatetime()->format('Y-m-d H:i:s')),
                'display' => 'background',
                'type' => "unavailability"
            );
        }
        //Conversion des données ressources en json 
        $materialResourcesUnavailableArrayJSON = new JsonResponse($materialResourcesUnavailableArray);
        return $materialResourcesUnavailableArrayJSON;
    }

    //Retourne la liste des HumanResourcesUnavailable en format JSON. 
    public function getHumanResourceUnavailables(ManagerRegistry $doctrine)
    {
        //recuperation du patient depuis la base de données
        $humanResourcesUnavailable = $doctrine->getRepository("App\Entity\UnavailabilityHumanResource")->findAll();
        $humanResourcesUnavailableArray = array();
        foreach ($humanResourcesUnavailable as $humanResourceUnavailable) {
            $resource = $humanResourceUnavailable->getHumanresource()->getId();
            $resource = "human-" . $resource;
            $humanResourcesUnavailableArray[] = array(
                'description' => 'Employé Indisponible',
                'resourceId' => ($resource),
                'start' => ($humanResourceUnavailable->getUnavailability()->getStartdatetime()->format('Y-m-d H:i:s')),
                'end' => ($humanResourceUnavailable->getUnavailability()->getEnddatetime()->format('Y-m-d H:i:s')),
                'display' => 'background',
                'type' => "unavailability"
            );
        }
        //Conversion des données ressources en json 
        $humanResourcesUnavailableArrayJSON = new JsonResponse($humanResourcesUnavailableArray);
        return $humanResourcesUnavailableArrayJSON;
    }
}
