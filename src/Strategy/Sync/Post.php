<?php declare(strict_types = 1);

namespace App\Strategy\Sync;

use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Service\ResponseHashService;
use GuzzleHttp\Client;
use App\Entity\Post as PostEntity;

class Post extends SyncStrategyInterface
{
    /** @var string */
    private $postEndpoint;

    /** @var PostRepository  */
    private $postRepository;

    /** @var UserRepository */
    private $userRepository;
    
    /** @var ResponseHashService */
    private $responseHashService;

    public function __construct(
        UserRepository $userRepository,
        PostRepository $postRepository,
        ResponseHashService $responseHashService,
        string $postEndpoint
    ) {
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
        $this->responseHashService = $responseHashService;
        $this->postEndpoint = $postEndpoint;
    }

    public function sync(): void
    {
        $client = new Client();

        $response = $client->request('GET', $this->postEndpoint, [
            'Content-Type' => 'application/json',
        ]);

        $result = $response->getBody()->getContents();

        $this->responseHashService->handleResponseHash($result, $this->postEndpoint);

        $this->postRepository->clean();
        $this->import(json_decode($result, true));
    }
    
    private function import(array $result): void
    {
        foreach ($result as $singlePost) {
            $post = new PostEntity();

            $post
                ->setUser($this->userRepository->findOneBy(['userId' => $singlePost['userId']]))
                ->setTitle($singlePost['title'])
                ->setBody($singlePost['body'])
            ;

            $this->postRepository->save($post);
        }
    }
}
