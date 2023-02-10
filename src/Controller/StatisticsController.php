<?php

namespace App\Controller;

use App\Repository\ScheduledActivityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;


class StatisticsController extends AbstractController
{

    public $date;
    public $dateFormatted;
    /*
     * @brief Allows to get stats
     * @param ManagerRegistry $doctrine
     * @param ScheduledActivityRepository $SAR
     * @return Response
     */
    public function index(ManagerRegistry $doctrine, ScheduledActivityRepository $SAR): Response
    {
        global $date;
        global $dateFormatted;
        $date = date(('Y-m-d'));
        if (isset($_GET["date"])) {
            $date = $_GET["date"];
            $date = str_replace('T12:00:00', '', $date);
        }
        $dateFormatted = date_create($date);
        $dateFormatted->format('Y-F-d');
        //get data for stats
        $getDisplayedActivitiesJSON = $this->getDisplayedActivitiesJSON($doctrine, $SAR);
        $humanResourcesSheduledJSON = $this->getHumanResourceScheduledJSON($doctrine);
        $materialResourcesSheduledJSON = $this->getMaterialResourceScheduledJSON($doctrine);
        $appointmentsJSON = $this->getAppointmentsJSON($doctrine);
        $occupancyRates = $this->getOccupancyRates($doctrine);
        //render the view
        return $this->render('statistics/index.html.twig', [
            'controller_name' => 'StatisticsController',
            'currentdate' => $date,
            'getHumanResourceScheduledJSON' => $humanResourcesSheduledJSON,
            'getMaterialResourceScheduledJSON' => $materialResourcesSheduledJSON,
            'getAppointmentsJSON' => $appointmentsJSON,
            'getDisplayedActivitiesJSON' => $getDisplayedActivitiesJSON,
            'occupancyRates' => $occupancyRates,
        ]);
    }

    /*
     * @brief Allows to get the resouces scheduled for a given activity
     * @param ManagerRegistry $doctrine
     * @param ScheduledActivityRepository $SAR
     * @return JsonResponse
     */
    public function getResourcesScheduled(ManagerRegistry $doctrine, $displayedActivity)
    {
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
        if ($MaterialResourceScheduleds != null) {
            foreach ($MaterialResourceScheduleds as $MaterialResourceScheduled) {
                $id = $MaterialResourceScheduled->getMaterialresource()->getId();
                $id = "materialresource_" . $id;
                $MaterialResourceScheduledArray[] = array(
                    $id,
                    $MaterialResourceScheduled->getMaterialresource()->getMaterialresourcename(),
                );
            }
        } else if ($MaterialResourceScheduleds == null) {
            $MaterialResourceScheduledArray[] = array(
                "materialresource_noResource",
                "Pas de ressource allouée"
            );
        }
        if ($humanResourceScheduleds != null) {
            foreach ($humanResourceScheduleds as $humanResourceScheduled) {
                $id = $humanResourceScheduled->getHumanresource()->getId();
                $id = "humanresource_" . $id;
                $HumanResourceScheduledArray[] = array(
                    $id,
                    $humanResourceScheduled->getHumanresource()->getHumanresourcename(),
                );
            }
        } else {
            $HumanResourceScheduledArray[] = array(
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
        if (count($MaterialResourceScheduledArray) > 0) {
            $resourceArray[1] = [];
            for ($i = 1; $i < count($MaterialResourceScheduledArray); $i++) {
                array_push($resourceArray[1], $MaterialResourceScheduledArray[$i][1]);
            }
        } else {
            $resourceArray[1] = "Aucune Ressource Matérielle";
        }
        if (count($HumanResourceScheduledArray) > 0) {
            $resourceArray[2] = [];
            for ($i = 1; $i < count($HumanResourceScheduledArray); $i++) {
                array_push($resourceArray[2], $HumanResourceScheduledArray[$i][1]);
            }
        } else {
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
            $resourceArray = $this->getResourcesScheduled($doctrine, $displayedActivity);
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
        //Conversion des données ressources en json
        $displayedActivitiesArrayJSON = new JsonResponse($displayedActivitiesArray);
        return $displayedActivitiesArrayJSON;
    }

    /*
     * @brief This function is the getter of the Human Resources from the database.
     * @param ManagerRegistry $doctrine
     * @return array all the data of the human resources to display
     */
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
                    if (!in_array($HumanResourceScheduled->getHumanresource()->getId(), $arrayId)) {
                        $categories = $doctrine->getRepository("App\Entity\CategoryOfHumanResource")->findBy(array('humanresource' => $HumanResourceScheduled->getHumanresource()));
                        $categoriesArray = array();
                        foreach ($categories as $category) {
                            $categoriesArray[] = array(
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
                            'businessHours' => ($this->getWorkingHours($doctrine, $HumanResourceScheduled->getHumanresource())),
                            'type' => 0
                        );
                        unset($HumanResourceArray);
                        $arrayId[] = $HumanResourceScheduled->getHumanresource()->getId();
                    }
                }
            }
        }
        $workingHoursEmpty = array();
        for ($i = 0; $i < 7; $i++) {
            $workingHoursEmpty[] = array(
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
            'businessHours' => $workingHoursEmpty,
            'type' => 1
        );
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
     * @brief This function is the getter of the material resources to display from the database.
     * @param ManagerRegistry $doctrine
     * @return array of the resource's data
     */
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
                    if (!in_array($MaterialResourceScheduled->getMAterialresource()->getId(), $arrayId)) {
                        $materialCategories = $doctrine->getRepository("App\Entity\CategoryOfMaterialResource")->findBy(array('materialresource' => $MaterialResourceScheduled->getMaterialresource()));
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
                        $arrayId[] = $MaterialResourceScheduled->getMaterialresource()->getId();
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
        unset($materialCategoryArray);
        //Conversion des données ressources en json
        $MaterialResourceScheduledArrayJSON = new JsonResponse($MaterialResourceScheduledArray);
        return $MaterialResourceScheduledArrayJSON;
    }

    /*
     * @brief This function is the getter of the appointments to display from the database.
     * @param ManagerRegistry $doctrine
     * @return array of the resource's data
     */
    public function getAppointmentsJSON(ManagerRegistry $doctrine)
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
            $appointmentArray[] = array(
                'id' => (str_replace(" ", "3aZt3r", $appointment->getId())),
                'day' => ($appointment->getDayappointment()->format('Y-m-d')),
                'earliestappointmenttime' => ($appointment->getEarliestappointmenttime()),
                'latestappointmenttime' => ($appointment->getLatestappointmenttime()),
                'scheduled' => $appointment->isScheduled(),

            );
        }
        //Conversion des données ressources en json
        $appointmentArrayJSON = new JsonResponse($appointmentArray);
        return $appointmentArrayJSON;
    }

    /*
     * @brief This function is the getter of the occupancy rates to display from the database.
     * @param ManagerRegistry $doctrine
     * @return array of the resource's data
     */
    public function getOccupancyRates(ManagerRegistry $doctrine)
    {
        global $displayedActivities;
        $dateStr = "1970-01-01";
        $humanResources = array();
        $materialResources = array();
        //We create the dates of the slots
        $sixam = new \DateTime($dateStr . '06:00:00');
        $nineam = new \DateTime($dateStr . '09:00:00');
        $twelvepm = new \DateTime($dateStr . '12:00:00');
        $threepm = new \DateTime($dateStr . '15:00:00');
        $sixpm = new \DateTime($dateStr . '18:00:00');
        $ninepm = new \DateTime($dateStr . '21:00:00');
        $humanId = array();
        $materialId = array();
        $humanCategoriesId = array();
        $materialCategoriesId = array();
        $humanCategories = array();
        $materialCategories = array();
        $occupancyArray = array();
        //We create the array of the slots
        $occupancyArray[0] = array(
            'creneau' => '6h-9h',
            'occupancy' => 0
        );
        $occupancyArray[1] = array(
            'creneau' => '9h-12h',
            'occupancy' => 0
        );
        $occupancyArray[2] = array(
            'creneau' => '12h-15h',
            'occupancy' => 0
        );
        $occupancyArray[3] = array(
            'creneau' => '15h-18h',
            'occupancy' => 0
        );
        $occupancyArray[4] = array(
            'creneau' => '18h-21h',
            'occupancy' => 0
        );
        if (sizeof($displayedActivities) > 0) { //If there is at least one activity
            foreach ($displayedActivities as $displayedActivity) { //We go through the activities
                $humanResourcesByActivity = array();
                $materialResourcesByActivity = array();
                $HumanResourceScheduleds = $doctrine->getRepository("App\Entity\HumanResourceScheduled")->findBy(array("scheduledactivity" => $displayedActivity)); //We get the human resources of the activity
                foreach ($HumanResourceScheduleds as $HumanResourceScheduled) { //We go through the human resources
                    $humanResourcesByActivity[$displayedActivity->getId()] = $HumanResourceScheduled->getHumanresource();
                    if (!in_array($HumanResourceScheduled->getHumanresource()->getId(), $humanId)) { //If the human resource is not in the array
                        $categories = $doctrine->getRepository("App\Entity\CategoryOfHumanResource")->findBy(array('humanresource' => $HumanResourceScheduled->getHumanresource())); //We get the categories of the human resource
                        $categoriesArray = array();
                        foreach ($categories as $category) {
                            if (!in_array($category->getHumanResourceCategory()->getId(), $humanCategoriesId)) { //If the category is not in the array
                                $humanCategoriesId[] = $category->getHumanResourceCategory()->getId();
                                //We add the category in the array
                                $categoriesArray[] = array(
                                    'id' => $category->getId(),
                                    'name' => $category->getHumanResourceCategory()->getCategoryname()
                                );
                                //We add the category in the array of the categories
                                $humanCategories[] = array(
                                    'id' => $category->getHumanResourceCategory()->getId(),
                                    'title' => $category->getHumanResourceCategory()->getCategoryname(),
                                    'occupancies' => $occupancyArray,
                                    'numberOfResources' => 1
                                );
                            }
                            //If the category is in the array
                            else {
                                $id = array_search($category->getHumanResourceCategory()->getId(), array_column($humanCategories, 'id'));
                                $humanCategories[$id]['numberOfResources']++;
                            }
                        }
                        //We add the human resource in the array
                        $id = $HumanResourceScheduled->getHumanresource()->getId();
                        $id = "humanresource_" . $id;
                        $humanResources[$HumanResourceScheduled->getHumanresource()->getId()] = array(
                            'id' => $id,
                            'title' => ($HumanResourceScheduled->getHumanresource()->getHumanresourcename()),
                            'categories' => $categoriesArray,
                            'businessHours' => ($this->getWorkingHours($doctrine, $HumanResourceScheduled->getHumanresource())),
                            'type' => 0,
                            'occupancies' => $occupancyArray,
                            'numberOfResources' => 1
                        );
                        unset($HumanResourceArray);//We delete the array of the human resources
                        $humanId[] = $HumanResourceScheduled->getHumanresource()->getId();//We add the id of the human resource in the array of the ids
                    }
                }
                //We do the same thing for the material resources
                $MaterialResourceScheduleds = $doctrine->getRepository("App\Entity\MaterialResourceScheduled")->findBy(array("scheduledactivity" => $displayedActivity));
                foreach ($MaterialResourceScheduleds as $MaterialResourceScheduled) {
                    $materialResourcesByActivity[$displayedActivity->getId()] = $MaterialResourceScheduled->getMaterialresource();
                    if (!in_array($MaterialResourceScheduled->getMaterialresource()->getId(), $materialId)) {
                        $categories = $doctrine->getRepository("App\Entity\CategoryOfMaterialResource")->findBy(array('materialresource' => $MaterialResourceScheduled->getMaterialresource()));
                        $categoriesArray = array();
                        foreach ($categories as $category) {
                            if (!in_array($category->getMaterialResourceCategory()->getId(), $materialCategoriesId)) {
                                $materialCategoriesId[] = $category->getMaterialResourceCategory()->getId();
                                $categoriesArray[] = array(
                                    'id' => $category->getId(),
                                    'name' => $category->getMaterialResourceCategory()->getCategoryname()
                                );
                                $materialCategories[] = array(
                                    'id' => $category->getMaterialResourceCategory()->getId(),
                                    'title' => $category->getMaterialResourceCategory()->getCategoryname(),
                                    'occupancies' => $occupancyArray,
                                    'numberOfResources' => 1
                                );
                            } else {
                                $id = array_search($category->getMaterialResourceCategory()->getId(), array_column($materialCategories, 'id'));
                                $materialCategories[$id]['numberOfResources']++;
                            }
                        }
                        $id = $MaterialResourceScheduled->getMaterialresource()->getId();
                        $id = "materialresource_" . $id;
                        $materialResources[$MaterialResourceScheduled->getMaterialresource()->getId()] = array(
                            'id' => $id,
                            'title' => ($MaterialResourceScheduled->getMaterialresource()->getMaterialresourcename()),
                            'categories' => $categoriesArray,
                            'type' => 1,
                            'occupancies' => $occupancyArray,
                            'numberOfResources' => 1
                        );
                        unset($MaterialResourceArray);
                        $materialId[] = $MaterialResourceScheduled->getMaterialresource()->getId();
                    }
                }
                /* BEGINNING OF THE SLOT CALCULATION */
                if ($displayedActivity->getStarttime() >= $sixam && $displayedActivity->getEndtime() <= $nineam) {//If the activity starts before 9am and ends before 9am we add the duration to the first slot
                    //var_dump($displayedActivity->getStarttime());
                    $duration = $displayedActivity->getEndtime()->diff($displayedActivity->getStarttime());
                    foreach ($humanResourcesByActivity as $humanResource) {//We add the duration to the occupancy of the human resource
                        $humanResources[$humanResource->getId()]['occupancies'][0]['occupancy'] += ($duration->i + $duration->h * 60);
                        foreach ($humanResources[$humanResource->getId()]['categories'] as $category) {//We add the duration to the occupancy of the category of the human resource
                            for ($i = 0; $i < sizeof($humanCategories); $i++) {
                                if ($humanCategories[$i]["title"] == $category["name"]) {
                                    $humanCategories[$i]["occupancies"][0]['occupancy'] += ($duration->i + $duration->h * 60);
                                }
                            }
                        }
                    }
                    foreach ($materialResourcesByActivity as $materialResource) {//We add the duration to the occupancy of the material resource
                        $materialResources[$materialResource->getId()]['occupancies'][0]['occupancy'] += ($duration->i + $duration->h * 60);
                        foreach ($materialResources[$materialResource->getId()]['categories'] as $category) {//We add the duration to the occupancy of the category of the material resource
                            for ($i = 0; $i < sizeof($materialCategories); $i++) {
                                if ($materialCategories[$i]["title"] == $category["name"]) {
                                    $materialCategories[$i]["occupancies"][0]['occupancy'] += ($duration->i + $duration->h * 60);
                                }
                            }
                        }
                    }
                } 
                else if ($displayedActivity->getStarttime() >= $nineam && $displayedActivity->getEndtime() <= $twelvepm) {//If the activity starts before 12pm and ends before 12pm we add the duration to the second slot
                    $duration = $displayedActivity->getEndtime()->diff($displayedActivity->getStarttime());
                    foreach ($humanResourcesByActivity as $humanResource) {//We add the duration to the occupancy of the human resource
                        $humanResources[$humanResource->getId()]['occupancies'][1]['occupancy'] += ($duration->i + $duration->h * 60);
                        foreach ($humanResources[$humanResource->getId()]['categories'] as $category) {//We add the duration to the occupancy of the category of the human resource
                            for ($i = 0; $i < sizeof($humanCategories); $i++) {
                                if ($humanCategories[$i]["title"] == $category["name"]) {
                                    $humanCategories[$i]["occupancies"][1]['occupancy'] += ($duration->i + $duration->h * 60);
                                }
                            }
                        }
                    }
                    foreach ($materialResourcesByActivity as $materialResource) {//We add the duration to the occupancy of the material resource
                        $materialResources[$materialResource->getId()]['occupancies'][1]['occupancy'] += ($duration->i + $duration->h * 60);
                        foreach ($materialResources[$materialResource->getId()]['categories'] as $category) {
                            for ($i = 0; $i < sizeof($materialCategories); $i++) {//We add the duration to the occupancy of the category of the material resource
                                if ($materialCategories[$i]["title"] == $category["name"]) {
                                    $materialCategories[$i]["occupancies"][1]['occupancy'] += ($duration->i + $duration->h * 60);
                                }
                            }
                        }
                    }
                } 
                else if ($displayedActivity->getStarttime() >= $twelvepm && $displayedActivity->getEndtime() <= $threepm) {//If the activity starts before 3pm and ends before 3pm we add the duration to the third slot
                    $duration = $displayedActivity->getEndtime()->diff($displayedActivity->getStarttime());
                    foreach ($humanResourcesByActivity as $humanResource) {//We add the duration to the occupancy of the human resource
                        $humanResources[$humanResource->getId()]['occupancies'][2]['occupancy'] += ($duration->i + $duration->h * 60);
                        foreach ($humanResources[$humanResource->getId()]['categories'] as $category) {//We add the duration to the occupancy of the category of the human resource
                            for ($i = 0; $i < sizeof($humanCategories); $i++) {
                                if ($humanCategories[$i]["title"] == $category["name"]) {
                                    $humanCategories[$i]["occupancies"][2]['occupancy'] += ($duration->i + $duration->h * 60);
                                }
                            }
                        }
                    }
                    foreach ($materialResourcesByActivity as $materialResource) {//We add the duration to the occupancy of the material resource
                        $materialResources[$materialResource->getId()]['occupancies'][2]['occupancy'] += ($duration->i + $duration->h * 60);
                        foreach ($materialResources[$materialResource->getId()]['categories'] as $category) {//We add the duration to the occupancy of the category of the material resource
                            for ($i = 0; $i < sizeof($materialCategories); $i++) {
                                if ($materialCategories[$i]["title"] == $category["name"]) {
                                    $materialCategories[$i]["occupancies"][2]['occupancy'] += ($duration->i + $duration->h * 60);
                                }
                            }
                        }
                    }
                } 
                else if ($displayedActivity->getStarttime() >= $threepm && $displayedActivity->getEndtime() <= $sixpm) {//If the activity starts before 6pm and ends before 6pm we add the duration to the fourth slot
                    $duration = $displayedActivity->getEndtime()->diff($displayedActivity->getStarttime());
                    foreach ($humanResourcesByActivity as $humanResource) {//We add the duration to the occupancy of the human resource
                        $humanResources[$humanResource->getId()]['occupancies'][3]['occupancy'] += ($duration->i + $duration->h * 60);
                        foreach ($humanResources[$humanResource->getId()]['categories'] as $category) {//We add the duration to the occupancy of the category of the human resource
                            for ($i = 0; $i < sizeof($humanCategories); $i++) {
                                if ($humanCategories[$i]["title"] == $category["name"]) {
                                    $humanCategories[$i]["occupancies"][3]['occupancy'] += ($duration->i + $duration->h * 60);
                                }
                            }
                        }
                    }
                    foreach ($materialResourcesByActivity as $materialResource) {//We add the duration to the occupancy of the material resource
                        $materialResources[$materialResource->getId()]['occupancies'][3]['occupancy'] += ($duration->i + $duration->h * 60);
                        foreach ($materialResources[$materialResource->getId()]['categories'] as $category) {//We add the duration to the occupancy of the category of the material resource
                            for ($i = 0; $i < sizeof($materialCategories); $i++) {
                                if ($materialCategories[$i]["title"] == $category["name"]) {
                                    $materialCategories[$i]["occupancies"][3]['occupancy'] += ($duration->i + $duration->h * 60);
                                }
                            }
                        }
                    }
                } 
                else if ($displayedActivity->getStarttime() >= $sixpm && $displayedActivity->getEndtime() <= $ninepm) {//If the activity starts before 9pm and ends before 9pm we add the duration to the fifth slot
                    $duration = $displayedActivity->getEndtime()->diff($displayedActivity->getStarttime());
                    foreach ($humanResourcesByActivity as $humanResource) {//We add the duration to the occupancy of the human resource
                        $humanResources[$humanResource->getId()]['occupancies'][4]['occupancy'] += ($duration->i + $duration->h * 60);
                        foreach ($humanResources[$humanResource->getId()]['categories'] as $category) {//We add the duration to the occupancy of the category of the human resource
                            for ($i = 0; $i < sizeof($humanCategories); $i++) {
                                if ($humanCategories[$i]["title"] == $category["name"]) {
                                    $humanCategories[$i]["occupancies"][4]['occupancy'] += ($duration->i + $duration->h * 60);
                                }
                            }
                        }
                    }
                    foreach ($materialResourcesByActivity as $materialResource) {//We add the duration to the occupancy of the material resource
                        $materialResources[$materialResource->getId()]['occupancies'][4]['occupancy'] += ($duration->i + $duration->h * 60);
                        foreach ($materialResources[$materialResource->getId()]['categories'] as $category) {//We add the duration to the occupancy of the category of the material resource
                            for ($i = 0; $i < sizeof($materialCategories); $i++) {
                                if ($materialCategories[$i]["title"] == $category["name"]) {
                                    $materialCategories[$i]["occupancies"][4]['occupancy'] += ($duration->i + $duration->h * 60);
                                }
                            }
                        }
                    }
                } 
                else if ($displayedActivity->getStarttime() >= $sixam && $displayedActivity->getStarttime() < $nineam && $displayedActivity->getEndtime() > $nineam) {//If the activity starts before 9am and ends after 9am we add the duration to the first and second slot
                    $duration = $displayedActivity->getStartTime()->diff($nineam);//We calculate the duration of the activity before 9am
                    $durationbis = $nineam->diff($displayedActivity->getEndTime());//We calculate the duration of the activity after 9am
                    foreach ($humanResourcesByActivity as $humanResource) {//We add the duration to the occupancy of the human resource
                        $humanResources[$humanResource->getId()]['occupancies'][0]['occupancy'] += ($duration->i + $duration->h * 60);                        
                        $humanResources[$humanResource->getId()]['occupancies'][1]['occupancy'] += ($durationbis->i + $durationbis->h * 60);
                        foreach ($humanResources[$humanResource->getId()]['categories'] as $category) {//We add the duration to the occupancy of the category of the human resource
                            for ($i = 0; $i < sizeof($humanCategories); $i++) {
                                if ($humanCategories[$i]["title"] == $category["name"]) {
                                    $humanCategories[$i]["occupancies"][0]['occupancy'] += ($duration->i + $duration->h * 60);
                                    $humanCategories[$i]["occupancies"][1]['occupancy'] += ($durationbis->i + $durationbis->h * 60);
                                }
                            }
                        }
                    }
                    foreach ($materialResourcesByActivity as $materialResource) {//We add the duration to the occupancy of the material resource
                        $materialResources[$materialResource->getId()]['occupancies'][0]['occupancy'] += ($duration->i + $duration->h * 60);
                        $materialResources[$materialResource->getId()]['occupancies'][1]['occupancy'] += ($durationbis->i + $durationbis->h * 60);
                        foreach ($materialResources[$materialResource->getId()]['categories'] as $category) {//We add the duration to the occupancy of the category of the material resource
                            for ($i = 0; $i < sizeof($materialCategories); $i++) {
                                if ($materialCategories[$i]["title"] == $category["name"]) {
                                    $materialCategories[$i]["occupancies"][0]['occupancy'] += ($duration->i + $duration->h * 60);
                                    $materialCategories[$i]["occupancies"][1]['occupancy'] += ($durationbis->i + $durationbis->h * 60);
                                }
                            }
                        }
                    }
                } 
                else if ($displayedActivity->getStarttime() >= $nineam && $displayedActivity->getStarttime() < $twelvepm && $displayedActivity->getEndtime() > $twelvepm) {//If the activity starts before 12pm and ends after 12pm we add the duration to the second and third slot
                    $duration = $displayedActivity->getStartTime()->diff($twelvepm);//We calculate the duration of the activity before 12pm
                    $durationbis = $twelvepm->diff($displayedActivity->getEndTime());//We calculate the duration of the activity after 12pm
                    foreach ($humanResourcesByActivity as $humanResource) {
                        $humanResources[$humanResource->getId()]['occupancies'][1]['occupancy'] += ($duration->i + $duration->h * 60);
                        $humanResources[$humanResource->getId()]['occupancies'][2]['occupancy'] += ($durationbis->i + $durationbis->h * 60);
                        foreach ($humanResources[$humanResource->getId()]['categories'] as $category) {//We add the duration to the occupancy of the category of the human resource
                            for ($i = 0; $i < sizeof($humanCategories); $i++) {
                                if ($humanCategories[$i]["title"] == $category["name"]) {
                                    $humanCategories[$i]["occupancies"][1]['occupancy'] += ($duration->i + $duration->h * 60);
                                    $humanCategories[$i]["occupancies"][2]['occupancy'] += ($durationbis->i + $durationbis->h * 60);
                                }
                            }
                        }
                    }
                    foreach ($materialResourcesByActivity as $materialResource) {//We add the duration to the occupancy of the material resource
                        $materialResources[$materialResource->getId()]['occupancies'][1]['occupancy'] += ($duration->i + $duration->h * 60);
                        $materialResources[$materialResource->getId()]['occupancies'][2]['occupancy'] += ($durationbis->i + $durationbis->h * 60);
                        foreach ($materialResources[$materialResource->getId()]['categories'] as $category) {//We add the duration to the occupancy of the category of the material resource
                            for ($i = 0; $i < sizeof($materialCategories); $i++) {
                                if ($materialCategories[$i]["title"] == $category["name"]) {
                                    $materialCategories[$i]["occupancies"][1]['occupancy'] += ($duration->i + $duration->h * 60);
                                    $materialCategories[$i]["occupancies"][2]['occupancy'] += ($durationbis->i + $durationbis->h * 60);
                                }
                            }
                        }
                    }
                } 
                else if ($displayedActivity->getStarttime() >= $twelvepm && $displayedActivity->getStarttime() < $threepm && $displayedActivity->getEndtime() > $threepm) {//If the activity starts before 3pm and ends after 3pm we add the duration to the third and fourth slot
                    $duration = $displayedActivity->getStartTime()->diff($threepm);//We calculate the duration of the activity before 3pm
                    $durationbis = $threepm->diff($displayedActivity->getEndTime());//We calculate the duration of the activity after 3pm
                    foreach ($humanResourcesByActivity as $humanResource) {//We add the duration to the occupancy of the human resource
                        $humanResources[$humanResource->getId()]['occupancies'][2]['occupancy'] += ($duration->i + $duration->h * 60);
                        $humanResources[$humanResource->getId()]['occupancies'][3]['occupancy'] += ($durationbis->i + $durationbis->h * 60);
                        foreach ($humanResources[$humanResource->getId()]['categories'] as $category) {//We add the duration to the occupancy of the category of the human resource
                            for ($i = 0; $i < sizeof($humanCategories); $i++) {
                                if ($humanCategories[$i]["title"] == $category["name"]) {
                                    $humanCategories[$i]["occupancies"][2]['occupancy'] += ($duration->i + $duration->h * 60);
                                    $humanCategories[$i]["occupancies"][3]['occupancy'] += ($durationbis->i + $durationbis->h * 60);
                                }
                            }
                        }
                    }
                    foreach ($materialResourcesByActivity as $materialResource) {//We add the duration to the occupancy of the material resource
                        $materialResources[$materialResource->getId()]['occupancies'][2]['occupancy'] += ($duration->i + $duration->h * 60);
                        $materialResources[$materialResource->getId()]['occupancies'][3]['occupancy'] += ($durationbis->i + $durationbis->h * 60);
                        foreach ($materialResources[$materialResource->getId()]['categories'] as $category) {//We add the duration to the occupancy of the category of the material resource
                            for ($i = 0; $i < sizeof($materialCategories); $i++) {
                                if ($materialCategories[$i]["title"] == $category["name"]) {
                                    $materialCategories[$i]["occupancies"][2]['occupancy'] += ($duration->i + $duration->h * 60);
                                    $materialCategories[$i]["occupancies"][3]['occupancy'] += ($durationbis->i + $durationbis->h * 60);
                                }
                            }
                        }
                    }
                } 
                else if ($displayedActivity->getStarttime() >= $threepm && $displayedActivity->getStarttime() < $sixpm && $displayedActivity->getEndtime() > $sixpm) {//If the activity starts before 6pm and ends after 6pm we add the duration to the fourth and fifth slot
                    $duration = $displayedActivity->getStartTime()->diff($sixpm);//We calculate the duration of the activity before 6pm
                    $durationbis = $sixpm->diff($displayedActivity->getEndTime());//We calculate the duration of the activity after 6pm
                    foreach ($humanResourcesByActivity as $humanResource) {//We add the duration to the occupancy of the human resource
                        $humanResources[$humanResource->getId()]['occupancies'][3]['occupancy'] += ($duration->i + $duration->h * 60);
                        $humanResources[$humanResource->getId()]['occupancies'][4]['occupancy'] += ($durationbis->i + $durationbis->h * 60);
                        foreach ($humanResources[$humanResource->getId()]['categories'] as $category) {//We add the duration to the occupancy of the category of the human resource
                            for ($i = 0; $i < sizeof($humanCategories); $i++) {
                                if ($humanCategories[$i]["title"] == $category["name"]) {
                                    $humanCategories[$i]["occupancies"][3]['occupancy'] += ($duration->i + $duration->h * 60);
                                    $humanCategories[$i]["occupancies"][4]['occupancy'] += ($durationbis->i + $durationbis->h * 60);
                                }
                            }
                        }
                    }
                    foreach ($materialResourcesByActivity as $materialResource) {//We add the duration to the occupancy of the material resource
                        $materialResources[$materialResource->getId()]['occupancies'][3]['occupancy'] += ($duration->i + $duration->h * 60);
                        $materialResources[$materialResource->getId()]['occupancies'][4]['occupancy'] += ($durationbis->i + $durationbis->h * 60);
                        foreach ($materialResources[$materialResource->getId()]['categories'] as $category) {//We add the duration to the occupancy of the category of the material resource
                            for ($i = 0; $i < sizeof($materialCategories); $i++) {
                                if ($materialCategories[$i]["title"] == $category["name"]) {
                                    $materialCategories[$i]["occupancies"][3]['occupancy'] += ($duration->i + $duration->h * 60);
                                    $materialCategories[$i]["occupancies"][4]['occupancy'] += ($durationbis->i + $durationbis->h * 60);
                                }
                            }
                        }
                    }
                }
                else if($displayedActivity->getStartTime()>=$sixam && $displayedActivity->getStartTime()<$nineam && $displayedActivity->getEndTime()>$twelvepm){//We add the duration to the first, the second and the third slot
                    $duration = $displayedActivity->getStartTime()->diff($nineam);//We calculate the duration of the activity before 9am
                    $durationbis = 180;//We calculate the duration of the activity between 9am and 12pm
                    $durationter = $twelvepm->diff($displayedActivity->getEndTime());//We calculate the duration of the activity after 12pm
                    foreach ($humanResourcesByActivity as $humanResource) {//We add the duration to the occupancy of the human resource
                        $humanResources[$humanResource->getId()]['occupancies'][0]['occupancy'] += ($duration->i + $duration->h * 60);
                        $humanResources[$humanResource->getId()]['occupancies'][1]['occupancy'] += ($durationbis);
                        $humanResources[$humanResource->getId()]['occupancies'][2]['occupancy'] += ($durationter->i + $durationter->h * 60);
                        foreach ($humanResources[$humanResource->getId()]['categories'] as $category) {//We add the duration to the occupancy of the category of the human resource
                            for ($i = 0; $i < sizeof($humanCategories); $i++) {
                                if ($humanCategories[$i]["title"] == $category["name"]) {
                                    $humanCategories[$i]["occupancies"][0]['occupancy'] += ($duration->i + $duration->h * 60);
                                    $humanCategories[$i]["occupancies"][1]['occupancy'] += ($durationbis);
                                    $humanCategories[$i]["occupancies"][2]['occupancy'] += ($durationter->i + $durationter->h * 60);
                                }
                            }
                        }
                    }
                    foreach ($materialResourcesByActivity as $materialResource) { //We add the duration to the occupancy of the material resource
                        $materialResources[$materialResource->getId()]['occupancies'][0]['occupancy'] += ($duration->i + $duration->h * 60);
                        $materialResources[$materialResource->getId()]['occupancies'][1]['occupancy'] += ($durationbis);
                        $materialResources[$materialResource->getId()]['occupancies'][2]['occupancy'] += ($durationter->i + $durationter->h * 60);
                        foreach ($materialResources[$materialResource->getId()]['categories'] as $category) { //We add the duration to the occupancy of the category of the material resource
                            for ($i = 0; $i < sizeof($materialCategories); $i++) {
                                if ($materialCategories[$i]["title"] == $category["name"]) {
                                    $materialCategories[$i]["occupancies"][0]['occupancy'] += ($duration->i + $duration->h * 60);
                                    $materialCategories[$i]["occupancies"][1]['occupancy'] += ($durationbis);
                                    $materialCategories[$i]["occupancies"][2]['occupancy'] += ($durationter->i + $durationter->h * 60);
                                }
                            }
                        }
                    }
                }
                else if($displayedActivity->getStartTime()>=$nineam && $displayedActivity->getStartTime()<$twelvepm && $displayedActivity->getEndTime()>$threepm){//We add the duration to the second, third slot and fourth slot
                    $duration = $displayedActivity->getStartTime()->diff($twelvepm);//We calculate the duration of the activity before 12pm
                    $durationbis = 180;//We calculate the duration of the activity between 12pm and 3pm
                    $durationter = $threepm->diff($displayedActivity->getEndTime());//We calculate the duration of the activity after 3pm
                    foreach ($humanResourcesByActivity as $humanResource) {//We add the duration to the occupancy of the human resource
                        $humanResources[$humanResource->getId()]['occupancies'][1]['occupancy'] += ($duration->i + $duration->h * 60);
                        $humanResources[$humanResource->getId()]['occupancies'][2]['occupancy'] += ($durationbis);
                        $humanResources[$humanResource->getId()]['occupancies'][3]['occupancy'] += ($durationter->i + $durationter->h * 60);

                        foreach ($humanResources[$humanResource->getId()]['categories'] as $category) {//We add the duration to the occupancy of the category of the human resource
                            for ($i = 0; $i < sizeof($humanCategories); $i++) {
                                if ($humanCategories[$i]["title"] == $category["name"]) {
                                    $humanCategories[$i]["occupancies"][1]['occupancy'] += ($duration->i + $duration->h * 60);
                                    $humanCategories[$i]["occupancies"][2]['occupancy'] += ($durationbis);
                                    $humanCategories[$i]["occupancies"][3]['occupancy'] += ($durationter->i + $durationter->h * 60);
                                }
                            }
                        }
                    }
                    foreach ($materialResourcesByActivity as $materialResource) {//We add the duration to the occupancy of the material resource
                        $humanResources[$humanResource->getId()]['occupancies'][1]['occupancy'] += ($duration->i + $duration->h * 60);
                        $humanResources[$humanResource->getId()]['occupancies'][2]['occupancy'] += ($durationbis);
                        $humanResources[$humanResource->getId()]['occupancies'][3]['occupancy'] += ($durationter->i + $durationter->h * 60);
                        foreach($materialResources[$materialResource->getId()]['categories'] as $category){//We add the duration to the occupancy of the category of the material resource
                            for($i=0;$i<sizeof($materialCategories);$i++){
                                if($materialCategories[$i]["title"]==$category["name"]){
                                    $materialCategories[$i]["occupancies"][1]['occupancy'] += ($duration->i + $duration->h * 60);
                                    $materialCategories[$i]["occupancies"][2]['occupancy'] += ($durationbis);
                                    $materialCategories[$i]["occupancies"][3]['occupancy'] += ($durationter->i + $durationter->h * 60);
                                }
                            }
                        }
                    }
                }
                else if($displayedActivity->getStartTime()>=$twelvepm && $displayedActivity->getStartTime()<$threepm && $displayedActivity->getEndTime()>$sixpm){//We add the duration to the third slot and fourth slot
                    $duration = $displayedActivity->getStartTime()->diff($threepm);//We calculate the duration of the activity before 3pm
                    $durationbis = 180;//We calculate the duration of the activity between 3pm and 6pm
                    $durationter = $sixpm->diff($displayedActivity->getEndTime());//We calculate the duration of the activity after 6pm

                    foreach ($humanResourcesByActivity as $humanResource) {//We add the duration to the occupancy of the human resource
                        $humanResources[$humanResource->getId()]['occupancies'][2]['occupancy'] += ($duration->i + $duration->h * 60);
                        $humanResources[$humanResource->getId()]['occupancies'][3]['occupancy'] += ($durationbis);
                        $humanResources[$humanResource->getId()]['occupancies'][4]['occupancy'] += ($durationter->i + $durationter->h * 60);
                        foreach ($humanResources[$humanResource->getId()]['categories'] as $category) {//We add the duration to the occupancy of the category of the human resource
                            for ($i = 0; $i < sizeof($humanCategories); $i++) {
                                if ($humanCategories[$i]["title"] == $category["name"]) {
                                    $humanCategories[$i]["occupancies"][2]['occupancy'] += ($duration->i + $duration->h * 60);
                                    $humanCategories[$i]["occupancies"][3]['occupancy'] += ($durationbis);
                                    $humanCategories[$i]["occupancies"][4]['occupancy'] += ($durationter->i + $durationter->h * 60);
                                }
                            }
                        }
                    }
                    foreach ($materialResourcesByActivity as $materialResource) {//We add the duration to the occupancy of the material resource
                        $materialResources[$materialResource->getId()]['occupancies'][2]['occupancy'] += ($duration->i + $duration->h * 60);
                        $materialResources[$materialResource->getId()]['occupancies'][3]['occupancy'] += ($durationbis);
                        $materialResources[$materialResource->getId()]['occupancies'][4]['occupancy'] += ($durationter->i + $durationter->h * 60);
                        foreach($materialResources[$materialResource->getId()]['categories'] as $category){//We add the duration to the occupancy of the category of the material resource
                            for($i=0;$i<sizeof($materialCategories);$i++){
                                if($materialCategories[$i]["title"]==$category["name"]){
                                    $materialCategories[$i]["occupancies"][2]['occupancy'] += ($duration->i + $duration->h * 60);
                                    $materialCategories[$i]["occupancies"][3]['occupancy'] += ($durationbis);
                                    $materialCategories[$i]["occupancies"][4]['occupancy'] += ($durationter->i + $durationter->h * 60);
                                }
                            }
                        }
                    }
                }
                unset($humanResourcesByActivity);
                unset($materialResourcesByActivity);
            }
            $results = array(
                'humanResources' => $humanResources,
                'materialResources' => $materialResources,
                'humanCategories' => $humanCategories,
                'materialCategories' => $materialCategories,
            );
        } else {
            $results = array(
                'humanResources' => "Aucune ressource planifiée",
                'materialResources' => "Aucune ressource planifiée",
                'humanCategories' => "Aucune ressource planifiée",
                'materialCategories' => "Aucune ressource planifiée",
            );
        }
        $resultsJSON = new JsonResponse($results);
        return $resultsJSON;
    }
}