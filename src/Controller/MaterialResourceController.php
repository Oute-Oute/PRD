<?php

namespace App\Controller;

use App\Entity\MaterialResource;
use App\Form\MaterialResourceType;
use App\Repository\MaterialResourceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/material/resource")
 */
class MaterialResourceController extends AbstractController
{
    /**
     * @Route("/", name="app_material_resource_index", methods={"GET"})
     */
    public function index(MaterialResourceRepository $materialResourceRepository): Response
    {
        return $this->render('material_resource/index.html.twig', [
            'material_resources' => $materialResourceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_material_resource_new", methods={"GET", "POST"})
     */
    public function new(Request $request, MaterialResourceRepository $materialResourceRepository): Response
    {
        if ($request->getMethod() === 'POST') {
            $materialResource = new MaterialResource();
            $param = $request->request->all();

            $name = $param['name'];
            $availability = $param['availability'];
            if($param['availability'] == 'dispo') {
                $materialResource->setAvailable(true);
            }
            else {
                $materialResource->setAvailable(false);
            }
            $materialResource->setMaterialresourcename($name);
            $materialResourceRepository = new MaterialResourceRepository($this->getDoctrine());
            $materialResourceRepository->add($materialResource, true);

            return $this->redirectToRoute('index_resources', [], Response::HTTP_SEE_OTHER);
        }
    }
    /**
     * @Route("/{id}", name="app_material_resource_show", methods={"GET"})
     */
    public function show(MaterialResource $materialResource): Response
    {
        return $this->render('material_resource/show.html.twig', [
            'material_resource' => $materialResource,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_material_resource_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, MaterialResource $materialResource, MaterialResourceRepository $materialResourceRepository): Response
    {
        $form = $this->createForm(MaterialResourceType::class, $materialResource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $materialResourceRepository->add($materialResource, true);

            return $this->redirectToRoute('index_resources', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('material_resource/edit.html.twig', [
            'material_resource' => $materialResource,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_material_resource_delete", methods={"POST"})
     */
    public function delete(Request $request, MaterialResource $materialResource, MaterialResourceRepository $materialResourceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$materialResource->getId(), $request->request->get('_token'))) {
            $materialResourceRepository->remove($materialResource, true);
        }

        return $this->redirectToRoute('index_resources', [], Response::HTTP_SEE_OTHER);
    }
}
