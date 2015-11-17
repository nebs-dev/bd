<?php

namespace UserBundle\Controller;

use Doctrine\ORM\Tools\Export\ExportException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use UserBundle\Form\RegistrationType;
use UserBundle\Entity\User;
use Swift_SmtpTransport;
use Swift_Mailer;

class BaseController extends Controller {

    /**
     * @param Request $request
     * @return Response
     * User login
     */
    public function loginAction(Request $request) {

        # If logged in cannot access this
        if( $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
            return $this->redirect($this->generateUrl('app_home'));
        }

        $user = $this->getUser();
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContextInterface::AUTHENTICATION_ERROR
            );
        } elseif (null !== $session && $session->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContextInterface::AUTHENTICATION_ERROR);
            $session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContextInterface::LAST_USERNAME);

        return $this->render(
            'UserBundle::login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
            )
        );
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function registrationAction(Request $request, $role) {
        # If logged in connot access this
        if( $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
            return $this->redirect($this->generateUrl('app_home'));
        }

        $user = new User();

        $em = $this->getDoctrine()->getManager();
        $roleRep = $em->getRepository('UserBundle:Role');
        $roles = $roleRep->getRolesRegistration();
        $session = $request->getSession();

        $form = $this->createForm(new RegistrationType($roles), $user)
            ->remove('isActive')
            ->remove('name')
            ->remove('surename')
            ->remove('address')
            ->remove('phone')
            ->remove('roles');

        $form->handleRequest($request);
        if($form->isValid()) {
//            $postData = $request->request->all();
//            $postRole = $roleRep->find($postData['user']['roles']);
//            $plainPassword = $user->getPassword();
//            $user->encryptPassword();

            try {
                $role = $em->getRepository('UserBundle:Role')->findOneByName($role);

                $user->setIsActive(0);

                # Add role
                $user->addRole($role);

                $em->persist($user);
                $em->flush();

                # Send confirmation email
                $this->sendRegistrationMail($user);

                $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('registration_success'));

            } catch(\Exception $e) {
                $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('registration_error'));
            }

            return $this->redirect($this->generateUrl('login'));
        }

        return $this->render('UserBundle::registration_'.$role.'.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @param $username
     * @param $hash
     * @return Response
     */
    public function validateAccountAction($username, $hash) {
        $em = $this->getDoctrine()->getManager();

        if($user = $em->getRepository('UserBundle:User')->getByHash($username, $hash)){
            $user->setIsActive(1);
            $em->persist($user);
            $em->flush();

            $this->sendSuccessActivationMail($user);
            $message = $this->get('translator')->trans('successfull_account_activation');
            $activation = true;
        } else {
            $message = $this->get('translator')->trans('error_account_activation');
            $activation = false;
        }

        return $this->render('UserBundle::activation.html.twig', array(
            'user'      => $user,
            'message'   => $message,
            'activation'=> $activation
        ));
    }


    public function forgotPasswordAction(Request $request) {
        # If logged in connot access this
        if( $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
            return $this->redirect($this->generateUrl('app_profile'));
        }

        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new RegistrationType(array()), null)
            ->remove('isActive')
            ->remove('username')
            ->remove('password')
            ->remove('roles')
            ->remove('name')
            ->remove('surename')
            ->remove('address')
            ->remove('phone');

        $form->handleRequest($request);
        if($form->isValid()) {
            if (filter_var($request->request->get('user')['email'], FILTER_VALIDATE_EMAIL)) {

                if($user = $em->getRepository('UserBundle:User')->findOneBy(array('email' => $request->request->get('user')['email']))) {
                    $this->sendForgotPasswordMail($user);
                }

                $session = $request->getSession();
                $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('reset_password_link_sent'));
                return $this->redirect($this->generateUrl('app_home'));
            } else {
                $session->getFlashBag()->add('formErrors', $this->get('translator')->trans('invalid_email'));
            }
        }

        return $this->render('UserBundle::forgotPassword.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Form for reseting action
     * @param $username
     * @param $hash
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function resetPasswordFormAction($username, $hash) {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new RegistrationType(array()), null)
            ->remove('isActive')
            ->remove('username')
            ->remove('email')
            ->remove('roles')
            ->remove('name')
            ->remove('surename')
            ->remove('address')
            ->remove('phone');

        if($user = $em->getRepository('UserBundle:User')->getByHash($username, $hash)){
            return $this->render('UserBundle::resetPassword.html.twig', array(
                'form' => $form->createView(),
                'user' => $user
            ));
        } else {
            return $this->redirect($this->generateUrl('app_home'));
        }
    }

    /**
     * Reset password
     * @param Request $request
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function resetPasswordAction(Request $request, User $user) {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();

        $form = $this->createForm(new RegistrationType(array()), null);

        $form->handleRequest($request);
        if($form->isValid()) {
            if ($request->request->get('user')['password']['first'] == $request->request->get('user')['password']['second']) {
                $user->setPassword($request->request->get('user')['password']['first']);
                $user->encryptPassword();
                $em->persist($user);
                $em->flush();

                $session = $request->getSession();
                $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('reset_password_success'));

                return $this->redirect($this->generateUrl('login'));
            } else {
                $session->getFlashBag()->add('formErrors', $this->get('translator')->trans('invalid_password'));
                return $this->render('UserBundle::resetPassword.html.twig', array(
                    'form' => $form->createView()
                ));
            }
        }
    }


    /**
    * 403 Redirect
    * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
    */
    public function noAccessAction() {
        return $this->render('UserBundle::403.html.twig');
    }


    /**
     * Send confirmation email
     * @param $user
     * @return bool
     */
    private function sendRegistrationMail($user) {
        try {
            $message = \Swift_Message::newInstance()
                ->setSubject($this->get('translator')->trans('welcome_best_destination'))
                ->setFrom($this->get('emailService')->getSiteEmail())
                ->setTo($user->getEmail())
                ->setBody($this->renderView(
                    // app/Resources/views/Emails/registration.html.twig
                        'AppBundle:Emails:account_confirmation.html.twig',
                        array('user' => $user)
                    ),
                    'text/html');

            $transport = Swift_SmtpTransport::newInstance('localhost', 25);
            $mailer = Swift_Mailer::newInstance($transport);

            $mailer->send($message);
            return true;

        } catch(ExportException $e) {
            return false;
        }
    }

    private function sendSuccessActivationMail($user) {
        try {
            $message = \Swift_Message::newInstance()
                ->setSubject($this->get('translator')->trans('Your account at Best Destination.com has been activated'))
                ->setFrom($this->get('emailService')->getSiteEmail())
                ->setTo($user->getEmail())
                ->setBody($this->renderView(
                    'AppBundle:Emails:account_activate.html.twig',
                    array('user' => $user)
                ),
                    'text/html');

            $transport = Swift_SmtpTransport::newInstance('localhost', 25);
            $mailer = Swift_Mailer::newInstance($transport);

            $mailer->send($message);
            return true;

        } catch(ExportException $e) {
            return false;
        }
    }

    /**
     * Send forgot password email
     * @param $user
     * @return bool
     */
    private function sendForgotPasswordMail($user) {
        try {
            $message = \Swift_Message::newInstance()
                ->setSubject($this->get('translator')->trans('best_destination_password_reset'))
                ->setFrom($this->get('emailService')->getSiteEmail())
                ->setTo($user->getEmail())
                ->setBody($this->renderView(
                        'AppBundle:Emails:forgot_password.html.twig',
                        array('user' => $user)
                    ),
                    'text/html');

            $transport = Swift_SmtpTransport::newInstance('localhost', 25);
            $mailer = Swift_Mailer::newInstance($transport);

            $mailer->send($message);
            return true;

        } catch(ExportException $e) {
            return false;
        }
    }


}
