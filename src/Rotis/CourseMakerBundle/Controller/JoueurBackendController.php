<?php
namespace Rotis\CourseMakerBundle\Controller;

use Symfony\Component\Security\Core\SecurityContext;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sonata\AdminBundle\Controller\CRUDController;

class JoueurBackendController extends CRUDController
{
    public function batchActionMerge(ProxyQueryInterface $selectedModelQuery)
    {
        $selectedModels = $selectedModelQuery->execute();

        // we do the work here
        $emails = '';
        $count = 0;
        foreach ($selectedModels as $selectedModel) {
            $emails .= $selectedModel->getEmail() . "\n";
            $count++;
        }

        return $this->render('RotisCourseMakerBundle:Backend:joueurs_email.html.twig', array(
                'emails' => $emails,
                'count' => $count
            ));
    }
}
