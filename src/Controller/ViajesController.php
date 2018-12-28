<?php

namespace App\Controller;

use App\Entity\Viajes;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\Choferes;


class ViajesController extends AbstractController
{
    /**
     * @Route("/viajes", name="viajes")
     */
    public function index(Request $request)
    {
       $em = $this -> getDoctrine() -> getManager();
       $viajes = $em -> getRepository(Viajes::class) -> findAll();

       $choferes = $em -> getRepository(Choferes::class) -> findAll();
       $list[""]="";

       foreach ($choferes as $chofer) {
         $list[$chofer -> getNombre()] = $chofer -> getId();
       }

        $formulario = $this -> createFormBuilder()

          -> add ('chofer', ChoiceType::class, array('choices' => array('----------------' => $list )))
          -> add ('origen', TextType::class)
          -> add ('destino', TextType::class)
          -> getForm()
          -> handleRequest($request);

          if ( $formulario->isSubmitted() && $formulario->isValid()) {}

        return $this->render('viajes/index.html.twig', [
            'formulario' => $formulario -> createView(),
            'viajes' => $viajes
        ]);
    }
}
