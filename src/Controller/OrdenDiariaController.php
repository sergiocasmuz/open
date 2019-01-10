<?php

namespace App\Controller;

use App\Entity\OrdenDiaria;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class OrdenDiariaController extends AbstractController
{
    /**
     * @Route("/orden/diaria", name="orden_diaria")
     */
    public function index(Request $request)
    {

        $em = $this ->getDoctrine() -> getManager();

        $ordenDiaria = $em -> getRepository(OrdenDiaria::class) -> findAll();
        $control = $em -> getRepository(OrdenDiaria::class) -> findByEstado(0);


        $formulario = $this -> createFormBuilder()
        -> add('ordenN', SubmitType::class, array('label' => 'Nueva Orden Diaria', 'attr' => array('class' => 'btn btn-primary')))
        -> getForm()
        -> handleRequest($request);

        if (   $formulario -> isSubmitted() && $formulario -> isValid()) {


          if( count($control) < 1 ){
          $ordenN = new OrdenDiaria();

          $ordenN -> setFecha(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));
          $ordenN -> setIngresos(0);
          $ordenN -> setsalidas(0);
          $ordenN -> setTotal(0);
          $ordenN -> setEstado(0);
          $em -> persist($ordenN);
          $em -> flush();
        }


        }

        return $this->render('orden_diaria/index.html.twig', [
            'formulario' => $formulario -> createView(),
            'ordenes' => $ordenDiaria
        ]);
    }
}
