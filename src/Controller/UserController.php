<?php declare(strict_types = 1);

namespace App\Controller;

use App\Service\UserResponseService;
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
    public function list(
        Request $request,
        UserRepository $userRepository,
        UserResponseService $userResponseService
    ): JsonResponse
    {
        $criteria = [];
        $sort = $userResponseService->generateSort($request->query->all());
        $result = $userRepository->findBy($criteria, $sort);

        return new JsonResponse(
            ['data' => $userResponseService->populateResponseBody($result)], 200
        );
    }

    /**
     * @Route("/{id}", name="show",  methods="GET", requirements={"id"="\d+"})
     */
    public function show(
        Request $request,
        UserRepository $userRepository,
        UserResponseService $userResponseService,
        int $id): JsonResponse
    {
        $sort = $userResponseService->generateSort($request->query->all());
        $result = $userRepository->findOneBy(['id' => $id], $sort);

        return new JsonResponse(
            ['data' => $this->populateResponseBody([$result])], 200
        );
    }
}
