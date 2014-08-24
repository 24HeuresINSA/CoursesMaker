<?php

namespace Rotis\CourseMakerBundle\Controller;

use Rotis\CourseMakerBundle\Entity\Resultat;
use Rotis\CourseMakerBundle\Form\ResultatType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ResultatController extends Controller
{
    public function createAction(Request $request)
    {
        $resultat = new Resultat();
        $form = $this->createForm(new ResultatType(),$resultat);
        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if($form->isValid())
            {
                foreach($form->get('courses')->getData() as $course) {
                    $resultat->addCourse($course);
                }
                $this->getDoctrine()->getManager()->persist($resultat);
                $this->getDoctrine()->getManager()->flush();

                return $this->redirect($this->generateUrl('dashboard'));
            }
        }
        return $this->render('RotisCourseMakerBundle:CRUD:resultat.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function removeAction($id)
    {
        $resultat = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Resultat')->find($id);
        foreach($resultat->getCourses() as $course) {
            $resultat->removeCourse($course);
        }
        $this->getDoctrine()->getManager()->remove($resultat);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirect($this->generateUrl('dashboard'));
    }
}
