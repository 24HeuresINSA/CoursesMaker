<?php

namespace Rotis\CourseMakerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CourseMakerController extends Controller
{
    public function accueilAction()
    {
        return $this->render('RotisCourseMakerBundle:CourseMaker:accueil.html.twig');
    }

    public function contactAction()
    {
        return $this->render('RotisCourseMakerBundle:CourseMaker:contact.html.twig');
    }

    public function infosAction()
    {
        return $this->render('RotisCourseMakerBundle:CourseMaker:infos.html.twig');
    }
}
