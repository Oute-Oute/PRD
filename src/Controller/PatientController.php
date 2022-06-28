<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Entity\PP;
use App\Form\PatientType;
use App\Repository\PatientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @Route("/patient")
 */
class PatientController extends AbstractController
{
    public function patientGet(PatientRepository $patientRepository, ManagerRegistry $doctrine): Response
    {
        return $this->render('patient/index.html.twig', [
            'patients' => $patientRepository->findAll(),
            'pathways' => $doctrine->getRepository("App\Entity\Pathway")->findAll()
        ]);
    }

    public function patientPost(Request $request, PatientRepository $patientRepository): Response
    {
        // Méthode POST pour ajouter un circuit
        if ($request->getMethod() === 'POST' ) {
    
            // On recupere toutes les données de la requete
            $param = $request->request->all();

            $lastname = $param['lastname'];             // le nom
            $firstname = $param['firstname'];     // le prenom

            // Création de l'activité
            $patient = new Patient(); 
            $patient->setLastname($lastname);
            $patient->setFirstname($firstname);
          
            // ajout dans la bd 
            $patientRepository->add($patient, false);

            return $this->redirectToRoute('Patients', [], Response::HTTP_SEE_OTHER);
        }
    }

    public function patientEdit(Request $request, Patient $patient, PatientRepository $patientRepository): Response
    {
        $form = $this->createForm(PatientType::class, $patient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $patientRepository->add($patient, true);

            return $this->redirectToRoute('Patients', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('patient/edit.html.twig', [
            'patient' => $patient,
            'form' => $form,
        ]);
    }

    public function patientDelete(Patient $patient, PatientRepository $patientRepository): Response
    {
        $appointmentRepository = $this->getDoctrine()->getManager()->getRepository("App\Entity\Appointment");
        $appointments = $appointmentRepository->findAll();
        foreach($appointments as $appointment)
        {
            if($appointment->getPatient() == $patient)
            {
                $appointmentRepository->remove($appointment, true);
            }
        }

        $appointmentRepository = $this->getDoctrine()->getManager()->getRepository("App\Entity\ScheduledActivity");
        $appointments = $appointmentRepository->findAll();
        foreach($appointments as $appointment)
        {
            if($appointment->getPatient() == $patient)
            {
                $appointmentRepository->remove($appointment, true);
            }
        }

        $patientRepository->remove($patient, true);
        return $this->redirectToRoute('Patients', [], Response::HTTP_SEE_OTHER);
    }
}
