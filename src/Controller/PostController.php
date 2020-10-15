<?php declare(strict_types = 1);

namespace App\Controller;

use App\Service\PostResponseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\PostRepository;
use App\Repository\UserRepository;

/** @Route("/post", name="post_") */
class PostController extends AbstractController
{
    /**
     * @Route("", name="list",  methods="GET")
     */
    public function list(
        Request $request,
        PostRepository $postRepository,
        PostResponseService $postResponseService
    ): JsonResponse
    {
        $criteria = [];
        $sort = $postResponseService->generateSort($request->query->all());
        $result = $postRepository->findBy($criteria, $sort);

        return new JsonResponse(
            ['data' => $postResponseService->populateResponseBody($result)], 200
        );
    }

    /**
     * @Route("/{id}", name="show", requirements={"id"="\d+"},  methods="GET")
     */
    public function show(
        Request $request,
        PostRepository $postRepository,
        PostResponseService $postResponseService,
        int $id
    ): JsonResponse
    {
        $sort = $postResponseService->generateSort($request->query->all());
        $result = $postRepository->findOneBy(['id' => $id], $sort);

        if ($result === null) {
            return new JsonResponse(['message' => 'Requested post does not exist'], 404);
        }

        return new JsonResponse(
            ['data' => $postResponseService->populateResponseBody([$result])], 200
        );
    }

    /**
     * @Route("/user/{id}", name="user", requirements={"id"="\d+"},  methods="GET")
     */
    public function showUserPosts(
        Request $request,
        PostRepository $postRepository,
        UserRepository $userRepository,
        PostResponseService $postResponseService,
        int $id
    ): JsonResponse
    {
        $sort = $postResponseService->generateSort($request->query->all());
        $user = $userRepository->findOneBy(['id' => $id]);

        if ($user === null) {
            return new JsonResponse(['message' => 'Requested user does not exist'], 404);
        }

        $userPosts = $postRepository->findBy(['user' => $user], $sort);

        if ($userPosts === null) {
            return new JsonResponse(['message' => 'There are no posts for the requested user'], 200);
        }

        return new JsonResponse(['data' => $postResponseService->populateResponseBody($userPosts), 'code' => 200], 200);
    }
}
