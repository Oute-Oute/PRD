<?php

namespace App\Controller;

use App\Entity\MaterialResourceScheduled;
use App\Entity\HumanResourceScheduled;
use App\Entity\ScheduledActivity;
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
        $date_today = array();
        if (isset($_POST['form'])) {
            $this->modificationPlanningPost($request, $doctrine, $entityManager);
            $date_today = $_POST['date'];
        }
        if (isset($_GET['date'])) {
            $date_today = $_GET["date"];
        }
        //Récupération des données nécessaires
        $listHumanResources = $doctrine->getRepository("App\Entity\HumanResource")->findBy(['available' => true]);
        $listMaterialResources = $doctrine->getRepository("App\Entity\MaterialResource")->findBy(['available' => true]);
        $listePatients = $doctrine->getRepository("App\Entity\Patient")->findAll();
        $listePathWayPatients = $doctrine->getRepository("App\Entity\Appointment")->findAll();
        $listeAppointment = $this->listAppointment($doctrine);
        $listescheduledActivity = $this->listScehduledActivity($doctrine, $SAR, $date_today);
        $listesuccessionJSON = $this->listSuccessorJSON($doctrine);
        $listeActivitiesJSON = $this->listActivityJSON($doctrine);
        $listAppointmentJSON = $this->listAppointmentJSON($doctrine);

        $listMaterialResourceJSON = $this->listMaterialResourcesJSON($doctrine);
        $listHumanResourceJSON = $this->listHumanResourcesJSON($doctrine);
        //dd($listAppointmentJSON);
        return $this->render('planning/modification-planning.html.twig', [
            'listepatients' => $listePatients,
            'listePathWaypatients' => $listePathWayPatients,
            'listMaterialResourceJSON' => $listMaterialResourceJSON,
            'listHumanResourceJSON' => $listHumanResourceJSON,
            'listHumanResources' => $listHumanResources,
            'listMaterialResources' => $listMaterialResources,
            'datetoday' => $date_today,
            'listScheduledActivitiesJSON' => $listescheduledActivity,
            'listeAppointments' => $listeAppointment,
            'listeSuccessorsJSON' => $listesuccessionJSON,
            'listeActivitiesJSON' => $listeActivitiesJSON,
            'listAppointmentsJSON' => $listAppointmentJSON
        ]);
    }

    public function bordel(string $date_today)
    {
        $modificationRepository = new ModificationRepository($this->getDoctrine());

        $date_today = strtotime(str_replace('T', ' ', $date_today));
        $dateTime_today = new \DateTime(date('Y-m-d h:i:s', $date_today));
        $date_today = new \DateTime('now'); //date('Y-m-d', $date_today);

        $interval = $date_today->diff($dateTime_today);
        dd($interval->format('Difference of %h hours, %i minutes and %s seconds'));

        $modifications = $modificationRepository->findAll();
        $modifArray = array();
        foreach ($modifications as $modification) {
            $modifArray[] = array(
                'dateTimeModified' => ($modification->getDatetimemodification()->format('Y-m-d h:i:s')),
                'dateModified' => ($modification->getDatemodified()->format('Y-m-d'))
            );
        }
        dd($modifArray);

        if (count($modifications) >= 1) {
            dd($modifications);
        }
    }

    public function modificationPlanningPost(Request $request, ManagerRegistry $doctrine, EntityManagerInterface $entityManager)
    {
        $form = $request->get('form');

        if ($form == 'add') {
            $idAppointment = $request->get('select-appointment');
            $AppointmentBegin = $request->get('time_begin');
            $Appointment = $doctrine->getRepository('App\Entity\Appointment')->findOneBy(['id' => $idAppointment]);
            $activities = $doctrine->getRepository('App\Entity\Activity')->findBy(['pathway' => $Appointment->getPathway()->getId()]);
            foreach ($activities as $activity) {
                $findfirst = $doctrine->getRepository('App\Entity\Successor')->findBy(['activityb' => $activity->getId()]);
                if ($findfirst == null) {
                    $idfirst = $activity->getId();
                }
            }
            $activitya = $doctrine->getRepository('App\Entity\Successor')->findOneBy(['id' => $idfirst])->getActivitya();

            do {
                $succesor = $doctrine->getRepository('App\Entity\Successor')->findOneBy(['activitya' => $activitya->getId()]);
                dd($succesor);
                $activityB = $succesor->getActivityb();
            } while ($activityB != null);
        }
    }
    public function listSuccessorJSON(ManagerRegistry $doctrine)
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

    public function listActivityJSON(ManagerRegistry $doctrine)
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
    public function modificationPlanninEventsgGet(Request $request, ManagerRegistry $doctrine, EntityManagerInterface $entityManager)
    {
    }


    public function listAppointment(ManagerRegistry $doctrine)
    {
        $appointments = $doctrine->getRepository("App\Entity\Appointment")->findAll();
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

    public function listAppointmentJSON(ManagerRegistry $doctrine)
    {
        $appointments = $doctrine->getRepository("App\Entity\Appointment")->findAll();
        $appointmentsArray = array();
        foreach ($appointments as $appointment) {
            $appointmentsArray[] = array(
                'id' => $appointment->getId(),
                'earliestappointmenttime' => $appointment->getEarliestappointmenttime()->format('H:i:s'),
                'lastestappointmenttime' => $appointment->getLatestappointmenttime()->format('H:i:s'),
                'dayappointment' => $appointment->getDayappointment()->format('Y:m:d'),
                'idPatient' => $this->getPatient($doctrine, $appointment->getPatient()->getId()),
                'idPathway' => $this->getPathway($doctrine, $appointment->getPathway()->getId()),
            );
        }
        $appointmentsArrayJSON = new JsonResponse($appointmentsArray);
        return $appointmentsArrayJSON;
    }

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
            'id' => (str_replace(" ", "3aZt3r", $id)),
            'lastname' => (str_replace(" ", "3aZt3r", $lastname)),
            'firstname' => (str_replace(" ", "3aZt3r", $firstname)),
            'title' => (str_replace(" ", "3aZt3r", $title))
        );

        //Conversion des données ressources en json 
        return $patientArray;
    }

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

    public function listHumanResourcesJSON(ManagerRegistry $doctrine)
    {
        $humanResources = $doctrine->getRepository("App\Entity\HumanResource")->findAll();
        $materialResourcesArray = array();

        if ($humanResources != null) {
            foreach ($humanResources as $humanResource) {
                $materialResourcesArray[] = array(
                    'id' => ("human-" . str_replace(" ", "3aZt3r", $humanResource->getId())),
                    'title' => (str_replace(" ", "3aZt3r", $humanResource->getHumanresourcename())),
                );
            }
        }

        //Conversion des données ressources en json
        $materialResourcesArrayJson = new JsonResponse($materialResourcesArray);
        return $materialResourcesArrayJson;
    }

    public function listMaterialResourcesJSON(ManagerRegistry $doctrine)
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

    public function listScehduledActivity(ManagerRegistry $doctrine, ScheduledActivityRepository $SAR, $date)
    {
        $TodayDate = substr($date, 0, 10);


        $scheduledActivities = $SAR->findSchedulerActivitiesByDate($TodayDate);
        $scheduledActivitiesArray = array();
        foreach ($scheduledActivities as $scheduledActivity) {
            $scheduledActivitiesHumanResources = $doctrine->getRepository("App\Entity\HumanResourceScheduled")->findBy((['scheduledactivity' => $scheduledActivity->getId()]));
            $scheduledActivitiesResourcesArray = array();
            foreach ($scheduledActivitiesHumanResources as $scheduledActivitiesHumanResource) {
                array_push($scheduledActivitiesResourcesArray, "human-" . $scheduledActivitiesHumanResource->getHumanresource()->getId());
            }

            $scheduledActivitiesMaterialResources = $doctrine->getRepository("App\Entity\MaterialResourceScheduled")->findBy((['scheduledactivity' => $scheduledActivity->getId()]));
            foreach ($scheduledActivitiesMaterialResources as $scheduledActivitiesMaterialResource) {
                array_push($scheduledActivitiesResourcesArray, "material-" . $scheduledActivitiesMaterialResource->getMaterialresource()->getId());
            }

            $patientId = $scheduledActivity->getAppointment()->getPatient()->getId();
            $start = $scheduledActivity->getStarttime();
            $day = $scheduledActivity->getDayscheduled();
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
                'patient' => $patientId,
                'appointment' => $idAppointment,
                'activity' => $idActivity,
            );
        }
        $scheduledActivitiesArrayJson = new JsonResponse($scheduledActivitiesArray);
        return $scheduledActivitiesArrayJson;
    }

    public function modificationPlanningValidation(Request $request, ScheduledActivityRepository $scheduledActivityRepository, HumanResourceScheduledRepository $humanResourceScheduledRepository, MaterialResourceScheduledRepository $materialResourceScheduledRepository, ManagerRegistry $doctrine)
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
        $listScheduledActivity = $scheduledActivityRepository->findBy(['dayscheduled' => \DateTime::createFromFormat('Y-m-d', substr($date, 0, 10))]);

        //on parcours la liste des évènement plannifié qui viennent d'être modifiés
        foreach ($listScheduledEvent as $event) {
            //on instancie un booléen pour savoir si l'évènement est déjà en bdd ou non
            $scheduledActivityExist = false;

            //on parcours la liste des évènement programmés déjà stocké en bdd
            foreach ($listScheduledActivity as $scheduledActivity) {
                //si l'évènement modifié correspond à un évènement déjà enregistré en bdd, alors on le met à jour
                if ($scheduledActivity->getId() == $event[0]->id) {
                    //on précise au booléen que l'évènement modifié existe déjà et qu'on a pas à le créer
                    $scheduledActivityExist = true;

                    //on met à jour ses attributs dans sa table
                    $scheduledActivity->setStarttime(\DateTime::createFromFormat('H:i:s', substr($event[0]->start, 11, 16)));
                    $scheduledActivity->setEndtime(\DateTime::createFromFormat('H:i:s', substr($event[0]->end, 11, 16)));
                    $scheduledActivity->setDayscheduled(\DateTime::createFromFormat('Y-m-d', substr($event[0]->start, 0, 10)));

                    $scheduledActivityRepository->add($scheduledActivity, true);

                    //on récupère la liste des ressources associées à l'évènement
                    $listeMaterialResourceScheduled = $materialResourceScheduledRepository->findBy((['scheduledactivity' => $scheduledActivity->getId()]));
                    $listeHumanResourceScheduled = $humanResourceScheduledRepository->findBy((['scheduledactivity' => $scheduledActivity->getId()]));

                    //on parcours la liste des ressources modifiés
                    foreach ($event[1] as $resource) {
                        //on regarde si la ressource modifié est de type Humaine
                        if (substr($resource->id, 0, 5) == "human") {
                            //on récupère l'objet ressource humaine correspondant
                            $humanResource = $doctrine->getRepository("App\Entity\HumanResource")->findOneBy(["id" => substr($resource->id, 6)]);

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
                            }
                        }

                        //sinon, la relation est donc de type matérielle
                        else {
                            //on récupère l'objet ressource matériel correspondant
                            $materialResource = $doctrine->getRepository("App\Entity\MaterialResource")->findOneBy(["id" => substr($resource->id, 9)]);

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
                            if (substr($resource->id, 0, 5) == "human") {
                                //on récupère en bdd la ressource humaine correspondante
                                $humanResource = $doctrine->getRepository("App\Entity\HumanResource")->findOneBy(["id" => substr($resource->id, 6)]);

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
                        }
                    }

                    //on parcours la liste des relation entre ressource matérielle et évènement programmé de la bdd
                    foreach ($listeMaterialResourceScheduled as $materialResourceScheduled) {
                        //on instancie un booléen pour savoir si la ressource est toujours associé à l'évènement modifié
                        $materialResourceExist = false;

                        //on parcours la liste des ressources associé à l'évènement
                        foreach ($event[1] as $resource) {
                            //on ne compare que les ressource de type matérielle
                            if (substr($resource->id, 0, 8) == "material") {
                                //on récupère en bdd la ressource matérielle correspondante
                                $materialResource = $doctrine->getRepository("App\Entity\MaterialResource")->findOneBy(["id" => substr($resource->id, 9)]);

                                //on regarde si la ressource est toujours associé à l'évènement modifié
                                if ($materialResourceScheduled->getMaterialresource() == $materialResource) {
                                    //on précise au booléen que la relation est toujours présente
                                    $materialResourceExist = true;
                                }
                            }
                        }

                        //si la relation n'est plus présente dans la liste des relations modifiés
                        if (!$materialResourceExist) {
                            //on supprime la relation entre l'évènement programmé et la ressource matérielle
                            $materialResourceScheduledRepository->remove($materialResourceScheduled, true);
                        }
                    }
                }
            }

            //si l'évènement modifié n'est pas encore en bdd
            if (!$scheduledActivityExist) {
                //on créer un nouvelle évènement
                $newScheduledActivity = new ScheduledActivity();
                $newScheduledActivity->setStarttime(\DateTime::createFromFormat('H:i:s', substr($event[0]->start, 11, 16)));
                $newScheduledActivity->setEndtime(\DateTime::createFromFormat('H:i:s', substr($event[0]->end, 11, 16)));
                $newScheduledActivity->setDayscheduled(\DateTime::createFromFormat('Y-m-d', substr($event[0]->start, 0, 10)));

                $activity = $doctrine->getRepository("App\Entity\Activity")->findOneBy(["id" => $event[0]->extendedProps->activity]);
                $appointment = $doctrine->getRepository("App\Entity\Appointment")->findOneBy(["id" => $event[0]->extendedProps->appointment]);

                $newScheduledActivity->setActivity($activity);
                $newScheduledActivity->setAppointment($appointment);

                $scheduledActivityRepository->add($newScheduledActivity, true);

                //on parcours la liste de ses ressources associés
                foreach ($event[1] as $resource) {
                    //on créer les relations avec les ressources de type humaine
                    if (substr($resource->id, 0, 5) == "human") {
                        //on créer la nouvelle relation entre la ressource humaine et le nouvel évènement
                        $humanResource = $doctrine->getRepository("App\Entity\HumanResource")->findOneBy(["id" => substr($resource->id, 6)]);
                        $newHumanResourceScheduled = new HumanResourceScheduled();
                        $newHumanResourceScheduled->setHumanresource($humanResource);
                        $newHumanResourceScheduled->setScheduledactivity($newScheduledActivity);

                        $humanResourceScheduledRepository->add($newHumanResourceScheduled, true);
                    }

                    //on créer les relations avec les ressources de type matérielle
                    else {
                        //on créer la nouvelle relation entre la ressource matérielle et le nouvel évènement
                        $materialResource = $doctrine->getRepository("App\Entity\MaterialResource")->findOneBy(["id" => substr($resource->id, 9)]);

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
