<?php

namespace App\Controller;

use App\Service\MixRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\String\u;

class VinylController extends AbstractController
{
	 public function __construct(
        private bool $isDebug,
        private MixRepository $mixRepository
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

    #[Route('/browse/{slug}', name: 'app_browse')]
    public function browse(string $slug = null): Response
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
		$mixes = $this->mixRepository->findAll();

        return $this->render('vinyl/browse.html.twig', [
            'genre' => $genre,
            'mixes' => $mixes,
        ]);
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