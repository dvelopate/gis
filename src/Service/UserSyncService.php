<?php declare(strict_types = 1);

namespace App\Service;

use App\Entity\ResponseHash;
use App\Repository\ResponseHashRepository;
use GuzzleHttp\Client;
use App\Repository\UserRepository;
use App\Entity\User;

class UserSyncService
{
    /**
     * @var int $endpoint
     */
    private $endpoint;
    
    /** @var UserRepository  */
    private $userRepository;

    /** @var ResponseHashRepository  */
    private $responseHashRepository;
    
    /** @var ResponseHashService */
    private $responseHashService;
    
    public function __construct(
        string $userEndpoint,
        UserRepository $userRepository,
        ResponseHashRepository $responseHashRepository,
        ResponseHashService $responseHashService
    ) {
        $this->endpoint = $userEndpoint;
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
            $this->userRepository->clean();
            $this->import(json_decode($result, true));
        }
    }

    private function import(array $result)
    {
        foreach ($result as $singleUser) {
            $user = new User();

            $user
                ->setName($singleUser['name'])
                ->setEmail($singleUser['email'])
                ->setUsername($singleUser['username'])
                ->setUserId($singleUser['userId'])
            ;

            $this->userRepository->save($user);
        }
    }
}
