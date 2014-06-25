<?php

namespace Rotis\CourseMakerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TarifController extends Controller
{
    public function createTarifAction(Request $request)
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

    public function removeTarifAction($id)
    {
        $tarif = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Tarif')->find($id);
        $this->getDoctrine()->getManager()->remove($tarif);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirect($this->generateUrl('dashboard'));
    }

    public function editTarifAction(Request $request, $id)
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
