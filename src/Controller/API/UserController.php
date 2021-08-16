<?php

namespace App\Controller\API;

use App\Controller\API\Helper\TraitementTrait;
use App\Entity\User;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_user_")
 */
class UserController extends AbstractController
{
    use TraitementTrait;

    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @Route("/users", name="user_list", methods={"GET"})
     */
    public function users(): JsonResponse
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        $users = $this->serializer->serialize($users,'json');
        return JsonResponse::fromJsonString($users);
    }

    /**
     * @Route("/user_connected", name="user_connected", methods={"GET"})
     */
    public function user_connect(): JsonResponse
    {
        $user = $this->getUser();
        $user = $this->serializer->serialize($user,'json');
        return JsonResponse::fromJsonString($user);
    }

    /**
     * @Route("/user/{id}", name="user_get", methods={"GET"})
     */
    public function user(User $user): JsonResponse
    {
        $user = $this->serializer->serialize($user,'json');
        return JsonResponse::fromJsonString($user);
    }

    /**
     * @Route("/user/{id}", name="user_delete", methods={"DELETE"})
     */
    public function deleteUser(User $user): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        return new JsonResponse('Deleted',RESPONSE::HTTP_NO_CONTENT);
    }
}
