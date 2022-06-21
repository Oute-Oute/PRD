<?php

namespace App\Controller;

use App\Entity\ResourceType;
use App\Form\ResourceTypeType;
use App\Repository\ResourceTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/resource_type")
 */
class ResourceTypeController extends AbstractController
{
    /**
     * @Route("/", name="app_resource_type_index", methods={"GET"})
     */
    public function index(ResourceTypeRepository $resourceTypeRepository): Response
    {
        return $this->render('resource_type/index.html.twig', [
            'resource_types' => $resourceTypeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_resource_type_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ResourceTypeRepository $resourceTypeRepository): Response
    {
        $resourceType = new ResourceType();
        $form = $this->createForm(ResourceTypeType::class, $resourceType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $resourceTypeRepository->add($resourceType, true);

            return $this->redirectToRoute('app_resource_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('resource_type/new.html.twig', [
            'resource_type' => $resourceType,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_resource_type_show", methods={"GET"})
     */
    public function show(ResourceType $resourceType): Response
    {
        return $this->render('resource_type/show.html.twig', [
            'resource_type' => $resourceType,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_resource_type_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, ResourceType $resourceType, ResourceTypeRepository $resourceTypeRepository): Response
    {
        $form = $this->createForm(ResourceTypeType::class, $resourceType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $resourceTypeRepository->add($resourceType, true);

            return $this->redirectToRoute('app_resource_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('resource_type/edit.html.twig', [
            'resource_type' => $resourceType,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_resource_type_delete", methods={"POST"})
     */
    public function delete(Request $request, ResourceType $resourceType, ResourceTypeRepository $resourceTypeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$resourceType->getId(), $request->request->get('_token'))) {
            $resourceTypeRepository->remove($resourceType, true);
        }

        return $this->redirectToRoute('app_resource_type_index', [], Response::HTTP_SEE_OTHER);
    }
}
