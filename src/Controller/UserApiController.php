<?php
/**
 * @author julienrajerison5@gmail.com
 *
 * Date : 29/07/2023
 */

namespace App\Controller;

use App\Helpers\JsonResponseHelper;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/user', name: 'api_user_')]
class UserApiController extends AbstractController
{
    private JsonResponseHelper $jsonResponseHelper;
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository, JsonResponseHelper $jsonResponseHelper)
    {
        $this->jsonResponseHelper = $jsonResponseHelper;
        $this->userRepository = $userRepository;
    }

    #[Route('/list/{page}', name: 'listing', requirements: ['page' => '\d+'])]
    public function listUsers(?int $page = 1): JsonResponse
    {
        $users = $this->userRepository->findBy([], [], $page * 10);

        return $this->json(
            [
                'data' => $this->jsonResponseHelper->serializeData($users, ['listing']),
                'code' => Response::HTTP_OK,
                'status' => 'success'
            ]
        );
    }
}