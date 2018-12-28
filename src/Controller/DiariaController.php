<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DiariaController extends AbstractController
{
    /**
     * @Route("/diaria", name="diaria")
     */
    public function index()
    {
        return $this->render('diaria/index.html.twig', [
            'controller_name' => 'DiariaController',
        ]);
    }
}
