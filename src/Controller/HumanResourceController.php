<?php

namespace App\Controller;

use App\Entity\HumanResource;
use App\Form\HumanResourceType;
use App\Repository\HumanResourceCategoryRepository;
use App\Repository\HumanResourceRepository;
use App\Repository\MaterialResourceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/human/resource")
 */
class HumanResourceController extends AbstractController
{
    /**
     * @Route("/", name="app_human_resource_index", methods={"GET"})
     */
    public function index(HumanResourceRepository $humanResourceRepository): Response
    {
        $humanResourceCategoryRepository = new HumanResourceCategoryRepository($this->getDoctrine());
        $humanResourceCategories = $humanResourceCategoryRepository->findAll();
        return $this->render('human_resource/index.html.twig', [
            'human_resources' => $humanResourceRepository->findBy(['available' => true]),
            'human_resources_categories' => $humanResourceCategories
        ]);
    }

    /**
     * @Route("/new", name="app_human_resource_new", methods={"GET", "POST"})
     */
    public function new(Request $request, HumanResourceRepository $humanResourceRepository): Response
    {
        
        if ($request->getMethod() === 'POST') {
            $humanResource = new HumanResource();
            $param = $request->request->all();
            $name = $param['name'];
            $humanResource->setAvailable(true);
            $humanResource->setHumanresourcename($name);
            $humanResourceRepository = new HumanResourceRepository($this->getDoctrine());
            $humanResourceRepository->add($humanResource, true);

            return $this->redirectToRoute('index_resources_humans', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}", name="app_human_resource_show", methods={"GET"})
     */
    public function show(HumanResource $humanResource): Response
    {
        return $this->render('human_resource/show.html.twig', [
            'human_resource' => $humanResource,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_human_resource_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, HumanResource $humanResource, HumanResourceRepository $humanResourceRepository): Response
    {
        $form = $this->createForm(HumanResourceType::class, $humanResource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $humanResourceRepository->add($humanResource, true);

            return $this->redirectToRoute('index_resources', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('human_resource/edit.html.twig', [
            'human_resource' => $humanResource,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_human_resource_delete", methods={"POST"})
     */
    public function delete(Request $request, HumanResource $humanResource, HumanResourceRepository $humanResourceRepository): Response
    {
            if($humanResource->isAvailable() == true) {
                $humanResource->setAvailable(false);
            }
            else {
                $humanResource->setAvailable(true);
            }

        $humanResourceRepository->add($humanResource, true);
        return $this->redirectToRoute('index_resources', [], Response::HTTP_SEE_OTHER);
    }
}
