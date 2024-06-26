<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Repository\PhotoRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\UX\Turbo\TurboBundle;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(PhotoRepository $photoRepository, Request $request): Response
    {

        $session = $request->getSession();

        $cart = $session->get("cart", []);
        $articles = [];
        dump($cart);
        foreach ($cart as $id => $amount) {
            $photo = $photoRepository->findById($id);
            if ($photo) {
                $articles[] = ["photo" => $photo[0], 'amount' => $amount];
            }
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

    #[Route('/cartremove', name: 'app_remove_cart_from_cart')]
    public function deleteCartFromCart(Request $request): Response
    {

        $session = $request->getSession();

        $session->set("cart",  []);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cartcheckout', name: 'app_checkout')]
    //#[IsGranted("ROLE_USER")]
    public function checkout(UserRepository $userRepository, Security $security, Request $request, EntityManagerInterface $entityManager, PhotoRepository $photoRepository): Response
    {
        $user = $security->getUser();
        if (!$user) {
            $this->addFlash(
                'error',
                'Vous devez être connecté pour valider votre panier.'
            );
            return $this->redirectToRoute('app_login');
        }
        $userEmail = $user->getUserIdentifier();
        $userDB = $userRepository->findByEmail($userEmail)[0];
        $customer = $userDB->getCustomer();
        $session = $request->getSession();
        $cart = $session->get("cart", []);
        $order = new Order();
        $order->setCustomer($customer)
            ->setCreatedAt(new DateTimeImmutable("now"));
        $entityManager->persist($order);

        foreach ($cart as $id => $amount) {

            $orderItem = new OrderItem();
            $orderItem->setPhotoId($photoRepository->findById($id)[0])
                ->setOrderId($order)
                ->setPrice($photoRepository->findById($id)[0]->getPrice())
                ->setQuantity($amount);
            $entityManager->persist($orderItem);
        }
        $entityManager->flush();

        $session->set("cart",  []);

        return $this->redirectToRoute('app_display_order');
    }
}
