<?php


namespace App\Helpers\Filters;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class AbstractFilters
{
    public $repository;
    public $filters;

    public function __construct(ServiceEntityRepository $repository, $filters)
    {
        $this->repository = $repository;
        $this->filters = $filters;
    }
}