<?php declare(strict_types = 1);

namespace App\Service;

use App\Entity\Post;

class PostResponseService
{
    /** @var Post[] $posts */
    public function populateResponseBody(array $posts): array
    {
        $responseBody = [];

        foreach ($posts as $post) {
            $responseBody[] = [
                'id' => $post->getId(),
                'userId' => $post->getUser()->getUserId(),
                'title' => $post->getTitle(),
                'body' => $post->getBody(),
            ];
        }

        return $responseBody;
    }

    public function generateSort(array $queryStrings): array
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
