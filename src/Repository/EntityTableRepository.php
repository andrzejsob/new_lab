<?php


namespace App\Repository;


class EntityTableRepository
{
    private $queryBuilder;
    private $queryBuilderCount;
    private $filters;
    private $order;
    private $pageNr;
    private $perPage;
    private $pageCount;

    public function __construct($repository, $filters, $order, $pageNr, $perPage)
    {
        $this->queryBuilder = $repository->getTableBuilder();
        $this->queryBuilderCount = $repository->getCountBuilder();
        $this->filters = $filters;
        $this->order = $order;
        $this->pageNr = $pageNr;
        $this->perPage = $perPage;
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        list($table, $column, $type) = explode('_', $this->order);

        return [
            'field' => $table . '_' . $column,
            'type' => $type
        ];
    }

    /**
     * @return mixed
     */
    public function getPageNr()
    {
        return $this->pageNr;
    }

    /**
     * @return mixed
     */
    public function getPageCount()
    {
        if (is_null($this->pageCount)) {
            $this->getCountResults();
        }

        return $this->pageCount;
    }


    public function findAllByFilters()
    {
        if ($countResults = $this->getCountResults()) {
            if ($countResults <= $this->perPage) {
                $this->pageNr = 1;
            }

            list($table, $column, $type) = explode('_', $this->order);

            $this->queryBuilder = $this->setParameters($this->queryBuilder);

            if (!empty(static::$columns[$table.'_'.$column]) && in_array($type, ['asc', 'desc'])) {
            $this->queryBuilder = $this->queryBuilder
                ->orderBy($table . '.' . $column, $type);
            }

            return $this->queryBuilder
                ->setFirstResult(($this->pageNr - 1) * $this->perPage)//offset
                ->setMaxResults($this->perPage)//limit
                ->getQuery()
                ->getResult();
        }

        return [];
    }

    private function getCountResults()
    {
        $this->queryBuilderCount = $this->setParameters($this->queryBuilderCount);
        $countResults = $this->queryBuilderCount
            ->getQuery()
            ->getSingleScalarResult();

        $this->pageCount = (int)ceil($countResults / $this->perPage);

        return $countResults;
    }

    private function setParameters($builder)
    {
        foreach ($this->filters as $table => $filtersByTable) {
            foreach ($filtersByTable as $column => $values) {
                if (!empty(static::$filterFields[$table][$column])) {
                    $builder = $builder
                        ->andWhere($table . '.' . $column . (is_array($values) ? ' IN (:' . $table . '_' . $column . ')' : ' = :' . $table . '_' . $column))
                        ->setParameter($table . '_' . $column, $values);
                }
            }
        }

        return $builder;
    }
}