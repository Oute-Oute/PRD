<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Repository\AppointmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;

use function PHPUnit\Framework\isNull;

class AppointmentController extends AbstractController
{
    public function appointmentGet(AppointmentRepository $appointmentRepository, ManagerRegistry $doctrine): Response
    {
        return $this->render('appointment/index.html.twig', [
            'appointments' => $appointmentRepository->findAll(),
            'patients' => $doctrine->getManager()->getRepository("App\Entity\Patient")->findall(),
            'pathways' => $doctrine->getManager()->getRepository("App\Entity\Pathway")->findall()
        ]);
    }

    public function appointmentPost(Request $request, AppointmentRepository $appointmentRepository, ManagerRegistry $doctrine): Response
    {
        // On recupere toutes les données de la requete
        $param = $request->request->all();

        if(count($param) != 5)
        {
            return $this->redirectToRoute('Appointment', [], Response::HTTP_SEE_OTHER);
        }

        $patient = $doctrine->getManager()->getRepository("App\Entity\Patient")->findOneBy(['id' => $param['idpatient']]);
        $pathway = $doctrine->getManager()->getRepository("App\Entity\Pathway")->findOneBy(['id' => $param['idpathway']]);
        $dayappointment = \DateTime::createFromFormat('Y-m-d', $param['dayappointment']);
        $earliestappointmenttime = \DateTime::createFromFormat('H:i', $param['earliestappointmenttime']);
        $latestappointmenttime = \DateTime::createFromFormat('H:i', $param['latestappointmenttime']);
        //dd($pathway);

        // Création du patient
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
}