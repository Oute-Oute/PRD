<?php

namespace App\Controller;

use App\Entity\SimulationInfo;
use RecursiveIteratorIterator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Iterator\RecursiveDirectoryIterator;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


class SimulationsController extends AbstractController
{

    public $date;
    public $dateFormatted;
    /*
     * @brief Allows to get stats
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    public function index(ManagerRegistry $doctrine): Response
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
        $simulationsInfos = $this->getInfos($doctrine);
        //render the view
        return $this->render('simulations/index.html.twig', [
            'controller_name' => 'SimulationsController',
            'currentdate' => $date,
            'simulationsInfos' => $simulationsInfos,
        ]);
    }

    public function serializeDB(ManagerRegistry $doctrine, bool $delete = false): void
    {
        $entities = [];
        $filenames = ["patients", "appointments", "humanresources", "categoryofhumanresources", "workinghours", "materialresources", "categoryofmaterialresources", "unavailabilities", "unavailabilityhumanresources", "unavailabilitymaterialresources", "scheduledactivities", "humanresourcescheduled", "materialresourcescheduled"];
        $entitiesNames = ["Patient", "Appointment", "HumanResource", "CategoryOfHumanResource", "WorkingHours", "MaterialResource", "CategoryOfMaterialResource", "Unavailability", "UnavailabilityHumanResource", "UnavailabilityMaterialResource", "ScheduledActivity", "HumanResourceScheduled", "MaterialResourceScheduled"];
        $sequenceNames = ["patient", "appointment", "human_resource", "category_of_human_resource", "working_hours", "material_resource", "category_of_material_resource", "unavailability", "unavailability_human_resource", "unavailability_material_resource", "scheduled_activity", "human_resource_scheduled", "material_resource_scheduled"];
        for ($i = 0; $i < sizeof($entitiesNames); $i++) {
            $entities[$i] = $doctrine->getRepository("App\Entity\\" . $entitiesNames[$i])->findAll();
        }
        $numberOfPatients = sizeof($entities[0]);
        $numberOfHumanResources = sizeof($entities[2]);
        $numberOfMaterialResources = sizeof($entities[5]);
        $simInfo = $doctrine->getRepository("App\Entity\SimulationInfo")->findOneBy(["iscurrent" => true]);
        if ($simInfo != null) {
            $simInfo->setNumberOfPatient($numberOfPatients);
            $simInfo->setNumberOfHumanResource($numberOfHumanResources);
            $simInfo->setNumberOfMaterialResource($numberOfMaterialResources);
            $simInfo->setCurrent(true);
            $id = $doctrine->getRepository("App\Entity\SimulationInfo")->add($simInfo, true);

            $dir = "Simulations";
            $simDir = $dir . "/" . $id;
            if (!file_exists($simDir)) {
                mkdir($simDir, 0777, true);
            }
            for ($i = 0; $i < sizeof($entities); $i++) {
                $file = fopen($simDir . "/" . $filenames[$i] . ".json", "w");
                $serializer = new Serializer([new DateTimeNormalizer(), new ObjectNormalizer()]);
                $formatted = $serializer->normalize($entities[$i]);
                fwrite($file, json_encode($formatted));
                fclose($file);
            }
        }
        //drain the tables
        if ($delete) {
            for ($i = 0; $i < sizeof($entitiesNames); $i++) {
                $doctrine->getRepository("App\Entity\\" . $entitiesNames[$i])->deleteALl();
            }
            //reset sqlLite sequences
            for ($i = 0; $i < sizeof($sequenceNames); $i++) {
                $doctrine->getConnection()->exec("DELETE FROM sqlite_sequence WHERE name = '" . $sequenceNames[$i] . "'");
            }
        }
    }

    public function deSerializeDB(ManagerRegistry $doctrine, int $id): Response
    {
        $entities = [];
        $filenames = ["patients", "appointments", "humanresources", "categoryofhumanresources", "workinghours", "materialresources", "categoryofmaterialresources", "unavailabilities", "unavailabilityhumanresources", "unavailabilitymaterialresources", "scheduledactivities", "humanresourcescheduled", "materialresourcescheduled"];
        $entitiesNames = ["Patient", "Appointment", "HumanResource", "CategoryOfHumanResource", "WorkingHours", "MaterialResource", "CategoryOfMaterialResource", "Unavailability", "UnavailabilityHumanResource", "UnavailabilityMaterialResource", "ScheduledActivity", "HumanResourceScheduled", "MaterialResourceScheduled"];
        $dir = "Simulations";
        $newDir = $dir . "/" . $id;
        for ($i = 0; $i < sizeof($filenames); $i++) {
            $file = fopen($newDir . "/" . $filenames[$i] . ".json", "r");
            $json = fread($file, filesize($newDir . "/" . $filenames[$i] . ".json"));
            $entities[$i] = json_decode($json, true);
            fclose($file);
        }
        for ($i = 0; $i < sizeof($entitiesNames); $i++) {
            for ($j = 0; $j < sizeof($entities[$i]); $j++) {
                $entity = $doctrine->getRepository("App\Entity\\" . $entitiesNames[$i]);
                $entity->setFromArray($entities[$i][$j], $doctrine);
            }
        }
        return new JsonResponse($entities);
    }

    public function getInfos(ManagerRegistry $doctrine)
    {
        $infosArray = [];
        $infos = $doctrine->getRepository("App\Entity\SimulationInfo")->findAllOrderByCurrent();
        for ($i = 0; $i < sizeof($infos); $i++) {
            $infosArray[$i] = [
                "id" => $infos[$i]->getId(),
                "simulationDateTime" => $infos[$i]->getSimulationdatetime(),
                "numberOfPatients" => $infos[$i]->getNumberofpatient(),
                "numberOfHumanResources" => $infos[$i]->getNumberofhumanresource(),
                "numberOfMaterialResources" => $infos[$i]->getNumberofmaterialresource(),
                "isCurrent" => $infos[$i]->Iscurrent()
            ];
        }
        $infos = new JsonResponse($infosArray);
        return $infos;
    }

    public function NewSimulation(ManagerRegistry $doctrine): Response
    {
        $this->serializeDB($doctrine, true);
        $currentSim = $doctrine->getRepository("App\Entity\SimulationInfo")->findOneBy(["iscurrent" => true]);
        if ($currentSim != null) {
            $currentSim->setCurrent(false);
        }
        $simInfo = new SimulationInfo();
        $simInfo->setNumberOfPatient(0);
        $simInfo->setNumberOfHumanResource(0);
        $simInfo->setNumberOfMaterialResource(0);
        $simInfo->setCurrent(true);
        $date = new \DateTime("now", new \DateTimeZone('Europe/Paris'));
        $date->format('Y-m-d H:i:s');
        $simInfo->setSimulationdatetime($date);
        $id = $doctrine->getRepository("App\Entity\SimulationInfo")->add($simInfo, true);
        return $this->redirectToRoute('Simulations');
    }

    public function saveSimulation(ManagerRegistry $doctrine): Response
    {
        $this->serializeDB($doctrine);
        return new JsonResponse("success");
    }

    public function loadSimulation(ManagerRegistry $doctrine, $id): Response
    {
        $this->serializeDB($doctrine, true);
        $this->deSerializeDB($doctrine, $id);
        return $this->redirectToRoute('Simulations');
    }

    public function deleteSimulation(ManagerRegistry $doctrine): Response
    {
        $id = $_POST["id"];
        var_dump($id);
        $simInfo = $doctrine->getRepository("App\Entity\SimulationInfo")->findOneBy(["id" => $id]);
        $doctrine->getRepository("App\Entity\SimulationInfo")->remove($simInfo, true);
        $dir = "Simulations";
        $simDir = $dir . "/" . $id;
        if (is_dir($simDir)) {
            $it = new RecursiveDirectoryIterator($simDir, RecursiveDirectoryIterator::SKIP_DOTS);
            $files = new RecursiveIteratorIterator(
                $it,
                RecursiveIteratorIterator::CHILD_FIRST
            );
            foreach ($files as $file) {
                if ($file->isDir()) {
                    rmdir($file->getRealPath());
                } else {
                    unlink($file->getRealPath());
                }
            }
            rmdir($simDir);
        }
        return new JsonResponse(['success' => 'true']);
        //return $this->redirectToRoute('Simulations');
    }
}