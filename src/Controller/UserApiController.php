<?php
/**
 * @author julienrajerison5@gmail.com
 *
 * Date : 29/07/2023
 */

namespace App\Controller;

use App\Entity\User;
use App\Helpers\JsonResponseHelper;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/user', name: 'api_user_')]
class UserApiController extends AbstractController
{
    private JsonResponseHelper $jsonResponseHelper;
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(UserRepository $userRepository, JsonResponseHelper $jsonResponseHelper, EntityManagerInterface $entityManager)
    {
        $this->jsonResponseHelper = $jsonResponseHelper;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
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

    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function createUser(Request $request): JsonResponse
    {
        $user = $this->jsonResponseHelper
                ->configureSerializer(['creating'])
                ->deserialize($request->getContent(), User::class, 'json');

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'Success',
        ]);
    }
}