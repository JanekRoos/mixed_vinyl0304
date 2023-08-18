<?php

namespace App\Service;

use Psr\Cache\CacheItemInterface;
use Symfony\Bridge\Twig\Command\DebugCommand;
//use Symfony\Component\Console\Input\ArrayInput;
//use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MixRepository
{
	//private HttpClientInterface $httpClient,
    public function __construct(
	    private HttpClientInterface $githubContentClient,
        private CacheInterface $cache,
		#[Autowire('%kernel.debug%')]
		private bool $isDebug,
		#[Autowire(service: 'twig.command.debug')]
        private DebugCommand  $twigDebugCommand,
		)
    {}
	
    public function findAll(): array
    {
//        $output = new BufferedOutput();
//        $this->twigDebugCommand->run(new ArrayInput([]), $output);
//        dd($output);		
//dd($this->githubContentClient);
        return $this->cache->get('mixes_data', function(CacheItemInterface $cacheItem) {
            //$cacheItem->expiresAfter(5);
			$cacheItem->expiresAfter($this->isDebug ? 5 : 60);
            //$response = $this->httpClient->request('GET', 'https://raw.githubusercontent.com/SymfonyCasts/vinyl-mixes/main/mixes.json');
//			 $response = $this->httpClient->request('GET', '/SymfonyCasts/vinyl-mixes/main/mixes.json');
			/* $response = $this->githubContentClient->request('GET', '/SymfonyCasts/vinyl-mixes/main/mixes.json', [
                'headers' => [
                    'Authorization' => 'Token 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',
                ]
            ]);
			*/
			$response = $this->githubContentClient->request('GET', '/SymfonyCasts/vinyl-mixes/main/mixes.json');
            return $response->toArray();
        });
    }
}