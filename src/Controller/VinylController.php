<?php

namespace App\Controller;

//050923 use App\Entity\VinylMix;
use App\Repository\VinylMixRepository;
//060923 use App\Service\MixRepository;
//050923 use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\String\u;

class VinylController extends AbstractController
{
	 public function __construct(
        private bool $isDebug,
//050923        private MixRepository $mixRepository
    )
    {}
	
    #[Route('/', name: 'app_homepage')]
    public function homepage(): Response
    {
//        return new Response('Title: "PB and Jams"');
        $tracks = [
            ['song' => 'Gangsta\'s Paradise', 'artist' => 'Coolio'],
            ['song' => 'Waterfalls', 'artist' => 'TLC'],
            ['song' => 'Creep', 'artist' => 'Radiohead'],
            ['song' => 'Kiss from a Rose', 'artist' => 'Seal'],
            ['song' => 'On Bended Knee', 'artist' => 'Boyz II Men'],
            ['song' => 'Fantasy', 'artist' => 'Mariah Carey'],
        ];
        //dd($tracks);
       // dump($tracks);
        return $this->render('vinyl/homepage.html.twig', [
            'title' => 'PB & Jams',
            'tracks' => $tracks,
        ]);
    }

//    public function browse(EntityManagerInterface $entityManager, string $slug = null): Response
    #[Route('/browse/{slug}', name: 'app_browse')]
	public function browse(VinylMixRepository $mixRepository, Request $request, string $slug = null): Response
    {
	//130723	dump($this->isDebug);
		//dd($this->getParameter('kernel.project_dir'));
        $genre = $slug ? u(str_replace('-', ' ', $slug))->title(true) : null;
//		$mixes = $this->getMixes();
//270623		dump($cache);
/*280623
        $mixes = $cache->get('mixes_data', function(CacheItemInterface $cacheItem) use ($httpClient) {
            $cacheItem->expiresAfter(5);
			$response = $httpClient->request('GET', 'https://raw.githubusercontent.com/SymfonyCasts/vinyl-mixes/main/mixes.json');
            return $response->toArray();
        });
		*/
		//310823 $mixes = $this->mixRepository->findAll();
//050923        $mixRepository = $entityManager->getRepository(VinylMix::class);
//050923		dd($mixRepository);
//        $mixes = $mixRepository->findAll();
//		$mixes = $mixRepository->findBy(['id' => '1']);
//080923		$mixes = $mixRepository->findBy([], ['votes' => 'DESC']);
//171023		$mixes = $mixRepository->findAllOrderedByVotes($slug);
		$queryBuilder = $mixRepository->createOrderedByVotesQueryBuilder($slug);
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            $request->query->get('page', 1),
            9
        );
        return $this->render('vinyl/browse.html.twig', [
            'genre' => $genre,
            'pager' => $pagerfanta,
        ]);		
      //010923  dd($mixes);
		/*171023
        return $this->render('vinyl/browse.html.twig', [
            'genre' => $genre,
            'mixes' => $mixes,
        ]);
		*/
    }
	private function getMixes(): array
    {
        // temporary fake "mixes" data
        return [
            [
                'title' => 'PB & Jams',
                'trackCount' => 14,
                'genre' => 'Rock',
                'createdAt' => new \DateTime('2021-10-02 00:00:00'),
            ],
            [
                'title' => 'Put a Hex on your Ex',
                'trackCount' => 8,
                'genre' => 'Heavy Metal',
                'createdAt' => new \DateTime('2022-04-28 00:00:00'),
            ],
            [
                'title' => 'Spice Grills - Summer Tunes',
                'trackCount' => 10,
                'genre' => 'Pop',
                'createdAt' => new \DateTime('2019-06-20 00:00:00'),
            ],
        ];
    }
}