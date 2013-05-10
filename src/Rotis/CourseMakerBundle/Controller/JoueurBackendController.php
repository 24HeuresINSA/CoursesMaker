<?php
namespace Rotis\CourseMakerBundle\Controller;

use Symfony\Component\Security\Core\SecurityContext;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sonata\AdminBundle\Controller\CRUDController;

class JoueurBackendController extends CRUDController
{
    public function batchActionEmails(ProxyQueryInterface $selectedModelQuery)
    {
        $selectedModels = $selectedModelQuery->execute();

        // we do the work here
        $allEmails = '';
        $count = 0;

        foreach ($selectedModels as $selectedModel) {
            $allEmails .= $selectedModel->getEmail() . "\n";

            $count++;
        }

        return $this->render('RotisCourseMakerBundle:Backend:joueurs_email.html.twig', array(
                'emails' => $allEmails,
                'count' => $count
            ));
    }

    public function batchActionEmailsByCourse(ProxyQueryInterface $selectedModelQuery)
    {
        $selectedModels = $selectedModelQuery->execute();

        // we do the work here
        $emailsByCourse = array();
        $count = 0;

        foreach ($selectedModels as $selectedModel) {
            $equipe = $selectedModel->getEquipe();

            if (! array_key_exists($equipe->getCourse()->getNom(), $emailsByCourse)) {
                $emailsByCourse[$equipe->getCourse()->getNom()] = array();
            }

            if (! array_key_exists($equipe->getCategorie()->getNom(), $emailsByCourse[$equipe->getCourse()->getNom()])) {
                $emailsByCourse[$equipe->getCourse()->getNom()][$equipe->getCategorie()->getNom()] = '';
            }

            $emailsByCourse[$equipe->getCourse()->getNom()][$equipe->getCategorie()->getNom()] .= $selectedModel->getEmail() . "\n";

            $count++;
        }

        return $this->render('RotisCourseMakerBundle:Backend:joueurs_email.html.twig', array(
                'emailsByCourse' => $emailsByCourse,
                'count' => $count
            ));
    }
}
