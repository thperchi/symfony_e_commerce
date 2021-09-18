<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/products", name="admin_products")
     */
    public function index(ProductRepository $products): Response
    {
        return $this->render('admin/index.html.twig', [
            'products' => $products->findAll(),
        ]);
    }
}
