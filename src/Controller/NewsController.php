<?php


namespace App\Controller;

use App\Entity\Hashtags;
use App\Entity\News;
use App\Repository\NewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function IndexAction(PaginatorInterface $paginator, Request $request)
    {
        $query = $this->getDoctrine()->getRepository(News::class)->findAllQuery();

        $news = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        $date = new \DateTime();
        $date->modify('-20 day');

        $hashtags = $this->getDoctrine()->getRepository(Hashtags::class)->findByDate($date);

        return $this->render('news/index.html.twig', ['news' => $news, 'pop_hashtags' => $hashtags]);
    }

    /**
     * @Route("/search", name="news_search")
     * @param Request $request
     * @return Response
     */
    public function search(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request)
    {
        $query = $request->query->get('q');
        $where = $request->query->get('w');
        $queryB = $this->getDoctrine()->getRepository(News::class)->searchByQuery($query, $where);

        $news = $paginator->paginate(
            $queryB, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        $date = new \DateTime();
        $date->modify('-20 day');

        $hashtags = $this->getDoctrine()->getRepository(Hashtags::class)->findByDate($date);

        return $this->render('news/index.html.twig', ['news' => $news, 'pop_hashtags' => $hashtags]);
    }

    /**
     * @Route("/{slug}", name="tagpage")
     * @param $slug
     * @return Response
     */
    public function HashtagAction($slug, EntityManagerInterface $em, PaginatorInterface $paginator, Request $request)
    {
        $queryB = $this->getDoctrine()->getRepository(News::class)->findByTag($slug);

        $news = $paginator->paginate(
            $queryB, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );


        $date = new \DateTime();
        $date->modify('-20 day');

        $hashtags = $this->getDoctrine()->getRepository(Hashtags::class)->findByDate($date);

        return $this->render('news/hashtag.html.twig', ['news' => $news, 'pop_hashtags' => $hashtags, 'tag' => $slug]);
    }

}