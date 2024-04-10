<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Repository\PhotoRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FrontController extends AbstractController
{
    #[Route('/', name: 'app_front')]
    public function index(PhotoRepository $photoRepository): Response
    {
        $photos = $photoRepository->findAll();
        return $this->render('front/index.html.twig', [
            'photos' => $photos,
        ]);
    }
    #[Route('/presentation', name: 'app_presentation')]
    public function presentation(): Response
    {
        return $this->render('front/presentation.html.twig', []);
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
}
