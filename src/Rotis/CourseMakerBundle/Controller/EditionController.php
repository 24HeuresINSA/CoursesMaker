<?php
namespace Rotis\CourseMakerBundle\Controller;


use Rotis\CourseMakerBundle\Entity\Edition;
use Rotis\CourseMakerBundle\Form\EditionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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

    public function createAction(Request $request)
    {
        $edition = new Edition();
        $form = $this->createForm(new EditionType(),$edition);
        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if($form->isValid())
            {
                $edition->setShowResults(false);
                $this->getDoctrine()->getManager()->persist($edition);
                $this->getDoctrine()->getManager()->flush();
                return $this->redirect($this->generateUrl('dashboard'));
            }
        }
        return $this->render('RotisCourseMakerBundle:CRUD:edition.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function removeAction($id)
    {
        $edition = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Edition')->find($id);
        $this->getDoctrine()->getManager()->remove($edition);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirect($this->generateUrl('dashboard'));
    }

    public function editAction(Request $request, $id)
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

    public function linkResultsAction()
    {
        $editions = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Edition')->findBy(array('hasResults' => true));

        return $this->render('RotisCourseMakerBundle:Edition:link.html.twig',array(
            'editions' => $editions,
        ));
    }
}
