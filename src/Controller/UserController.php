<?php declare(strict_types = 1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/** @Route("/user", name="user_") */
class UserController extends AbstractController
{
    /**
     * @Route("", name="list")
     */
    public function list(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
            'function' => 'src/Controller/UserController::list',
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
            'function' => 'src/Controller/UserController::show',
        ]);
    }
}
