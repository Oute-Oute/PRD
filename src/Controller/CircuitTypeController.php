<?php

namespace App\Controller;

use App\Entity\CircuitType;
use App\Form\CircuitTypeType;
use App\Repository\CircuitTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/circuit-type")
 */
class CircuitTypeController extends AbstractController
{
    /**
     * @Route("/", name="app_circuit_type_index", methods={"GET"})
     */
    public function index(CircuitTypeRepository $circuitTypeRepository): Response
    {
        return $this->render('circuit_type/index.html.twig', [
            'circuit_types' => $circuitTypeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_circuit_type_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CircuitTypeRepository $circuitTypeRepository): Response
    {
        $circuitType = new CircuitType();
        $form = $this->createForm(CircuitTypeType::class, $circuitType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $circuitTypeRepository->add($circuitType, true);

            return $this->redirectToRoute('app_circuit_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('circuit_type/new.html.twig', [
            'circuit_type' => $circuitType,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_circuit_type_show", methods={"GET"})
     */
    public function show(CircuitType $circuitType): Response
    {
        return $this->render('circuit_type/show.html.twig', [
            'circuit_type' => $circuitType,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_circuit_type_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CircuitType $circuitType, CircuitTypeRepository $circuitTypeRepository): Response
    {
        $form = $this->createForm(CircuitTypeType::class, $circuitType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $circuitTypeRepository->add($circuitType, true);

            return $this->redirectToRoute('app_circuit_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('circuit_type/edit.html.twig', [
            'circuit_type' => $circuitType,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_circuit_type_delete", methods={"POST"})
     */
    public function delete(Request $request, CircuitType $circuitType, CircuitTypeRepository $circuitTypeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$circuitType->getId(), $request->request->get('_token'))) {
            $circuitTypeRepository->remove($circuitType, true);
        }

        return $this->redirectToRoute('app_circuit_type_index', [], Response::HTTP_SEE_OTHER);
    }
}
