<?php

namespace App\Controller;

use App\Entity\Resource;
use App\Form\ResourceType;
use App\Repository\ResourceRepository;
use App\Repository\HumanResourceRepository;
use App\Repository\MaterialResourceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/resource")
 */
class ResourceController extends AbstractController
{
        /**
     * @Route("/", name="app_human_resource_index", methods={"GET"})
     */
    public function index(HumanResourceRepository $humanResourceRepository): Response
    {
        $materialResourceRepository = new MaterialResourceRepository($this->getDoctrine());
        return $this->render('human_resource/index.html.twig', [
            'human_resources' => $humanResourceRepository->findAll(),
            'material_resources' => $materialResourceRepository->findAll()
        ]);
    }

    /**
     * @Route("/new", name="app_resource_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ResourceRepository $resourceRepository): Response
    {
        //$form = $this->createForm(ResourceType::class, $resource);
        //$form->handleRequest($request);

        // Méthode POST pour ajouter un circuit
        if ($request->getMethod() === 'POST' ) {
        
            // On recupere toutes les données de la requete
            $param = $request->request->all();

            $name = $param['resourcename'];             // le nom
            $type = $param['resourcetype'];             // le type

            // Création de l'activité
            $resource = new Resource();
            
            $resource->setResourcename($name);
            $resource->setResourcetype($type);
            $resource->setAble(true);
            
            // ajout dans la bd 
            $resourceRepository = new ResourceRepository($this->getDoctrine());
            $resourceRepository->add($resource, true);

            return $this->redirectToRoute('app_resource_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('resource/new.html.twig', [
            'resource' => $resource,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_resource_show", methods={"GET"})
     */
    public function show(Resource $resource): Response
    {
        return $this->render('resource/show.html.twig', [
            'resource' => $resource,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_resource_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Resource $resource, ResourceRepository $resourceRepository): Response
    {
        $form = $this->createForm(ResourceType::class, $resource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $resourceRepository->add($resource, true);

            return $this->redirectToRoute('app_resource_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('resource/edit.html.twig', [
            'resource' => $resource,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_resource_delete", methods={"POST"})
     */
    public function delete(Request $request, Resource $resource, ResourceRepository $resourceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$resource->getId(), $request->request->get('_token'))) {
            $resourceRepository->remove($resource, true);
        }

        return $this->redirectToRoute('app_resource_index', [], Response::HTTP_SEE_OTHER);
    }
}
