<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Doctrine\DBAL\Driver\Connection;


use App\Entity\Choferes;
use App\Entity\ChoferesDiaria;
use App\Entity\OrdenDiaria;
use App\Entity\Viajes;

class EditarController extends AbstractController
{
    /**
     * @Route("/editar/{idViaje}", name="editar")
     */
    public function index(Connection $connection, Request $request, $idViaje)
    {
        $em = $this -> getDoctrine() -> getManager();
        $viajes = $em -> getRepository(Viajes::class) -> find($idViaje);
        $choferes = $em -> getRepository(Choferes::class) -> find($viajes->getChofer());
        $od = $em -> getRepository(OrdenDiaria::class) -> findByOD();

        $sql = "SELECT * from choferes_diaria chD
                                left join choferes ch on ch.id = chD.chofer_id
                                where chD.o_diaria=? and chD.estado= ?";
        $stmt = $connection->prepare($sql);
        $stmt->bindValue(1, $od[0][1]);
        $stmt->bindValue(2, 0);
        $stmt->execute();
        $chof= $stmt->fetchAll();

        $chofer1 = $em -> getRepository(Choferes::class) -> find($viajes->getChofer());

        $list[$chofer1->getNombre()." #".$chofer1->getId()] = $chofer1->getId();
        foreach ($chof as $choferDia) {
        $list[$choferDia["nombre"]." #".$choferDia["id"]] = $choferDia["id"];
        }





        $f = $viajes -> getDate();
        $f1 = $f->format('d/m/Y');

        $h =  $viajes -> getSalida();
        $h1 = $h->format('H:i');

        $h =  $viajes -> getLlegada();
        $h2 = $h->format('H:i');

        $formulario = $this -> createFormBuilder()
        -> add('idViaje', HiddenType::class, array('attr'=> array('class' => 'form-control', 'value' => $viajes -> getId() ) ) )
        -> add('fecha', TextType::class, array('attr'=> array('class' => 'form-control', 'value' => $f1 ) ) )
        -> add('salida', TextType::class, array('attr'=> array('class' => 'form-control', 'value' => $h1 ) ) )
        -> add('origen',TextType::class, array('attr'=> array('class' => 'form-control', 'value' => $viajes -> getOrigen()) ) )
        -> add('destino', TextType::class, array('attr'=> array('class' => 'form-control', 'value' => $viajes -> getDestino()) ) )
        -> add('llegada', TextType::class, array('attr'=> array('class' => 'form-control', 'value' => $h2 ) ) )
        -> add('monto', IntegerType::class, array('attr'=> array('class' => 'form-control', 'value' => $viajes -> getMonto() ) ) )
        -> add('chofer', ChoiceType::class,  array('choices' => array('----------------' => $list ), 'attr' => array('class' => 'form-control')  ))
        -> add('guardar', SubmitType::class, array('attr'=> array('class' => 'btn btn-primary') ))
        -> getForm()
        ->handleRequest($request);

          if ( $formulario->isSubmitted() && $formulario->isValid()) {

              $R = $formulario -> getData();

              $viaje = $em -> getRepository(Viajes::class) -> find($R["idViaje"]);

              $f1 = explode("/",$R["fecha"]);
              $f2 = $f1[2]."-".$f1[1]."-".$f1[0];

              $viaje -> setDate(\DateTime::createFromFormat('Y-m-d', date("Y-m-d", strtotime($f2))));
              $viaje -> setSalida(\DateTime::createFromFormat('H:i', date("H:i", strtotime($R["salida"])) )  );
              $viaje -> setOrigen($R["origen"]);
              $viaje -> setDestino($R["destino"]);
              $viaje -> setLlegada(\DateTime::createFromFormat('H:i', date("H:i", strtotime($R["llegada"])) ) );
              $viaje -> setMonto($R["monto"]);
              $viaje -> setChofer($R["chofer"]);
              $viaje -> setODiaria($od[0][0]->getId());

              $em -> persist($viaje);
              $em -> flush();

              $link = '/viajes';

              return $this->redirect($link);

          }

        return $this->render('editar/fichaViaje.html.twig', [
            'viajes' => $viajes,
            'chofer' => $choferes,
            'formulario' => $formulario -> createView(),
            'idViaje' => $idViaje
        ]);
    }
}
