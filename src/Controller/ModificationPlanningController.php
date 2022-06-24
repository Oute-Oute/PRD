<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Controller de la page modification du planning
 */
class ModificationPlanningController extends AbstractController
{
    /**
     * Fonction pour l'affichage de la page modification planning par la méthode GET
     */
    public function modificationPlanningGet(ManagerRegistry $doctrine): Response
    {
        $date_today = $_GET["date"];
        //Récupération des données nécessaires
        $listeResourceTypes = $doctrine->getRepository("App\Entity\ResourceType")->findAll(); 
        $listeResources = $doctrine->getRepository("App\Entity\Resource")->findBy(['able' => true]);
        $listePatients = $doctrine->getRepository("App\Entity\Patient")->findAll();
        
        $listeResourceJSON=$this->listeResourcesJSON($doctrine); 

        return $this->render('planning/modification-planning.html.twig', ['resourcestypes' => $listeResourceTypes, 'listeresources'=>$listeResources, 'listepatients'=>$listePatients, 'listeResourcesJSON'=>$listeResourceJSON, 'datetoday' => $date_today ]);
    }

    public function modificationPlanningPost(Request $request, ManagerRegistry $doctrine, EntityManagerInterface $entityManager)
    {
        $form = $request->request->get('form');
        
        dd($request);

        if($form == 'modify')
        {
            $title = $request->request->get('title');
            $start = $request->request->get('start');
            $length = $request->request->get('length');
            $id = $request->request->get('id');

            $repositoryPCR = $doctrine->getRepository('\App\Entity\PatientCircuitResource');
            
            if(isset($title) && isset($start) && isset($length) && isset($id)){
                $PCR = $repositoryPCR->find($id);
                $date_start = \DateTime::createFromFormat('Y-m-d H:i', str_replace("T", "", $start));
                $PCR->setStartDateTime($date_start);
                $entityManager->flush();
            }
        }
        else if($form == 'add')
        {
            echo "</br>" . "j'ajoute" . "</br>";
        }
    }

    public function listeResourcesJSON(ManagerRegistry $doctrine){
        $resources = $doctrine->getRepository("App\Entity\Resource")->findAll();  
        $resourcesArray=array(); 
        foreach($resources as $resource){
            $resourcesArray[]=array(
                'id' =>(str_replace(" ", "3aZt3r", $resource->getId())),
                'title'=>(str_replace(" ", "3aZt3r", $resource->getName())),
            ); 
        }   
        //Conversion des données ressources en json
        $resourcesArrayJson= new JsonResponse($resourcesArray); 
        return $resourcesArrayJson; 
    }

}
