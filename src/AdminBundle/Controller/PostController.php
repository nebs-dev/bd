<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\PostType;
use AppBundle\Entity\Post;
use AppBundle\Entity\PostComment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class PostController extends Controller {

    /**
     * List all posts
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('AppBundle:Post')->findBy(
            array(),
            array('createdAt' => 'DESC'));
        return $this->render('AdminBundle:Post:list.html.twig', array('posts' => $posts));
    }

    /**
     * New Post
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $post = new Post();

        $parents = $em->getRepository('AppBundle:Post')->findParents();

        $form = $this->createForm(new PostType($parents), $post);
        $form->handleRequest($request);
        if($form->isValid()) {
            $em->persist($post);
            $em->flush();

            $post->upload();
            $em->persist($post);
            $em->flush();

            $session = $request->getSession();
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('create_success'));

            return $this->redirect($this->generateUrl('admin_posts'));
        }

        return $this->render('AdminBundle:Post:new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Edit POst
     * @param Request $request
     * @param Post $post
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Post $post) {
        $em = $this->getDoctrine()->getManager();

        $parents = $em->getRepository('AppBundle:Post')->findParents();

        $form = $this->createForm(new PostType($parents), $post);
        $form->handleRequest($request);

        if($form->isValid()) {
            $post->upload();
            $em->persist($post);
            $em->flush();

            $session = $request->getSession();
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('edit_success'));

            return $this->redirect($this->generateUrl('admin_post_edit', array('id' => $post->getId())));
        }

        return $this->render('AdminBundle:Post:edit.html.twig', array(
            'form' => $form->createView(),
            'post' => $post
        ));
    }

    /**
     * Delete Post
     * @param Request $request
     * @param Post $post
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Post $post) {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();

        try {
            $em->remove($post);
            $em->flush();
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('delete_success'));
        } catch(\Exception $e) {
            $session->getFlashBag()->add('msgError', $this->get('translator')->trans('delete_error'));
        }

        return $this->redirect($this->generateUrl('admin_posts'));
    }

    /**
     * List all Comments
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function commentsAction() {
        $em = $this->getDoctrine()->getManager();
        $comments = $em->getRepository('AppBundle:PostComment')->findAll();
        return $this->render('AdminBundle:PostComment:list.html.twig', array('comments' => $comments));
    }

    /**
     * Delete Comment
     * @param Request $request
     * @param PostComment $comment
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function commentDeleteAction(Request $request, PostComment $comment) {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();

        try {
            $em->remove($comment);
            $em->flush();
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('delete_success'));
        } catch(\Exception $e) {
            $session->getFlashBag()->add('msgError', $this->get('translator')->trans('delete_error'));
        }

        return $this->redirect($this->generateUrl('admin_posts_comments'));
    }

}