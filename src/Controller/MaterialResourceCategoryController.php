<?php

namespace App\Controller;

use App\Entity\MaterialResourceCategory;
use App\Form\MaterialResourceCategoryType;
use App\Repository\MaterialResourceCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/material/resource/category")
 */
class MaterialResourceCategoryController extends AbstractController
{
    /**
     * @Route("/", name="app_material_resource_category_index", methods={"GET"})
     */
    public function index(MaterialResourceCategoryRepository $materialResourceCategoryRepository): Response
    {
        return $this->render('material_resource_category/index.html.twig', [
            'material_resource_categories' => $materialResourceCategoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_material_resource_category_new", methods={"GET", "POST"})
     */
    public function new(Request $request, MaterialResourceCategoryRepository $materialResourceCategoryRepository): Response
    {
        if ($request->getMethod() === 'POST') {
            $materialResourceCateg = new MaterialResourceCategory();
            $param = $request->request->all();
            $name = $param['name'];

            $materialResourceCateg->setCategoryname($name);
            $materialResourceCategoryRepository = new MaterialResourceCategoryRepository($this->getDoctrine());
            $materialResourceCategoryRepository->add($materialResourceCateg, true);

            return $this->redirectToRoute('index_material_resources', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}", name="app_material_resource_category_show", methods={"GET"})
     */
    public function show(MaterialResourceCategory $materialResourceCategory): Response
    {
        return $this->render('material_resource_category/show.html.twig', [
            'material_resource_category' => $materialResourceCategory,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_material_resource_category_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, MaterialResourceCategory $materialResourceCategory, MaterialResourceCategoryRepository $materialResourceCategoryRepository): Response
    {
        $form = $this->createForm(MaterialResourceCategoryType::class, $materialResourceCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $materialResourceCategoryRepository->add($materialResourceCategory, true);

            return $this->redirectToRoute('app_material_resource_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('material_resource_category/edit.html.twig', [
            'material_resource_category' => $materialResourceCategory,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_material_resource_category_delete", methods={"POST"})
     */
    public function delete(Request $request, MaterialResourceCategory $materialResourceCategory, MaterialResourceCategoryRepository $materialResourceCategoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$materialResourceCategory->getId(), $request->request->get('_token'))) {
            $materialResourceCategoryRepository->remove($materialResourceCategory, true);
        }

        return $this->redirectToRoute('app_material_resource_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
