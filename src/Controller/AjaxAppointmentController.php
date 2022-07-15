<?php

namespace App\Controller;
//AJAX request for the pathway to display on datepicker

use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Appointment;
use App\Repository\AppointmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

class AjaxAppointmentController extends AbstractController
{

    public function getTargets(ManagerRegistry $doctrine)
    {
        $pathway = $this->getPathwayByName($_POST["pathway"], $doctrine);
        $targets = $this->getTargetByPathwayJSON($doctrine, $pathway);
        return new JsonResponse($targets);
    }

    public function getPathwayByName($name, ManagerRegistry $doctrine)
    {
        $pathway = $doctrine->getManager()->getRepository("App\Entity\Pathway")->findBy(["pathwayname"=>$name]);
        $pathArray=[];
        foreach ($pathway as $path) {
            $pathArray[] = [
                'id' => $path->getId(),
                'pathwayname' => $path->getPathwayname()
            ];
        }

        return $pathway;
    }

    public function getTargetByPathwayJSON(ManagerRegistry $doctrine, $pathway)
    {
        $targets = $doctrine->getRepository("App\Entity\Target")->findBy(["pathway" => $pathway]);
        $targetsJSON = [];
        foreach ($targets as $target) {
            $targetsJSON[] = [
                'id' => $target->getId(),
                'dayweek' => $target->getDayweek(),
                'target' => $target->getTarget()
            ];
        }
        return $targetsJSON;
    }
}
