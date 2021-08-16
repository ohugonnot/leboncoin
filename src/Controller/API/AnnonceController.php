<?php

namespace App\Controller\API;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Controller\API\Helper\TraitementTrait;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_annonce_")
 */
class AnnonceController extends AbstractController
{
    use TraitementTrait;

    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @Route("/annonces", name="annonce_list", methods={"GET"})
     */
    public function annonces(): JsonResponse
    {
        $annonces = $this->getDoctrine()->getRepository(Annonce::class)->findAll();
        $annonces = $this->serializer->serialize($annonces,'json');
        return JsonResponse::fromJsonString($annonces);
    }

    /**
     * @Route("/annonce/{id}", requirements={"id"="\d+"}, name="annonce_update", methods={"PUT","PATCH"})
     * @Route("/annonce", name="annonce_create", methods={"POST"})
     */
    public function createAnnonce(Request $request,?Annonce $annonce = null): JsonResponse
    {
        $annonce = $annonce??new Annonce();
        $form = $this->createForm(AnnonceType::class, $annonce);
        return $this->submitForm($request,$form,$annonce);
    }

    /**
     * @Route("/annonce/{id}", requirements={"id"="\d+"}, name="annonce_get", methods={"GET"})
     */
    public function annonce(Annonce $annonce): JsonResponse
    {
        $annonce = $this->serializer->serialize($annonce,'json');
        return JsonResponse::fromJsonString($annonce);
    }

    /**
     * @Route("/annonce/{id}", requirements={"id"="\d+"}, name="annonce_delete", methods={"DELETE"})
     */
    public function deleteAnnonce(Annonce $annonce): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($annonce);
        $em->flush();
        return new JsonResponse('Deleted',RESPONSE::HTTP_NO_CONTENT);
    }
}
