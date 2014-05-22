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

    public function excelAction($id = null)
    {
        if(!$id)
        {
            $courses = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Course')->findAll();
        }
        else
        {
            $courses = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Course')->findByEdition($id);
        }
        return $this->render('RotisCourseMakerBundle:Admin:excel.html.twig', array(
            'courses' => $courses,
        ));
    }


}
