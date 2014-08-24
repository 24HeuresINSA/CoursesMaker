<?php
namespace Rotis\CourseMakerBundle\Controller;


use Rotis\CourseMakerBundle\Entity\Categorie;
use Rotis\CourseMakerBundle\Form\CategorieType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;

class CategorieController extends Controller
{
    public function createAction(Request $request)
    {
        $categorie = new Categorie();
        $form = $this->createForm(new CategorieType(),$categorie);
        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if($form->isValid())
            {
                $this->getDoctrine()->getManager()->persist($categorie);
                $this->getDoctrine()->getManager()->flush();
                return $this->redirect($this->generateUrl('dashboard'));
            }
        }
        return $this->render('RotisCourseMakerBundle:CRUD:categorie.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function removeAction($id)
    {
        $categorie = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Categorie')->find($id);
        $this->getDoctrine()->getManager()->remove($categorie);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirect($this->generateUrl('dashboard'));
    }

    public function editAction(Request $request, $id)
    {
        $categorie = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Categorie')->find($id);
        $form = $this->createForm(new CategorieType, $categorie);
        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if($form->isValid())
            {
                $this->getDoctrine()->getManager()->flush();
                return $this->redirect($this->generateUrl('dashboard'));
            }
        }
        return $this->render('RotisCourseMakerBundle:CRUD:categorie.html.twig',array(
            'form' => $form->createView(),
        ));
    }

}
