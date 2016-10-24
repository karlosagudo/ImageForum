<?php
/**
 * Created by PhpStorm.
 * User: carlosagudobelloso
 * Date: 24/10/16
 * Time: 6:10.
 */

namespace AppBundle\Services;

use AppBundle\Entity\Forum;
use Doctrine\ORM\EntityManager;

class VisitsCounter
{
    /** @var EntityManager */
    private $em;

    /**
     * VisitsCounter constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return int
     */
    public function getViews($addOne = true)
    {
        if (!$forum = $this->em->getRepository('AppBundle:Forum')->find(1)) { //when we start
            $forum = new Forum();
            $forum->setViews(0);
            $forum->setId(1);
        }

        $numberOfViews = $forum->getViews();

        if (!$addOne) { //ajax view
            return $numberOfViews;
        }

        $forum->setViews($numberOfViews + 1);

        $this->em->persist($forum);
        $this->em->flush($forum);

        return $numberOfViews + 1;
    }
}
