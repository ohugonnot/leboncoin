<?php

namespace App\Controller\API;

use App\Controller\API\Helper\TraitementTrait;
use App\Entity\Categorie;
use App\Form\CategorieType;
use JMS\Serializer\SerializationContext;
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
     * @Route("/categories", name="list", methods={"GET"})
     */
    public function categories(): JsonResponse
    {
        $categories = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
        $categorie_serialize = $this->serializer->serialize($categories,'json');
        return JsonResponse::fromJsonString($categorie_serialize);
    }

    /**
     * @Route("/categorie/{id}", name="get", methods={"GET"})
     */
    public function categorie(Categorie $categorie): JsonResponse
    {
        $categorie_serialize = $this->serializer->serialize($categorie,'json');
        return JsonResponse::fromJsonString($categorie_serialize);
    }

    /**
     * @Route("/categorie/{id}", name="update", methods={"PUT","PATCH"})
     * @Route("/categorie", name="create", methods={"POST"})
     */
    public function createCategorie(Request $request,?Categorie $categorie = null): JsonResponse
    {
        $categorie = $categorie??new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);

        $data = json_decode($request->getContent(),true);
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();
            $categorie_serialize = $this->serializer->serialize($categorie,'json',SerializationContext::create()->setGroups([$categorie->getName()]));
            return JsonResponse::fromJsonString($categorie_serialize,Response::HTTP_CREATED);
        }
        return $this->errorResponse($form);
    }

    /**
     * @Route("/categorie/{id}", name="delete", methods={"DELETE"})
     */
    public function deleteCategorie(Categorie $categorie): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($categorie);
        $em->flush();
        return new JsonResponse('Deleted',RESPONSE::HTTP_NO_CONTENT);
    }
}
