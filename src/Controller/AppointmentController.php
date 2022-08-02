<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Repository\AppointmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\Date;
use Knp\Component\Pager\PaginatorInterface;

class AppointmentController extends AbstractController
{
    public function appointmentGet(AppointmentRepository $appointmentRepository, ManagerRegistry $doctrine,Request $request, PaginatorInterface $paginator): Response
    {

        global $date;
        $date = date(('Y-m-d'));
        if (isset($_GET["date"])) {
            $date = $_GET["date"];
            $date = str_replace('T12:00:00', '', $date);
        }

        $currentAppointment=$paginator->paginate(
            $appointmentRepository->findAppointmentByDate($date), 
            $request->query->getInt('page',1),
            10
        ); 
        $patientsJSON = $this->getPatientsJSON($doctrine);
        $pathwaysJSON = $this->getPathwaysJSON($doctrine);
        //dd($doctrine->getManager()->getRepository("App\Entity\Patient")->findall(),$patients);
        //créer la page de gestion des rendez-vous en envoyant la liste de tous les rendez-vous, patients et parcours stockés en database
        return $this->render('appointment/index.html.twig', [
            'currentappointments' =>$currentAppointment ,
            'currentdate' => $date,
            'patientsJSON' => $patientsJSON,
            'pathwaysJSON' => $pathwaysJSON
        ]);
    }


    /*
     * @brief This function is the getter of the Pathways from the database.
     * @param ManagerRegistry $doctrine
     * @return array of the pathways's data
     */
    public function getPathwaysJSON(ManagerRegistry $doctrine)
    {
        //recuperation du pathway depuis la base de données
        $pathways = $doctrine->getRepository("App\Entity\Pathway")->findAll();
        $pathwaysArray = array();
        foreach ($pathways as $pathway) {
            //ajout des données du pathway dans un tableau
            $pathwaysArray[] = array(
                'id' => $pathway->getId(),
                'title' => (str_replace(" ", "3aZt3r", $pathway->getPathwayname()))
            );
        }
        return new JsonResponse($pathwaysArray);
    }

    public function getPatientsJSON(ManagerRegistry $doctrine)
    {
        $patients = $doctrine->getRepository("App\Entity\Patient")->findAll();
        $patientsArray = array();
        foreach ($patients as $patient) {
            //ajout des données du pathway dans un tableau
            $patientsArray[] = array(
                'id' => $patient->getId(),
                'firstname' => (str_replace(" ", "3aZt3r", $patient->getfirstname())),
                'lastname' => (str_replace(" ", "3aZt3r", $patient->getlastname())),
            );
        }
        return new JsonResponse($patientsArray);
    }

    public function appointmentAdd(Request $request, AppointmentRepository $appointmentRepository, ManagerRegistry $doctrine): Response
    {
        // On recupere toutes les données de la requete
        $param = $request->request->all();

        $name = explode(" ", $param["patient"]);
        //parse_str($nameParsed[0], $nameParsed);
        $patient = $doctrine->getManager()->getRepository("App\Entity\Patient")->findOneBy(['firstname' => $name[1], 'lastname' => $name[0]]);
        $pathway = $doctrine->getManager()->getRepository("App\Entity\Pathway")->findOneBy(['pathwayname' => $param["pathway"]]);
        $dayappointment = \DateTime::createFromFormat('d-m-Y H:i:s', str_replace("/", "-", $param['dayappointment'] . ' ' . "00:00:00"));

        if ($param["earliestappointmenttime"] != "") {
            $earliestappointmenttime = \DateTime::createFromFormat('H:i', $param['earliestappointmenttime']);
        } else {
            $earliestappointmenttime = \DateTime::createFromFormat('H:i', "00:00");
        }
        if ($param["latestappointmenttime"] != "") {

            $latestappointmenttime = \DateTime::createFromFormat('H:i', $param['latestappointmenttime']);
        } else {
            $latestappointmenttime = \DateTime::createFromFormat('H:i', "23:59");
        }
        // Création du rendez-vous
        $appointment = new Appointment();
        $appointment->setPatient($patient);
        $appointment->setPathway($pathway);
        $appointment->setDayappointment($dayappointment);
        $appointment->setEarliestappointmenttime($earliestappointmenttime);
        $appointment->setLatestappointmenttime($latestappointmenttime);
        $appointment->setScheduled(false);

        // ajout dans la bdd
        $appointmentRepository->add($appointment, true);
        return $this->redirectToRoute('Appointment', [], Response::HTTP_SEE_OTHER);
    }

    public function appointmentEdit(Request $request, AppointmentRepository $appointmentRepository, ManagerRegistry $doctrine)
    {
        //on récupère les nouvelles informations sur le rendez-vous
        $param = $request->request->all();
        $name = explode(" ", $param["patient"]);
        $appointment = $appointmentRepository->findOneBy(['id' => $param['idappointment']]);
        $patient = $doctrine->getManager()->getRepository("App\Entity\Patient")->findOneBy(['firstname' => $name[1], 'lastname' => $name[0]]);
        $pathway = $doctrine->getManager()->getRepository("App\Entity\Pathway")->findOneBy(['pathwayname' => $param["pathway"]]);
        $dayappointment = \DateTime::createFromFormat('d-m-Y H:i:s', str_replace("/", "-", $param['dayappointment'] . ' ' . "00:00:00"));
        if ($param["earliestappointmenttime"] != "") {
            $earliestappointmenttime = \DateTime::createFromFormat('H:i', $param['earliestappointmenttime']);
        } else {
            $earliestappointmenttime = \DateTime::createFromFormat('H:i', "00:00");
        }
        if ($param["latestappointmenttime"] != "") {

            $latestappointmenttime = \DateTime::createFromFormat('H:i', $param['latestappointmenttime']);
        } else {
            $latestappointmenttime = \DateTime::createFromFormat('H:i', "23:59");
        }
        //on modifie les données du rendez-vous
        $appointment->setPatient($patient);
        $appointment->setPathway($pathway);
        $appointment->setDayappointment($dayappointment);
        $appointment->setEarliestappointmenttime($earliestappointmenttime);
        $appointment->setLatestappointmenttime($latestappointmenttime);
        $appointment->setScheduled(false);

        //on met à jour le rendez-vous dans la bdd
        $appointmentRepository->add($appointment, true);

        return $this->redirectToRoute('Appointment', [], Response::HTTP_SEE_OTHER);
    }

    public function appointmentDelete(ManagerRegistry $doctrine, Appointment $appointment, AppointmentRepository $appointmentRepository): Response
    {
        //on récupère toutes les activités programmées associées au rendez-vous
        $scheduledActivityRepository = $doctrine->getManager()->getRepository("App\Entity\ScheduledActivity");
        $scheduledActivities = $scheduledActivityRepository->findBy(['appointment' => $appointment]);

        foreach ($scheduledActivities as $scheduledActivity) {
            $date = $appointment->getDayappointment()->format('Y-m-d');

            //suppression des données associées au rendez-vous de la table MaterialResourceScheduled
            $materialResourceScheduledRepository = $doctrine->getManager()->getRepository("App\Entity\MaterialResourceScheduled");
            $allMaterialResourceScheduled = $materialResourceScheduledRepository->findBy(['scheduledactivity' => $scheduledActivity]);

            foreach ($allMaterialResourceScheduled as $materialResourceScheduled) {
                $materialResourceScheduledRepository->remove($materialResourceScheduled, true);
            }


            //suppression des données associées au rendez-vous de la table HumanResourceScheduled
            $humanResourceScheduledRepository = $doctrine->getManager()->getRepository("App\Entity\HumanResourceScheduled");
            $allHumanResourceScheduled = $humanResourceScheduledRepository->findBy(['scheduledactivity' => $scheduledActivity]);

            foreach ($allHumanResourceScheduled as $humanResourceScheduled) {
                $humanResourceScheduledRepository->remove($humanResourceScheduled, true);
            }


            //suppression des données associées au rendez-vous de la table ScheduledActivity
            $scheduledActivityRepository->remove($scheduledActivity, true);
        }

        //suppression du rendez-vous
        $appointmentRepository->remove($appointment, true);

        return $this->redirectToRoute('Appointment', [], Response::HTTP_SEE_OTHER);
    }


    public function getTargets(ManagerRegistry $doctrine, AppointmentRepository $AR)
    {
        $date = new \DateTime();
        if (isset($_POST["date"])) {
            $date = \DateTime::createFromFormat('Y-m-d', $_POST["date"]);
        }
        $pathway = $this->getPathwayByName($_POST["pathway"], $doctrine);
        $targets = $this->getTargetByPathwayJSON($doctrine, $pathway, $AR, $date);
        $data[] =
            [
                "pathway" => $pathway,
                "targets" => $targets
            ];
        return new JsonResponse($data);
    }

    public function lookAutocompletes(ManagerRegistry $doctrine)
    {
        $pathway = $this->getPathwayByName($_POST["pathway"], $doctrine);
        $patient = $this->getPatientByName($_POST["patient"], $doctrine);
        $data[] =
            [
                "pathway" => $pathway,
                "patient" => $patient
            ];
        return new JsonResponse($data);
    }

    public function getPathwayByName($name, ManagerRegistry $doctrine)
    {
        $pathway = $doctrine->getManager()->getRepository("App\Entity\Pathway")->findBy(["pathwayname" => $name]);
        return $pathway;
    }

    public function getPatientByName($name, ManagerRegistry $doctrine)
    {
        $name = explode(" ", $name);
        $patient = $doctrine->getManager()->getRepository("App\Entity\Patient")->findBy(['firstname' => $name[1], 'lastname' => $name[0]]);
        return $patient;
    }

    public function getTargetByPathwayJSON(ManagerRegistry $doctrine, $pathway, AppointmentRepository $AR, $date)
    {
        $targets = $doctrine->getRepository("App\Entity\Target")->findBy(["pathway" => $pathway]);
        $targetsJSON = [];
        $month = $date->format('m');
        $year = $date->format('Y');
        $numberOfDay = 0;
        switch ($month) {
            case '02':
                if ($year % 4 == 0) {
                    $numberOfDay = 29;
                } else {
                    $numberOfDay = 28;
                }
                break;
            case '04':
            case '06':
            case '09':
            case '11':
                $numberOfDay = 30;
                break;
            default:
                $numberOfDay = 31;
                break;
        }
        $targets = $doctrine->getRepository("App\Entity\Target")->findBy(["pathway" => $pathway]);
        $targetsByDay = [];
        foreach ($targets as $target) {
            $targetsByDay[$target->getDayweek()] = $target->getTarget();
        }
        for ($i = 1; $i <= $numberOfDay; $i++) {
            if ($i < 10) {
                $i = "0" . $i;
            }
            $datestr = $year . "-" . $month . "-" . $i;
            $dateToGet = new \DateTime($datestr);
            $dayWeek = date('w', $dateToGet->getTimestamp());
            $dateToGet = $dateToGet->format('Y-m-d');
            $nbrOfAppt = $AR->getNumberOfAppointmentByPathwayByDate($pathway, $dateToGet);
            if ($nbrOfAppt > 0) { //if there is at least one appointment on this day
                if (array_key_exists($dayWeek, $targetsByDay)) {
                    if ($targetsByDay[$dayWeek] <= $nbrOfAppt) { // if more or equal appointment than target 
                        $color = "#ff0000";
                    } else if ($targetsByDay[$dayWeek] - 2 <= $nbrOfAppt && $targetsByDay[$dayWeek] - 2 >= 0) { // if more than target - 2 appointment but less than target
                        $color = "#ffff00";
                    } else {
                        $color = "#00ff00"; //default
                    }
                    $ratioTarget = $nbrOfAppt . "/" . $targetsByDay[$dayWeek];
                    $targetsJSON[] = [
                        'color' => $color,
                        'description' => $ratioTarget,
                        'start' => $datestr . "T00:00:00",
                        'end' => $datestr . "T23:59:59"
                    ];
                } else {
                    $ratioTarget = $nbrOfAppt . "/--";
                    $targetsJSON[] = [
                        'color' => '#FF0000',
                        'description' => $ratioTarget,
                        'start' => $datestr . "T00:00:00",
                        'end' => $datestr . "T23:59:59"
                    ];
                }
            } else {
                if (array_key_exists($dayWeek, $targetsByDay)) { //if target exist but no appointment
                    $ratioTarget = $nbrOfAppt . "/" . $targetsByDay[$dayWeek];
                    $targetsJSON[] = [
                        'color' => '#00FF00',
                        'description' => $ratioTarget,
                        'start' => $datestr . "T00:00:00",
                        'end' => $datestr . "T23:59:59"
                    ];
                } else {
                    $ratioTarget = $nbrOfAppt . "/--"; //if no target and no appointment
                    $targetsJSON[] = [
                        'color' => '#0000FF',
                        'description' => $ratioTarget,
                        'start' => $datestr . "T00:00:00",
                        'end' => $datestr . "T23:59:59"
                    ];
                }
            }
        }

        return $targetsJSON;
    }

    public function getInfosAppointmentById(ManagerRegistry $doctrine)
    {
        $appointment = $doctrine->getRepository("App\Entity\Appointment")->findOneBy(array("id" => $_POST["id"]));
        $scheduledActivities = $doctrine->getRepository("App\Entity\ScheduledActivity")->findBy(array("appointment" => $appointment));
        $activitiesArray = [];
        foreach ($scheduledActivities as $scheduledActivity) {
            $activitiesArray[] = [
                "startTime" => $scheduledActivity->getStartTime()->format("H:i:s"),
                "endTime" => $scheduledActivity->getEndTime()->format("H:i:s"),
                "activity" => $scheduledActivity->getActivity()->getActivityname(),
            ];
        }

        $data[] =
            [
                "dayAppointment" => $appointment->getDayappointment()->format("Y-m-d"),
                "earliestAppointmentTime" => $appointment->getEarliestappointmenttime()->format("H:i:s"),
                "latestAppointmentTime" => $appointment->getLatestappointmenttime()->format("H:i:s"),
                "patientLastname" => $appointment->getPatient()->getLastname(),
                "patientFirstname" => $appointment->getPatient()->getFirstname(),
                "pathwayName" => $appointment->getPathway()->getPathwayname(),
                "activities" => $activitiesArray
            ];
        return new JsonResponse($data);
    }
}
