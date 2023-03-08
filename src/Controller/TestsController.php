<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $entities[0] = $doctrine->getRepository("App\Entity\Patient")->findAll();
        $entities[1] = $doctrine->getRepository("App\Entity\Appointment")->findAll();
        $entities[2] = $doctrine->getRepository("App\Entity\HumanResource")->findAll();
        $entities[3] = $doctrine->getRepository("App\Entity\CategoryOfHumanResource")->findAll();
        $entities[4] = $doctrine->getRepository("App\Entity\WorkingHours")->findAll();
        $entities[5] = $doctrine->getRepository("App\Entity\MaterialResource")->findAll();
        $entities[6] = $doctrine->getRepository("App\Entity\CategoryOfMaterialResource")->findAll();
        $entities[7] = $doctrine->getRepository("App\Entity\Unavailability")->findAll();
        $entities[8] = $doctrine->getRepository("App\Entity\UnavailabilityHumanResource")->findAll();
        $entities[9] = $doctrine->getRepository("App\Entity\UnavailabilityMaterialResource")->findAll();
        $entities[10] = $doctrine->getRepository("App\Entity\ScheduledActivity")->findAll();
        $entities[11] = $doctrine->getRepository("App\Entity\HumanResourceScheduled")->findAll();
        $entities[12] = $doctrine->getRepository("App\Entity\MaterialResourceScheduled")->findAll();
        //var_dump($doctrine->getRepository("App\Entity\HumanResourceScheduled"));
        $dir = "Simulations";
        $newDir = $dir . "/2";
        if (!file_exists($newDir)) {
            mkdir($newDir, 0777, true);
        }
        for ($i = 0; $i < sizeof($entities); $i++) {
            $file = fopen($newDir . "/" . $filenames[$i] . ".json", "w");
            $serializer = new Serializer([new ObjectNormalizer()]);
            $formatted = $serializer->normalize($entities[$i]);
            fwrite($file, json_encode($formatted));
            fclose($file);
        }
        //drain the tables
        $doctrine->getRepository("App\Entity\HumanResourceScheduled")->deleteALl();
        $doctrine->getRepository("App\Entity\MaterialResourceScheduled")->deleteALl();
        $doctrine->getRepository("App\Entity\ScheduledActivity")->deleteALl();
        $doctrine->getRepository("App\Entity\UnavailabilityHumanResource")->deleteALl();
        $doctrine->getRepository("App\Entity\UnavailabilityMaterialResource")->deleteALl();
        $doctrine->getRepository("App\Entity\Unavailability")->deleteALl();
        $doctrine->getRepository("App\Entity\WorkingHours")->deleteALl();
        $doctrine->getRepository("App\Entity\HumanResource")->deleteALl();
        $doctrine->getRepository("App\Entity\CategoryOfHumanResource")->deleteALl();
        $doctrine->getRepository("App\Entity\MaterialResource")->deleteALl();
        $doctrine->getRepository("App\Entity\CategoryOfMaterialResource")->deleteALl();
        $doctrine->getRepository("App\Entity\Appointment")->deleteALl();
        $doctrine->getRepository("App\Entity\Patient")->deleteALl();


        return new JsonResponse($formatted);
    }

}