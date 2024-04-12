<?php

namespace App\Controller;

use App\Repository\PhotoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

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

    #[Route('/cart/{id}', name: 'app_add_to_cart_from_photo')]
    public function addToCartFromPhoto(string $id, Request $request): Response
    {

        $session = $request->getSession();
        $cart = $session->get("cart", []);
        if (isset($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $session->set("cart",  $cart);
        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
        return $this->render('front/_cart.html.twig', ["cartNumber" => count($cart)]);
    }

    #[Route('/cartcheckoutadd/{id}', name: 'app_add_to_cart_from_cart')]
    public function addToCartFromCart(string $id, Request $request): Response
    {

        $session = $request->getSession();
        $cart = $session->get("cart", []);
        if (isset($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $session->set("cart",  $cart);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cartcheckoutremove/{id}', name: 'app_remove_from_cart_from_cart')]
    public function removeFromCartFromCart(string $id, Request $request): Response
    {

        $session = $request->getSession();
        $cart = $session->get("cart", []);

        if (isset($cart[$id])) {
            if ($cart[$id] > 1) {
                $cart[$id] = $cart[$id] - 1;
            } else {
                unset($cart[$id]);
            }
        }

        $session->set("cart",  $cart);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cartcheckoutdelete/{id}', name: 'app_delete_from_cart_from_cart')]
    public function deleteFromCartFromCart(string $id, Request $request): Response
    {

        $session = $request->getSession();
        $cart = $session->get("cart", []);

        unset($cart[$id]);


        $session->set("cart",  $cart);

        return $this->redirectToRoute('app_cart');
    }
}
