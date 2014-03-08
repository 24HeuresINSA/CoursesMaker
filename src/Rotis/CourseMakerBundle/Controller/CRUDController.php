<?php

namespace Rotis\CourseMakerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Rotis\CourseMakerBundle\Entity\Categorie;
use Rotis\CourseMakerBundle\Entity\Edition;
use Rotis\CourseMakerBundle\Entity\Course;
use Rotis\CourseMakerBundle\Entity\Tarif;
use Rotis\CourseMakerBundle\Entity\Type;

use Rotis\CourseMakerBundle\Form\CategorieType;
use Rotis\CourseMakerBundle\Form\EditionType;
use Rotis\CourseMakerBundle\Form\CourseType;
use Rotis\CourseMakerBundle\Form\TarifType;
use Rotis\CourseMakerBundle\Form\TypeType;


class CRUDController extends Controller
{

    public function dashboardAction()
    {
        $em = $this->getDoctrine()->getManager();
        $editions = $em->getRepository('RotisCourseMakerBundle:Edition')->findAll();
        $categories = $em->getRepository('RotisCourseMakerBundle:Categorie')->findAll();
        $types = $em->getRepository('RotisCourseMakerBundle:Type')->findAll();
        $courses = $em->getRepository('RotisCourseMakerBundle:Course')->findAll();
        $tarifs = $em->getRepository('RotisCourseMakerBundle:Tarif')->findAll();

        return $this->render('RotisCourseMakerBundle:CRUD:dashboard.html.twig', array(
            'editions' => $editions, 'categories' => $categories, 'types' => $types, 'courses' => $courses, 'tarifs' => $tarifs,
        ));
    }

    public function createCategorieAction(Request $request)
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

    public function removeCategorieAction($id)
    {
        $categorie = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Categorie')->find($id);
        $this->getDoctrine()->getManager()->remove($categorie);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirect($this->generateUrl('dashboard'));
    }

    public function editCategorieAction(Request $request, $id)
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

    public function createCourseAction(Request $request)
    {
        $course = new Course();
        $form = $this->createForm(new CourseType(),$course);
        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if($form->isValid())
            {
                $this->getDoctrine()->getManager()->persist($course);
                $this->getDoctrine()->getManager()->flush();
                return $this->redirect($this->generateUrl('dashboard'));
            }
        }
        return $this->render('RotisCourseMakerBundle:CRUD:course.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function removeCourseAction($id)
    {
        $course = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Course')->find($id);
        $this->getDoctrine()->getManager()->remove($course);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirect($this->generateUrl('dashboard'));
    }

    public function editCourseAction(Request $request, $id)
    {
        $course = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Course')->find($id);
        $form = $this->createForm(new CourseType, $course);
        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if($form->isValid())
            {
                $this->getDoctrine()->getManager()->flush();
                return $this->redirect($this->generateUrl('dashboard'));
            }
        }
        return $this->render('RotisCourseMakerBundle:CRUD:course.html.twig',array(
            'form' => $form->createView(),
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
        $tarif = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:tarif')->find($id);
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

    public function createTypeAction(Request $request)
    {
        $type = new Type();
        $form = $this->createForm(new TypeType(),$type);
        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if($form->isValid())
            {
                $this->getDoctrine()->getManager()->persist($type);
                $this->getDoctrine()->getManager()->flush();

                return $this->redirect($this->generateUrl('dashboard'));
            }
        }
        return $this->render('RotisCourseMakerBundle:CRUD:type.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function removeTypeAction($id)
    {
        $type = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Type')->find($id);
        $this->getDoctrine()->getManager()->remove($type);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirect($this->generateUrl('dashboard'));
    }

    public function editTypeAction(Request $request, $id)
    {
        $type = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Type')->find($id);
        $form = $this->createForm(new TypeType, $type);
        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if($form->isValid())
            {
                $this->getDoctrine()->getManager()->flush();
                return $this->redirect($this->generateUrl('dashboard'));
            }
        }
        return $this->render('RotisCourseMakerBundle:CRUD:type.html.twig',array(
            'form' => $form->createView(),
        ));
    }

}