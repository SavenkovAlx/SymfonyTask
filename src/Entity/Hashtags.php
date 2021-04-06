<?php

namespace App\Entity;

use App\Repository\HashtagsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Hashtags
 *
 * @ORM\Table(name="hashtags")
 * @ORM\Entity(repositoryClass=HashtagsRepository::class)
 */
class Hashtags
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
     * @ORM\Column(name="tag", type="string", length=100, unique=true)
     */
    private $tag;

    /**
     * @var string
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\News", mappedBy="hashtag")
     * @ORM\JoinColumn(name="news_id", referencedColumnName="id", nullable=false)
     *
     */
    private $news;

    public function __construct() {
        $this->news = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set tag
     *
     * @param string $tag
     *
     * @return Hashtags
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Set news
     *
     * @param string $news
     *
     * @return NewsHashtags
     */
    public function setNews($news)
    {
        $this->hashtag = $news;

        return $this;
    }

    /**
     * Get news
     *
     * @return string
     */
    public function getNews()
    {
        return $this->news;
    }
}
