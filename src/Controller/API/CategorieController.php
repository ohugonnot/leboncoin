<?php

namespace App\Controller\API;

use App\Controller\API\Helper\TraitementTrait;
use App\Entity\Categorie;
use App\Form\CategorieType;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_categorie_")
 */
class CategorieController extends AbstractController
{
    use TraitementTrait;

    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @Route("/categories", name="categorie_list", methods={"GET"})
     */
    public function categories(): JsonResponse
    {
        $categories = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
        $categories = $this->serializer->serialize($categories,'json');
        return JsonResponse::fromJsonString($categories);
    }

    /**
     * @Route("/categorie/{id}", name="categorie_update", methods={"PUT","PATCH"})
     * @Route("/categorie", name="categorie_create", methods={"POST"})
     */
    public function createCategorie(Request $request,?Categorie $categorie = null): JsonResponse
    {
        $categorie = $categorie??new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        return $this->submitForm($request,$form,$categorie);
    }

    /**
     * @Route("/categorie/{id}", name="categorie_get", methods={"GET"})
     */
    public function categorie(Categorie $categorie): JsonResponse
    {
        $categorie = $this->serializer->serialize($categorie,'json');
        return JsonResponse::fromJsonString($categorie);
    }

    /**
     * @Route("/categorie/{id}", name="categorie_delete", methods={"DELETE"})
     */
    public function deleteCategorie(Categorie $categorie): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($categorie);
        $em->flush();
        return new JsonResponse('Deleted',RESPONSE::HTTP_NO_CONTENT);
    }
}
