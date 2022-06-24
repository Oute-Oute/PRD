<?php

namespace App\Controller;

use App\Entity\Circuit;
use App\Entity\ActivityCircuit;
use App\Form\CircuitType;
use App\Repository\CircuitRepository;
use App\Repository\ActivityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

/**
 * @Route("/circuit")
 */
class CircuitController extends AbstractController
{
    /**
     * @Route("/", name="app_circuit_index", methods={"GET"})
     */
    public function index(CircuitRepository $circuitRepository): Response
    {
        return $this->render('circuit/index.html.twig', [
            'circuits' => $circuitRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_circuit_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CircuitRepository $circuitRepository): Response
    {

        // Méthode POST pour ajouter un circuit
        if ($request->getMethod() === 'POST' ) {
            
            // On recupere toutes les données de la requete
            $param = $request->request->all();
            // et le nombre d'activité
            $nbActivity = count($param) - 2;
            $circuit = new Circuit();
            $circuit->setCircuitname($param['name']);
            $circuit->setCircuittype($param['type']);

            $activities = $this->getDoctrine()->getManager()->getRepository("App\Entity\Activity")->findAll();
            $activityCircuitRepository = $this->getDoctrine()->getManager()->getRepository("App\Entity\ActivityCircuit");

            $circuitRepository->add($circuit, true);

            for($i = 0; $i < $nbActivity; $i++)
            {
                $str = 'activity-';
                $activityCircuit = new ActivityCircuit();
                $str .= $i;
                $numListe = $param[$str]-1;
                $activityCircuit->setActivity($activities[$numListe]);
                $activityCircuit->setCircuit($circuit);
                $activityCircuitRepository->add($activityCircuit, true);
                dd($activityCircuit);
            }
            
            return $this->redirectToRoute('app_circuit_index', [], Response::HTTP_SEE_OTHER);
        }
        
        $circuit = new Circuit();

        $activityRepository = new ActivityRepository($this->getDoctrine());
        $activities = $activityRepository->findAll();

        return $this->renderForm('circuit/new.html.twig', [
            'circuit' => $circuit,
            'activities' => $activities,
        ]);
    }

    /**
     * @Route("/{id}", name="app_circuit_show", methods={"GET"})
     */
    public function show(Circuit $circuit): Response
    {
        return $this->render('circuit/show.html.twig', [
            'circuit' => $circuit,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_circuit_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Circuit $circuit, CircuitRepository $circuitRepository): Response
    {
        $form = $this->createForm(CircuitType::class, $circuit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $circuitRepository->add($circuit, true);

            return $this->redirectToRoute('app_circuit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('circuit/edit.html.twig', [
            'circuit' => $circuit,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_circuit_delete", methods={"POST"})
     */
    public function delete(Request $request, Circuit $circuit, CircuitRepository $circuitRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$circuit->getId(), $request->request->get('_token'))) {
            $circuitRepository->remove($circuit, true);
        }

        return $this->redirectToRoute('app_circuit_index', [], Response::HTTP_SEE_OTHER);
    }
}
