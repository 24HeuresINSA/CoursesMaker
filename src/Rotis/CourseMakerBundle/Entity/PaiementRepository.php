<?php

namespace Rotis\CourseMakerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\Tests\Common\Annotations\Null;
use Symfony\Component\Validator\Constraints\Collection;

/**
 * PaiementRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PaiementRepository extends EntityRepository
{
    public function findByStatut($joueur,$statut)
    {
        $qb = $this->createQueryBuilder('p');
        $qb->where('p.statut = :statut')
            ->join('p.joueurs','j','WITH','j.id = :joueur')
            ->setParameters(array('joueur' => $joueur, 'statut' => $statut));
        $query = $qb->getQuery();
        $paiements = $query->getResult();
        return $paiements;
    }
}
