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


/*
 * Modification planning controller
 */
class ModificationPlanningController extends AbstractController
{
    public $dateModified;

    //Part 1
    //This part manages the controller (GET and POST)

    /*
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
        $listMaterialResourceJSON = $this->getMaterialResourcesJSON($doctrine);
        $listHumanResourceJSON = $this->getHumanResourcesJSON($doctrine);

        $this->modificationAdd($dateModified, $idUser, $doctrine);

        //On redirige sur la page html modification planning et on envoie toutes les données dont on a besoin
        return $this->render('planning/modification-planning.html.twig', [
            'currentdate' => $dateModified,
            'dateFormatted' => $dateModifiedStringFormat,
            'settingsRepository' => $settingsRepository,

            'listMaterialResourceJSON' => $listMaterialResourceJSON,
            'listHumanResourceJSON' => $listHumanResourceJSON,
            'listScheduledActivitiesJSON' => $listscheduledActivityJSON,
            
        ]);
    }

    /*
     * This method recover all informations from modification planning and update database
     */
    public function modificationPlanningValidation(Request $request, AppointmentRepository $appointmentRepository, ScheduledActivityRepository $scheduledActivityRepository, HumanResourceScheduledRepository $humanResourceScheduledRepository, MaterialResourceScheduledRepository $materialResourceScheduledRepository, ManagerRegistry $doctrine, EntityManagerInterface $entityManager)
    {
        //recover all informations from twig file
        $listEvent = json_decode($request->request->get("events"));
        $listResource = json_decode($request->request->get("list-resource"));
        $dateModified=$request->request->get("validation-date");
        $listScheduledAppointments = $this->getInformationsByDateArray($doctrine, $dateModified)[0];
        $userId = $request->request->get("user-id");
        
        //update the new scheduled appointments
        foreach($listEvent as $oneEvent)
        {
            $appointment = $doctrine->getRepository("App\Entity\Appointment")->findOneBy(["id" => $oneEvent->extendedProps->appointment]);
            $appointment->setScheduled(true);
            $appointmentRepository->add($appointment, true);
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
            var_dump($event[0]->start);
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

    /*
     * This function verifies if a modification for this date is already in progress
     */
    public function getModifications(ManagerRegistry $doctrine)
    {
        $idUser = $_POST['idUser']; $dateModified = $_POST['dateModified'];

        $modificationRepository = $doctrine->getRepository("App\Entity\Modification");
        $settingsRepository = $doctrine->getRepository("App\Entity\Settings");
        
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
                'userId' => ($modification->getUser()->getId()),
                'firstname' => ($modification->getUser()->getFirstname()),
                'lastname' => ($modification->getUser()->getLastname())
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
                        return new JsonResponse($modifArray);
                    }
                } 
                else {
                    // Supprimer la modif dans BDD car trop vieille
                    $modificationRepository->remove($modification, true);
                }
            }
            $i++;
        }
        return new JsonResponse([]);
    }

    /*
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
        } else {
            //$userRepository->add($user, true);

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

    /*
     * This function delete the data modification when the user leave modification planning and then redirect to consultation planning
     */
    public function modificationDeleteOnUnload(Request $request, ManagerRegistry $doctrine, $username = '')
    {   $dateModified = $request->request->get("validation-date");
        if (isset($_GET['dateModified'])) {
            $dateModified = $_GET['dateModified'];
        }
        if(isset($_GET['id'])){
            $id = $_GET['id'];
        }
        else{
            $id = 0;
        }
        $route='/ConsultationPlanning?date='.$dateModified;
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
        return $this->redirect($route);
    }


    //Part 3
    //This part manages the functions use for the page creation

    //Part 3.1
    //This part manages the functions use for get the data in JSON format

    /*
     * This function recover all the successors from the database and return the data in a JSON format for JS
     * @return successorsArrayJSON an array in JSON format with all the data
     */
    public function getSuccessorsArray($successorsArray, ManagerRegistry $doctrine, $idactivity)
    {
        $successors = $doctrine->getRepository('App\Entity\Successor')->findBy(['activitya' => $idactivity]);
        foreach ($successors as $succesor) {
            $successorsArray[] = array(
                'id' => $succesor->getId(),
                'idactivitya' => $succesor->getActivitya()->getId(),
                'idactivityb' => $succesor->getActivityb()->getId(),
                'delaymin' => $succesor->getDelaymin(),
                'delaymax' => $succesor->getDelaymax(),
            );
        }
        return $successorsArray;
    }

    /*
     * This function recover all the activities from the database and return the data in a JSON format for JS
     * @return activitiesArrayJSON an array in JSON format with all the data
     */
    public function getActivitiesArray($activitiesArray, ManagerRegistry $doctrine, $idpathway)
    {
        $activities = $doctrine->getRepository('App\Entity\Activity')->findBy(['pathway' => $idpathway]);
        foreach ($activities as $activity) {
            $activitiesArray[] = array(
                'id' => $activity->getId(),
                'name' => (str_replace(" ", "3aZt3r", $activity->getActivityname())),
                'duration' => $activity->getDuration(),
                'idPathway' => $activity->getPathway()->getId()
            );
        }
        return $activitiesArray;
    }

    /*
     * This function recover all the appointments for a date given and return the data in a JSON format for JS
     * @return appointmentsArrayJSON an array in JSON format with all the data
     */
    public function getInformationsByDateArray(ManagerRegistry $doctrine, $date)
    {
        $date = new \DateTime(date('Y-m-d', strtotime(substr($date, 0, 10))));
        $appointments = $doctrine->getRepository("App\Entity\Appointment")->findBy(['dayappointment' => $date]);
        $appointmentsArray = array();
        $activitiesArray = array();
        $successorsArray = array();
        foreach ($appointments as $appointment) {
            $pathwayAlreadySave = false;
            if($activitiesArray != array()){
                foreach($activitiesArray as $activity){
                    if($activity["idPathway"] == $appointment->getPathway()->getId()){
                        $pathwayAlreadySave = true;
                    }
                }
            }
            if(!$pathwayAlreadySave){
                $activitiesArray = $this->getActivitiesArray($activitiesArray, $doctrine, $appointment->getPathway()->getId());
            }

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
        foreach($activitiesArray as $activity){
            $successorsArray = $this->getSuccessorsArray($successorsArray, $doctrine, $activity["id"]);
        }

        $informationsByDateArray = array(
            $appointmentsArray,
            $activitiesArray,
            $successorsArray
        );
        return $informationsByDateArray;
    }

    /*
     * This function recover all the human resources and return the data in a JSON format for JS
     * @return humanResourcesArrayJson an array in JSON format with all the data
     */
    public function getHumanResourcesJSON(ManagerRegistry $doctrine)
    {
        $humanResources = $doctrine->getRepository("App\Entity\HumanResource")
        ->findBy(array(),array("humanresourcename" => "ASC"));
        $humanResourcesArray = array();
        $categories=$this->getCategoryOfHumanResource($doctrine);
        if ($humanResources != null) {
            foreach ($humanResources as $humanResource) {
                $idResource=("human-" . str_replace(" ", "3aZt3r", $humanResource->getId()));
                
                $categoriesArray=array();
                foreach ($categories[$idResource] as $category) {
                    $categoriesArray[]=array(
                        'id' => $category["idcategory"],
                        'name' => $category["categoryname"],
                    );
                }
                $humanResourcesArray[] = array(
                    'id' => $idResource,
                    'title' => (str_replace(" ", "3aZt3r", $humanResource->getHumanresourcename())),
                    'businessHours' => ($this->getWorkingHours($doctrine, $humanResource)),
                    'categories' => $categoriesArray,
                    'type'=>1,

                );
                
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
        $humanResourcesArray[] = array(
            'id' => 'h-default',
            'title' => 'Aucune ressource',
            'categories' => array(
                array(
                    'id' => '0',
                    'name' => 'Aucune catégorie',
                ),
            ),
            'businessHours' => $workingHoursEmpty,
            'type' => 0
        );
        //Conversion des données ressources en json
        $humanResourcesArrayJson = new JsonResponse($humanResourcesArray);
        return $humanResourcesArrayJson;
    }

    /*
     * This function recover all the material resources and return the data in a JSON format for JS
     * @return materialResourcesArrayJson an array in JSON format with all the data
     */
    public function getMaterialResourcesJSON(ManagerRegistry $doctrine)
    {
        $materialResources = $doctrine->getRepository("App\Entity\MaterialResource")
        ->findBy(array(),array("materialresourcename" => "ASC"));
        $materialResourcesArray = array();
        $categories=$this->getCategoryOfMaterialResource($doctrine);
        if ($materialResources != null) {
            foreach ($materialResources as $materialResource) {
                $idResource=("material-" . str_replace(" ", "3aZt3r", $materialResource->getId()));
                $categoriesArray=array();
                foreach ($categories[$idResource] as $category) {
                    $categoriesArray[]=array(
                        'id' => $category["idcategory"],
                        'name' => $category["categoryname"],
                    );
                }
                unset($categories[$idResource]);
                $materialResourcesArray[] = array(
                    'id' =>$idResource,
                    'title' => (str_replace(" ", "3aZt3r", $materialResource->getMaterialresourcename())),
                    'categories' => $categoriesArray,
                    'type'=>1,
                );
            }
        }
        $materialResourcesArray[] = array(
            'id' => 'm-default',
            'title' => 'Aucune ressource',
            'categories' => array(
                array(
                    'id' => '0',
                    'name' => 'Aucune catégorie',
                ),
            ),
            'type' => 0
        );
        //Conversion des données ressources en json
        $materialResourcesArrayJson = new JsonResponse($materialResourcesArray);
        return $materialResourcesArrayJson;
    }

    /*
     * This function recover all the relationships between each activity and each of its associated human resources 
     * and return the data in a JSON format for JS
     * @return activitiesHumanResourcesArrayJSON an array in JSON format with all the data
     */
    public function getActivityHumanResources(ManagerRegistry $doctrine)
    {
        $activitiesHumanResources = $doctrine->getRepository('App\Entity\ActivityHumanResource')->findAll();
        $activitiesHumanResourcesArray = array();
        foreach ($activitiesHumanResources as $activityHumanResources) {
            $key=$activityHumanResources->getActivity()->getId();
            $activitiesHumanResourcesArray[$key][] = array(
                'id' => $activityHumanResources->getId(),
                'activityId' => $key,
                'humanResourceCategoryId' => $activityHumanResources->getHumanresourcecategory()->getId(),
                'quantity' => $activityHumanResources->getQuantity(),
                'name' => $activityHumanResources->getHumanresourcecategory()->getCategoryname(),
            );
        }
        
        return $activitiesHumanResourcesArray;
    }

    /*
     * This function recover all the relationships between each activity and each of its associated material resources 
     * and return the data in a JSON format for JS
     * @return activitiesMaterialResourcesArrayJSON an array in JSON format with all the data
     */
    public function getActivityMaterialResources(ManagerRegistry $doctrine)
    {
        $activitiesMaterialResources = $doctrine->getRepository("App\Entity\ActivityMaterialResource")->findAll();
        $activitiesMaterialResourcesArray = array();
        foreach ($activitiesMaterialResources as $activityMaterialResources) {
            $key=$activityMaterialResources->getActivity()->getId();
            $activitiesMaterialResourcesArray[$key][] = array(
                'id' => $activityMaterialResources->getId(),
                'activityId' => $key,
                'materialResourceCategoryId' => $activityMaterialResources->getMaterialresourcecategory()->getId(),
                'quantity' => $activityMaterialResources->getQuantity(),
                'name' => $activityMaterialResources->getMaterialresourcecategory()->getCategoryname(),
            );
        }
        return $activitiesMaterialResourcesArray;
    }

/*
     * This function recover all the relationships between each activity and each of its associated human resources 
     * and return the data in a JSON format for JS
     * @return activitiesHumanResourcesArrayJSON an array in JSON format with all the data
     */
    public function getHumanResourcesScheduled(ManagerRegistry $doctrine){
        $humanResourceScheduleds = $doctrine->getRepository('App\Entity\HumanResourceScheduled')->findAll();
        $activitiesHumanResourcesArray = array();
        foreach ($humanResourceScheduleds as $humanResourceScheduled) {
            $key=$humanResourceScheduled->getScheduledActivity()->getId();
            $activitiesHumanResourcesArray[$key][] = array(
                'id' => "human-". $humanResourceScheduled->getHumanresource()->getId(),
                'title' => $humanResourceScheduled->getHumanresource()->getHumanresourcename(),
            );
            
        }
        return $activitiesHumanResourcesArray;
    }

    /*
     * This function recover all the relationships between each activity and each of its associated material resources 
     * and return the data in a JSON format for JS
     * @return activitiesMaterialResourcesArrayJSON an array in JSON format with all the data
     */
    public function getMaterialResourcesScheduled(ManagerRegistry $doctrine){
        $materialResourceScheduleds = $doctrine->getRepository("App\Entity\MaterialResourceScheduled")->findAll();
        $activitiesMaterialResourcesArray = array();
        foreach ($materialResourceScheduleds as $materialResourceScheduled) {
            $key=$materialResourceScheduled->getScheduledActivity()->getId();
            $activitiesMaterialResourcesArray[$key][] = array(
                'id' => "material-" . $materialResourceScheduled->getMaterialresource()->getId(),
                'title' => $materialResourceScheduled->getMaterialresource()->getMaterialresourcename(),
            );
        }
        return $activitiesMaterialResourcesArray;
    }



    /*
     * This function set all data needed for create all event in fullcalendar corresponding to each scheduled activity
     * @return scheduledActivitiesArrayJSON an array in JSON format with all the data
     */
    public function getScheduledActivityJSON(ManagerRegistry $doctrine, ScheduledActivityRepository $SAR, $date)
    {
        $TodayDate = substr($date, 0, 10);


        $scheduledActivities = $SAR->findSchedulerActivitiesByDate($TodayDate);
        $activitiesMaterialResources = $this->getActivityMaterialResources($doctrine);
        $activitiesHumanResources = $this->getActivityHumanResources($doctrine);
        $materialResourcesScheduled = $this->getMaterialResourcesScheduled($doctrine);
        $humanResourcesScheduled = $this->getHumanResourcesScheduled($doctrine);
        $scheduledActivitiesArray = array();
        foreach ($scheduledActivities as $scheduledActivity) {
            $key=$scheduledActivity->getActivity()->getId();
            //Obtention du nombre de resources matérielles à renseigner pour cette activité        
            $quantityMaterialResources = 0;
            $categoryMaterialResource = array();
            if(!array_key_exists($key, $activitiesMaterialResources)){
                $categoryMaterialResource[] = array(
                    'id' => "h-default",
                    'quantity' => 0,
                    'categoryname' => "Pas de Catégorie nécessaire"
                );
                $quantityMaterialResources=1;
            }
            else{
                foreach ($activitiesMaterialResources[$key] as $activityMaterialResources) {
                    $categoryMaterialResource[] = array(
                        'id' => $activityMaterialResources["id"],
                        'quantity' => $activityMaterialResources["quantity"],
                        'categoryname' => $activityMaterialResources["name"]
                    );
                    $quantityMaterialResources = $quantityMaterialResources + $activityMaterialResources["quantity"];
                    unset($activityMaterialResources[$key]);
                }
                
            }

            //Obtention du nombre de resources Humaines à renseigner pour cette activité
            $quantityHumanResources = 0;
            $categoryHumanResource = array();
            if(!array_key_exists($key, $activitiesHumanResources)){
                $categoryHumanResource[] = array(
                    'id' => "h-default",
                    'quantity' => 0,
                    'categoryname' => "Pas de Catégorie nécessaire"
                );
                $quantityHumanResources=1;
            }
            else{
                foreach ($activitiesHumanResources[$key] as $activityHumanResources) {
                    $categoryHumanResource[] = array(
                        'id' => $activityHumanResources["id"],
                        'quantity' => $activityHumanResources["quantity"],
                        'categoryname' => $activityHumanResources["name"]
                    );
                    $quantityHumanResources = $quantityHumanResources + $activityHumanResources["quantity"];
                }
            }
            
            //Tableau contenant toutes les ressources déja plannifiées pour une activité
            $scheduledActivitiesResourcesArray = array();
            //Recherche des ressources Humaines déja plannifiées 
            $key=$scheduledActivity->getId();
            $scheduledActivitesHumanResourcesArray = array();
            if(array_key_exists($key, $humanResourcesScheduled)){
            foreach ($humanResourcesScheduled[$key] as $humanResourceScheduled) {
                $scheduledActivitesHumanResourcesArray[] = array(
                    'id' =>$humanResourceScheduled["id"],
                    'title' => $humanResourceScheduled["title"],
                );
                $quantityHumanResources = $quantityHumanResources - 1;
                array_push($scheduledActivitiesResourcesArray, $humanResourceScheduled["id"]);
            }
        }
            //Recherche des ressources matérielles déjà plannifiées
            $scheduledActivitiesMaterialResourceArray = array();
            if(array_key_exists($key, $materialResourcesScheduled)){
            foreach ($materialResourcesScheduled[$key] as $materialResourceScheduled) {
                $scheduledActivitiesMaterialResourceArray[] = array(
                    'id' => $materialResourceScheduled["id"],
                    'title' => $materialResourceScheduled["title"],
                );
                $quantityMaterialResources = $quantityMaterialResources - 1;
                array_push($scheduledActivitiesResourcesArray, $materialResourceScheduled["id"]);
            }
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
                'idPatient'=>$scheduledActivity->getAppointment()->getPatient()->getId(),
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

    /*
     * This function recover all category of material resource from the database and return an array in JSON format for JS
     * @return categoryOfMaterialResourceArrayJSON an array in JSON format with all the data
     */
    public function getCategoryOfMaterialResource(ManagerRegistry $doctrine)
    {
        $categoryOfMaterialResources = $doctrine->getRepository("App\Entity\CategoryOfMaterialResource")->findAll();
        $categoryOfMaterialResourceArray = array();
        foreach($categoryOfMaterialResources as $categoryOfMaterialResource)
        {
            $key='material-' . $categoryOfMaterialResource->getMaterialresource()->getId();
            $categoryOfMaterialResourceArray[$key][] = array(
                'idcategory' => $categoryOfMaterialResource->getMaterialresourcecategory()->getId(),
                'idresource' => $key,
                'categoryname' => (str_replace(" ", "3aZt3r", $categoryOfMaterialResource->getMaterialresourcecategory()->getCategoryname())),
                'resourcename' => (str_replace(" ", "3aZt3r", $categoryOfMaterialResource->getMaterialresource()->getMaterialresourcename()))
            );
        }
        return $categoryOfMaterialResourceArray;
    }

    /*
     * This function recover all category of human resource from the database and return an array in JSON format for JS
     * @return categoryOfHumanResourceArrayJSON an array in JSON format with all the data
     */
    public function getCategoryOfHumanResource(ManagerRegistry $doctrine)
    {
        $categoryOfHumanResources = $doctrine->getRepository("App\Entity\CategoryOfHumanResource")->findAll();
        $categoryOfHumanResourceArray = array();
        foreach($categoryOfHumanResources as $categoryOfHumanResource)
        {
            $key='human-' . $categoryOfHumanResource->getHumanresource()->getId();
            $categoryOfHumanResourceArray[$key][] = array(
                'idcategory' => $categoryOfHumanResource->getHumanresourcecategory()->getId(),
                'idresource' => $key,
                'categoryname' => (str_replace(" ", "3aZt3r", $categoryOfHumanResource->getHumanresourcecategory()->getCategoryname())),
                'resourcename' => (str_replace(" ", "3aZt3r", $categoryOfHumanResource->getHumanresource()->getHumanresourcename()))
            );
        }
        return $categoryOfHumanResourceArray;
    }

    /*
     * This function recover all category material resource from the database and return an array in JSON format for JS
     * @return materialResourcesCategoryArrayJSON an array in JSON format with all the data
     */
    public function getCategoryMaterialResource(ManagerRegistry $doctrine)
    {
        $materialResourcesCategory = $doctrine->getRepository("App\Entity\MaterialResourceCategory")->findAll();
        $materialResourcesCategoryArray = array();
        foreach($materialResourcesCategory as $materialResourceCategory)
        {
            $materialResourcesCategoryArray[] = array(
                'idcategory' => $materialResourceCategory->getId(),
                'categoryname' => (str_replace(" ", "3aZt3r", $materialResourceCategory->getCategoryname())),
                'resources'=>[]
            );
        }
        return $materialResourcesCategoryArray;
    }

    /*
     * This function recover all category human resource from the database and return an array in JSON format for JS
     * @return humanResourcesCategoryArrayJSON an array in JSON format with all the data
     */
    public function getCategoryHumanResource(ManagerRegistry $doctrine)
    {
        $humanResourcesCategory = $doctrine->getRepository("App\Entity\HumanResourceCategory")->findAll();
        $humanResourcesCategoryArray = array();
        foreach($humanResourcesCategory as $humanResourceCategory)
        {
            $humanResourcesCategoryArray[] = array(
                'idcategory' => $humanResourceCategory->getId(),
                'categoryname' => (str_replace(" ", "3aZt3r", $humanResourceCategory->getCategoryname())),
                'resources'=>[]
            );
        }
        return $humanResourcesCategoryArray;
    }
 

    //Part 3.2
    //This part manages the functions use for get the data in an array

    /*
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

    /*
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

    /*
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

    /*
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
    
    
    /*
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

    /*
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

    public function GetAddPlanning(ManagerRegistry $doctrine){
        $dateModified = $_POST['dateModified'];
        return new JsonResponse($this->getInformationsByDateArray($doctrine, $dateModified));
    }

    public function GetAutoAddInfos(ManagerRegistry $doctrine){
        $categoryMaterialResource=$this->getCategoryMaterialResource($doctrine);
        $categoryHumanResource=$this->getCategoryHumanResource($doctrine);
        $listActivityHumanResources = $this->getActivityHumanResources($doctrine);
        $listActivityMaterialResources = $this->getActivityMaterialResources($doctrine);
        $categoryOfHumanResource = $this->getCategoryOfHumanResource($doctrine);
        $categoryOfMaterialResource = $this->getCategoryOfMaterialResource($doctrine); 
        $data=array(
            'categoryMaterialResource'=>$categoryMaterialResource,
            'categoryHumanResource'=>$categoryHumanResource,
            'listeActivityHumanResources'=>$listActivityHumanResources,
            'listeActivityMaterialResources'=>$listActivityMaterialResources,
            'categoryOfHumanResource'=>$categoryOfHumanResource,
            'categoryOfMaterialResource'=>$categoryOfMaterialResource
        );
        return new JsonResponse($data);
    }

    public function GetErrorsInfos(ManagerRegistry $doctrine){
        $dateModified = $_POST['dateModified'];
        $categoryMaterialResource=$this->getCategoryMaterialResource($doctrine);
        $categoryHumanResource=$this->getCategoryHumanResource($doctrine);
        $listActivityHumanResources = $this->getActivityHumanResources($doctrine);
        $listActivityMaterialResources = $this->getActivityMaterialResources($doctrine);
        $categoryOfHumanResource = $this->getCategoryOfHumanResource($doctrine);
        $categoryOfMaterialResource = $this->getCategoryOfMaterialResource($doctrine); 
        $InfosByDate=$this->getInformationsByDateArray($doctrine, $dateModified);
        $data=array(
            'categoryMaterialResource'=>$categoryMaterialResource,
            'categoryHumanResource'=>$categoryHumanResource,
            'listeActivityHumanResources'=>$listActivityHumanResources,
            'listeActivityMaterialResources'=>$listActivityMaterialResources,
            'categoryOfHumanResource'=>$categoryOfHumanResource,
            'categoryOfMaterialResource'=>$categoryOfMaterialResource,
            'listeAppointments'=>$InfosByDate[0],
            'listeActivity'=>$InfosByDate[1],
            'listeSuccessors'=>$InfosByDate[2],
        );
        return new JsonResponse($data);
    }
}
