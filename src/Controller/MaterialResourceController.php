<?php

namespace App\Controller;

use App\Entity\MaterialResource;
use App\Entity\CategoryOfMaterialResource;
use App\Form\MaterialResource1Type;
use App\Repository\MaterialResourceRepository;
use App\Repository\MaterialResourceCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryOfMaterialResourceRepository;


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
        $materialResourceCategoryRepository = new MaterialResourceCategoryRepository($this->getDoctrine());
        $materialResourceCategories = $materialResourceCategoryRepository->findAll();
        return $this->render('material_resource/index.html.twig', [
            'material_resources' => $materialResourceRepository->findBy(['available' => true]),
            'material_resources_categories' => $materialResourceCategories
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
            $name = $param['categoryname'];
            $materialResource->setAvailable(true);
            $materialResource->setMaterialresourcename($name);
            $materialResourceRepository = new MaterialResourceRepository($this->getDoctrine());
            $materialResourceRepository->add($materialResource, true);
            $materialResourceCategoryRepository = new MaterialResourceCategoryRepository($this->getDoctrine());


            // On récupère toutes les catégories
            $categoryOfMaterialResourceRepository = new CategoryOfMaterialResourceRepository($this->getDoctrine());
            $categories = $categoryOfMaterialResourceRepository->findAll();            

            // On récupère le nombre de catégories
            $nbCategory = $param['nbCategory'];


            //$activityArray = array();


            for($i = 0; $i < $nbCategory; $i++)
            {
                $linkCategRes = new CategoryOfMaterialResource();      

                $linkCategRes->setMaterialresource($materialResource);
                $linkCategRes->setMaterialResourcecategory($materialResourceCategoryRepository->findById($param['select-'.$i])[0]);
                $categoryOfMaterialResourceRepository->add($linkCategRes, true);
            }
            return $this->redirectToRoute('index_material_resources', [], Response::HTTP_SEE_OTHER);
        }
    
}

    /**
     * @Route("/{id}/edit", name="app_material_resource_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, MaterialResource $materialResource, MaterialResourceRepository $materialResourceRepository): Response
    {
        $form = $this->createForm(MaterialResource1Type::class, $materialResource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $materialResourceRepository->add($materialResource, true);

            return $this->redirectToRoute('app_material_resource_index', [], Response::HTTP_SEE_OTHER);
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

        return $this->redirectToRoute('index_material_resources', [], Response::HTTP_SEE_OTHER);
    }
}
