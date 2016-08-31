<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Training;
use AppBundle\Entity\TrainingRegistration;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * TrainingRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TrainingRepository extends EntityRepository
{
    /**
     * @param $id
     * @return Training[]|null
     */
    public function getFullCurrentTrainingEntityById($id)
    {
        $qb = $this->getFullCurrentTrainingQuery();
        $qb
            ->andWhere('t.id = :id')
            ->setParameter(':id', $id);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @return Training[]|null
     */
    public function getFullCurrentTrainings()
    {
        $qb = $this->getFullCurrentTrainingQuery();

        return $qb->getQuery()->getResult();
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getFullCurrentTrainingQuery()
    {
        $qb = $this->createQueryBuilder('t');
        $qb->leftJoin('t.trainingRegistrations', 'tr')->addSelect('tr')
            ->where($qb->expr()->gte('t.start', 'CURRENT_DATE()'));
        return $qb;
    }

}