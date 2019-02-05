<?php

namespace App\Controller;

use App\Entity\Viajes;
use App\Entity\OrdenDiaria;
use App\Entity\Choferes;
use App\Entity\ChoferesDiaria;
use App\Entity\CuentasCorrientes;
use App\Entity\CCmovimientos;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Doctrine\DBAL\Driver\Connection;

class ViajesController extends AbstractController
{
    /**
     * @Route("/viajes", name="viajes")
     */
    public function index(Connection $connection, Request $request)
    {
       $em = $this -> getDoctrine() -> getManager();
       $viajes = $em -> getRepository(Viajes::class) -> findBy(array(),array('id' => 'desc'));
       $od = $em -> getRepository(OrdenDiaria::class) -> findByOD();
       $viajesCHOFI = array();
       //print_r($od[0][0]->getId());

       $c = 0;
       foreach ($viajes as $viaje) {
         $choferes = $em -> getRepository(Choferes::class) -> find($viaje->getChofer());

         $viajesCHOFI[$c]["id"] = $viaje -> getId();
         $viajesCHOFI[$c]["idChofer"] = $viaje -> getChofer();
         $viajesCHOFI[$c]["chofer"] = $choferes -> getNombre()." #".$viaje -> getChofer();
         $viajesCHOFI[$c]["origen"] = $viaje -> getOrigen();
         $viajesCHOFI[$c]["destino"] = $viaje -> getDestino();
         $viajesCHOFI[$c]["salida"] = $viaje -> getSalida();
         $viajesCHOFI[$c]["llegada"] = $viaje -> getLlegada();
         $viajesCHOFI[$c]["monto"] = $viaje -> getMonto();
         $c++;
       }



       $sql = "SELECT * from choferes_diaria chD
                               left join choferes ch on ch.id = chD.chofer_id
                               where chD.o_diaria=? and chD.estado= ?";
       $stmt = $connection->prepare($sql);
       $stmt->bindValue(1, $od[0][1]);
       $stmt->bindValue(2, 0);
       $stmt->execute();
       $chof= $stmt->fetchAll();

       $list[""] = "";
       foreach ($chof as $choferDia) {
       $list[$choferDia["nombre"]." #".$choferDia["id"]] = $choferDia["id"];
       }


       $cCorrientes = $em -> getRepository(CuentasCorrientes::class) -> findAll();

       $listCC[""] = "";
       foreach ($cCorrientes as $cc) {
       $listCC[$cc->getNombre()." ".$cc->getApellido()] = $cc->getId();
       }


        $formulario = $this -> createFormBuilder()

          -> add ('chofer', ChoiceType::class, array('choices' => array('----------------' => $list), 'attr' => array('class' => 'form-control')))
          -> add ('salida', TextType::class, array( 'attr'=> array('class' => 'form-control' ) ))
          -> add ('origen', TextType::class, array( 'attr'=> array('class' => 'form-control' ) ))
          -> add ('destino', TextType::class, array( 'attr'=> array('class' => 'form-control' ) ))
          -> add ('cc', ChoiceType::class, array('choices' => array('----------------' => $listCC), 'attr' => array('class' => 'form-control')))
          -> getForm()
          -> handleRequest($request);

          if ( $formulario->isSubmitted() && $formulario->isValid()) {

              $rta = $formulario -> getData();

              $viajes = new Viajes();
              //  \DateTime::createFromFormat('H:i:s', "01:00:00" )
              $viajes -> setDate(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));
              $viajes -> setSalida(\DateTime::createFromFormat('H:i:s', date("H:i:s") )  );
              $viajes -> setOrigen($rta["origen"]);
              $viajes -> setDestino($rta["destino"]);
              $viajes -> setLlegada(\DateTime::createFromFormat('H:i:s', "00:00:00" )  );
              $viajes -> setMonto("0");
              $viajes -> setEstado("0");
              $viajes -> setChofer($rta["chofer"]);
              $viajes -> setOp("0");
              $viajes -> setODiaria("8");
              //$viajes -> setCc($rta["cc"]);

              $em -> persist($viajes);
              $em -> flush();

              if( $rta["cc"] != null){

                  $ccMov = new CCmovimientos();

                  $ccMov -> setIdViaje($viajes->getId());
                  $ccMov -> setfecha(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));
                  $ccMov -> setMonto("0");
                  $ccMov -> setDetalle("Desde".$rta["origen"]." hasta ".$rta["destino"]);
                  $em -> persist($ccMov);
                  $em -> flush();



                            }

                return $this->redirect("/viajes");
          }

        return $this->render('viajes/index.html.twig', [
            'formulario' => $formulario -> createView(),
            'viajes' => $viajesCHOFI,
        ]);
    }
}
