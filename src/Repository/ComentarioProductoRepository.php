<?php

namespace App\Repository;

use App\Entity\ComentarioProducto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ComentarioProducto|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComentarioProducto|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComentarioProducto[]    findAll()
 * @method ComentarioProducto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComentarioProductoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ComentarioProducto::class);
    }

}
