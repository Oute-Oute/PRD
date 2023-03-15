<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


class TestsController extends AbstractController
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
        //render the view
        return $this->render('tests/index.html.twig', [
            'controller_name' => 'TestsController',
            'currentdate' => $date,
        ]);
    }

    public function serializeDB(ManagerRegistry $doctrine): Response
    {
        $entities = [];
        $filenames = ["patients", "appointments", "humanresources", "categoryofhumanresources", "workinghours", "materialresources", "categoryofmaterialresources", "unavailabilities", "unavailabilityhumanresources", "unavailabilitymaterialresources", "scheduledactivities", "humanresourcescheduled", "materialresourcescheduled"];
        $entitiesNames = ["Patient", "Appointment", "HumanResource", "CategoryOfHumanResource", "WorkingHours", "MaterialResource", "CategoryOfMaterialResource", "Unavailability", "UnavailabilityHumanResource", "UnavailabilityMaterialResource", "ScheduledActivity", "HumanResourceScheduled", "MaterialResourceScheduled"];
        $sequenceNames = ["patient", "appointment", "human_resource", "category_of_human_resource", "working_hours", "material_resource", "category_of_material_resource", "unavailability", "unavailability_human_resource", "unavailability_material_resource", "scheduled_activity", "human_resource_scheduled", "material_resource_scheduled"];
        for ($i = 0; $i < sizeof($entitiesNames); $i++) {
            $entities[$i] = $doctrine->getRepository("App\Entity\\" . $entitiesNames[$i])->findAll();
        }
        $dir = "Simulations";
        $newDir = $dir . "/4";
        if (!file_exists($newDir)) {
            mkdir($newDir, 0777, true);
        }
        for ($i = 0; $i < sizeof($entities); $i++) {
            $file = fopen($newDir . "/" . $filenames[$i] . ".json", "w");
            $serializer = new Serializer([new DateTimeNormalizer(), new ObjectNormalizer()]);
            $formatted = $serializer->normalize($entities[$i]);
            fwrite($file, json_encode($formatted));
            fclose($file);
        }
        //drain the tables
        for ($i = 0; $i < sizeof($entitiesNames); $i++) {
            $doctrine->getRepository("App\Entity\\" . $entitiesNames[$i])->deleteALl();
        }
        //reset sqlLite sequences
        for ($i = 0; $i < sizeof($sequenceNames); $i++) {
            $doctrine->getConnection()->exec("DELETE FROM sqlite_sequence WHERE name = '" . $sequenceNames[$i] . "'");
        }
        return new JsonResponse($formatted);
    }

    public function deSerializeDB(ManagerRegistry $doctrine): Response
    {
        $entities = [];
        $filenames = ["patients", "appointments", "humanresources", "categoryofhumanresources", "workinghours", "materialresources", "categoryofmaterialresources", "unavailabilities", "unavailabilityhumanresources", "unavailabilitymaterialresources", "scheduledactivities", "humanresourcescheduled", "materialresourcescheduled"];
        $entitiesNames = ["Patient", "Appointment", "HumanResource", "CategoryOfHumanResource", "WorkingHours", "MaterialResource", "CategoryOfMaterialResource", "Unavailability", "UnavailabilityHumanResource", "UnavailabilityMaterialResource", "ScheduledActivity", "HumanResourceScheduled", "MaterialResourceScheduled"];
        $dir = "Simulations";
        $newDir = $dir . "/3";
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

}