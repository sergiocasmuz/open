<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Choferes;
use App\Entity\Viajes;
use App\Entity\ChoferesDiaria;
use App\Entity\OrdenDiaria;
use App\Entity\MovDiaria;
use App\Entity\Cuentas;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Doctrine\ORM\EntityRepository;

class DiariaController extends AbstractController
{
    /**
     * @Route("/diaria", name="diaria")
     */
    public function index(Connection $connection, Request $request)
    {
        $em = $this -> getDoctrine() -> getManager();

        $choferes = $em -> getRepository(Choferes::class) -> findAll();

        $oDiaria = $em -> getRepository(OrdenDiaria::class) -> findByOD();
        $od = $oDiaria[0][1];

        $movimientoE = $em -> getRepository(MovDiaria::class)->findByEntradas($od);
        $movimientoS = $em -> getRepository(MovDiaria::class)->findBySalidas($od);

        ///////////////////////MOVIMIENTOS
        $formMov = $this -> createFormBuilder()
        -> add('tipo', ChoiceType::class, array('choices' => array('----------------' => array(
                                                                                            'Entrada'=>'entrada',
                                                                                            'Salida'=>'salida') ),
                                                                'attr' => array('class' => 'form-control')  ))

        -> add('detalle', TextareaType::class, array('attr' => array('class' => 'form-control')))
        -> add('monto', IntegerType::class, array('attr' => array('class' => 'form-control')))
        -> add('ingresar', SubmitType::class, array('attr' => array('class' => 'btn btn-primary')))

        -> getForm()
        -> handleRequest($request);

          if ( $formMov->isSubmitted() && $formMov->isValid()) {

              $r = $formMov -> getData();

              if($r["monto"]!=0){

              if($r["tipo"] == "entrada"){$monto = $r["monto"];}else{$monto = $r["monto"] *-1;}
              $movDiaria = new MovDiaria();
              $movDiaria -> setFecha(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));
              $movDiaria -> setDetalle($r["detalle"]);
              $movDiaria -> setMonto($monto);
              $movDiaria -> setODiaria($od);
              $em -> persist($movDiaria);
              $em -> flush();

              return $this->redirect("/diaria");

            }

          }


          //////////////resumen
          $sumaCuentas = $em -> getRepository(Cuentas::class) -> findByIngresos($od);
          $sumaDeudas = $em -> getRepository(Cuentas::class) -> findByDeudas($od);

          $movEntradas = $em -> getRepository(MovDiaria::class) -> findByEntradasTotal($od);
          $movSalidas = $em -> getRepository(MovDiaria::class)  -> findBySalidasTotal($od);

          $entradas = $movEntradas["suma"] + $sumaCuentas["suma"];
          $salidas = $movSalidas["suma"] + $sumaDeudas["suma"];

          $total = $entradas + $salidas;

          $formCerrarDia = $this -> createFormBuilder()
          -> add('entradas', TextType::class, array('attr' => array('class' => 'form-control', 'value' => $sumaCuentas["suma"])))
          -> add('salidas', TextType::class, array('attr' => array('class' => 'form-control', 'value' => $sumaDeudas["suma"])))
          -> add('total', TextType::class)

          -> getForm()
          -> handleRequest($request);

          if ( $formCerrarDia->isSubmitted() && $formCerrarDia->isValid()) {}

        $chofiDiaria = $em -> getRepository(ChoferesDiaria::class) -> findByODiaria($od);

        $list[""]="";
        foreach ($choferes as $choferDia) {
          $chofer = $em -> getRepository(Choferes::class) -> find($choferDia -> getId());
          $list[$chofer -> getNombre()." #".$chofer -> getId()] = $chofer -> getId();
        }


        $formIniDia = $this -> createFormBuilder()
        -> add ('chofer', ChoiceType::class, array('choices' => array('----------------' => $list ), 'attr' => array('class' => 'form-control')  ))
        -> add('abrir', SubmitType::class, array('attr' => array('class' => 'btn btn-primary')))
        -> getForm()
        -> handleRequest($request);

        if ( $formIniDia->isSubmitted() && $formIniDia->isValid()) {

            $rta = $formIniDia -> getData();

            $check1 = $em -> getRepository(ChoferesDiaria::class) -> findByCheck($rta["chofer"], 2);

            if(count($check1) < 1 ){

            $now = \DateTime::createFromFormat('Y-m-d', date("Y-m-d"));

            $abrir = new ChoferesDiaria();
            $abrir -> setIngreso($now);
            $abrir -> setChoferId($rta["chofer"]);
            $abrir -> setODiaria($od);
            $abrir -> setEstado(0);
            $em->persist($abrir);
            $em->flush();
            return $this->redirect("/diaria");
          }

        }

        $sql = "SELECT * from choferes_diaria chD
                                left join choferes ch on ch.id = chD.chofer_id
                                where chD.o_diaria=? ";

        $stmt = $connection->prepare($sql);
        $stmt->bindValue(1, $od);
        $stmt->execute();
        $chof= $stmt->fetchAll();

        $chofis = array();
        foreach ($chof as $chofer) {

          $viajes = $em -> getRepository(Viajes::class) -> findByRec($chofer["id"],$od);
          if($viajes[0][1]>0){
          $chofis[$chofer["id"]]["suma"] = $viajes[0][1];
        }else{$chofis[$chofer["id"]]["suma"]=0;}

          $chofis[$chofer["id"]]["porc"] = $chofer["porcentaje"];
          $chofis[$chofer["id"]]["comi"] = ($chofer["porcentaje"] * $viajes[0][1]) / 100 ;
          $chofis[$chofer["id"]]["id"] = $chofer["id"];
          $chofis[$chofer["id"]]["nombre"] = $chofer["nombre"]." #".$chofer["id"];
          $chofis[$chofer["id"]]["estado"] = $chofer["estado"];
        }


        return $this->render('diaria/index.html.twig', [
            'chofiDiaria' => $chofis,
            'formMov' => $formMov -> createView(),
            'formIniDia' => $formIniDia -> createView(),
            'movimientoE' => $movimientoE,
            'movimientoS' => $movimientoS,
            'formCerrarDia' => $formCerrarDia -> createView(),
            'entradas' => $entradas,
            'salidas' => $salidas,
            'total' => $total
        ]);
    }
}
