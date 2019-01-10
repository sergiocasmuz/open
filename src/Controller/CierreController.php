<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Viajes;
use App\Entity\Choferes;
use App\Entity\ChoferesDiaria;
use App\Entity\Cuentas;
use App\Entity\OrdenDiaria;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class CierreController extends AbstractController
{
    /**
     * @Route("/cierre/{idChofer}", name="cierre")
     */
    public function index(Request $request, $idChofer)
    {

        $em = $this -> getDoctrine() -> getManager();

        $choferes = $em -> getRepository(Choferes::class) -> find($idChofer);
        $oDiaria = $em -> getRepository(OrdenDiaria::class) -> findByOD();

        $od = $oDiaria[0][1];

        $viajes = $em -> getRepository(Viajes::class) -> findByCierre($idChofer);
        $rec = $em -> getRepository(Viajes::class) -> findByDeuda($idChofer);
        $deuda = ($rec[0][1] * $choferes -> getPorcentaje()) / 100;

        $formCerrar = $this -> createFormBuilder()
        -> add('total', HiddenType::class, array('attr' => array('value' => $deuda )))
        -> getForm()
        -> handleRequest($request);

          if ( $formCerrar->isSubmitted() && $formCerrar->isValid()) {

            $rta = $formCerrar -> getData();

            $now = \DateTime::createFromFormat('Y-m-d', date("Y-m-d"));

            $mov1 = new Cuentas();
            $mov1 -> setNroCuenta($idChofer);
            $mov1 -> setFecha($now);
            $mov1 -> setDetalle("Deuda de comosión");
            $mov1 -> setMonto($deuda * -1);
            $mov1 -> setODiaria($od);

            $em -> persist($mov1);
            $em -> flush();

            $mov2 = new Cuentas();
            $mov2 -> setNroCuenta($idChofer);
            $mov2 -> setFecha($now);
            $mov2 -> setMonto($deuda);
            $mov2 -> setDetalle("Pago de deuda de comosión");
            $mov2 -> setODiaria($od);

            $em -> persist($mov2);
            $em -> flush();

            foreach ($viajes as $viaje) {

              $editarOP = $em -> getRepository(Viajes::class) -> find($viaje -> getId());

              $editarOP -> setOp($mov1->getId());
              $editarOP -> setEstado(1);
              $em -> persist($editarOP);
              $em -> flush();
            }

            $chofiDiaria = $em -> getRepository(ChoferesDiaria::class) -> findByOrdenDiaria($idChofer,$od);


            $chofiDiaria[0] -> setEstado(1);
            $em -> persist($chofiDiaria[0]);
            $em -> flush();

            return $this->redirect("/diaria");

          }

          $formAplazar = $this -> createFormBuilder()
          -> add('total1', HiddenType::class, array('attr' => array('value' => $deuda )))
          -> getForm()
          -> handleRequest($request);

          if ( $formAplazar->isSubmitted() && $formAplazar->isValid()) {

            $rta = $formCerrar -> getData();

            $now = \DateTime::createFromFormat('Y-m-d', date("Y-m-d"));

            $mov1 = new Cuentas();
            $mov1 -> setNroCuenta($idChofer);
            $mov1 -> setFecha($now);
            $mov1 -> setMonto($deuda * -1);

            $em -> persist($mov1);
            $em -> flush();

            $chofiDiaria = $em -> getRepository(ChoferesDiaria::class) -> find($idChofer);
            $em -> remove($chofiDiaria);
            $em -> flush();

          }


        return $this->render('cierre/resumen.html.twig', [
            'viajes' => $viajes,
            'rec' => $rec[0][1],
            'deuda' => $deuda,
            'formCierre' => $formCerrar -> createView(),
            'formAplazar' => $formAplazar -> createView()
        ]);
    }
}
