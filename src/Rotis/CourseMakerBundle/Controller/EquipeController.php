<?php
namespace Rotis\CourseMakerBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

use Rotis\CourseMakerBundle\Form\Type\RegistrationType;
use Rotis\CourseMakerBundle\Form\Model\Registration;

class EquipeController extends Controller
{
    public function registerAction()
    {
           $form = $this->createForm(
            new RegistrationType(),
            new Registration()
        );
        return $this->render(
            'RotisCourseMakerBundle:Equipe:register.html.twig',
            array('form' => $form->createView())
        );
    }

    public function createAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $form = $this->createForm(new RegistrationType(), new Registration());
        $form->bind($this->getRequest());
        if ($form->isValid())
        {   
            $registration = $form->getData();
            $em->persist($registration->getUser());
            $em->flush();
            
            return $this->redirect($this->generateUrl('accueil'));
        }
        
        return $this->render('RotisCourseMakerBundle:Equipe:register.html.twig', array('form' => $form->createView()));
    }
}
