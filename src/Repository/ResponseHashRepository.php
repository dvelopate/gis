<?php declare(strict_types = 1);

namespace App\Repository;

use App\Entity\ResponseHash;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ResponseHash|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResponseHash|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResponseHash[]    findAll()
 * @method ResponseHash[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResponseHashRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResponseHash::class);
    }

    public function save(ResponseHash $responseHash): void
    {
        $this->_em->persist($responseHash);
        $this->_em->flush();
    }
}
