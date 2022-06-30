<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Repository\PatientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

class PatientController extends AbstractController
{
    public function patientGet(PatientRepository $patientRepository): Response
    {
        return $this->render('patient/index.html.twig', [
            'patients' => $patientRepository->findAll()
        ]);
    }

    public function patientAdd(Request $request, PatientRepository $patientRepository): Response
    {
        // Méthode POST pour ajouter un patient
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

    public function patientEdit(Request $request, PatientRepository $patientRepository, EntityManagerInterface $entityManager): Response
    {
        $idpatient = $request->request->get("idpatient");
        $lastname = $request->request->get("lastname");
        $firstname = $request->request->get("firstname");

        $patient = $patientRepository->findOneBy(['id' => $idpatient]);
        $patient->setLastname($lastname);
        $patient->setFirstname($firstname);

        $entityManager->persist($patient);
        $entityManager->flush();

        return $this->redirectToRoute('Patients', [], Response::HTTP_SEE_OTHER);
    }

    public function patientDelete(Patient $patient, PatientRepository $patientRepository): Response
    {
        //suppression des données associées au patient de la table Appointment
        $appointmentRepository = $this->getDoctrine()->getManager()->getRepository("App\Entity\Appointment");
        $appointments = $appointmentRepository->findBy(['patient' => $patient]);

        foreach($appointments as $appointment)
        {
            //suppression des données associées au patient dans les tables ScheduledActivity, MaterialResourceScheduled et HumanResourceScheduled 
            $scheduledActivityRepository = $this->getDoctrine()->getManager()->getRepository("App\Entity\ScheduledActivity");
            $scheduledActivities = $scheduledActivityRepository->findBy(['appointment' => $appointment]);

            foreach($scheduledActivities as $scheduledActivity)
            {
                //suppression des données associées au patient de la table MaterialResourceScheduled
                $materialResourceScheduledRepository = $this->getDoctrine()->getManager()->getRepository("App\Entity\MaterialResourceScheduled");
                $allMaterialResourceScheduled = $materialResourceScheduledRepository->findBy(['scheduledactivity' => $scheduledActivity]);

                foreach($allMaterialResourceScheduled as $materialResourceScheduled)
                {
                    $materialResourceScheduledRepository->remove($materialResourceScheduled, true);
                }


                //suppression des données associées au patient de la table HumanResourceScheduled
                $humanResourceScheduledRepository = $this->getDoctrine()->getManager()->getRepository("App\Entity\HumanResourceScheduled");
                $allHumanResourceScheduled = $humanResourceScheduledRepository->findBy(['scheduledactivity' => $scheduledActivity]);

                foreach($allHumanResourceScheduled as $humanResourceScheduled)
                {
                    $humanResourceScheduledRepository->remove($humanResourceScheduled, true);
                }


                //suppression des données associées au patient de la table ScheduledActivity
                $scheduledActivityRepository->remove($scheduledActivity, true);
            }
            $appointmentRepository->remove($appointment, true);
        }

        //suppression du patient dans la table Patient
        $patientRepository->remove($patient, true);

        return $this->redirectToRoute('Patients', [], Response::HTTP_SEE_OTHER);
    }
}
