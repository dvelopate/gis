<?php declare(strict_types = 1);

namespace App\Service;

use App\Entity\User;

class UserResponseService
{
    /** @var User[] $users */
    public function populateResponseBody(array $users): array
    {
        $responseBody = [];

        foreach ($users as $user) {
            $responseBody[] = [
                'id' => $user->getId(),
                'userId' => $user->getUserId(),
                'name' => $user->getName(),
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
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
