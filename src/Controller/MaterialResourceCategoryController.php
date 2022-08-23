<?php

namespace App\Controller;

use App\Entity\MaterialResourceCategory;
use App\Form\MaterialResourceCategoryType;
use App\Repository\ActivityMaterialResourceRepository;
use App\Repository\MaterialResourceCategoryRepository;
use App\Repository\CategoryOfMaterialResourceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/*
 * @file        MaterialResourceCategoryController.php
 * @brief       Contains the functions that allow to handle the material resource categories
 * @details     Allows to create, read, update, delete every material resource categories
 * @date        2022
 */

/**
 * @Route("/material/resource/category")
 */
class MaterialResourceCategoryController extends AbstractController
{

    /*
      * @brief Allows to create a new material resource category in the database
     */

    /**
     * @Route("/new", name="app_material_resource_category_new", methods={"GET", "POST"})
     */
    public function new(Request $request, MaterialResourceCategoryRepository $materialResourceCategoryRepository,ManagerRegistry $doctrine): Response
    {
        if ($request->getMethod() === 'POST') {
            $materialResourceCateg = new MaterialResourceCategory();
            $param = $request->request->all();
            $name = $param['name'];

            $materialResourceCateg->setCategoryname($name);
            $materialResourceCategoryRepository = new MaterialResourceCategoryRepository($doctrine);
            $materialResourceCategoryRepository->add($materialResourceCateg, true);

            return $this->redirectToRoute('index_material_resources', [], Response::HTTP_SEE_OTHER);
        }
    }


    /*
      * @brief Allows to edit a material resource category that is already in the database
     */

    /**
     * @Route("/{id}/edit", name="app_material_resource_category_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, MaterialResourceCategoryRepository $materialResourceCategoryRepository, EntityManagerInterface $entityManager): Response
    {
        $idCateg = $request->request->get("idcategoryedit");
        $nameCateg = $request->request->get("categorynameedit");

        $category = $materialResourceCategoryRepository->findOneBy(['id' => $idCateg]);
        $category->setCategoryname($nameCateg);

        $entityManager->persist($category);
        $entityManager->flush();

        return $this->redirectToRoute('index_material_resources_category', [], Response::HTTP_SEE_OTHER);
    }

    /*
      * @brief Allows to delete a material resource category from the database
     */

    /**
     * @Route("/{id}", name="app_material_resource_category_delete", methods={"POST"})
     */    
    public function delete(Request $request, MaterialResourceCategory $materialResourceCategory, MaterialResourceCategoryRepository $materialResourceCategoryRepository,ManagerRegistry $doctrine): Response
    {
        $categOfMaterialResourceRepository = new CategoryOfMaterialResourceRepository($doctrine);
        $activitiesMaterialResourceRepository = new ActivityMaterialResourceRepository($doctrine);

        $em=$doctrine->getManager();
        $categsOfResources = $categOfMaterialResourceRepository->findBy(['materialresourcecategory' => $materialResourceCategory]);
        for ($indexCategOf = 0; $indexCategOf < count($categsOfResources); $indexCategOf++) {
            $em->remove($categsOfResources[$indexCategOf]);
        }

        $activitiesMaterialResource = $activitiesMaterialResourceRepository->findBy(['materialresourcecategory' => $materialResourceCategory]);
        for ($indexActivitiesMaterialResource = 0; $indexActivitiesMaterialResource < count($activitiesMaterialResource); $indexActivitiesMaterialResource++){
        $em->remove($activitiesMaterialResource[$indexActivitiesMaterialResource]);
        }

        $em->flush();
        if ($this->isCsrfTokenValid('delete'.$materialResourceCategory->getId(), $request->request->get('_token'))) {
            $materialResourceCategoryRepository->remove($materialResourceCategory, true);
        }

        return $this->redirectToRoute('index_material_resources', [], Response::HTTP_SEE_OTHER);
    }
}
