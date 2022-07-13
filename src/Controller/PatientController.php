<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Repository\PatientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\UnavailabilityMaterialResourceRepository;
use App\Repository\UnavailabilityHumanResourceRepository;

class PatientController extends AbstractController
{
    public function patientGet(PatientRepository $patientRepository, ManagerRegistry $doctrine): Response
    {
        //créer la page de gestion des patients en envoyant la liste de tous les patients stockés en database
        return $this->render('patient/index.html.twig', [
            'patients' => $patientRepository->findAll(),
            'currentappointments' => $doctrine->getManager()->getRepository("App\Entity\Appointment")->findall()
        ]);
    }
    
    public function patientAdd(Request $request, PatientRepository $patientRepository): Response
    {
        // On recupere toutes les données de la requête
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

    public function patientEdit(Request $request, PatientRepository $patientRepository, EntityManagerInterface $entityManager): Response
    {
        //on récupère les nouvelles informations sur le patient
        $idpatient = $request->request->get("idpatient");
        $lastname = $request->request->get("lastname");
        $firstname = $request->request->get("firstname");

        //on récupère le patient grâce à son id
        $patient = $patientRepository->findOneBy(['id' => $idpatient]);

        //on modifie les données du patient
        $patient->setLastname($lastname);
        $patient->setFirstname($firstname);

        //on met à jour le patient dans la bdd
        $entityManager->persist($patient);
        $entityManager->flush();

        return $this->redirectToRoute('Patients', [], Response::HTTP_SEE_OTHER);
    }

    public function patientDelete(Patient $patient, EntityManagerInterface $entityManager, PatientRepository $patientRepository, UnavailabilityMaterialResourceRepository $unavailabilityMaterialResourceRepository, UnavailabilityHumanResourceRepository $unavailabilityHumanResourceRepository): Response
    {
        //suppression des données associées au patient de la table Appointment
        $appointmentRepository = $this->getDoctrine()->getManager()->getRepository("App\Entity\Appointment");
        $appointments = $appointmentRepository->findBy(['patient' => $patient]);

        foreach($appointments as $appointment)
        {
            $date = $appointment->getDayappointment()->format('Y-m-d');

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

                    $strDate = substr($date, 0, 10);
                    $strStart = $strDate . " " . $scheduledActivity->getStarttime()->format('H:i:s');

                    $listUnavailabilityMaterialResource = $unavailabilityMaterialResourceRepository->findUnavailabilityMaterialResourceByDate($strStart, $materialResourceScheduled->getMaterialresource()->getId());

                    foreach($listUnavailabilityMaterialResource as $unavailabilityMaterialResource)
                    {
                        $unavailability = $unavailabilityMaterialResource->getUnavailability();
                        $entityManager->remove($unavailabilityMaterialResource);
                        $entityManager->flush($unavailabilityMaterialResource);
                        $entityManager->remove($unavailability);
                        $entityManager->flush($unavailability);
                    }
                }


                //suppression des données associées au patient de la table HumanResourceScheduled
                $humanResourceScheduledRepository = $this->getDoctrine()->getManager()->getRepository("App\Entity\HumanResourceScheduled");
                $allHumanResourceScheduled = $humanResourceScheduledRepository->findBy(['scheduledactivity' => $scheduledActivity]);

                foreach($allHumanResourceScheduled as $humanResourceScheduled)
                {
                    $humanResourceScheduledRepository->remove($humanResourceScheduled, true);

                    $strDate = substr($date, 0, 10);
                    $strStart = $strDate . " " . $scheduledActivity->getStarttime()->format('H:i:s');

                    $listUnavailabilityHumanResource = $unavailabilityHumanResourceRepository->findUnavailabilityHumanResourceByDate($strStart, $humanResourceScheduled->getHumanresource()->getId());

                    foreach($listUnavailabilityHumanResource as $unavailabilityHumanResource)
                    {
                        $unavailability = $unavailabilityHumanResource->getUnavailability();
                        $entityManager->remove($unavailabilityHumanResource);
                        $entityManager->flush($unavailabilityHumanResource);
                        $entityManager->remove($unavailability);
                        $entityManager->flush($unavailability);
                    }
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
