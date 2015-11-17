<?php

namespace AppBundle\Controller;

use AppBundle\Form\MessageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\Message;
use UserBundle\Entity\User;
use Swift_SmtpTransport;
use Swift_Mailer;


class MessageController extends Controller {

    /**
     * User Messages
     * @return Response
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $conversations = $em->getRepository('UserBundle:Message')->getConversations($this->getUser());

        return $this->render('AppBundle:Message:index.html.twig', array(
            'conversations' => $conversations
        ));
    }

    /**
     * Single conversation && Send Message form
     * @param User $sender
     * @return Response
     */
    public function conversationAction(User $sender) {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        # If host - he must have bookings with this sender(guest)
        if(!$this->get('PermissionsService')->conversationAllowed($user, $sender))
            return $this->redirect($this->generateUrl('user_403'));

        # Set all messages in conversation as NOT new && return messages
        $messages = $em->getRepository('UserBundle:Message')->updateConversationRead($sender, $user);
        $form = $this->createForm(new MessageType());

        return $this->render('AppBundle:Message:conversation.html.twig', array(
            'messages'  => $messages,
            'sender'    => $sender,
            'form'      => $form->createView()
        ));
    }

    /**
     * Send new Message
     * @param Request $request
     * @param User $receiver
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function sendAction(Request $request, User $receiver) {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $message = new Message();

        $form = $this->createForm(new MessageType(), $message);
        $form->handleRequest($request);

        if($form->isValid()) {
            $message->setUserFrom($this->getUser());
            $message->setUserTo($receiver);

            $em->persist($message);
            $em->flush();

            $session->getFlashBag()->add('formSuccess', $this->get('translator')->trans('create_success'));

            # Send mail to message receiver
            $this->sendNewMessageMail($message);

        } else {
            $session->getFlashBag()->add('formErrors', (string)$form->getErrors(true, false));
        }

        return $this->redirect($this->generateUrl('app_conversation', array('id' => $receiver->getId())));
    }



    /**
     * Send confirmation email
     * @param $user
     * @return bool
     */
    private function sendNewMessageMail($message) {
        try {
            $message = \Swift_Message::newInstance()
                ->setSubject($this->get('translator')->trans('new_message_subject'))
                ->setFrom($this->get('emailService')->getSiteEmail())
                ->setTo($message->getUserTo()->getEmail())
                ->setBody($this->renderView(
                    // app/Resources/views/Emails/registration.html.twig
                        'AppBundle:Emails:new_message.html.twig',
                        array('message' => $message)
                    ),
                    'text/html');

            $transport = Swift_SmtpTransport::newInstance('localhost', 25);
            $mailer = Swift_Mailer::newInstance($transport);

            $mailer->send($message);
            return true;

        } catch (ExportException $e) {
            return false;
        }
    }



}