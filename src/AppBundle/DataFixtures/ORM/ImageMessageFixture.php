<?php
/**
 * Created by PhpStorm.
 * User: carlosagudobelloso
 * Date: 23/10/16
 * Time: 12:39
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\ImageMessage;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class ImageMessageFixture implements FixtureInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $numberOfMessageImages = $this->container->getParameter('messages_fixtures_number');
        $width = mt_rand(640,1920);
        $height = floor($width / 1.78); //max of 1080

        for($i = 0; $i < $numberOfMessageImages ; $i++) {
            $imageMessage = new ImageMessage();
            $imageMessage->setTitle($faker->title);
            $imageMessage->setImage($faker->imageUrl($width, $height));
            $manager->persist($imageMessage);
        }

        $manager->flush();
    }
}