<?php

namespace Rotis\CourseMakerBundle\Controller;

use Rotis\CourseMakerBundle\Entity\Type;
use Rotis\CourseMakerBundle\Form\TypeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TypeController extends Controller
{
    public function createAction(Request $request)
    {
        $type = new Type();
        $form = $this->createForm(new TypeType(),$type);
        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if($form->isValid())
            {
                $this->getDoctrine()->getManager()->persist($type);
                $this->getDoctrine()->getManager()->flush();

                return $this->redirect($this->generateUrl('dashboard'));
            }
        }
        return $this->render('RotisCourseMakerBundle:CRUD:type.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function removeAction($id)
    {
        $type = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Type')->find($id);
        $this->getDoctrine()->getManager()->remove($type);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirect($this->generateUrl('dashboard'));
    }

    public function editAction(Request $request, $id)
    {
        $type = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Type')->find($id);
        $form = $this->createForm(new TypeType(), $type);
        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if($form->isValid())
            {
                $this->getDoctrine()->getManager()->flush();
                return $this->redirect($this->generateUrl('dashboard'));
            }
        }
        return $this->render('RotisCourseMakerBundle:CRUD:type.html.twig',array(
            'form' => $form->createView(),
        ));
    }
}
