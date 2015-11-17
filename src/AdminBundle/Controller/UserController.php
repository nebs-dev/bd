<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UserBundle\Entity\User;
use AdminBundle\Form\UserType;

class UserController extends Controller {

    /**
     * List all users
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('UserBundle:User')->getAll();

        return $this->render('AdminBundle:User:list.html.twig', array('users' => $users));
    }

    /**
     * Create new User
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function newAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $user = new User();

        $role = $em->getRepository('UserBundle:Role');

        $form = $this->createForm(new UserType($role), $user);
        $form->handleRequest($request);

        if($form->isValid()) {
            $postData = $request->request->all();
            $postRole = $role->find($postData['user']['roles']);
            $postPassword = $postData['user']['password']['first'];

            $user->setPassword($postPassword);

            # Add role
            $user->addRole($postRole);

            $em->persist($user);
            $em->flush();

//            exit(\Doctrine\Common\Util\Debug::dump($user->getPassword()));
//
//            $user->encryptPassword();

            $session = $request->getSession();
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('user_create_success'));

            return $this->redirect($this->generateUrl('admin_users'));
        }

        return $this->render('AdminBundle:User:new.html.twig', array('form' => $form->createView()));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $role = $em->getRepository('UserBundle:Role');

        $user = $em->getRepository('UserBundle:User')->find($id);
        $oldPassword = $user->getPassword();

        $form = $this->createForm(new UserType($role, $user), $user);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $postData = $request->request->all();
            $formData = $form->getData();
            $postRole = $role->find($postData['user']['roles']);
            $postPassword = $postData['user']['password']['first'];

            # If password field is filled change password
            if (!empty($postPassword) || $postPassword != '') {
                $user->setPassword($postPassword);
                $user->encryptPassword();

            # If not filled, don't change password
            } else {
                $user->setPassword($oldPassword);
            }

            # Remove current roles
            foreach($formData->getRoles() as $role) {
                $user->removeRole($role);
            }

            # Add role
            $user->addRole($postRole);

            $em->persist($user);
            $em->flush();

            $session = $request->getSession();
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('user_edit_success'));

            return $this->redirect($this->generateUrl('admin_users_edit', array('id' => $id)));
        }

        return $this->render('AdminBundle:User:edit.html.twig', array('form' => $form->createView(), 'user' => $user));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('UserBundle:User')->find($id);
        $session = $request->getSession();

        try {
            $em->remove($user);
            $em->flush();
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('user_delete_success'));
        } catch(\Exception $e) {
            $session->getFlashBag()->add('msgError', $this->get('translator')->trans('user_delete_error'));
        }

        return $this->redirect($this->generateUrl('admin_users'));
    }

    /**
     * Change user status
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function changeStatusAction(Request $request, User $user) {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();

        try {
            $status = ($user->getIsActive()) ? 0 : 1;

            $user->setIsActive($status);
            $em->persist($user);
            $em->flush();
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('change_status_success'));
        } catch(\Exception $e) {
            $session->getFlashBag()->add('msgError', $this->get('translator')->trans('change_status_error'));
        }

        return $this->redirect($this->generateUrl('admin_users'));
    }

    public function accommodationsAction(User $user) {
        return $this->render('AdminBundle:User:accommodations.html.twig', array('user' => $user));
    }
}
