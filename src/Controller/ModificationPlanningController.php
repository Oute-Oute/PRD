<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

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

        return $this->render('planning/modification-planning.html.twig', ['resourcestypes' => $resourcestypes ]);
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