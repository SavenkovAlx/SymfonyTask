<?php


namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Entity\News;
use App\Entity\Hashtags;
use Abraham\TwitterOAuth\TwitterOAuth;


class UpdateNewsCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:update-news';

    public function __construct(EntityManagerInterface $em)
    {

        $this->entityManager = $em;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Update news feed from twitter BBC.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command update news from https://twitter.com/bbc');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $consumerKey = 'taTWgJe9JVBU9XXFu7zAK1JFX';
        $consumerKeySecret = 'EeyNv6jb36q0SWUNRpeJLFMS3pOqqzs24ZvuGki1foijgoG49W';
        $accessToken = '97639832-Z5o8XZi8dcUTpeUBfpcndD7MOnxT7iwkI7QeHJlDm';
        $accessTokenSecret = 'GzFlBECCLKx8xSK4kkfZguYFHGvSsZEPWIBTn1kEcgIXr';

        $connection = new TwitterOAuth($consumerKey, $consumerKeySecret, $accessToken, $accessTokenSecret);
        $tweets = $connection->get("statuses/user_timeline", ["count" => 20, "screen_name" => "BBC", "user_id" => "19701628", "exclude_replies" => true, "include_entities" => "true", "tweet_mode" => "extended"]);
        $em = $this->entityManager;
        $repository = $em->getRepository(News::class);

        foreach ($tweets as $tweet) {
            $newsId = $this->CreateNews($tweet, $em, $repository);

            if ($newsId == 0) {
                break;
            }

            $tags = array();

            foreach ($tweet->entities->hashtags as $tag) {
                array_push($tags, $tag->text);
            }

//            $this->CreateTag($em, $tags, $newsId);
        }


        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'Update News',
            '============',
            '',
        ]);

        return Command::SUCCESS;
    }

    public function CreateNews($tweet, $em, $repository)
    {
        $tagsRepository = $em->getRepository(Hashtags::class);
        $publicatDate = new \DateTime(date("Y-m-d H:i:s", strtotime($tweet->created_at)));

        $tags = array();

        foreach ($tweet->entities->hashtags as $tag) {
            $tagIn = $tagsRepository->findOneBy(['tag' => $tag->text]);
            if ($tagIn) {
                array_push($tags, $tagIn);
            } else {
                $tagIn = new Hashtags();
                $tagIn->setTag($tag->text);
                $em->persist($tagIn);
                $em->flush();
                array_push($tags, $tagIn);
            }

        }

        $news = new News();

        $text = "";

        if (!isset($tweet->text)) {
            $text = $tweet->full_text;
        } else {
            $text = $tweet->text;
        }

        $product = $repository->findBy(['publicatDate' => $publicatDate]);

        if (count($product) > 0) {
            print("Not found new tweets!");
            return 0;
        }

        $news = new News();

        $text = "";

        if (!isset($tweet->text)) {
            $text = $tweet->full_text;
        } else {
            $text = $tweet->text;
        }


        $news->setpublicatDate($publicatDate);
        $news->setText($text);
        $news->setHashtag($tags);

        if (isset($tweet->entities->media)) {
            $news->setImage($tweet->entities->media[0]->media_url_https);
        }

        $em->persist($news);
        $em->flush();

        $newsId = $news->getId();

        return $newsId;
    }

}