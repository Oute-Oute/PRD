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
 * @Route("/resources-types")
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
     * @Route("-humans", name="app_resource_type_index_humans", methods={"GET"})
     */
    public function indexFilteredHumans(ResourceTypeRepository $resourceTypeRepository): Response
    {
        $indexHumans = $resourceTypeRepository->findAll();
        $tabHumans = [];
        $iHumans = 0;
        foreach ($indexHumans as $elem){
            $iHumans = $iHumans + 1;
            if($elem->getType() != "Matérielle"){
                array_unshift($tabHumans, $elem);
            }
        }
        return $this->render('resource_type/index.html.twig', [
            'resource_types' => $tabHumans,
        ]);

        
    }

    /**
     * @Route("-materials", name="app_resource_type_index_materials", methods={"GET"})
     */
    public function indexFilteredMaterials(ResourceTypeRepository $resourceTypeRepository): Response
    {
        $indexMaterials = $resourceTypeRepository->findAll();
        $tabMaterials = [];
        $iMaterials = 0;
        foreach ($indexMaterials as $elem){
            $iMaterials = $iMaterials + 1;
            if($elem->getType() != "Humaine"){
                array_unshift($tabMaterials, $elem);
            }
        }
        return $this->render('resource_type/index.html.twig', [
            'resource_types' => $tabMaterials,
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
