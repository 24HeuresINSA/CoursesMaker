<?php

namespace Rotis\CourseMakerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    public function relousAction()
    {
        $coureursNoMail =  $this->getDoctrine()->getManager()->getRepository('RotisCourseMakerBundle:Joueur')->findJWithoutMail();
        $coureursNada = $this->getDoctrine()->getManager()->getRepository('RotisCourseMakerBundle:Joueur')->fidJWithoutMailNorTel();

        return $this->render('RotisCourseMakerBundle:Admin:relous.html.twig', array(
            'coureursNoMail' => $coureursNoMail,
            'coureursNada' => $coureursNada, 
        ));
    }


}
