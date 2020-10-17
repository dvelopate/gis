<?php declare(strict_types = 1);

namespace App\Strategy\Sync;

use App\Exception\SyncException;
use App\Repository\PostRepository;
use App\Repository\ResponseHashRepository;
use App\Repository\UserRepository;
use App\Service\ResponseHashService;
use GuzzleHttp\Client;
use App\Entity\User as UserEntity;

class User extends SyncStrategyInterface
{
    /** @var string $endpoint */
    private $endpoint;

    /** @var UserRepository */
    private $userRepository;

    /** @var PostRepository */
    private $postRepository;

    /** @var ResponseHashRepository */
    private $responseHashRepository;

    /** @var ResponseHashService */
    private $responseHashService;

    public function __construct(
        UserRepository $userRepository,
        PostRepository $postRepository,
        ResponseHashRepository $responseHashRepository,
        ResponseHashService $responseHashService,
        string $userEndpoint
    ) {
        $this->userRepository = $userRepository;
        $this->postRepository = $postRepository;
        $this->responseHashRepository = $responseHashRepository;
        $this->responseHashService = $responseHashService;
        $this->endpoint = $userEndpoint;
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
            $this->userRepository->clean();
            $this->import(json_decode($result, true));

            return;
        }

        throw new SyncException($this->endpoint);
    }

    private function import(array $result): void
    {
        foreach ($result as $singleUser) {
            $user = new UserEntity();

            $user
                ->setName($singleUser['name'])
                ->setEmail($singleUser['email'])
                ->setUsername($singleUser['username'])
                ->setUserId($singleUser['id'])
            ;

            $this->userRepository->save($user);
        }
    }
}
