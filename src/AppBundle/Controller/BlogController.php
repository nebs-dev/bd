<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Entity\PostComment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class BlogController extends Controller {

    /**
     * List all posts for language
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('AppBundle:Post')->getByLang($request->getLocale());

        # Discover
        $discover = $em->getRepository('AppBundle:Discover')->findAll();
        shuffle($discover);

        $additionalOffers = $em->getRepository('AppBundle:AdditionalOffer\AdditionalOffer')->findAll();
        shuffle($additionalOffers);

        return $this->render('AppBundle:Blog:index.html.twig', array(
            'posts'         => $posts,
            'discover'=> $discover,
            'additionalOffers' => $additionalOffers
        ));
    }

    public function singleAction(Post $post) {
        $em = $this->getDoctrine()->getManager();

        # Discover
        $discover = $em->getRepository('AppBundle:Discover')->findAll();
        shuffle($discover);

        return $this->render('AppBundle:Blog:single.html.twig', array(
            'post' => $post,
            'discover'=> $discover
        ));
    }

    /**
     * Create new Comment
     * @return Response
     */
    public function newCommentAction() {
        $em = $this->getDoctrine()->getManager();

        parse_str($_POST['formData'], $formData);
        $text = $formData['text'];
        $post = $em->getRepository('AppBundle:Post')->find($_POST['postId']);

        $response = array();
        try {
            $comment = new PostComment();
            $comment->setPost($post);
            $comment->setText($text);
            $comment->setUser($this->getUser());

            # Validation
            $validator = $this->get('validator');
            $errors = $validator->validate($comment);
            $errorsString = (string)$errors;

            if (count($errors) > 0) {
                $response['error'] = $errorsString;
            } else {
                $em->persist($comment);
                $em->flush();
                $response['comment'] = array(
                    'createdAt' => date('d.m.Y. H:i', $comment->getCreatedAt()->getTimestamp()),
                    'text'      => $comment->getText(),
                    'user'      => array(
                        'id'       => $comment->getUser()->getId(),
                        'username' => $comment->getUser()->getUsername()
                    ),
                );
            }

        } catch(\Exception $e) {
            $response['error'] = $e->getMessage();
        }

        return new Response(json_encode($response));
    }

}