<?php

namespace App\Controller;

use App\Service\CartService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
//---------------------------------------------------------------------------------------------------------------------------------------
    #[Route('/cart', name: 'app_cart')]
    public function index(CartService $cs): Response
    {
        $cartWithData=$cs->getCardWithData();    
        $total=$cs->getTotal();

        return $this->render('cart/index.html.twig', [
            'items' => $cartWithData,
            'total' => $total
        ]);
    }
//---------------------------------------------------------------------------------------------------------------------------------------
    #[Route('/cart/add/{id}', name:'cart_add')]
    public function add($id, CartService $cs)
    {
        $cs->add($id);
        $this->addFlash('success', 'Le produit à bien été ajouté !');
        return $this->redirectToRoute('app_cart');
    }
//---------------------------------------------------------------------------------------------------------------------------------------
    #[Route('/cart/remove/{id}', name:'cart_remove')]
    public function remove($id, CartService $cs)
    {
        $cs->remove($id);
        $this->addFlash('warning', 'Le produit à bien été retiré !');
     return $this->redirectToRoute('app_cart');
    }

}
