<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function index(ProductRepository $products): Response
    {
        $user = $this->getUser();
        $userCart = $user->getCart();
        $cart = array();
        for ($i=0; $i < count($userCart); $i++) {
            $product = $products->findOneBy(['id' => $userCart[$i]]);
            array_push($cart, $product);
        }

        return $this->render('cart/index.html.twig', [
            'user' => $user,
            'cart' => $cart
        ]);
    }

    /**
     * @Route("/add-product-to-cart/{id}", name="add_product_to_cart")
     */
    public function addProductToCart(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        if(empty($user->getCart())) {
            $userCart = array();
        } else {
            $userCart = $user->getCart();
        }
        array_push($userCart, $id);
        $user->setCart($userCart);
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute("products");
    }
}