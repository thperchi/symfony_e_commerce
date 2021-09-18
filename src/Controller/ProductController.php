<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{

    /**
     * @Route("/products", name="products")
     */
    public function index(ProductRepository $repo, UserRepository $users): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $repo->findAll(),
        ]);
    }
    
    /**
     * @Route("/admin/add_product", name="add_product")
     */
    public function addProduct(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
    
        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();
        }
    
        return $this->render("product/product-form.html.twig", [
            "form_title" => "Ajouter un produit",
            "form" => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/modify-product/{id}", name="modify_product")
     */
    public function modifyProduct(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $product = $entityManager->getRepository(Product::class)->find($id);
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->flush();
        }

        return $this->render("product/product-form.html.twig", [
            "form_title" => "Modifier un produit",
            "form" => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/delete-product/{id}", name="delete_product")
     */
    public function deleteProduct(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);
        $entityManager->remove($product);
        $entityManager->flush();

        return $this->redirectToRoute("products");
    }
}