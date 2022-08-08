<?php

namespace App\Controller;

use App\Entity\MaterialResourceScheduled;
use App\Entity\HumanResourceScheduled;
use App\Entity\ScheduledActivity;
use App\Entity\Modification;
use App\Repository\AppointmentRepository;
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
use DateTimeZone;


/**
 * Modification planning controller
 */
class ModificationPlanningController extends AbstractController
{
    public $dateModified;

    //Part 1
    //This part manages the controller (GET and POST)

    /**
     * This function build the main page of modification planning and give many informations
     */
    public function modificationPlanningGet(ManagerRegistry $doctrine, ScheduledActivityRepository $SAR): Response
    {
        $english_months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
        $french_months = array('janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');
        
        global $dateModified;
        //Récupération de la date à laquelle on modifie le planning
        if (isset($_GET['date'])) {
            $dateModified = $_GET["date"];
        }
        if (isset($_GET['id'])) {
            $idUser = $_GET["id"];
        }
        else{
            $idUser=-1; 
        }

        $dateModifiedFormatted = date_create($dateModified);
        $dateModifiedFormatted->format('Y-F-d');
        $dateModifiedStringFormat = str_replace($english_months, $french_months, $dateModifiedFormatted->format('d F Y'));


        //define arrays for data needed by the twig file
        $settingsRepository = $doctrine->getRepository("App\Entity\Settings")->findAll();

        //define arrays for data needed by the JS file
        $listscheduledActivityJSON = $this->getScheduledActivityJSON($doctrine, $SAR, $dateModified);
        $listSuccessorJSON = $this->getSuccessorJSON($doctrine);
        $listActivitiesJSON = $this->getActivityJSON($doctrine);
        $listAppointmentJSON = $this->getAppointmentJSON($doctrine, $dateModified);
        $listMaterialResourceJSON = $this->getMaterialResourcesJSON($doctrine);
        $listHumanResourceJSON = $this->getHumanResourcesJSON($doctrine);
        $listActivityHumanResourcesJSON = $this->getActivityHumanResourcesJSON($doctrine);
        $listActivityMaterialResourcesJSON = $this->getActivityMaterialResourcesJSON($doctrine);
        $categoryOfMaterialResourceJSON = $this->getCategoryOfMaterialResourceJSON($doctrine);
        $categoryOfHumanResourceJSON = $this->getCategoryOfHumanResourceJSON($doctrine);
        $categoryMaterialResourceJSON = $this->getCategoryMaterialResourceJSON($doctrine); 
        $categoryHumanResourceJSON = $this->getCategoryHumanResourceJSON($doctrine); 

        if ($this->alertModif($dateModified, $idUser, $doctrine, $settingsRepository)) {
            $this->modificationAdd($dateModified, $idUser, $doctrine);
        }

        //On redirige sur la page html modification planning et on envoie toutes les données dont on a besoin
        return $this->render('planning/modification-planning.html.twig', [
            'currentdate' => $dateModified,
            'dateFormatted' => $dateModifiedStringFormat,
            'settingsRepository' => $settingsRepository,

            'listMaterialResourceJSON' => $listMaterialResourceJSON,
            'listHumanResourceJSON' => $listHumanResourceJSON,
            'listScheduledActivitiesJSON' => $listscheduledActivityJSON,
            'listSuccessorsJSON' => $listSuccessorJSON,
            'listActivitiesJSON' => $listActivitiesJSON,
            'listAppointmentsJSON' => $listAppointmentJSON,
            'listActivityHumanResourcesJSON' => $listActivityHumanResourcesJSON, //à simplifier : peut directement être mis dans les données de l'activité
            'listActivityMaterialResourcesJSON' => $listActivityMaterialResourcesJSON, //à simplifier : peut directement être mis dans les données de l'activité
            'categoryOfMaterialResourceJSON' => $categoryOfMaterialResourceJSON, //regarder si ce n'est pas possible de les mettres directement avec les ressources
            'categoryOfHumanResourceJSON' => $categoryOfHumanResourceJSON, //regarder si ce n'est pas possible de les mettres directement avec les ressources
            'categoryMaterialResourceJSON'=> $categoryMaterialResourceJSON,
            'categoryHumanResourceJSON' => $categoryHumanResourceJSON,
        ]);
    }

    /**
     * This method recover all informations from modification planning and update database
     */
    public function modificationPlanningValidation(Request $request, AppointmentRepository $appointmentRepository, ScheduledActivityRepository $scheduledActivityRepository, HumanResourceScheduledRepository $humanResourceScheduledRepository, MaterialResourceScheduledRepository $materialResourceScheduledRepository, ManagerRegistry $doctrine, EntityManagerInterface $entityManager)
    {
        //recover all informations from twig file
        $listEvent = json_decode($request->request->get("events"));
        $listResource = json_decode($request->request->get("list-resource"));
        $listScheduledAppointments = json_decode($request->request->get("scheduled-appointments"));
        $userId = $request->request->get("user-id");
        
        //update the new scheduled appointments
        foreach($listScheduledAppointments as $oneScheduledAppointment)
        {
            if($oneScheduledAppointment->scheduled)
            {
                $appointment = $doctrine->getRepository("App\Entity\Appointment")->findOneBy(["id" => $oneScheduledAppointment->id]);
                $appointment->setScheduled($oneScheduledAppointment->scheduled);
                $appointmentRepository->add($appointment, true);
            }
        }
        
        //we associate the events and the resources for make a unique list
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
        foreach ($listScheduledEvent as $event) 
        {
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
                            }
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
                                    }
                                }
                            }

                            //si la relation n'est plus présente dans la liste des relations modifiés
                            if (!$materialResourceExist) {
                                $materialResourceScheduledRepository->remove($materialResourceScheduled, true);
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
                        }
                    }
                }
            }
        }
        $this->modificationDeleteOnUnload($request, $doctrine, $_GET['username']);
        return $this->redirect("/ModificationPlanning?date=" . $date . "&id=" . $userId);
    }


    //Part 2
    //This part manages the modification data

    /**
     * This function verified if a modification for this date is already in progress
     */
    public function alertModif($dateModified, $idUser, $doctrine, $settingsRepository)
    {
        $modificationRepository = $doctrine->getRepository("App\Entity\Modification");
        $modifications = $modificationRepository->findAll();

        $dateModified = str_replace('T12:00:00', '', $dateModified);
        $dateToday = new \DateTime('now', new DateTimeZone('Europe/Paris'));
        $dateToday = new \DateTime($dateToday->format('Y-m-d H:i:s'));

        $modifAlertTime = 8;
        foreach($settingsRepository as $setting){
            $modifAlertTime = intdiv($setting->getAlertmodificationtimer(), 60000);
        }

        $modifArray = array();
        $i = 0;
        foreach ($modifications as $modification) {
            $modifArray[] = array(
                'dateTimeModified' => ($modification->getDatetimemodification()->format('Y-m-d H:i:s')),
                'dateModified' => ($modification->getDatemodified()->format('Y-m-d')),
                'userId' => ($modification->getUser()->getId())
            );
            $usernameModifiying = $doctrine->getRepository("App\Entity\User")->findOneBy(['id' => $modifArray[$i]['userId']])->getUsername();

            $datetimeModified = new \DateTime(date('Y-m-d H:i:s', strtotime($modifArray[$i]['dateTimeModified'])));
            $interval = $datetimeModified->diff($dateToday);

            $intervalHour = $interval->format('%h');
            $intervalMinutes = $interval->format('%i');

            if ($modifArray[$i]['dateModified'] == $dateModified) {
                // ATTENTION, le timer doit être supérieur à celui du popup
                if ($intervalHour * 60 + $intervalMinutes < $modifAlertTime + 2) {
                    if ($idUser == $modifArray[$i]['userId']) { // Empeche d'envoyer une erreur si un user quitte et revient
                        $modificationRepository->remove($modification, true);
                    }
                    else {
                        echo "<script>
                            if(confirm('Une modification de ".$usernameModifiying." pour le ".$dateModified." est déjà en cours, voulez-vous continuer ?')){
                                redirect = 1;
                            }
                            else{
                                redirect = 0;
                                window.location.assign('/ConsultationPlanning');
                            }
                        </script>";
                        return "<script>document.write(redirect);</script>";
                    }
                } 
                else {
                    // Supprimer la modif dans BDD car trop vieille
                    $modificationRepository->remove($modification, true);
                }
            }
            $i++;
        }
        return true;
    }

    /**
     * This function add a data to the table Modification for the user
     */
    public function modificationAdd($dateModified, $idUser, $doctrine)
    {
        $modificationRepository = $doctrine->getRepository("App\Entity\Modification");
        $userRepository = $doctrine->getRepository("App\Entity\User");
        $user = $userRepository->findOneBy(['id' => $idUser]);

        // Pour le développement, on n'ajoute pas dans la bdd si on est pas connecté
        // A enlever plus tard car on est censé être connecté
        if (!$user) {
            //dd($user, "Erreur, vous n'êtes pas connecté !");
        } else {
            $userRepository->add($user, true);

            $datetimeModified = new \DateTime(date('Y-m-d', strtotime($dateModified)));
            $dateToday = new \DateTime('now', new DateTimeZone('Europe/Paris'));
            $dateToday = new \DateTime($dateToday->format('Y-m-d H:i:s'));

            $modification = new Modification();
            $modification->setUser($user);
            $modification->setDatemodif($datetimeModified);
            $modification->setDatetimemodification($dateToday);

            // ajout dans la bdd
            $modificationRepository->add($modification, true);
        }
    }

    /**
     * This function delete the data modification when the user leave modification planning and then redirect to consultation planning
     */
    public function modificationDeleteOnUnload(Request $request, ManagerRegistry $doctrine, $username = '')
    {
        $dateModified = $request->request->get("validation-date");
        if (isset($_GET['dateModified'])) {
            $dateModified = $_GET['dateModified'];
        }
        if(isset($_GET['id'])){
            $id = $_GET['id'];
        }
        else{
            $id = 0;
        }
        $dateModified = str_replace('T12:00:00', '', $dateModified);

        $modificationRepository = new ModificationRepository($doctrine);
        $modifications = $modificationRepository->findAll();
        $i = 0;
        foreach ($modifications as $modification) {
            if ($modification->getDatemodified()->format('Y-m-d') == $dateModified && ($modification->getUser()->getId() == $id || $modification->getUser()->getUserIdentifier() == $username)) {
                $modificationRepository->remove($modification, true);
            }
            $i++;
        }
        return $this->redirectToRoute('ConsultationPlanning', [], Response::HTTP_SEE_OTHER);
    }


    //Part 3
    //This part manages the functions use for the page creation

    //Part 3.1
    //This part manages the functions use for get the data in JSON format

    /**
     * This function recover all the successors from the database and return the data in a JSON format for JS
     * @return successorsArrayJSON an array in JSON format with all the data
     */
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

    /**
     * This function recover all the activities from the database and return the data in a JSON format for JS
     * @return activitiesArrayJSON an array in JSON format with all the data
     */
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

    /**
     * This function recover all the appointments for a date given and return the data in a JSON format for JS
     * @return appointmentsArrayJSON an array in JSON format with all the data
     */
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

    /**
     * This function recover all the human resources and return the data in a JSON format for JS
     * @return humanResourcesArrayJson an array in JSON format with all the data
     */
    public function getHumanResourcesJSON(ManagerRegistry $doctrine)
    {
        $humanResources = $doctrine->getRepository("App\Entity\HumanResource")->findAll();
        $humanResourcesArray = array();

        if ($humanResources != null) {
            foreach ($humanResources as $humanResource) {
                $categories=$doctrine->getRepository("App\Entity\CategoryOfHumanResource")->findBy(array('humanresource' => $humanResource));
                $categoriesArray=array();
                foreach ($categories as $category) {
                    $categoriesArray[]=array(
                        'id' => $category->getId(),
                        'name' => $category->getHumanResourceCategory()->getCategoryname()
                    );
                }
                $humanResourcesArray[] = array(
                    'id' => ("human-" . str_replace(" ", "3aZt3r", $humanResource->getId())),
                    'title' => (str_replace(" ", "3aZt3r", $humanResource->getHumanresourcename())),
                    'workingHours' => ($this->getWorkingHours($doctrine, $humanResource)),
                    'categories' => $categoriesArray

                );
            }
        }
        //Conversion des données ressources en json
        $humanResourcesArrayJson = new JsonResponse($humanResourcesArray);
        return $humanResourcesArrayJson;
    }

    /**
     * This function recover all the material resources and return the data in a JSON format for JS
     * @return materialResourcesArrayJson an array in JSON format with all the data
     */
    public function getMaterialResourcesJSON(ManagerRegistry $doctrine)
    {
        $materialResources = $doctrine->getRepository("App\Entity\MaterialResource")->findAll();
        $materialResourcesArray = array();
        if ($materialResources != null) {
            foreach ($materialResources as $materialResource) {
                $categories=$doctrine->getRepository("App\Entity\CategoryOfMaterialResource")->findBy(array('materialresource' => $materialResource));
                $categoriesArray=array();
                foreach ($categories as $category) {
                    $categoriesArray[]=array(
                        'id' => $category->getId(),
                        'name' => $category->getMaterialResourceCategory()->getCategoryname()
                    );
                }
                $materialResourcesArray[] = array(
                    'id' => ("material-" . str_replace(" ", "3aZt3r", $materialResource->getId())),
                    'title' => (str_replace(" ", "3aZt3r", $materialResource->getMaterialresourcename())),
                    'categories' => $categoriesArray
                );
            }
        }
        unset($categoriesArray);
        //Conversion des données ressources en json
        $materialResourcesArrayJson = new JsonResponse($materialResourcesArray);
        return $materialResourcesArrayJson;
    }

    /**
     * This function recover all the relationships between each activity and each of its associated human resources 
     * and return the data in a JSON format for JS
     * @return activitiesHumanResourcesArrayJSON an array in JSON format with all the data
     */
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
        $activitiesHumanResourcesArrayJSON = new JsonResponse($activitiesHumanResourcesArray);
        return $activitiesHumanResourcesArrayJSON;
    }

    /**
     * This function recover all the relationships between each activity and each of its associated material resources 
     * and return the data in a JSON format for JS
     * @return activitiesMaterialResourcesArrayJSON an array in JSON format with all the data
     */
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
        $activitiesMaterialResourcesArrayJSON = new JsonResponse($activitiesMaterialResourcesArray);
        return $activitiesMaterialResourcesArrayJSON;
    }

    /**
     * This function set all data needed for create all event in fullcalendar corresponding to each scheduled activity
     * @return scheduledActivitiesArrayJSON an array in JSON format with all the data
     */
    public function getScheduledActivityJSON(ManagerRegistry $doctrine, ScheduledActivityRepository $SAR, $date)
    {
        $TodayDate = substr($date, 0, 10);


        $scheduledActivities = $SAR->findSchedulerActivitiesByDate($TodayDate);
        $scheduledActivitiesArray = array();
        foreach ($scheduledActivities as $scheduledActivity) {
            //Obtention du nombre de resources matérielles à renseigner pour cette activité
            $activitiesMaterialResourcesByActivityId = $doctrine->getRepository("App\Entity\ActivityMaterialResource")->findBy(['activity' => $scheduledActivity->getActivity()->getId()]);
            $quantityMaterialResources = 0;
            $categoryMaterialResource = array();
            foreach ($activitiesMaterialResourcesByActivityId as $activityMaterialResourcesByActivityId) {
                $categoryMaterialResource[] = array(
                    'id' => $activityMaterialResourcesByActivityId->getMaterialresourcecategory()->getId(),
                    'quantity' => $activityMaterialResourcesByActivityId->getQuantity(),
                    'categoryname' => $activityMaterialResourcesByActivityId->getMaterialresourcecategory()->getCategoryname()
                );
                $quantityMaterialResources = $quantityMaterialResources + $activityMaterialResourcesByActivityId->getQuantity();
            }

            //Obtention du nombre de resources Humaines à renseigner pour cette activité
            $activitiesHumanResourcesByActivityId = $doctrine->getRepository("App\Entity\ActivityHumanResource")->findBy(['activity' => $scheduledActivity->getActivity()->getId()]);
            $quantityHumanResources = 0;
            $categoryHumanResource = array();
            foreach ($activitiesHumanResourcesByActivityId as $activityHumanResourcesByActivityId) {
                $categoryHumanResource[] = array(
                    'id' => $activityHumanResourcesByActivityId->getHumanresourcecategory()->getId(),
                    'quantity' => $activityHumanResourcesByActivityId->getQuantity(),
                    'categoryname' => $activityHumanResourcesByActivityId->getHumanresourcecategory()->getCategoryname()
                );
                $quantityHumanResources = $quantityHumanResources + $activityHumanResourcesByActivityId->getQuantity();
            }
            //Tableau contenant toutes les ressources déja plannifiées pour une activité
            $scheduledActivitiesResourcesArray = array();
            //Recherche des ressources Humaines déja plannifiées 
            $scheduledActivitiesHumanResources = $doctrine->getRepository("App\Entity\HumanResourceScheduled")->findBy((['scheduledactivity' => $scheduledActivity->getId()]));
            $scheduledActivitesHumanResourcesArray = array();
            foreach ($scheduledActivitiesHumanResources as $scheduledActivitiesHumanResource) {
                $scheduledActivitesHumanResourcesArray[] = array(
                    'id' => "human-" . $scheduledActivitiesHumanResource->getHumanresource()->getId(),
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
                    'id' => "material-" . $scheduledActivitiesMaterialResource->getMaterialresource()->getId(),
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
                'categoryMaterialResource' => $categoryMaterialResource,
                'categoryHumanResource' => $categoryHumanResource,
                'type' => "activity",
                'description'=>''
            );
        }
        $unavailabityArray = $this->getUnavailabity($doctrine);
        $scheduledActivitiesArray = array_merge($scheduledActivitiesArray, $unavailabityArray);
        $scheduledActivitiesArrayJSON = new JsonResponse($scheduledActivitiesArray);
        return $scheduledActivitiesArrayJSON;
    }

    /**
     * This function recover all category of material resource from the database and return an array in JSON format for JS
     * @return categoryOfMaterialResourceArrayJSON an array in JSON format with all the data
     */
    public function getCategoryOfMaterialResourceJSON(ManagerRegistry $doctrine)
    {
        $categoryOfMaterialResources = $doctrine->getRepository("App\Entity\CategoryOfMaterialResource")->findAll();
        $categoryOfMaterialResourceArray = array();
        foreach($categoryOfMaterialResources as $categoryOfMaterialResource)
        {
            $categoryOfMaterialResourceArray[] = array(
                'idcategory' => $categoryOfMaterialResource->getMaterialresourcecategory()->getId(),
                'idresource' => 'material-' . $categoryOfMaterialResource->getMaterialresource()->getId(),
                'categoryname' => (str_replace(" ", "3aZt3r", $categoryOfMaterialResource->getMaterialresourcecategory()->getCategoryname()))
            );
        }
        $categoryOfMaterialResourceArrayJSON = new JsonResponse($categoryOfMaterialResourceArray);
        return $categoryOfMaterialResourceArrayJSON;
    }

    /**
     * This function recover all category of human resource from the database and return an array in JSON format for JS
     * @return categoryOfHumanResourceArrayJSON an array in JSON format with all the data
     */
    public function getCategoryOfHumanResourceJSON(ManagerRegistry $doctrine)
    {
        $categoryOfHumanResources = $doctrine->getRepository("App\Entity\CategoryOfHumanResource")->findAll();
        $categoryOfHumanResourceArray = array();
        foreach($categoryOfHumanResources as $categoryOfHumanResource)
        {
            $categoryOfHumanResourceArray[] = array(
                'idcategory' => $categoryOfHumanResource->getHumanresourcecategory()->getId(),
                'idresource' => 'human-' . $categoryOfHumanResource->getHumanresource()->getId(),
                'categoryname' => (str_replace(" ", "3aZt3r", $categoryOfHumanResource->getHumanresourcecategory()->getCategoryname()))
            );
        }
        $categoryOfHumanResourceArrayJSON = new JsonResponse($categoryOfHumanResourceArray);
        return $categoryOfHumanResourceArrayJSON;
    }

    /**
     * This function recover all category material resource from the database and return an array in JSON format for JS
     * @return materialResourcesCategoryArrayJSON an array in JSON format with all the data
     */
    public function getCategoryMaterialResourceJSON(ManagerRegistry $doctrine)
    {
        $materialResourcesCategory = $doctrine->getRepository("App\Entity\MaterialResourceCategory")->findAll();
        $materialResourcesCategoryArray = array();
        foreach($materialResourcesCategory as $materialResourceCategory)
        {
            $materialResourcesCategoryArray[] = array(
                'idcategory' => $materialResourceCategory->getId(),
                'categoryname' => (str_replace(" ", "3aZt3r", $materialResourceCategory->getCategoryname()))
            );
        }
        $materialResourcesCategoryArrayJSON = new JsonResponse($materialResourcesCategoryArray);
        return $materialResourcesCategoryArrayJSON;
    }

    /**
     * This function recover all category human resource from the database and return an array in JSON format for JS
     * @return humanResourcesCategoryArrayJSON an array in JSON format with all the data
     */
    public function getCategoryHumanResourceJSON(ManagerRegistry $doctrine)
    {
        $humanResourcesCategory = $doctrine->getRepository("App\Entity\HumanResourceCategory")->findAll();
        $humanResourcesCategoryArray = array();
        foreach($humanResourcesCategory as $humanResourceCategory)
        {
            $humanResourcesCategoryArray[] = array(
                'idcategory' => $humanResourceCategory->getId(),
                'categoryname' => (str_replace(" ", "3aZt3r", $humanResourceCategory->getCategoryname()))
            );
        }
        $humanResourcesCategoryArrayJSON = new JsonResponse($humanResourcesCategoryArray);
        return $humanResourcesCategoryArrayJSON;
    }
 

    //Part 3.2
    //This part manages the functions use for get the data in an array

    /**
     * This function recover a patient by his identifier from the database and return an array with the data
     * @return patientArray an array with all data related to it
     */
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

    /**
     * This function recover a pathway by his identifier from the database and return an array with the data
     * @return pathwayArray an array with all data related to it
     */
    public function getPathway(ManagerRegistry $doctrine, $id)
    {
        //recuperation du pathway depuis la base de données
        $pathway = $doctrine->getRepository("App\Entity\Pathway")->findOneBy(array('id' => $id));
        $pathwayArray = array();
        $idpath = $pathway->getId();
        $idpath = "pathway-" . $idpath; //formatage pour fullcalendar
        //ajout des données du pathway dans un tableau
        $pathwayArray[] = array(
            'id' => $idpath,
            'title' => (str_replace(" ", "3aZt3r", $pathway->getPathwayname()))
        );
        return $pathwayArray;
    }

    /**
     * This function is the getter of the working hours to display from the database.
     * @param ManagerRegistry $doctrine
     * @return array of the resource's data
     */
    public function getWorkingHours(ManagerRegistry $doctrine, $resource)
    {
        global $dateModified;
        $dateTimeModified = explode("T", $dateModified);
        $dayWeek = date('w', strtotime($dateTimeModified[0]));
        //recuperation du pathway depuis la base de données
        $workingHours = $doctrine->getRepository("App\Entity\WorkingHours")->findOneBy(['humanresource' => $resource, 'dayweek' => $dayWeek]);
        $workingHoursArray = array();
        if($workingHours !=null){
            $dayWorkingHours = $workingHours->getDayweek();
            //ajout des données du pathway dans un tableau
            $workingHoursArray[] = [
                'day' => $dayWorkingHours,
                'startTime' => ($workingHours->getStarttime()->format('H:i')),
                'endTime' => ($workingHours->getEndtime()->format('H:i')),
            ];
        }
        else{
            $workingHoursArray[] = [
                'day' => $dayWeek,
                'startTime' => '00:00',
                'endTime' => '00:00',
            ];
        }
        return $workingHoursArray;
    }

    /**
     * This function get the unavailabitity of the material resources.
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
            $resource = "material-" . $resource;
                $materialResourcesUnavailableArray[] = array(
                    'description' =>'Ressource Indisponible',
                    'resourceId' => ($resource),
                    'start' => ($materialResourceUnavailable->getUnavailability()->getStartdatetime()->format('Y-m-d H:i:s')),
                    'end' => ($materialResourceUnavailable->getUnavailability()->getEnddatetime()->format('Y-m-d H:i:s')),
                    'display'=>'background',
                    'color'=>'#ff0000',
                    'type'=>'unavailability'
                );
            }
        return $materialResourcesUnavailableArray;
    }
    
    
    /**
     * This function get the unavailabitity of the human resources.
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
            $resource = "human-" . $resource;
            $humanResourcesUnavailableArray[] = array(
                'description' =>'Employé Indisponible',
                'resourceId' => ($resource),
                'start' => ($humanResourceUnavailable->getUnavailability()->getStartdatetime()->format('Y-m-d H:i:s')),
                'end' => ($humanResourceUnavailable->getUnavailability()->getEnddatetime()->format('Y-m-d H:i:s')),
                'display'=>'background',
                'color'=>'#ff0000',
                'type'=>'unavailability'
            );


        }
        return $humanResourcesUnavailableArray;
    }

    /**
     * This function get all the unavailabitity of the material and human resources.
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
}
