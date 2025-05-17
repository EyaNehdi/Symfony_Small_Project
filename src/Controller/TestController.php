<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test',methods: ['GET','HEAD'])]
    public function index($name): Response
    {
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
            'name' => $name
        ]);
    }
}
