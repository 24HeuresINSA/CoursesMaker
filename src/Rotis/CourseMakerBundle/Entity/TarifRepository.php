<?php

namespace Rotis\CourseMakerBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * TarifRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TarifRepository extends EntityRepository
{
    public function findTarifByCourseCate($course,$cate)
    {
        $qb = $this
            ->createQueryBuilder('t')
            ->where('t.course =:course')
            ->andWhere('t.categorie =:cate')
            ->setParameters(array('course' => $course, 'cate' => $cate));
        $query = $qb->getQuery();
        $prix = $query->getSingleResult();
        return $prix;
    }
}
