<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @file        PatientController.php
 * @brief       Contains the functions that allow to handle the patients
 * @details     Allows to create, read, update, delete every patients
 * @date        2022
 */

class PatientController extends AbstractController
{

    /**
      * Allows to list every patients in the database with the paginator
     */
    public function patientGet(Request $request, PaginatorInterface $paginator,PatientRepository $patientRepository, ManagerRegistry $doctrine): Response
    {
        $patients=$this->getAllPatient($patientRepository); 
        $patients=$paginator->paginate(
            $patientRepository->findAllPatient(),
            $request->query->getInt('page',1),
            8
        ); 
        return $this->render('patient/index.html.twig', ['patients' => $patients]);
    }
    
    /**
      * Allows to list every patients in the database without pagination
     */
    public function getAllPatient(PatientRepository $patientRepository){
        $patient=$patientRepository->findBy(array(),array('lastname' => 'ASC')); 
        return $patient; 

    }

    /**
      * Allows to add a patient in the database
     */
    public function patientAdd(Request $request, PatientRepository $patientRepository): Response
    {
        //Get parameters from the request
        $param = $request->request->all();

        $lastname = $param['lastname'];       // le nom
        $firstname = $param['firstname'];     // le prenom

        //Creating the patient
        $patient = new Patient(); 
        $patient->setLastname($lastname);
        $patient->setFirstname($firstname);
            
        //Adding the patient in the database
        $patientRepository->add($patient, true);

        return $this->redirectToRoute('Patients', [], Response::HTTP_SEE_OTHER);
    }

    /**
      * Allows to edit a patient that is already in the database
     */
    public function patientEdit(Request $request, PatientRepository $patientRepository, EntityManagerInterface $entityManager): Response
    {
        //Get the new data of the patient
        $idpatient = $request->request->get("idpatient");
        $lastname = $request->request->get("lastname");
        $firstname = $request->request->get("firstname");

        //Get the edited patient with his id
        $patient = $patientRepository->findOneBy(['id' => $idpatient]);

        //Modifying the patient attributes
        $patient->setLastname($lastname);
        $patient->setFirstname($firstname);

        //Updating the patient in the database
        $entityManager->persist($patient);
        $entityManager->flush();

        return $this->redirectToRoute('Patients', [], Response::HTTP_SEE_OTHER);
    }

    /**
      * Allows to delete a patient that is already in the database
     */
    public function patientDelete(Patient $patient, EntityManagerInterface $entityManager, PatientRepository $patientRepository,ManagerRegistry $doctrine): Response
    {
        //Deleting linked data to the patient
        $appointmentRepository = $doctrine->getManager()->getRepository("App\Entity\Appointment");
        $appointments = $appointmentRepository->findBy(['patient' => $patient]);

        foreach($appointments as $appointment)
        {
            $date = $appointment->getDayappointment()->format('Y-m-d');
             
            $scheduledActivityRepository = $doctrine->getManager()->getRepository("App\Entity\ScheduledActivity");
            $scheduledActivities = $scheduledActivityRepository->findBy(['appointment' => $appointment]);

            foreach($scheduledActivities as $scheduledActivity)
            {
                
                $materialResourceScheduledRepository = $doctrine->getManager()->getRepository("App\Entity\MaterialResourceScheduled");
                $allMaterialResourceScheduled = $materialResourceScheduledRepository->findBy(['scheduledactivity' => $scheduledActivity]);

                foreach($allMaterialResourceScheduled as $materialResourceScheduled)
                {
                    $materialResourceScheduledRepository->remove($materialResourceScheduled, true);
                }

                $humanResourceScheduledRepository = $doctrine->getManager()->getRepository("App\Entity\HumanResourceScheduled");
                $allHumanResourceScheduled = $humanResourceScheduledRepository->findBy(['scheduledactivity' => $scheduledActivity]);

                foreach($allHumanResourceScheduled as $humanResourceScheduled)
                {
                    $humanResourceScheduledRepository->remove($humanResourceScheduled, true);
                }

                $scheduledActivityRepository->remove($scheduledActivity, true);
            }
            $appointmentRepository->remove($appointment, true);
        }

        //Deleting the patient in the database
        $patientRepository->remove($patient, true);

        return $this->redirectToRoute('Patients', [], Response::HTTP_SEE_OTHER);
    }

    /**
      * Allows to display the data of a specific patient
     */
    public function getDataPatient(ManagerRegistry $doctrine)
    {
        $appointments = $this->getAppointmentByPatientId($_POST["idPatient"], $doctrine);
        return new JsonResponse($appointments);
    }

    /**
      * Allows to display every appointments linked to a specific patient
     */
    public function getAppointmentByPatientId($id, ManagerRegistry $doctrine)
    {
        $patient = $doctrine->getManager()->getRepository("App\Entity\Patient")->findOneBy(["id"=>$id]);
        $appointments = $doctrine->getManager()->getRepository("App\Entity\Appointment")->findBy(["patient"=>$patient]);
        $appointmentArray=[];
        foreach ($appointments as $appointment) {
            $date = $appointment->getDayappointment()->format('U');
            if($date >= date('U')){
                $appointmentArray[] = [
                    'pathwayname' => $appointment->getPathway()->getPathwayname(),
                    'date' => $appointment->getDayappointment()->format('d-m-Y'),
                ];
            }
        }

        return $appointmentArray;
    }

    /**
      * Allows to autocomplete the searchbar with the firstname and lastname of patients that are in database
     */
    public function autocompletePatient(Request $request, PatientRepository $patientRepository)
    {
        $term = strtolower($request->query->get('term'));
        $patients = $patientRepository->findAll();
        $results = array();
        foreach ($patients as $patient) {
            if (   strpos(strtolower($patient->getLastname()), $term) !== false 
                || strpos(strtolower($patient->getFirstname()), $term) !== false 
                || strpos(strtolower($patient->getLastname()." ".$patient->getFirstname()), $term) !== false 
                || strpos(strtolower($patient->getFirstname()." ".$patient->getLastname()), $term) !== false) {
                $results[] = [
                    'id' => $patient->getId(),
                    'value' => $patient->getLastname() . ' ' . $patient->getFirstname(),
                    'firstname' => $patient->getFirstname(),
                    'lastname' => $patient->getLastname(),

                ];
            }
        }
        return new JsonResponse($results);
    }
}
