<?php declare(strict_types = 1);

namespace App\Entity;

use App\Repository\ResponseHashRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ResponseHashRepository::class)
 */
class ResponseHash
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $route;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $responseHash;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function setRoute(string $route): self
    {
        $this->route = $route;

        return $this;
    }

    public function getResponseHash(): ?string
    {
        return $this->responseHash;
    }

    public function setResponseHash(string $responseHash): self
    {
        $this->responseHash = $responseHash;

        return $this;
    }
}
