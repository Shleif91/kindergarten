<?php

namespace Klac\AppBundle\Controller;

use Klac\AppBundle\Form\UserType;
use Klac\AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package Klac\AppBundle\Controller
 *
 * @Route("/users")
 */
class UserController extends Controller
{
    /**
     * @Route("", name="users_index")
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $this->get('user.service')->getUsersQuery(), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );

        $forms = [];

        foreach ($pagination as $user) {
            $forms[$user->getId()] = $this->createDeleteForm($user)->createView();
        }

        return $this->render('KlacAppBundle:User:index.html.twig', [
            'forms' => $forms,
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/new", name="user_new")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, [
            'action' => $this->generateUrl('user_new', ['id' => $user->getId()]),
            'validation_groups' => 'Registration'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $this->get('user.service')->saveUser($user);

            return $this->redirectToRoute('users_index');
        }

        return $this->render('KlacAppBundle:User:new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit")
     *
     * @param User $user
     * @param Request $request
     * @return Response
     */
    public function editAction(Request $request, User $user)
    {
        $form = $this->createForm(UserType::class, $user, [
            'action' => $this->generateUrl('user_edit', ['id' => $user->getId()])
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $this->get('user.service')->saveUser($user);

            return $this->redirectToRoute('users_index');
        }

        return $this->render('KlacAppBundle:User:edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete")
     * @Method({"DELETE"})
     *
     * @param User $user
     * @param Request $request
     * @return Response
     */
    public function deleteAction(Request $request, User $user)
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('user.service')->deleteUser($user);
        }

        return $this->redirectToRoute('users_index');
    }

    /**
     * @param User $user
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
    private function createDeleteForm(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}