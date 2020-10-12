<?php declare(strict_types = 1);

namespace App\Service;

use App\Repository\ResponseHashRepository;
use GuzzleHttp\Client;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Entity\Post;

class PostSyncService
{
    /** @var int $endpoint */
    private $endpoint;
    
    /** @var ResponseHashRepository */
    private $responseHashRepository;
    
    /** @var PostRepository */
    private $postRepository;
    
    /** @var UserRepository */
    private $userRepository;
    
    /** @var ResponseHashService */
    private $responseHashService;

    public function __construct(
        string $postEndpoint,
        PostRepository $postRepository,
        UserRepository $userRepository,
        ResponseHashRepository $responseHashRepository,
        ResponseHashService $responseHashService
    ) {
        $this->endpoint = $postEndpoint;
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
        $this->responseHashRepository = $responseHashRepository;
        $this->responseHashService = $responseHashService;
    }

    public function sync(): void
    {
        $client = new Client();

        $response = $client->request('GET', $this->endpoint, [
            'Content-Type' => 'application/json',
        ]);

        $result = $response->getBody()->getContents();

        if ($this->responseHashService->handleResponseHash($result, $this->endpoint)) {
            $this->postRepository->clean();
            $this->import(json_decode($result, true));
        }
    }

    private function import(array $result): void
    {
        foreach ($result as $singlePost) {
            $post = new Post();

            $post
                ->setUser($this->userRepository->findOneBy(['userId' => $singlePost['userId']]))
                ->setTitle($singlePost['title'])
                ->setBody($singlePost['body'])
            ;

            $this->postRepository->save($post);
        }
    }
}
