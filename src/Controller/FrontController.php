<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Repository\CustomerRepository;
use App\Repository\OrderRepository;
use App\Repository\PhotoRepository;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Twig\Environment;

class FrontController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function noLocalHomePage(PhotoRepository $photoRepository): Response
    {

        return $this->redirectToRoute('app_front', ['_locale' => 'fr']);
    }

    #[Route('/{_locale<%app.supported_locales%>}', name: 'app_front')]
    public function index(PhotoRepository $photoRepository): Response
    {
        $photos = $photoRepository->findAll();
        return $this->render('front/index.html.twig', [
            'photos' => $photos,
        ]);
    }
    #[Route('/{_locale<%app.supported_locales%>}/pages/{pageName}', name: 'app_static_page')]
    public function staticPage(string $_locale, string $pageName, Environment $twig): Response
    {
        $template = 'front/pages/' . $pageName . '.' . $_locale . '.html.twig';
        $loader = $twig->getLoader();
        if (!$loader->exists($template)) {
            throw new NotFoundHttpException();
        }
        return $this->render($template, []);
    }



    #[Route('/photo/{slug}', name: 'app_display_photo')]
    public function displayPhoto(string $slug, PhotoRepository $photoRepository): Response
    {
        $photo = $photoRepository->findOneBySlug($slug);

        if (!$photo) {
            throw $this->createNotFoundException('La photo demandée n\'existe pas.');
        }

        return $this->render('front/photo.html.twig', [
            'photo' => $photo,
        ]);
    }

    #[Route('/tag/{slug}', name: 'app_display_tag')]
    public function displayTagPhoto(string $slug, TagRepository $tagRepository): Response
    {
        $tag = $tagRepository->findOneBySlug($slug);

        $photos = $tag->getPhotos();


        return $this->render('front/tag.html.twig', [
            'tag' => $tag,
            'photos' => $photos,
        ]);
    }

    #[Route('/order', name: 'app_display_order')]
    #[IsGranted("ROLE_USER")]
    public function displayOrder(UserRepository $userRepository, Security $security): Response
    {
        $user = $security->getUser();
        if ($user) {
            $userEmail = $user->getUserIdentifier();
            $userDB = $userRepository->findByEmail($userEmail)[0];
            $customerDB = $userDB->getCustomer();
            $orders = $customerDB->getOrders();


            $payload = [];
            foreach ($orders as $order) {
                $orderItems = $order->getOrderItems();
                $payload[] = ["order" => $order, 'items' => $orderItems];
            }


            return $this->render('front/order.html.twig', [
                'payload' => $payload,
            ]);
        } else {
            // 
        }
    }
}
