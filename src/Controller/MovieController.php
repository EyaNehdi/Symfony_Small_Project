<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieTypeForm;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MovieController extends AbstractController
{
    //Test Method
    // #[Route('/movies', name: 'app_movie')]
    // public function index(EntityManagerInterface $em): Response
    // {
    //     //$movies= $movieRepository->findAll();
    //     $repository = $em->getRepository(Movie::class);
    //     $movies = $repository->findAll();
    //     dd($movies);

    //     return $this->render('movie/index.html.twig',);
    // }
    private $movieRepository;
    private $em;
    public function __construct(MovieRepository $movieRepository, EntityManagerInterface $em)
    {
        $this->movieRepository = $movieRepository;
        $this->em = $em;
    }

    #[Route('/showMovie', name: 'show_movie', methods: ['GET'])]
    public function index(): Response
    {
        $movies = $this->movieRepository->findAll();
        // dd($movies);
        return $this->render('movie/showMovie.html.twig', [
            'movies' => $movies,
        ]);
    }

    #[Route('/showMovie/{id}', methods: ['GET'], name: 'details_movie')]
    public function getById($id): Response
    {
        $movie = $this->movieRepository->find($id);
        // dd($movies);
        return $this->render('movie/detailsMovie.html.twig', [
            'movie' => $movie,
        ]);
    }
    #[Route('/addMovie', name: 'add_movie')]
    public function addMovie(Request $request): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieTypeForm::class, $movie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newMovie = $form->getData();
            $imagePath = $form->get('imagePath')->getData();
            if ($imagePath) {
                $newFileName = uniqid() . '.' . $imagePath->guessExtension();
                try {
                    $imagePath->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads',
                        $newFileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }
                $newMovie->setImagePath('/uploads/' . $newFileName);
                $this->em->persist($newMovie);
                $this->em->flush();
                return $this->redirectToRoute('show_movie');
            }
        }
        return $this->render('movie/addMovie.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/editMovie/{id}', name: 'edit_movie')]
    public function editMovie(Request $request, $id): Response
    {
        $movie = $this->movieRepository->find($id);
        $form = $this->createForm(MovieTypeForm::class, $movie);
        $form->handleRequest($request);
        $imagePath = $form->get('imagePath')->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            if ($imagePath) {
                if ($movie->getImagePath() !== null) {
                    if (file_exists($this->getParameter('kernel.project_dir') . $movie->getImagePath())) {
                        $this->getParameter('kernel.project_dir') . $movie->getImagePath();
                        $newFileName = uniqid() . '.' . $imagePath->guessExtension();
                        try {
                            $imagePath->move(
                                $this->getParameter('kernel.project_dir') . '/public/uploads',
                                $newFileName
                            );
                        } catch (FileException $e) {
                            return new Response($e->getMessage());
                        }
                        $movie->setImagePath('/uploads/' . $newFileName);
                        $this->em->flush();
                        return $this->redirectToRoute('show_movie');
                    } else {
                        $newFileName = uniqid() . '.' . $imagePath->guessExtension();
                        try {
                            $imagePath->move(
                                $this->getParameter('kernel.project_dir') . '/public/uploads',
                                $newFileName
                            );
                        } catch (FileException $e) {
                            return new Response($e->getMessage());
                        }
                        $movie->setImagePath('/uploads/' . $newFileName);
                        $this->em->flush();
                        return $this->redirectToRoute('show_movie');
                    }
                } else {
                    $newFileName = uniqid() . '.' . $imagePath->guessExtension();
                    try {
                        $imagePath->move(
                            $this->getParameter('kernel.project_dir') . '/public/uploads',
                            $newFileName
                        );
                    } catch (FileException $e) {
                        return new Response($e->getMessage());
                    }
                    $movie->setImagePath('/uploads/' . $newFileName);
                    // $this->em->persist($movie);
                    $this->em->flush();
                    return $this->redirectToRoute('show_movie');
                }
            } else {
                $movie->setTitle($form->get('title')->getData());
                $movie->setReleaseYear($form->get('releaseYear')->getData());
                $movie->setDescription($form->get('description')->getData());
                $movie->setImagePath($form->get('imagePath')->getData());
                // $this->em->persist($movie);
                $this->em->flush();
                return $this->redirectToRoute('show_movie');
            }
        }
        return $this->render('movie/editMovie.html.twig', [
            'form' => $form->createView(),
            'movie' => $movie,
        ]);
    }
    #[Route('/deleteMovie/{id}',name: 'delete_movie',methods: ['GET','DELETE'])]
    public function deleteMovie($id): Response{
        $movie = $this->movieRepository->find($id);
        if ($movie){
            $this->em->remove($movie);
            $this->em->flush();
            return $this->redirectToRoute('show_movie');
        }
        return $this->render('movie/showMovie.html.twig', [
            'movies' => $this->movieRepository->findAll(),
        ]);
    }
}
