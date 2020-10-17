<?php declare(strict_types = 1);

namespace App\Factory;

use App\Repository\PostRepository;
use App\Repository\ResponseHashRepository;
use App\Repository\UserRepository;
use App\Service\ResponseHashService;
use App\Strategy\Sync\SyncStrategyInterface;
use App\Strategy\Sync\User;
use App\Strategy\Sync\Post;
use InvalidArgumentException;

class SyncStrategyFactory
{
    public const USER = 'user';
    public const POST = 'post';

    /** @var UserRepository */
    private $userRepository;

    /** @var PostRepository */
    private $postRepository;

    /** @var ResponseHashRepository */
    private $responseHashRepository;

    /** @var ResponseHashService */
    private $responseHashService;
    
    /** @var string $userEndpoint */
    private $userEndpoint;

    /** @var string $postEndpoint */
    private $postEndpoint;
    
    public function __construct(
        UserRepository $userRepository,
        PostRepository $postRepository,
        ResponseHashRepository $responseHashRepository,
        ResponseHashService $responseHashService,
        string $userEndpoint,
        string $postEndpoint
    ) {
        $this->userRepository = $userRepository;
        $this->postRepository = $postRepository;
        $this->responseHashRepository = $responseHashRepository;
        $this->responseHashService = $responseHashService;
        $this->userEndpoint = $userEndpoint;
        $this->postEndpoint = $postEndpoint;
    }

    public function build(string $type): SyncStrategyInterface
    {
        switch ($type) {
            case self::USER:
                return new User(
                    $this->userRepository,
                    $this->postRepository,
                    $this->responseHashRepository,
                    $this->responseHashService,
                    $this->userEndpoint
                );

                break;
            case self::POST:
                return new Post(
                    $this->userRepository,
                    $this->postRepository,
                    $this->responseHashService,
                    $this->postEndpoint
                );

                break;
            default: 
                throw new InvalidArgumentException();    
        }
    }
}
