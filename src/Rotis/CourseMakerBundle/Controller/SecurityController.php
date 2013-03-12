<?php
namespace Rotis\CourseMakerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;


use Rotis\CourseMakerBundle\Form\Type\RegistrationType;
use Rotis\CourseMakerBundle\Form\Model\Registration;

class SecurityController extends Controller
{
    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();

        // recupere l erreur de login si presente
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR))
        {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        }
        else
        {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
        
        return $this->render('RotisCourseMakerBundle:Security:login.html.twig',
            array(
                //last username entered by the user
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error'         => $error,
            )
        );


    }
    
    public function registerAction()
    {
           $form = $this->createForm(
            new RegistrationType(),
            new Registration()
        );
        return $this->render(
            'RotisCourseMakerBundle:Security:register.html.twig',
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
            $encoder = $this->encoder->getEncoder($user);
            $raw = $user->getPassword();
            $salt = $user->getSalt();
            $encoded = $encoder->encodePassword($raw, $salt);

            if (!$encoder->isPasswordValid($encoded, $raw, $salt)) 
            {
                throw new \Exception('Password incorrectly encoded during user registration', 428);
            }
            else 
            {
                $user->setPassword($encoded);
            }
            $registration = $form->getData();
            $em->persist($registration->getUser());
            $em->flush();
            
            return $this->redirect($this->generateUrl('accueil'));
        }
        
        return $this->render('Rotis:CourseMakerBundle:register.html.twig', array('form' => $form->createView()));
    }
}
