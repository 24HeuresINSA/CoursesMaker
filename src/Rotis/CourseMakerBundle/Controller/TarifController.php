<?php

namespace Rotis\CourseMakerBundle\Controller;

use Rotis\CourseMakerBundle\Entity\Tarif;
use Rotis\CourseMakerBundle\Form\TarifType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TarifController extends Controller
{
    public function createAction(Request $request)
    {
        $tarif = new Tarif();
        $form = $this->createForm(new TarifType(),$tarif);
        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if($form->isValid())
            {
                $this->getDoctrine()->getManager()->persist($tarif);
                $this->getDoctrine()->getManager()->flush();
                return $this->redirect($this->generateUrl('dashboard'));
            }
        }
        return $this->render('RotisCourseMakerBundle:CRUD:tarif.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function removeAction($id)
    {
        $tarif = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Tarif')->find($id);
        $this->getDoctrine()->getManager()->remove($tarif);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirect($this->generateUrl('dashboard'));
    }

    public function editAction(Request $request, $id)
    {
        $tarif = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Tarif')->find($id);
        $form = $this->createForm(new TarifType, $tarif);
        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if($form->isValid())
            {
                $this->getDoctrine()->getManager()->flush();
                return $this->redirect($this->generateUrl('dashboard'));
            }
        }
        return $this->render('RotisCourseMakerBundle:CRUD:tarif.html.twig',array(
            'form' => $form->createView(),
        ));
    }
}
