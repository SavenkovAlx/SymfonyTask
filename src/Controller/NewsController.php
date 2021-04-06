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
    public function IndexAction(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request)
    {
        $dql = "SELECT n FROM App\Entity\News n ORDER BY n.publicatDate DESC";
        $query = $em->createQuery($dql);

        $news = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );


        $date = new \DateTime();
        $date->modify('-20 day');

//        $news = $this->getDoctrine()->getRepository(News::class)->findAll();
        $hashtags = $this->getDoctrine()->getRepository(Hashtags::class)->findByDate($date);

//        'news' => $news, 'pop_hashtags' => $hashtags

        return $this->render('news/index.html.twig', ['news' => $news, 'pop_hashtags' => $hashtags]);
    }

    /**
     * @Route("/search", name="news_search")
     * @param Request $request
     * @return Response
     */
    public function search(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request)
    {

        $querySearch = "%" . $request->query->get('q') . "%";
        $where = $request->query->get('w');
        if ($where == 'hashtag') {
            $dql = "SELECT n, h FROM App\Entity\News n JOIN n.hashtag h WHERE h.tag LIKE '$querySearch' ORDER BY n.publicatDate DESC";
        } elseif ($where == 'text') {
            $dql = "SELECT n FROM App\Entity\News n WHERE n.text LIKE '$querySearch' ORDER BY n.publicatDate DESC";

        }

        $query = $em->createQuery($dql);

        $news = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        $date = new \DateTime();
        $date->modify('-20 day');

//        $news = $this->getDoctrine()->getRepository(News::class)->searchByQuery($query, $where);
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
        $dql = "SELECT n, h FROM App\Entity\News n JOIN n.hashtag h WHERE h.tag LIKE '%$slug%' ORDER BY n.publicatDate DESC";
        $query = $em->createQuery($dql);

        $news = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );


        $date = new \DateTime();
        $date->modify('-20 day');

//        $news = $this->getDoctrine()->getRepository(News::class)->findByTag($slug);
        $hashtags = $this->getDoctrine()->getRepository(Hashtags::class)->findByDate($date);

        return $this->render('news/hashtag.html.twig', ['news' => $news, 'pop_hashtags' => $hashtags, 'tag' => $slug]);
    }

}