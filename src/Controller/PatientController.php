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
            'patients' => $patientRepository->findAll()
        ]);
    }

    public function patientPost(Request $request, PatientRepository $patientRepository): Response
    {
        // Méthode POST pour ajouter un circuit
        if ($request->getMethod() === 'POST' ) {
            // On recupere toutes les données de la requete
            $param = $request->request->all();

            $lastname = $param['lastname'];       // le nom
            $firstname = $param['firstname'];     // le prenom

            // Création du patient
            $patient = new Patient(); 
            $patient->setLastname($lastname);
            $patient->setFirstname($firstname);
            
            // ajout dans la bdd
            $patientRepository->add($patient, true);

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
        //suppression des données associées au patient de la table Appointment
        $appointmentRepository = $this->getDoctrine()->getManager()->getRepository("App\Entity\Appointment");
        $appointments = $appointmentRepository->findBy(['patient_id' => $patient->getId()]);

        foreach($appointments as $appointment)
        {
            $appointmentRepository->remove($appointment, true);
        }


        //suppression des données associées au patient dans les tables ScheduledActivity, MaterialResourceScheduled et HumanResourceScheduled 
        $scheduledActivityRepository = $this->getDoctrine()->getManager()->getRepository("App\Entity\ScheduledActivity");
        $scheduledActivities = $scheduledActivityRepository->findBy(['patient_id' => $patient->getId()]);

        foreach($scheduledActivities as $scheduledActivity)
        {
            //suppression des données associées au patient de la table MaterialResourceScheduled
            $materialResourceScheduledRepository = $this->getDoctrine()->getManager()->getRepository("App\Entity\MaterialResourceScheduled");
            $allMaterialResourceScheduled = $materialResourceScheduledRepository->findBy(['scheduledactivity_id' => $scheduledActivity->getId()]);

            foreach($allMaterialResourceScheduled as $materialResourceScheduled)
            {
                $materialResourceScheduledRepository->remove($materialResourceScheduled, true);
            }


            //suppression des données associées au patient de la table HumanResourceScheduled
            $humanResourceScheduledRepository = $this->getDoctrine()->getManager()->getRepository("App\Entity\HumanResourceScheduled");
            $allHumanResourceScheduled = $humanResourceScheduledRepository->findBy(['scheduledactivity_id' => $scheduledActivity->getId()]);

            foreach($allHumanResourceScheduled as $humanResourceScheduled)
            {
                $humanResourceScheduledRepository->remove($humanResourceScheduled, true);
            }


            //suppression des données associées au patient de la table ScheduledActivity
            $scheduledActivityRepository->remove($scheduledActivity, true);
        }

        //suppression du patient dans la table Patient
        $patientRepository->remove($patient, true);

        return $this->redirectToRoute('Patients', [], Response::HTTP_SEE_OTHER);
    }
}
