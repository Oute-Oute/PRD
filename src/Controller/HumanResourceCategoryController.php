<?php

namespace App\Controller;

use App\Entity\HumanResourceCategory;
use App\Form\HumanResourceCategoryType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\HumanResourceCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/human/resource/category")
 */
class HumanResourceCategoryController extends AbstractController
{
    /**
     * @Route("/", name="app_human_resource_category_index", methods={"GET"})
     */
    public function index(HumanResourceCategoryRepository $humanResourceCategoryRepository): Response
    {
        return $this->render('human_resource_category/index.html.twig', [
            'human_resource_categories' => $humanResourceCategoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_human_resource_category_new", methods={"GET", "POST"})
     */
    public function new(Request $request, HumanResourceCategoryRepository $humanResourceCategoryRepository): Response
    {
        if ($request->getMethod() === 'POST') {
            $humanResourceCateg = new HumanResourceCategory();
            $param = $request->request->all();
            $name = $param['name'];

            $humanResourceCateg->setCategoryname($name);
            $humanResourceCategoryRepository = new HumanResourceCategoryRepository($this->getDoctrine());
            $humanResourceCategoryRepository->add($humanResourceCateg, true);

            return $this->redirectToRoute('index_human_resources', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}", name="app_human_resource_category_show", methods={"GET"})
     */
    public function show(HumanResourceCategory $humanResourceCategory): Response
    {
        return $this->render('human_resource_category/show.html.twig', [
            'human_resource_category' => $humanResourceCategory,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_human_resource_category_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, HumanResourceCategoryRepository $humanResourceCategoryRepository, EntityManagerInterface $entityManager): Response
    {
        $idCateg = $request->request->get("idcategory");
        $nameCateg = $request->request->get("categoryname");
        $category = $humanResourceCategoryRepository->findOneBy(['id' => $idCateg]);
        $category->setCategoryname($nameCateg);

        $entityManager->persist($category);
        $entityManager->flush();

        return $this->redirectToRoute('index_human_resources', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}", name="app_human_resource_category_delete", methods={"POST"})
     */
    public function delete(Request $request, HumanResourceCategory $humanResourceCategory, HumanResourceCategoryRepository $humanResourceCategoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$humanResourceCategory->getId(), $request->request->get('_token'))) {
            $humanResourceCategoryRepository->remove($humanResourceCategory, true);
        }

        return $this->redirectToRoute('index_human_resources', [], Response::HTTP_SEE_OTHER);
    }
}
