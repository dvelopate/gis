<?php declare(strict_types = 1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Entity\Post;

/** @Route("/post", name="post_") */
class PostController extends AbstractController
{

    /**
     * @Route("", name="list",  methods="GET")
     */
    public function list(Request $request, PostRepository $postRepository): JsonResponse
    {
        $criteria = [];
        $sort = $this->generateSort($request->query->all());
        $result = $postRepository->findBy($criteria, $sort);

        return new JsonResponse(
            ['data' => $this->populateResponseBody($result)], 200
        );
    }

    /**
     * @Route("/{id}", name="show", requirements={"id"="\d+"},  methods="GET")
     */
    public function show(Request $request, PostRepository $postRepository, int $id): JsonResponse
    {
        $sort = $this->generateSort($request->query->all());
        $result = $postRepository->findOneBy(['id' => $id], $sort);

        return new JsonResponse(
            ['data' => $this->populateResponseBody([$result])], 200
        );
    }

    /**
     * @Route("/user/{id}", name="user", requirements={"id"="\d+"},  methods="GET")
     */
    public function showUserPosts(
        Request $request,
        PostRepository $postRepository,
        UserRepository $userRepository
    ): JsonResponse
    {
        $sort = $this->generateSort($request->query->all());


        $user = $userRepository->findOneBy(['userId' => $request->get('userId')]);

        if ($user === null) {
            return new JsonResponse(['message' => 'Requested user does not exist'], 200);
        }

        $userPosts = $postRepository->findBy(['user' => $user], $sort);

        if ($userPosts === null) {
            return new JsonResponse([], 200);
        }

        return new JsonResponse(['data' => $this->populateResponseBody($userPosts), 'code' => 200], 200);
    }

        /**
     * @var Post[] $posts
     */
    private function populateResponseBody(array $posts): array
    {
        $responseBody = [];

        foreach ($posts as $post) {
            $responseBody[] = [
                'id' => $post->getId(),
                'userId' => $post->getUser()->getId(),
                'title' => $post->getTitle(),
                'body' => $post->getBody(),
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
            in_array(strtolower($queryStrings['sort']), Post::SORTABLE_FIELDS)
            &&
            isset($queryStrings['direction'])
            &&
            in_array(strtolower($queryStrings['direction']), Post::SORT_DIRECTIONS)
        ) {
            $sort[$queryStrings['sort']] = $queryStrings['direction'];
        }

        return $sort;
    }
}
