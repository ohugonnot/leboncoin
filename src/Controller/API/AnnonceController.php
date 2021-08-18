<?php

namespace App\Controller\API;

use App\Entity\Annonce;
use App\Entity\AnnonceAutomobile;
use App\Entity\AnnonceEmploi;
use App\Entity\AnnonceImmobilier;
use App\Entity\Categorie;
use App\Form\AnnonceType;
use App\Controller\API\Helper\TraitementTrait;
use JMS\Serializer\SerializationContext;
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

    private function getCategorieByName(?string $name=null) : ?Categorie
    {
        return $this->getDoctrine()->getRepository(Categorie::class)->findOneBy(['name'=>$name]??[]);
    }

    private function getCategorieRepo(?string $name=null) : string
    {
        $repo = Annonce::class;
        if(!$name)
            return $repo;
        $categorie = $this->getCategorieByName($name);
        if($categorie && $categorie->getName() === Categorie::AUTOMOBILE)
            $repo = AnnonceAutomobile::class;
        if($categorie && $categorie->getName() === Categorie::IMMOBILIER)
            $repo = AnnonceImmobilier::class;
        if($categorie && $categorie->getName() === Categorie::EMPLOI)
            $repo = AnnonceAutomobile::class;
        return $repo;
    }

    private function annonceFactory(?string $name=null) : Annonce
    {
        $annonce = new Annonce();
        if(!$name)
            return $annonce;
        $categorie = $this->getCategorieByName($name);
        if($categorie && $categorie->getName() === Categorie::AUTOMOBILE)
            $annonce = new AnnonceAutomobile();
        if($categorie && $categorie->getName() === Categorie::IMMOBILIER)
            $annonce = new AnnonceImmobilier();
        if($categorie && $categorie->getName() === Categorie::EMPLOI)
            $annonce = new AnnonceEmploi();
        return $annonce;
    }

    /**
     * @Route("/annonce/search/{q}", name="search", methods={"GET"})
     */
    public function searchAnnonceAutomobile(Request $request, ?string $q=null): JsonResponse
    {
        // Todo Methode pure PHP à améliorer avec une requête dans le repo
        $q = $q??$request->query->get('q');
        $annonces = $this->getDoctrine()->getManager()->getRepository(AnnonceAutomobile::class)->findAll();
        $resultats = [];
        foreach($annonces as $annonce)
        {
            similar_text(strtolower($q), strtolower($annonce->getModele()),$perc);
            $annonce->match=$perc;
            $resultats[$perc.$annonce->getId()] = $annonce;
        }
        krsort($resultats);
        $resultats = array_values($resultats);
        $resultats = array_slice($resultats,0,$request->query->get('limit'));
        $resultats = $this->serializer->serialize($resultats,'json',SerializationContext::create()->setGroups(['Automobile','Search']));
        return JsonResponse::fromJsonString($resultats,200);
    }

    /**
     * @Route("/annonce/{categorie}", requirements={"categorie"="[a-zA-Z]+"}, name="list_categorie", methods={"GET"})
     * @Route("/annonce", name="list", methods={"GET"})
     */
    public function annonces(?string $categorie=null): JsonResponse
    {
        $repo = $this->getCategorieRepo($categorie);
        $annonces = $this->getDoctrine()->getRepository($repo)->findAll();
        return JsonResponse::fromJsonString($this->serializer->serialize($annonces,'json'));
    }

    /**
     * @Route("/annonce/{categorie}/{id}", requirements={"id"="\d+","categorie"="[a-zA-Z]+"}, name="get_categorie", methods={"GET"})
     * @Route("/annonce/{id}", requirements={"id"="\d+"}, name="get", methods={"GET"})
     */
    public function annonce(int $id, ?string $categorie): JsonResponse
    {
        $repo = $this->getCategorieRepo($categorie);
        $annonce = $this->getDoctrine()->getRepository($repo)->find($id);
        if(!$annonce)
            return new JsonResponse('Not Found',Response::HTTP_NOT_FOUND);
        return JsonResponse::fromJsonString($this->serializer->serialize($annonce,'json'));
    }

    /**
     * @Route("/annonce/{id}", requirements={"id"="\d+"}, name="update", methods={"PUT","PATCH"})
     * @Route("/annonce", name="create", methods={"POST"})
     */
    public function createAnnonce(Request $request,?Annonce $annonce = null): JsonResponse
    {
        $data = json_decode($request->getContent(),true);
        $categorie_name = $data['categorie']['name']??$data['categorie']??null;
        $categorie = $this->getCategorieByName($categorie_name);
        if(!$categorie)
            return new JsonResponse(['Error'=>'Il faut renseigner une categorie valide'], Response::HTTP_BAD_REQUEST);
        $annonce = $annonce??$this->annonceFactory($categorie->getName());
        $form = $this->createForm(AnnonceType::class,$annonce);
        $data = json_decode($request->getContent(),true);
        $data['categorie'] = $categorie->getId();
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $annonce->setUser($this->getUser());
            $annonce->setCategorie($categorie);
            $em->persist($annonce);
            $em->flush();
            $annonce_serialize = $this->serializer->serialize($annonce,'json',SerializationContext::create()->setGroups([$categorie->getName()]));
            return JsonResponse::fromJsonString($annonce_serialize, Response::HTTP_CREATED);
        }
        return $this->errorResponse($form);
    }

    /**
     * @Route("/annonce/{id}", requirements={"id"="\d+"}, name="delete", methods={"DELETE"})
     */
    public function deleteAnnonce(Annonce $annonce): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($annonce);
        $em->flush();
        return new JsonResponse('Deleted',RESPONSE::HTTP_NO_CONTENT);
    }
}
