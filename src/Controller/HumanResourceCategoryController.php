<?php
namespace App\Controller;

use App\Entity\HumanResourceCategory;
use App\Repository\CategoryOfHumanResourceRepository;
use App\Form\HumanResourceCategoryType;
use App\Repository\ActivityHumanResourceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\HumanResourceCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

/*
 * @file        HumanResourceCategoryController.php
 * @brief       Contains the functions that allow to handle the human resource categories
 * @details     Allows to create, read, update, delete every human resource categories
 * @date        2022
 */

/**
 * @Route("/human/resource/category")
 */
class HumanResourceCategoryController extends AbstractController
{

    /*
      * @brief Allows to list every human resource categories in the database
     */

    /**
     * @Route("/", name="app_human_resource_category_index", methods={"GET"})
     */
    public function index(HumanResourceCategoryRepository $humanResourceCategoryRepository): Response
    {
        return $this->render('human_resource_category/index.html.twig', [
            'human_resource_categories' => $humanResourceCategoryRepository->findBy(array(), array('categoryname' => 'ASC')),
        ]);
    }

    

    /*
      * @brief Allows to create a new human resource category in the database
     */

    /**
     * @Route("/new", name="app_human_resource_category_new", methods={"GET", "POST"})
     */
    public function new(Request $request, HumanResourceCategoryRepository $humanResourceCategoryRepository,ManagerRegistry $doctrine): Response
    {
        if ($request->getMethod() === 'POST') {
            $humanResourceCateg = new HumanResourceCategory();
            $param = $request->request->all();
            $name = $param['name'];

            $humanResourceCateg->setCategoryname($name);
            $humanResourceCategoryRepository = new HumanResourceCategoryRepository($doctrine);
            $humanResourceCategoryRepository->add($humanResourceCateg, true);

            return $this->redirectToRoute('index_human_resources', [], Response::HTTP_SEE_OTHER);
        }
    }

    

    /*
     * @brief Allows to show data of a specific human resource category
     */

    /**
     * @Route("/{id}", name="app_human_resource_category_show", methods={"GET"})
     */
    public function show(HumanResourceCategory $humanResourceCategory): Response
    {
        return $this->render('human_resource_category/show.html.twig', [
            'human_resource_category' => $humanResourceCategory,
        ]);
    }

    

    /*
      * @brief Allows to edit a human resource category that is already in the database
     */

    /**
     * @Route("/{id}/edit", name="app_human_resource_category_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, HumanResourceCategoryRepository $humanResourceCategoryRepository, EntityManagerInterface $entityManager): Response
    {
        $idCateg = $request->request->get("idcategoryedit");
        $nameCateg = $request->request->get("categorynameedit");
        $category = $humanResourceCategoryRepository->findOneBy(['id' => $idCateg]);
        $category->setCategoryname($nameCateg);

        $entityManager->persist($category);
        $entityManager->flush();

        return $this->redirectToRoute('index_human_resources', [], Response::HTTP_SEE_OTHER);
    }

    

    /*
      * @brief Allows to delete a human resource category from the database
     */

    /**
     * @Route("/{id}", name="app_human_resource_category_delete", methods={"POST"})
     */
    public function delete(Request $request, HumanResourceCategory $humanResourceCategory, HumanResourceCategoryRepository $humanResourceCategoryRepository,ManagerRegistry $doctrine): Response
    {
        $categOfHumanResourceRepository = new CategoryOfHumanResourceRepository($doctrine);
        $activitiesHumanResourceRepository = new ActivityHumanResourceRepository($doctrine);

        $em=$doctrine->getManager();
        //delete the links between resources that are linked to this deleted category
        $categsOfResources = $categOfHumanResourceRepository->findBy(['humanresourcecategory' => $humanResourceCategory]);
        for ($indexCategOf = 0; $indexCategOf < count($categsOfResources); $indexCategOf++) {
            $em->remove($categsOfResources[$indexCategOf]);
        }

        //delete the links between an activity and the category that is deleted
        $activitiesHumanResource = $activitiesHumanResourceRepository->findBy(['humanresourcecategory' => $humanResourceCategory]);
        for ($indexActivitiesHumanResource = 0; $indexActivitiesHumanResource < count($activitiesHumanResource); $indexActivitiesHumanResource++){
        $em->remove($activitiesHumanResource[$indexActivitiesHumanResource]);
        }
        
        $em->flush();
        if ($this->isCsrfTokenValid('delete'.$humanResourceCategory->getId(), $request->request->get('_token'))) {
            $humanResourceCategoryRepository->remove($humanResourceCategory, true);
        }

        return $this->redirectToRoute('index_human_resources', [], Response::HTTP_SEE_OTHER);
    }
}
