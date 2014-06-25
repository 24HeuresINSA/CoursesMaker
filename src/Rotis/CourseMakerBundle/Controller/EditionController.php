<?php
namespace Rotis\CourseMakerBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;

class EditionController extends Controller
{
    public function numeroAction($numero)
    {
        $edition = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Edition')->findOneBy(array('numero' => $numero));

        return $this->render('RotisCourseMakerBundle:Edition:show.html.twig', array(
            'edition' => $edition,
        ));
    }

    public function createEditionAction(Request $request)
    {
        $edition = new Edition();
        $form = $this->createForm(new EditionType(),$edition);
        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if($form->isValid())
            {
                $this->getDoctrine()->getManager()->persist($edition);
                $this->getDoctrine()->getManager()->flush();
                return $this->redirect($this->generateUrl('dashboard'));
            }
        }
        return $this->render('RotisCourseMakerBundle:CRUD:edition.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function removeEditionAction($id)
    {
        $edition = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Edition')->find($id);
        $this->getDoctrine()->getManager()->remove($edition);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirect($this->generateUrl('dashboard'));
    }

    public function editEditionAction(Request $request, $id)
    {
        $edition = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Edition')->find($id);
        $form = $this->createForm(new EditionType, $edition);
        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if($form->isValid())
            {
                $this->getDoctrine()->getManager()->flush();
                return $this->redirect($this->generateUrl('dashboard'));
            }
        }
        return $this->render('RotisCourseMakerBundle:CRUD:edition.html.twig',array(
            'form' => $form->createView(),
        ));
    }
}
