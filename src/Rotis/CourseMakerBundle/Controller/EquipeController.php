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

    public function editAction($name)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $equipe = $em->getRepository('RotisCourseMakerBundle:Equipe')->findOneBy($name);
        $form = $this->createForm(new RegistrationType(),$equipe);
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
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
            $this->get('session')->setFlash(
                'notice',
                'Equipe mise a jour!'
            );
            return $this->redirect($this->generateUrl('accueil'));
        }
        }
        return $this->render('RotisCourseMakerBundle:Equipe:edit_equipe.html.twig', array('form' => $form->createView()));
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
            $this->get('session')->setFlash(
                'notice',
                'Equipe créée!'
            );
            return $this->redirect($this->generateUrl('accueil'));
        }
        
        return $this->render('RotisCourseMakerBundle:Equipe:register.html.twig', array('form' => $form->createView()));
    }

    public function edit_equipeAction($name)
    {
        if ($name == $this->getUser()->getUsername())
        {
            $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('RotisCourseMakerBundle:Equipe');
            $equipe = $repository->findOneBy(array('username' => $name));
            return $this->render('RotisCourseMakerBundle:Equipe:edit_equipe.html.twig',array('equipe' => $equipe));
        }
        else
        {
            if (false === $this->get('security.context')->isGranted('ROLE_ADMIN'))
            {
                return $this->redirect($this->generateUrl('accueil'));
            }
            else
            {
                $repository = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('RotisCourseMakerBundle:Equipe');
                $equipe = $repository->findOneBy(array('username' => $name));
                return $this->render('RotisCourseMakerBundle:Equipe:edit_equipe.html.twig',array('equipe' => $equipe));
            }
        }
    }

    public function listeAction()
    {
        $repository = $this->getDoctrine()
                           ->getManager()
                           ->getRepository('RotisCourseMakerBundle:Equipe');

        $listeEquipes = $repository->findAll();
        return $this->render('RotisCourseMakerBundle:Equipe:control_equipe.html.twig',array('equipes' => $listeEquipes));
    }
}
