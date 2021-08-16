<?php
namespace App\Controller\API\Helper;

use App\Entity\Categorie;
use JMS\Serializer\SerializationContext;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

trait TraitementTrait
{
    private function getErrorsFromForm(FormInterface $form): array
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }
        return $errors;
    }

    private function errorResponse(FormInterface $form): JsonResponse
    {
        $errors = $this->getErrorsFromForm($form);
        $data = [
            'type' => 'validation_error',
            'title' => 'There was a validation error',
            'errors' => $errors
        ];
        return new JsonResponse($data, Response::HTTP_BAD_REQUEST);
    }

    private function submitForm(Request $request, FormInterface $form, $object): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $categorie = $this->getDoctrine()->getRepository(Categorie::class)->findOneBy($data['categorie']??[]);
        if(!$categorie)
            return new JsonResponse(['Error'=>'La catégorie n\'existe pas'], Response::HTTP_BAD_REQUEST);
        $data['categorie'] = $categorie->getId();
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $object->setUser($this->getUser());
            $em->persist($object);
            $em->flush();
            $object = $this->serializer->serialize($object,'json',SerializationContext::create()->setGroups([$categorie->getName()]));
            return JsonResponse::fromJsonString($object, Response::HTTP_CREATED);
        }
        return $this->errorResponse($form);
    }
}