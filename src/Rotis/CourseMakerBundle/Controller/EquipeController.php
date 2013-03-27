<?php
namespace Rotis\CourseMakerBundle\Controller;


use Rotis\CourseMakerBundle\Entity\Joueur;
use Rotis\CourseMakerBundle\Form\Model\PlayerAddition;
use Rotis\CourseMakerBundle\Form\Type\EditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Rotis\CourseMakerBundle\Form\Type\RegistrationType;
use Rotis\CourseMakerBundle\Form\Model\Registration;
use Rotis\CourseMakerBundle\Form\Type\PlayerAdditionType;

class EquipeController extends Controller
{
    public function registerAction()
    {
       if (true === $this->get('security.context')->isGranted('ROLE_USER')) {
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

        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('RotisCourseMakerBundle:Equipe');
        $em = $this->getDoctrine()->getEntityManager();
        $form = $this->createForm(new PlayerAdditionType(), new PlayerAddition());
        $equipe= $repository->findOneBy(array('username' => $name));

        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')
            && true === $this->get('security.context')->isGranted('ROLE_USER')
            && (! method_exists($this->get('security.context')->getToken()->getUser(), 'getName')
            || $this->get('security.context')->getToken()->getUser()->getName() !== $name))
        {
            return $this->redirect($this->generateUrl('accueil'));
        }

        if ($this->getRequest()->getMethod() == 'POST') {
            $form->bindRequest($this->getRequest());

            if ($form->isValid())
            {
                $factory = $this->get('security.encoder_factory');
                $registration = $form->getData();
                $joueur = $registration->getJoueur();

                /*
                 $encoder = $factory->getEncoder($user);
                $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
                $user->setPassword($password);
                $em->merge($user);
                $em->flush();
                $this->get('session')->setFlash(
                    'notice',
                    'Equipe modifiée!'
                );*/
                return $this->redirect($this->generateUrl('accueil'));
            }
        }

        return $this->render('RotisCourseMakerBundle:Equipe:edit_equipe.html.twig', array('equipe' => $equipe,'form' => $form->createView()));
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
            $joueur = $registration->getJoueur();
            $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('RotisCourseMakerBundle:Equipe');
            $encoder = $factory->getEncoder($user);
            $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
            $user->setPassword($password);
            $joueur->setEquipe($user);
            $user->addJoueur($joueur);
            $em->persist($joueur);
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
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('RotisCourseMakerBundle:Equipe');
        $equipe = $repository->findOneBy(array('username' => $name));
        $request = $this->get('request');
        $em = $this->get('doctrine.orm.entity_manager');
        $form = $this->get('form.factory')->create(new EditType());
        if ($name == $equipe->getUsername())
        {

            return $this->render('RotisCourseMakerBundle:Equipe:edit_equipe.html.twig',array('equipe' => $equipe,'form' => $form->createView()));
        }
        else
        {
            if (false === $this->get('security.context')->isGranted('ROLE_ADMIN'))
            {
                return $this->redirect($this->generateUrl('accueil'));
            }
            else
            {
                return $this->render('RotisCourseMakerBundle:Equipe:edit_equipe.html.twig',array('equipe' => $equipe,'form' => $form->  createView()));
            }
        }
    }

    public function listeAction($name)
    {
        if ( $name === "equipe" )
        {
                $repository = $this->getDoctrine()
                           ->getManager()
                           ->getRepository('RotisCourseMakerBundle:Equipe');


        $listeEquipes = $repository->findAll();
        return $this->render('RotisCourseMakerBundle:Equipe:control_equipe.html.twig',array('equipes' => $listeEquipes));
        }
        elseif ( $name === "course" )
        {

            $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('RotisCourseMakerBundle:Course');


            $listeCourses = $repository->findAll();
            return $this->render('RotisCourseMakerBundle:Course:control_course.html.twig',array('courses' => $listeCourses));

        }
     }
}
