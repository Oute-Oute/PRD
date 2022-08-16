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

class PatientController extends AbstractController
{

    /**
      * @brief Allows to list every patients in the database with the paginator
     */
    public function patientGet(Request $request, PaginatorInterface $paginator,PatientRepository $patientRepository, ManagerRegistry $doctrine): Response
    {
        $patients=$paginator->paginate(
            $patientRepository->findAllPatient(),
            $request->query->getInt('page',1),
            8
        ); 
        return $this->render('patient/index.html.twig', ['patients' => $patients]);
    }
    
    /**
      * @brief Allows to list every patients in the database without pagination
     */
    public function getAllPatient(PatientRepository $patientRepository){
        $patient=$patientRepository->findBy(array(),array('lastname' => 'ASC')); 
        return $patient; 

    }

    /**
      * @brief Allows to add a patient in the database
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
      * @brief Allows to edit a patient that is already in the database
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
      * @brief Allows to delete a patient that is already in the database
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
      * @brief Allows to display the data of a specific patient
     */
    public function getDataPatient(ManagerRegistry $doctrine)
    {
        $appointments = $this->getAppointmentByPatientId($_POST["idPatient"], $doctrine);
        return new JsonResponse($appointments);
    }

    /**
      * @brief Allows to display every appointments linked to a specific patient
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
      * @brief Allows to autocomplete the searchbar with the firstname and lastname of patients that are in database
     */
    public function autocompletePatient(Request $request, PatientRepository $patientRepository)
    {
        $utf8 = array( 
            "œ"=>"oe",
            "æ"=>"ae",
            "à" => "a",
            "á" => "a",
            "â" => "a",
            "à" => "a",
            "ä" => "a",
            "å" => "a",
            "&#257;" => "a",
            "&#259;" => "a",
            "&#462;" => "a",
            "&#7841;" => "a",
            "&#7843;" => "a",
            "&#7845;" => "a",
            "&#7847;" => "a",
            "&#7849;" => "a",
            "&#7851;" => "a",
            "&#7853;" => "a",
            "&#7855;" => "a",
            "&#7857;" => "a",
            "&#7859;" => "a",
            "&#7861;" => "a",
            "&#7863;" => "a",
            "&#507;" => "a",
            "&#261;" => "a",
            "ç" => "c",
            "&#263;" => "c",
            "&#265;" => "c",
            "&#267;" => "c",
            "&#269;" => "c",
            "&#271;" => "d",
            "&#273;" => "d",
            "è" => "e",
            "é" => "e",
            "ê" => "e",
            "ë" => "e",
            "&#275;" => "e",
            "&#277;" => "e",
            "&#279;" => "e",
            "&#281;" => "e",
            "&#283;" => "e",
            "&#7865;" => "e",
            "&#7867;" => "e",
            "&#7869;" => "e",
            "&#7871;" => "e",
            "&#7873;" => "e",
            "&#7875;" => "e",
            "&#7877;" => "e",
            "&#7879;" => "e",
            "&#285;" => "g",
            "&#287;" => "g",
            "&#289;" => "g",
            "&#291;" => "g",
            "&#293;" => "h",
            "&#295;" => "h",
            "&#309;" => "j",
            "&#314;" => "l",
            "&#316;" => "l",
            "&#318;" => "l",
            "&#320;" => "l",
            "&#322;" => "l",
            "ñ" => "n",
            "&#324;" => "n",
            "&#326;" => "n",
            "&#328;" => "n",
            "&#329;" => "n",
            "ò" => "o",
            "ó" => "o",
            "ô" => "o",
            "õ" => "o",
            "ö" => "o",
            "ø" => "o",
            "&#333;" => "o",
            "&#335;" => "o",
            "&#337;" => "o",
            "&#417;" => "o",
            "&#466;" => "o",
            "&#511;" => "o",
            "&#7885;" => "o",
            "&#7887;" => "o",
            "&#7889;" => "o",
            "&#7891;" => "o",
            "&#7893;" => "o",
            "&#7895;" => "o",
            "&#7897;" => "o",
            "&#7899;" => "o",
            "&#7901;" => "o",
            "&#7903;" => "o",
            "&#7905;" => "o",
            "&#7907;" => "o",
            "ð" => "o",
            "&#341;" => "r",
            "&#343;" => "r",
            "&#345;" => "r",
            "&#347;" => "s",
            "&#349;" => "s",
            "&#351;" => "s",
            "&#355;" => "t",
            "&#357;" => "t",
            "&#359;" => "t",
            "ù" => "u",
            "ú" => "u",
            "û" => "u",
            "ü" => "u",
            "&#361;" => "u",
            "&#363;" => "u",
            "&#365;" => "u",
            "&#367;" => "u",
            "&#369;" => "u",
            "&#371;" => "u",
            "&#432;" => "u",
            "&#468;" => "u",
            "&#470;" => "u",
            "&#472;" => "u",
            "&#474;" => "u",
            "&#476;" => "u",
            "&#7909;" => "u",
            "&#7911;" => "u",
            "&#7913;" => "u",
            "&#7915;" => "u",
            "&#7917;" => "u",
            "&#7919;" => "u",
            "&#7921;" => "u",
            "&#373;" => "w",
            "&#7809;" => "w",
            "&#7811;" => "w",
            "&#7813;" => "w",
            "ý" => "y",
            "ÿ" => "y",
            "&#375;" => "y",
            "&#7929;" => "y",
            "&#7925;" => "y",
            "&#7927;" => "y",
            "&#7923;" => "y",
            );
        $term = strtr(mb_strtolower($request->query->get('term'),'UTF-8'), $utf8);
        $patients = $patientRepository->findAll();
        $results = array();
        foreach ($patients as $patient) {
            if (   strpos(strtr(mb_strtolower($patient->getLastname()." ".$patient->getFirstname(),'UTF-8'),$utf8), $term) !== false 
                || strpos(strtr(mb_strtolower($patient->getFirstname()." ".$patient->getLastname(),'UTF-8'),$utf8), $term) !== false) {
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
