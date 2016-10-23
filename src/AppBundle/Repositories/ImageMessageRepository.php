<?php
/**
 * Created by PhpStorm.
 * User: carlosagudobelloso
 * Date: 23/10/16
 * Time: 12:58.
 */

namespace AppBundle\Repositories;

use Doctrine\ORM\EntityRepository;

class ImageMessageRepository extends EntityRepository
{
    public function getTotal()
    {
        $posts = $this->createQueryBuilder('posts')
            ->select('count(posts)')
            ->getQuery()
            ->useQueryCache(true)
            ->getSingleScalarResult();

        return $posts;
    }

    /**
     * @param $limit
     * @param $page
     *
     * @return array
     */
    public function getPaginate($limit, $page)
    {
        $offset = $limit * $page;

        $posts = $this->createQueryBuilder('posts')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->orderBy('posts.id','desc')
            ->getQuery()
            ->useQueryCache(true)
            ->getArrayResult();

        return $posts;
    }

    public function getAll()
    {
        $results = [];
        $arResults = $this->createQueryBuilder('posts')->getQuery()->useQueryCache(true)->getArrayResult();
        $i = 0;
        foreach($arResults as $arResult) {
            $results[$i] = array_values($arResult);
            $i++;
        }

        return $results;
    }
}
