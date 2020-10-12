<?php declare(strict_types = 1);

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\UserRepository;

/** @Route("/user", name="user_") */
class UserController extends AbstractController
{
    /**
     * @Route("", name="list", methods="GET")
     */
    public function list(Request $request, UserRepository $userRepository): JsonResponse
    {
        $criteria = [];
        $sort = $this->generateSort($request->query->all());
        $result = $userRepository->findBy($criteria, $sort);

        return new JsonResponse(
            ['data' => $this->populateResponseBody($result)], 200
        );
    }

    /**
     * @Route("/{id}", name="show",  methods="GET")
     */
    public function show(Request $request, UserRepository $userRepository, int $id): JsonResponse
    {
        $sort = $this->generateSort($request->query->all());
        $result = $userRepository->findOneBy(['id' => $id], $sort);

        return new JsonResponse(
            ['data' => $this->populateResponseBody([$result])], 200
        );
    }

    /**
     * @var User[] $users
     */
    private function populateResponseBody(array $users): array
    {
        $responseBody = [];

        foreach ($users as $user) {
            $responseBody[] = [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
            ];
        }

        return $responseBody;
    }
    
    private function generateSort(array $queryStrings): array
    {
        $sort = [];
        
        if (
            isset($queryStrings['sort'])
            &&
            in_array($queryStrings['sort'], User::SORTABLE_FIELDS)
            &&
            isset($queryStrings['direction'])
            &&
            in_array($queryStrings['direction'], User::SORT_DIRECTIONS)
        ) {
            $sort[$queryStrings['sort']] = $queryStrings['direction'];
        }

        return $sort;
    }
}
