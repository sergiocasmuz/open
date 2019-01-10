<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Choferes;
use App\Entity\Viajes;
use App\Entity\ChoferesDiaria;
use App\Entity\OrdenDiaria;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

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

        $chofiDiaria = $em -> getRepository(ChoferesDiaria::class) -> findByODiaria($od);

        $list[""]="";
        foreach ($choferes as $choferDia) {
          $chofer = $em -> getRepository(Choferes::class) -> find($choferDia -> getId());
          $list[$chofer -> getNombre()." #".$chofer -> getId()] = $chofer -> getId();
        }


        $formIniDia = $this -> createFormBuilder()
        -> add ('chofer', ChoiceType::class, array('choices' => array('----------------' => $list ), 'attr' => array('class' => 'form-control')  ))
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
            'formIniDia' => $formIniDia -> createView()
        ]);
    }
}
