<?php declare(strict_types = 1);

namespace App\Service;

use App\Entity\ResponseHash;
use App\Repository\ResponseHashRepository;

class ResponseHashService
{
    /** @var string $hashAlgorithm */
    private $hashAlgorithm;
    
    /** @var string $hashSalt */
    private $hashSalt;
    
    /** @var ResponseHashRepository */
    private $responseHashRepository;
    
    public function __construct(
        ResponseHashRepository $responseHashRepository,
        string $hashAlgorithm,
        string $hashSalt
    ) {
        $this->responseHashRepository = $responseHashRepository;
        $this->hashAlgorithm = $hashAlgorithm;
        $this->hashSalt = $hashSalt;
    }

    public function handleResponseHash(string $response, string $endpoint): bool
    {
        $result = $this->responseHashRepository->findOneBy(['route' => $endpoint]);

        if ($result === null) {
            $responseHash = new ResponseHash();
            
            $responseHash
                ->setRoute($endpoint)
                ->setResponseHash($this->hashResponseData($response))
            ;

            $this->responseHashRepository->save($responseHash);

            return true;
        }

        if ($result->getResponseHash() !== $this->hashResponseData($response)) {
            $result->setResponseHash($this->hashResponseData($response));

            $this->responseHashRepository->save($result);

            return true;
        }

        return false;
    }
    
    private function hashResponseData(string $response): string
    {
        return hash_hmac($this->hashAlgorithm, $response, $this->hashSalt);
    }
}
