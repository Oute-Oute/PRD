<?php

namespace App\Controller;

use App\Entity\Pathway;
use App\Entity\AP;
use App\Form\PathwayType;
use App\Repository\PathwayRepository;
use App\Repository\ActivityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

/**
 * @Route("/circuit")
 */
class PathwayController extends AbstractController
{
    public function index(PathwayRepository $pathwayRepository): Response
    {
        $activityRepository = new ActivityRepository($this->getDoctrine());
        $activities = $activityRepository->findAll();
        return $this->render('pathway/index.html.twig', [
            'pathways' => $pathwayRepository->findAll(),
            'activities' => $activities,
        ]);
    }

    public function new(Request $request, PathwayRepository $pathwayRepository): Response
    {

        // Méthode POST pour ajouter un circuit
        if ($request->getMethod() === 'POST' ) {
            
            // On recupere toutes les données de la requete
            $param = $request->request->all();
            // et le nombre d'activité
            $nbActivity = count($param) - 2;
            $pathway = new Pathway();
            $pathway->setPathwayname($param['circuitname']);
            $pathway->setPathwaytype($param['circuittype']);

            $activities = $this->getDoctrine()->getManager()->getRepository("App\Entity\Activity")->findAll();
            $activityPathwayRepository = $this->getDoctrine()->getManager()->getRepository("App\Entity\AP");

            $pathwayRepository->add($pathway, true);

            for($i = 0; $i < $nbActivity; $i++)
            {
                $str = 'activity-';
                $activityPathway = new AP();
                $str .= $i;
                $numListe = $param[$str]-1;
                $activityPathway->setActivity($activities[$numListe]);
                $activityPathway->setPathway($pathway);
                $activityPathwayRepository->add($activityPathway, true);
                dd($activityPathway);
            }
            
            return $this->redirectToRoute('Pathways', [], Response::HTTP_SEE_OTHER);
        }
        
        
    }

    /**
     * @Route("/{id}", name="app_circuit_show", methods={"GET"})
     */
    public function show(Pathway $pathway): Response
    {
        return $this->render('pathway/show.html.twig', [
            'pathway' => $pathway,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_circuit_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Pathway $pathway, PathwayRepository $pathwayRepository): Response
    {
        $form = $this->createForm(CircuitType::class, $pathway);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pathwayRepository->add($pathway, true);

            return $this->redirectToRoute('Pathways', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pathway/edit.html.twig', [
            'pathway' => $pathway,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_circuit_delete", methods={"POST"})
     */
    public function delete(Request $request, Pathway $pathway, PathwayRepository $pathwayRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pathway->getId(), $request->request->get('_token'))) {
            $pathwayRepository->remove($pathway, true);
        }

        return $this->redirectToRoute('Pathways', [], Response::HTTP_SEE_OTHER);
    }
}
