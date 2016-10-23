<?php
/**
 * Created by PhpStorm.
 * User: carlosagudobelloso
 * Date: 23/10/16
 * Time: 11:51.
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="image_message")
 * @ORM\Entity(repositoryClass="AppBundle\Repositories\ImageMessageRepository")
 */
class ImageMessage
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    private $image;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return ImageMessage
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set image.
     *
     * @param string $image
     *
     * @return ImageMessage
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image.
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }
}
