<?php

namespace Rotis\CourseMakerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CourseMakerController extends Controller
{
    public function dispatcherAction()
    {
        return $this->render('RotisCourseMakerBundle:CourseMaker:test.html.twig');
    }
}
