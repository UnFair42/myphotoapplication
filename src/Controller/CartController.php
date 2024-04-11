<?php

namespace App\Controller;

use App\Repository\PhotoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(PhotoRepository $photoRepository, Request $request): Response
    {

        $session = $request->getSession();
        $cart = $session->get("cart", []);
        $articles = [];
        foreach ($cart as $id => $amount) {
            $articles[] = ["photo" => $photoRepository->findById($id)[0], 'amount' => $amount];
        }

        return $this->render('cart/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/cart/{id}/{slug}', name: 'app_add_to_cart')]
    public function addToCart(string $slug, string $id, Request $request): Response
    {

        $session = $request->getSession();
        $cart = $session->get("cart", []);
        if (isset($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $session->set("cart",  $cart);

        return $this->redirectToRoute('app_display_photo', ['slug' => $slug]);
    }
}
