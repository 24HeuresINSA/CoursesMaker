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

    public function adminAction()
    {
        if (true === $this->get('security.context')->isGranted('ROLE_ADMIN'))
        {
            return $this->render('RotisCourseMakerBundle:CourseMaker:admin.html.twig');
        }
        else
        {
            return $this->redirect($this->generateUrl('accueil'));
        }
    }

    public function infos_coureursAction()
    {
        if (true === $this->get('security.context')->isGranted('ROLE_USER'))
        {
            return $this->render('RotisCourseMakerBundle:CourseMaker:infos_coureurs.html.twig');
        }
        else
        {
            return $this->redirect($this->generateUrl('accueil'));
        }

    }
}
