<?php

namespace Klac\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Klac\AppBundle\Entity\User;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class UserController
 * @package Klac\ApiBundle\Controller
 */
class UserController extends FOSRestController
{
    /**
     * @Rest\Get("/users/me", name="get_my_info")
     *
     * @ApiDoc(
     *     section="01. Users",
     *     resource=true,
     *     description="Get information by login user",
     *     headers={
     *         {
     *            "name"="Authorization: Bearer [JWT_TOKEN]",
     *            "description"="JWT Token",
     *            "required"=true
     *         }
     *     },
     *     output={
     *         "class"="Klac\AppBundle\Entity\User",
     *         "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"},
     *         "groups"={"brief_view"}
     *     },
     *     statusCodes={
     *         200="Returned when successful",
     *         401="Returned when invalid JWT Token"
     *     }
     * )
     *
     * @Rest\View(serializerGroups={"brief_view"}, statusCode=200)
     *
     * @return User
     */
    public function getMeAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        return $user;
    }

    /**
     * @Rest\Post("/users/new", name="api_users_new")
     *
     * @ParamConverter("user", converter="fos_rest.request_body", options={
     *     "deserializationContext"={"groups"={"create"}}
     * })
     *
     * @ApiDoc(
     *     section="01. Users",
     *     resource=true,
     *     description="Create new user",
     *     output={
     *         "class"="Klac\AppBundle\Entity\User",
     *         "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"},
     *         "groups"={"view_user"}
     *     },
     *     statusCodes={
     *         200="Returned when successful",
     *         401="Returned when not authorized"
     *     }
     * )
     *
     * @Rest\View(serializerGroups={"view_user"}, statusCode=201)
     *
     * @param Request $request
     * @param User $user
     * @param ConstraintViolationListInterface $validationErrors
     * @return User
     */
    public function newUserAction(Request $request, User $user, ConstraintViolationListInterface $validationErrors)
    {
        if (count($validationErrors) > 0) {
            $errors = [];
            foreach ($validationErrors as $validationError) {
                $errors[$validationError->getPropertyPath()] = $validationError->getMessage();
            }
            throw new BadRequestHttpException(json_encode($errors));
        }

        $this->get('user.service')->saveUser($user);

        return $user;
    }

    /**
     * @Rest\Put("/users/{id}", name="update_user")
     *
     * @ParamConverter("user", converter="hybrid_request_converter", options={
     *     "deserializationContext"={"groups"={"update"}}
     * })
     *
     * @ApiDoc(
     *     section="01. Users",
     *     resource=false,
     *     description="Update the user",
     *     headers={
     *         {
     *             "name"="Authorization: Bearer [ACCESS_TOKEN]",
     *             "description"="Authorization key",
     *             "required"=true
     *         }
     *     },
     *     input={
     *         "class"="Klac\AppBundle\Entity\User",
     *         "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"},
     *         "groups"={"update"}
     *     },
     *     output={
     *         "class"="Klac\AppBundle\Entity\User",
     *         "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"},
     *         "groups"={"view_user"}
     *     },
     *     statusCodes={
     *         200="Returned when successfully updated",
     *         401="Returned when not authorized",
     *         404="Returned when the user is not found"
     *     },
     * )
     *
     * @Rest\View(serializerGroups={"view_user"}, statusCode=200)
     *
     * @Security("is_granted('edit', user)")
     *
     * @param Request $request
     * @param User $user
     * @param ConstraintViolationListInterface $validationErrors
     * @return User
     */
    public function editUserAction(Request $request, User $user, ConstraintViolationListInterface $validationErrors)
    {
        if (count($validationErrors) > 0) {
            $errors = [];
            foreach ($validationErrors as $validationError) {
                $errors[$validationError->getPropertyPath()] = $validationError->getMessage();
            }
            throw new BadRequestHttpException(json_encode($errors));
        }

        $this->get('user.service')->updateUser($user);

        return $user;
    }
}