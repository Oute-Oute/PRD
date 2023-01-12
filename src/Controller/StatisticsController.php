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
    public $dateFormatted;
    /*
     * @brief Allows to get stats
     * 
     */
    public function index(ManagerRegistry $doctrine, ScheduledActivityRepository $SAR): Response
    {

        $english_months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
        $french_months = array('janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');

        global $date;
        global $dateFormatted;
        $date = date(('Y-m-d'));
        if (isset($_GET["date"])) {
            $date = $_GET["date"];
            $date = str_replace('T12:00:00', '', $date);
        }
        $header = "";
        if (isset($_GET["headerResources"])) {
            $header = $_GET["headerResources"];
        }

        $dateFormatted = date_create($date);
        $dateFormatted->format('Y-F-d');
        $dateStr = str_replace($english_months, $french_months, $dateFormatted->format('d F Y'));
        $getDisplayedActivitiesJSON = $this->getDisplayedActivitiesJSON($doctrine, $SAR);
        $humanResourcesSheduledJSON = $this->getHumanResourceScheduledJSON($doctrine);
        $materialResourcesSheduledJSON = $this->getMaterialResourceScheduledJSON($doctrine);
        $appointmentsJSON = $this->getAppointmentsJSON($doctrine);
        $waitingTimes = $this->getWaitingTimes($doctrine);
        $occupancyRates = $this->getOccupancyRates($doctrine);
        return $this->render('statistics/index.html.twig', [
            'controller_name' => 'StatisticsController',
            'currentdate' => $date,
            'dateFormatted' => $dateStr,
            'getHumanResourceScheduledJSON' => $humanResourcesSheduledJSON,
            'getMaterialResourceScheduledJSON' => $materialResourcesSheduledJSON,
            'getAppointmentsJSON' => $appointmentsJSON,
            'getDisplayedActivitiesJSON' => $getDisplayedActivitiesJSON,
            'waitingTimes' => $waitingTimes,
            'occupancyRates' => $occupancyRates,
        ]);
    }


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
                //'patient' => $this->getPatient($doctrine, $appointment->getPatient()->getId(), $businessHours),
                //'pathway' => $this->getPathway($doctrine, $appointment->getPathway()->getId()),

            );
        }
        //Conversion des données ressources en json
        $appointmentArrayJSON = new JsonResponse($appointmentArray);
        return $appointmentArrayJSON;
    }

    public function getWaitingTimes(ManagerRegistry $doctrine)
    {
        global $displayedActivities;
        if (sizeof($displayedActivities) > 0) {
            $waitingTimes = array();
            $incrementWaitingTimes = 0;
            $activitiesByAppointment = array();
            foreach ($displayedActivities as $displayedActivity) {
                $activitiesByAppointment[$displayedActivity->getAppointment()->getId()][] = $displayedActivity;
            }

            foreach ($activitiesByAppointment as $appointment) {
                for ($i = 0; $i < sizeof($appointment) - 1; $i++) {
                    $end = $appointment[$i]->getEndTime()->format('H:i:s');
                    $start = $appointment[$i + 1]->getStartTime()->format('H:i:s');
                    $endTime = new \DateTime($end);
                    $startTime = new \DateTime($start);
                    $inter = $endTime->diff($startTime);
                    $hour = $inter->format("%H");
                    $min = $inter->format("%I");
                    $waitingTime = $hour * 60 + $min;
                    $waitingTimes[$incrementWaitingTimes] = $waitingTime;
                    $incrementWaitingTimes++;
                }
            }
            $minimum = min($waitingTimes);
            $maximum = max($waitingTimes);
            $mean = array_sum($waitingTimes) / sizeof($waitingTimes);

            $waitingResults = array(
                'minimum' => $minimum,
                'maximum' => $maximum,
                'mean' => $mean
            );
            $waitingTimeJSON = new JsonResponse($waitingResults);
            return $waitingTimeJSON;
        } else {
            $waitingResults = array(
                'minimum' => "Aucune activité planifiée",
                'maximum' => "Aucune activité planifiée",
                'mean' => "Aucune activité planifiée"
            );
            $waitingTimeJSON = new JsonResponse($waitingResults);
            return $waitingTimeJSON;
        }
    }

    public function getOccupancyRates(ManagerRegistry $doctrine)
    {
        global $displayedActivities;
        $dateStr = "1970-01-01";
        $humanResources = array();
        $materialResources = array();
        $sixam = new \DateTime($dateStr . '06:00:00');
        $nineam = new \DateTime($dateStr . '09:00:00');
        $twelvepm = new \DateTime($dateStr . '12:00:00');
        $threepm = new \DateTime($dateStr . '15:00:00');
        $sixpm = new \DateTime($dateStr . '18:00:00');
        $ninepm = new \DateTime($dateStr . '21:00:00');
        $arrayId = array();
        $occupancyArray = array();
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
        if (sizeof($displayedActivities) > 0) {
            foreach ($displayedActivities as $displayedActivity) {
                
                $humanResourcesByActivity = array();
                $materialResourcesByActivity = array();
                $HumanResourceScheduleds = $doctrine->getRepository("App\Entity\HumanResourceScheduled")->findBy(array("scheduledactivity" => $displayedActivity));
                foreach ($HumanResourceScheduleds as $HumanResourceScheduled) {
                    $humanResourcesByActivity[$displayedActivity->getId()]= $HumanResourceScheduled->getHumanresource();
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
                        $humanResources[$HumanResourceScheduled->getHumanresource()->getId()] = array(
                            'id' => $id,
                            'title' => ($HumanResourceScheduled->getHumanresource()->getHumanresourcename()),
                            'categories' => ($categoriesArray),
                            'businessHours' => ($this->getWorkingHours($doctrine, $HumanResourceScheduled->getHumanresource())),
                            'type' => 0,
                            'occupancy' => $occupancyArray
                        );
                        unset($HumanResourceArray);
                        $arrayId[] = $HumanResourceScheduled->getHumanresource()->getId();
                    }
                }
                $MaterialResourceScheduleds = $doctrine->getRepository("App\Entity\MaterialResourceScheduled")->findBy(array("scheduledactivity" => $displayedActivity));
                foreach ($MaterialResourceScheduleds as $MaterialResourceScheduled) {
                    $materialResourcesByActivity[$displayedActivity->getId()] = $MaterialResourceScheduled->getMaterialresource();
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
                    $materialResources[$MaterialResourceScheduled->getMaterialresource()->getId()] = array(
                        'id' => $id,
                        'title' => ($MaterialResourceScheduled->getMaterialresource()->getMaterialresourcename()),
                        'categories' => ($materialCategoryArray),
                        'type' => 0,
                        'occupancy' => $occupancyArray
                    );


                }
                if ($displayedActivity->getStarttime() >= $sixam && $displayedActivity->getEndtime() <= $nineam) {
                    foreach($humanResourcesByActivity as $humanResource){
                        $duration=$displayedActivity->getEndtime()->diff($displayedActivity->getStarttime());
                        $humanResources[$humanResource->getId()]['occupancy'][0]['occupancy'] += ($duration->i+$duration->h*60);
                    }
                    foreach($materialResourcesByActivity as $materialResource){
                        $duration=$displayedActivity->getEndtime()->diff($displayedActivity->getStarttime());
                        $materialResources[$materialResource->getId()]['occupancy'][0]['occupancy'] += ($duration->i+$duration->h*60);
                    }


                } else if ($displayedActivity->getStarttime() >= $nineam && $displayedActivity->getEndtime() <= $twelvepm) {
                    foreach($humanResourcesByActivity as $humanResource){
                        $duration=$displayedActivity->getEndtime()->diff($displayedActivity->getStarttime());
                        $humanResources[$humanResource->getId()]['occupancy'][1]['occupancy'] += ($duration->i+$duration->h*60);
                    }
                    foreach($materialResourcesByActivity as $materialResource){
                        $duration=$displayedActivity->getEndtime()->diff($displayedActivity->getStarttime());
                        $materialResources[$materialResource->getId()]['occupancy'][1]['occupancy'] += ($duration->i+$duration->h*60);
                    }

                } else if ($displayedActivity->getStarttime() >= $twelvepm && $displayedActivity->getEndtime() <= $threepm) {
                    foreach($humanResourcesByActivity as $humanResource){
                        $duration=$displayedActivity->getEndtime()->diff($displayedActivity->getStarttime());
                        $humanResources[$humanResource->getId()]['occupancy'][2]['occupancy'] += ($duration->i+$duration->h*60);
                    }
                    foreach($materialResourcesByActivity as $materialResource){
                        $duration=$displayedActivity->getEndtime()->diff($displayedActivity->getStarttime());
                        $materialResources[$materialResource->getId()]['occupancy'][2]['occupancy'] += ($duration->i+$duration->h*60);
                    }

                } else if ($displayedActivity->getStarttime() >= $threepm && $displayedActivity->getEndtime() <= $sixpm) {
                    foreach($humanResourcesByActivity as $humanResource){
                        $duration=$displayedActivity->getEndtime()->diff($displayedActivity->getStarttime());
                        $humanResources[$humanResource->getId()]['occupancy'][3]['occupancy'] += ($duration->i+$duration->h*60);
                    }
                    foreach($materialResourcesByActivity as $materialResource){
                        $duration=$displayedActivity->getEndtime()->diff($displayedActivity->getStarttime());
                        $materialResources[$materialResource->getId()]['occupancy'][3]['occupancy'] += ($duration->i+$duration->h*60);
                    }

                } else if ($displayedActivity->getStarttime() >= $sixpm && $displayedActivity->getEndtime() <= $ninepm) {
                    foreach($humanResourcesByActivity as $humanResource){
                        $duration=$displayedActivity->getEndtime()->diff($displayedActivity->getStarttime());
                        $humanResources[$humanResource->getId()]['occupancy'][4]['occupancy'] += ($duration->i+$duration->h*60);
                    }
                    foreach($materialResourcesByActivity as $materialResource){
                        $duration=$displayedActivity->getEndtime()->diff($displayedActivity->getStarttime());
                        $materialResources[$materialResource->getId()]['occupancy'][4]['occupancy'] += ($duration->i+$duration->h*60);
                    }

                } else if ($displayedActivity->getStarttime() >= $sixam && $displayedActivity->getStarttime() < $nineam && $displayedActivity->getEndtime() > $nineam) {
                    foreach($humanResourcesByActivity as $humanResource){
                        $duration=$displayedActivity->getEndtime()->diff($nineam);
                        $humanResources[$humanResource->getId()]['occupancy'][0]['occupancy'] += ($duration->i+$duration->h*60);
                        $durationbis=$nineam->diff($displayedActivity->getEndTime());
                        $humanResources[$humanResource->getId()]['occupancy'][1]['occupancy'] += ($durationbis->i+$durationbis->h*60);

                    }
                    foreach($materialResourcesByActivity as $materialResource){
                        $duration=$displayedActivity->getEndtime()->diff($nineam);
                        $materialResources[$materialResource->getId()]['occupancy'][0]['occupancy'] += ($duration->i+$duration->h*60);
                        $durationbis=$nineam->diff($displayedActivity->getEndTime());
                        $materialResources[$materialResource->getId()]['occupancy'][1]['occupancy'] += ($durationbis->i+$durationbis->h*60);
                    }

                } else if ($displayedActivity->getStarttime() >= $nineam && $displayedActivity->getStarttime() < $twelvepm && $displayedActivity->getEndtime() > $twelvepm) {
                    foreach($humanResourcesByActivity as $humanResource){
                        $duration=$displayedActivity->getStarttime()->diff($twelvepm);
                        $humanResources[$humanResource->getId()]['occupancy'][1]['occupancy'] += ($duration->i+$duration->h*60);
                        $durationbis=$twelvepm->diff($displayedActivity->getEndTime());
                        $humanResources[$humanResource->getId()]['occupancy'][2]['occupancy'] += ($durationbis->i+$durationbis->h*60);

                    }
                    foreach($materialResourcesByActivity as $materialResource){
                        $duration=$displayedActivity->getStarttime()->diff($twelvepm);
                        $materialResources[$materialResource->getId()]['occupancy'][1]['occupancy'] += ($duration->i+$duration->h*60);
                        $durationbis=$twelvepm->diff($displayedActivity->getEndTime());
                        $materialResources[$materialResource->getId()]['occupancy'][2]['occupancy'] += ($durationbis->i+$durationbis->h*60);
                    }

                } else if ($displayedActivity->getStarttime() >= $twelvepm && $displayedActivity->getStarttime() < $threepm && $displayedActivity->getEndtime() > $threepm) {
                    foreach($humanResourcesByActivity as $humanResource){
                        $duration=$displayedActivity->getStarttime()->diff($threepm);
                        $humanResources[$humanResource->getId()]['occupancy'][2]['occupancy'] += ($duration->i+$duration->h*60);
                        $durationbis=$threepm->diff($displayedActivity->getEndTime());
                        $humanResources[$humanResource->getId()]['occupancy'][3]['occupancy'] += ($durationbis->i+$durationbis->h*60);

                    }
                    foreach($materialResourcesByActivity as $materialResource){
                        $duration=$displayedActivity->getStarttime()->diff($threepm);
                        $materialResources[$materialResource->getId()]['occupancy'][2]['occupancy'] += ($duration->i+$duration->h*60);
                        $durationbis=$threepm->diff($displayedActivity->getEndTime());
                        $materialResources[$materialResource->getId()]['occupancy'][3]['occupancy'] += ($durationbis->i+$durationbis->h*60);
                    }

                } else if ($displayedActivity->getStarttime() >= $threepm && $displayedActivity->getStarttime() < $sixpm && $displayedActivity->getEndtime() > $sixpm) {
                    foreach($humanResourcesByActivity as $humanResource){
                        $duration=$displayedActivity->getStarttime()->diff($sixpm);
                        $humanResources[$humanResource->getId()]['occupancy'][3]['occupancy'] += ($duration->i+$duration->h*60);
                        $durationbis=$sixpm->diff($displayedActivity->getEndTime());
                        $humanResources[$humanResource->getId()]['occupancy'][4]['occupancy'] += ($durationbis->i+$durationbis->h*60);

                    }
                    foreach($materialResourcesByActivity as $materialResource){
                        $duration=$displayedActivity->getStarttime()->diff($sixpm);
                        $materialResources[$materialResource->getId()]['occupancy'][3]['occupancy'] += ($duration->i+$duration->h*60);
                        $durationbis=$sixpm->diff($displayedActivity->getEndTime());
                        $materialResources[$materialResource->getId()]['occupancy'][4]['occupancy'] += ($durationbis->i+$durationbis->h*60);
                    }

                } else {
                    var_dump("AUTRE!!!!!!!!!!!!!!!!!!");
                }

                unset($humanResourcesByActivity);
                unset($materialResourcesByActivity);
            }
            $results=array(
                'humanResources' => $humanResources,
                'materialResources' => $materialResources
            );
        }
        else{
            $results=array(
                'humanResources' => "Aucune ressource planifiée",
                'materialResources' => "Aucune ressource planifiée"
            );
        }
        $resultsJSON=new JsonResponse($results);
        return $resultsJSON;
    }
}