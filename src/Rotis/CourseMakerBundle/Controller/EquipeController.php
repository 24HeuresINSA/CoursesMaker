<?php
namespace Rotis\CourseMakerBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Rotis\CourseMakerBundle\Form\Type\RegistrationType;
use Rotis\CourseMakerBundle\Form\Model\Registration;

class EquipeController extends Controller
{
    public function registerAction()
    {

           if (true === $this->get('security.context')->isGranted('ROLE_USER')) 
           {
               return $this->redirect($this->generateUrl('accueil'));
           }
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
            $factory = $this->get('security.encoder_factory');
            $registration = $form->getData();
            $user = $registration->getUser();
            $encoder = $factory->getEncoder($user);
            $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
            $user->setPassword($password);
            $em->persist($user);
            $em->flush();
            
            return $this->redirect($this->generateUrl('accueil'));
        }
        
        return $this->render('RotisCourseMakerBundle:Equipe:register.html.twig', array('form' => $form->createView()));
    }
}
