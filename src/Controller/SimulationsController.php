<?php

namespace App\Controller;

use App\Entity\SimulationInfo;
use RecursiveIteratorIterator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Iterator\RecursiveDirectoryIterator;
use Symfony\Component\HttpClient\HttpClient;
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
     * @brief Allow to get the simulation page
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

    /*
     * @brief Allow to serialize the database into json files
     * @param ManagerRegistry $doctrine
     * @param bool $delete
     * @return Response
     */
    public function serializeDB(ManagerRegistry $doctrine, bool $delete = false): int
    {
        $entities = [];
        //set the names of the files and the entities
        $filenames = ["patients", "appointments", "humanresources", "categoryofhumanresources", "workinghours", "materialresources", "categoryofmaterialresources", "unavailabilities", "unavailabilityhumanresources", "unavailabilitymaterialresources", "scheduledactivities", "humanresourcescheduled", "materialresourcescheduled"];
        $entitiesNames = ["Patient", "Appointment", "HumanResource", "CategoryOfHumanResource", "WorkingHours", "MaterialResource", "CategoryOfMaterialResource", "Unavailability", "UnavailabilityHumanResource", "UnavailabilityMaterialResource", "ScheduledActivity", "HumanResourceScheduled", "MaterialResourceScheduled"];
        $sequenceNames = ["patient", "appointment", "human_resource", "category_of_human_resource", "working_hours", "material_resource", "category_of_material_resource", "unavailability", "unavailability_human_resource", "unavailability_material_resource", "scheduled_activity", "human_resource_scheduled", "material_resource_scheduled"];
        for ($i = 0; $i < sizeof($entitiesNames); $i++) {
            $entities[$i] = $doctrine->getRepository("App\Entity\\" . $entitiesNames[$i])->findAll(); //get all the entities
        }
        $numberOfPatients = sizeof($entities[0]); //get the number of patients
        $numberOfHumanResources = sizeof($entities[2]); //get the number of human resources
        $numberOfMaterialResources = sizeof($entities[5]); //get the number of material resources
        $simInfo = $doctrine->getRepository("App\Entity\SimulationInfo")->findOneBy(["iscurrent" => true]); //get the current simulation info
        if ($simInfo != null) { //if there is a current simulation info update it
            $simInfo->setNumberOfPatient($numberOfPatients);
            $simInfo->setNumberOfHumanResource($numberOfHumanResources);
            $simInfo->setNumberOfMaterialResource($numberOfMaterialResources);
            $simInfo->setCurrent(true);
            $id = $doctrine->getRepository("App\Entity\SimulationInfo")->add($simInfo, true); //add the simulation info and get the id
            $dir = "Simulations"; //set the directory
            $simDir = $dir . "/" . $id; //set the simulation directory
            if (!file_exists($simDir)) { //if the directory doesn't exist create it
                mkdir($simDir, 0777, true);
            }
            for ($i = 0; $i < sizeof($entities); $i++) { //serialize the entities
                $file = fopen($simDir . "/" . $filenames[$i] . ".json", "w"); //open the file
                $serializer = new Serializer([new DateTimeNormalizer(), new ObjectNormalizer()]); //create the serializer
                $formatted = $serializer->normalize($entities[$i]); //normalize the entities
                fwrite($file, json_encode($formatted)); //write the entities into the file
                fclose($file); //close the file
            }
        }
        if ($delete) { //if the delete parameter is true
            for ($i = 0; $i < sizeof($entitiesNames); $i++) { //delete all the entities
                $doctrine->getRepository("App\Entity\\" . $entitiesNames[$i])->deleteALl(); //delete all the entities
            }
            //reset sqlLite sequences
            for ($i = 0; $i < sizeof($sequenceNames); $i++) {
                $doctrine->getConnection()->exec("DELETE FROM sqlite_sequence WHERE name = '" . $sequenceNames[$i] . "'");
            }
        }
        return 0;
    }

    /*
     * @brief Allow to deSerialize the database from json files and insert them into the database
     * @param ManagerRegistry $doctrine
     * @param int $id
     * @return Response
     */
    public function deSerializeDB(ManagerRegistry $doctrine, int $id): void
    {
        $entities = [];
        //set the names of the files and the entities
        $filenames = ["patients", "appointments", "humanresources", "categoryofhumanresources", "workinghours", "materialresources", "categoryofmaterialresources", "unavailabilities", "unavailabilityhumanresources", "unavailabilitymaterialresources", "scheduledactivities", "humanresourcescheduled", "materialresourcescheduled"];
        $entitiesNames = ["Patient", "Appointment", "HumanResource", "CategoryOfHumanResource", "WorkingHours", "MaterialResource", "CategoryOfMaterialResource", "Unavailability", "UnavailabilityHumanResource", "UnavailabilityMaterialResource", "ScheduledActivity", "HumanResourceScheduled", "MaterialResourceScheduled"];
        $dir = "Simulations"; //set the directory
        $newDir = $dir . "/" . $id; //set the simulation directory
        for ($i = 0; $i < sizeof($filenames); $i++) { //deserialize the entities
            $file = fopen($newDir . "/" . $filenames[$i] . ".json", "r"); //open the file
            $json = fread($file, filesize($newDir . "/" . $filenames[$i] . ".json")); //read the file
            $entities[$i] = json_decode($json, true); //decode the json
            fclose($file); //close the file
        }
        usleep(1000); //wait 1ms to avoid errors
        for ($i = 0; $i < sizeof($entitiesNames); $i++) { //insert the entities into the database
            for ($j = 0; $j < sizeof($entities[$i]); $j++) {
                $entity = $doctrine->getRepository("App\Entity\\" . $entitiesNames[$i]); //get the entity
                $entity->setFromArray($entities[$i][$j], $doctrine); //set the entity from the array
            }
        }
    }

    /*
     * @brief Allow to get the simulation infos
     * @param ManagerRegistry $doctrine
     * @return JsonResponse
     */
    public function getInfos(ManagerRegistry $doctrine)
    {
        $infosArray = []; //create the array
        $infos = $doctrine->getRepository("App\Entity\SimulationInfo")->findAllOrderByCurrent(); //get the simulation infos
        for ($i = 0; $i < sizeof($infos); $i++) {
            $infosArray[$i] = [ //add the infos to the array
                "id" => $infos[$i]->getId(),
                "simulationDateTime" => $infos[$i]->getSimulationdatetime(),
                "numberOfPatients" => $infos[$i]->getNumberofpatient(),
                "numberOfHumanResources" => $infos[$i]->getNumberofhumanresource(),
                "numberOfMaterialResources" => $infos[$i]->getNumberofmaterialresource(),
                "isCurrent" => $infos[$i]->Iscurrent()
            ];
        }
        $infos = new JsonResponse($infosArray); //create the json response
        return $infos;
    }

    /*
     * @brief Allow to create a new simulation
     * @param ManagerRegistry $doctrine
     * @return JsonResponse
     */
    public function NewSimulation(ManagerRegistry $doctrine): Response
    {
        $this->serializeDB($doctrine, true); //serialize the database
        $currentSim = $doctrine->getRepository("App\Entity\SimulationInfo")->findOneBy(["iscurrent" => true]); //get the current simulation info
        if ($currentSim != null) { //if there is a current simulation info update it
            $currentSim->setCurrent(false); //set the current simulation info to false
        }
        $simInfo = new SimulationInfo(); //create a new simulation info
        $simInfo->setNumberOfPatient(0); //set the number of patients to 0
        $simInfo->setNumberOfHumanResource(0); //set the number of human resources to 0
        $simInfo->setNumberOfMaterialResource(0); //set the number of material resources to 0
        $simInfo->setCurrent(true); //set the current simulation info to true
        $date = new \DateTime("now", new \DateTimeZone('Europe/Paris')); //get the current date
        $date->format('Y-m-d H:i:s'); //format the date
        $simInfo->setSimulationdatetime($date); //set the simulation date
        $id = $doctrine->getRepository("App\Entity\SimulationInfo")->add($simInfo, true); //add the simulation info to the database
        return $this->redirectToRoute('Simulations'); //redirect to the simulation page
    }

    /*
     * @brief Allow to save the current simulation
     * @param ManagerRegistry $doctrine
     * @return JsonResponse
     */
    public function saveSimulation(ManagerRegistry $doctrine): Response
    {
        $this->serializeDB($doctrine); //serialize the database
        return $this->redirectToRoute('Simulations'); //redirect to the simulation page
    }

    /*
     * @brief Allow to change the current simulation
     * @param ManagerRegistry $doctrine
     * @return JsonResponse
     */
    public function changeSimulation(ManagerRegistry $doctrine): Response
    {
        $id = $_POST["id"]; //get the id of the simulation
        $currentSim = $doctrine->getRepository("App\Entity\SimulationInfo")->findOneBy(["iscurrent" => true]); //get the current simulation info
        if ($currentSim != null) { //if there is a current simulation info update it
            $currentSim->setCurrent(false); //set the current simulation info to false
            $doctrine->getRepository("App\Entity\SimulationInfo")->add($currentSim, true); //add the simulation info to the database
            $this->serializeDB($doctrine, true); //serialize the database
        }
        usleep(1000); //wait 1ms to avoid errors
        $dir = "Simulations"; //set the directory
        $simDir = $dir . "/" . $id; //set the simulation directory
        if (is_dir($simDir)) { //if the simulation directory exists
            $this->deSerializeDB($doctrine, $id); //deserialize the database
        }
        $simInfo = $doctrine->getRepository("App\Entity\SimulationInfo")->findOneBy(["id" => $id]); //get the simulation info
        $simInfo->setCurrent(true); //set the current simulation info to true
        $doctrine->getRepository("App\Entity\SimulationInfo")->add($simInfo, true); //add the simulation info to the database
        return $this->redirectToRoute('Simulations'); //redirect to the simulation page
    }

    /*
     * @brief Allow to delete a simulation
     * @param ManagerRegistry $doctrine
     * @return JsonResponse
     */
    public function deleteSimulation(ManagerRegistry $doctrine): Response
    {
        $id = $_POST["id"]; //get the id of the simulation
        $simInfo = $doctrine->getRepository("App\Entity\SimulationInfo")->findOneBy(["id" => $id]); //get the simulation info
        $iscurrent = $simInfo->Iscurrent(); //get if the simulation is the current simulation
        $doctrine->getRepository("App\Entity\SimulationInfo")->remove($simInfo, true); //remove the simulation info from the database
        $dir = "Simulations"; //set the directory
        $simDir = $dir . "/" . $id; //set the simulation directory
        if (is_dir($simDir)) { //if the simulation directory exists
            $it = new RecursiveDirectoryIterator($simDir, RecursiveDirectoryIterator::SKIP_DOTS); //get the files in the simulation directory
            $files = new RecursiveIteratorIterator( //get the files in the simulation directory
                $it,
                RecursiveIteratorIterator::CHILD_FIRST
            );
            foreach ($files as $file) {
                if ($file->isDir()) { //if the file is a directory
                    rmdir($file->getRealPath()); //remove the directory
                } else {
                    unlink($file->getRealPath()); //remove the file
                }
            }
            rmdir($simDir); //remove the simulation directory
        }
        if ($iscurrent) { //if the simulation is the current simulation
            $newSim = $doctrine->getRepository("App\Entity\SimulationInfo")->findBy(array(), array('id' => 'ASC'), 1, 0); //get the first simulation info
            if ($newSim != null) { //if there is a simulation info
                $this->deSerializeDB($doctrine, $newSim[0]->getId()); //deserialize the database
                $newSim[0]->setCurrent(true); //set the current simulation info to true
                $doctrine->getRepository("App\Entity\SimulationInfo")->add($newSim[0], true); //add the simulation info to the database
            }
        }
        return $this->redirectToRoute('Simulations'); //redirect to the simulation page
    }
}