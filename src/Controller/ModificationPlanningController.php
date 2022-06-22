<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;

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
        //Récupération des données ressources de la base de données
        $repository = $doctrine->getRepository("App\Entity\ResourceType");
        $resourcestypes = $repository->findAll();

        $resources = $this->getDoctrine()->getRepository("App\Entity\Resource")->findAll();  
        foreach($resources as $resources){
            $resourcesCollection[]=array(
                'id' =>($resources->getId()),
                'name'=>($resources->getName()),
            ); 
        }   
        $jsonresponse= new JsonResponse($resourcesCollection); 
        return $this->render('planning/modification-planning.html.twig', ['resourcestypes' => $resourcestypes,'resources'=>$jsonresponse ]);
    }

    public function modificationPlanningPost(Request $request, ManagerRegistry $doctrine)
    {
        $form = $request->request->get('form');
        $title = $request->request->get('title');
        $start = $request->request->get('start');
        $length = $request->request->get('length');

        if($form == 'modify')
        {
            $id = $request->request->get('id');
            echo "</br>" . "je modifie" . "</br>";

        }
        else if($form == 'add')
        {
            echo "</br>" . "j'ajoute" . "</br>";
        }

        //dd($request);
    }
}