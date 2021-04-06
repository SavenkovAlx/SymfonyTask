<?php

namespace App\Entity;

use App\Repository\NewsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * News
 *
 * @ORM\Table(name="news")
 * @ORM\Entity(repositoryClass=NewsRepository::class)
 */
class News
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text")
     */
    private $text;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="publicat_date", type="datetime")
     */
    private $publicatDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;


    /**
     * @var string
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Hashtags", inversedBy="news")
     * @ORM\JoinColumn(name="hashtags_id", referencedColumnName="id", nullable=false)
     */
    private $hashtag;
    public function __construct() {
        $this->hashtag = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return News
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set publicatDate
     *
     * @param \DateTime $publicatDate
     *
     * @return News
     */
    public function setpublicatDate($publicatDate)
    {
        $this->publicatDate = $publicatDate;

        return $this;
    }

    /**
     * Get publicatDate
     *
     * @return \DateTime
     */
    public function getpublicatDate()
    {
        return $this->publicatDate;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Set hashtag
     *
     * @param string $hashtag
     *
     * @return NewsHashtags
     */
    public function setHashtag($hashtag)
    {
        $this->hashtag = $hashtag;

        return $this;
    }

    /**
     * Get hashtag
     *
     * @return string
     */
    public function getHashtag()
    {
        return $this->hashtag;
    }
}

