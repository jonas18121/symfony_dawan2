<?php
//service

namespace App\SearchQuery;

use App\Repository\BoardGameRepository;

class BoardGameQuery
{
    private $repository;

    public function __construct(BoardGameRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * example: /search/name=example-name+age-groupe=12
     * 
     * @return \App\Entity\BoardGame[]
     */
    public function createCriteria(string $query) : array
    {
        //var_dump($query);//die;
        $criteriaStrings = explode('+', $query);

        $criteriaAsArray = [];

        foreach($criteriaStrings as $criteria)
        {
            [$fieldName, $value] = explode('=', $criteria);
            $criteriaAsArray[$fieldName] = $value;
        }

        $builder = $this->repository->createdQueryBuilder('bg');

        foreach($criteriaAsArray as $field => $value)
        {
            //création de la requête
        }

        return $builder->getQuery()->getResult();
    }
}