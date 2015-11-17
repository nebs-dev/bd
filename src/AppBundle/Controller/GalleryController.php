<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Accommodation;
use AppBundle\Entity\UnitGallery;
use AppBundle\Entity\Video;
use AppBundle\Form\UnitGalleryType;
use AppBundle\Form\VideoType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Gallery;
use AppBundle\Form\GalleryType;


class GalleryController extends Controller {

    #####################
    ### ACCOMMODATION ###
    #####################

    /**
     * New Accommodation photo
     * @param Request $request
     * @param $accommodationId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function newAction(Request $request, $accommodationId) {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $gallery = new Gallery();
        $accommodation = $em->getRepository('AppBundle:Accommodation')->find($accommodationId);

        $form = $this->createForm(new GalleryType(), $gallery);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $gallery->setAccommodation($accommodation);
            $gallery->setFeaturedImage(0);
            $gallery->upload();

            $em->persist($gallery);
            $em->flush();

            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('edit_success'));
        } else {
            $session->getFlashBag()->add('msgSuccess', (string)$form->getErrors(true, false));
        }


        return $this->redirect($this->generateUrl('app_accommodation_edit', array('id' => $accommodationId)));
    }

    /**
     * Delete Accommodation Photo
     * @param Request $request
     * @param Gallery $photo
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Gallery $photo) {
        $em = $this->getDoctrine()->getManager();
        $accommodation = $photo->getAccommodation();
        $session = $request->getSession();

        try {
            $em->remove($photo);
            $em->flush();
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('delete_success'));

        } catch(\Exception $e) {
            $session->getFlashBag()->add('msgError', $e->getMessage());
        }

        return $this->redirect($this->generateUrl('app_accommodation_edit', array('id' => $accommodation->getId())));
    }

    /**
     * Set featured image Accommodation
     * @param Request $request
     * @param Gallery $photo
     * @param $accommodationId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function setFeaturedImageAction(Request $request, Gallery $photo) {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $galleryRep = $em->getRepository('AppBundle:Gallery');
        $accommodationId = $photo->getAccommodation()->getId();

        try {
            $gallery = $galleryRep->getGallery($accommodationId);

            foreach($gallery as $galleryPhoto) {
                $galleryPhoto->setFeaturedImage(0);
            }

            $photo->setFeaturedImage(1);
            $em->persist($photo);
            $em->flush();

            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('featured_image_success'));
            if($request->get('_route') == 'app_featuredImage_steps_accommodation') {
                return $this->redirect($this->generateUrl('app_profile_step_6', array('id' => $accommodationId)));
            } else {
                return $this->redirect($this->generateUrl('app_accommodation_edit', array('id' => $accommodationId)));
            }

        } catch(\Exception $e) {
            $session->getFlashBag()->add('msgError', $e->getMessage());
            if($request->get('_route') == 'app_featuredImage_steps_accommodation') {
                return $this->redirect($this->generateUrl('app_profile_step_6', array('id' => $accommodationId)));
            } else {
                return $this->redirect($this->generateUrl('app_accommodation_edit', array('id' => $accommodationId)));
            }
        }
    }

    ######################
    ### /ACCOMMODATION ###
    ######################


    ############
    ### UNIT ###
    ############

    /**
     * New Accommodation photo
     * @param Request $request
     * @param $accommodationId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function newUnitAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $gallery = new UnitGallery();
        $unit = $em->getRepository('AppBundle:Unit')->find($id);

        $form = $this->createForm(new UnitGalleryType(array($unit)), $gallery);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $gallery->setUnit($unit);
            $gallery->setFeaturedImage(0);
            $gallery->upload();

            $em->persist($gallery);
            $em->flush();

            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('edit_success'));
        } else {
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('upload_error'));
        }

        return $this->redirect($this->generateUrl('app_unit_edit', array('id' => $id)));
    }

    /**
     * Delete Unit Photo
     * @param Request $request
     * @param Gallery $photo
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteUnitAction(Request $request, UnitGallery $photo) {
        $em = $this->getDoctrine()->getManager();
        $unit = $photo->getUnit();
        $session = $request->getSession();

        try {
            $em->remove($photo);
            $em->flush();
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('delete_success'));

        } catch(\Exception $e) {
            $session->getFlashBag()->add('msgError', $e->getMessage());
        }

        return $this->redirect($this->generateUrl('app_unit_edit', array(
            'id' => $unit->getId()
        )));
    }

    /**
     * Set featured image Unit
     * @param Request $request
     * @param Gallery $photo
     * @param $unitId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function setFeaturedImageUnitAction(Request $request, UnitGallery $photo) {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $galleryRep = $em->getRepository('AppBundle:UnitGallery');

        $unit = $photo->getUnit();
        $accommodationId = $unit->getAccommodation()->getId();

        try {
            $gallery = $galleryRep->getGallery($unit->getId());

            foreach($gallery as $galleryPhoto) {
                $galleryPhoto->setFeaturedImage(0);
            }

            $photo->setFeaturedImage(1);
            $em->persist($photo);
            $em->flush();

            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('featured_image_success'));

            if($request->get('_route') == 'app_featuredImage_steps_unit') {
                return $this->redirect($this->generateUrl('app_profile_step_6', array('id' => $accommodationId)));
            } else {
                /**
                 * TODO: Putanja do uređivanja Unit u profilu
                 */
                return $this->redirect($this->generateUrl('app_unit_edit', array('id' => $accommodationId)));
            }

        } catch(\Exception $e) {
            $session->getFlashBag()->add('msgError', $e->getMessage());
            if($request->get('_route') == 'app_featuredImage_steps_unit') {
                return $this->redirect($this->generateUrl('app_profile_step_6', array('id' => $accommodationId)));
            } else {
                return $this->redirect($this->generateUrl('app_unit_edit', array('id' => $accommodationId)));
            }
        }
    }

    #############
    ### /UNIT ###
    #############



    #############
    ### VIDEO ###
    #############

    /**
     * Add new video
     * @param Request $request
     * @param Accommodation $accommodation
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function videoNewAction(Request $request, Accommodation $accommodation) {
        if(!$this->get('permissionsService')->isOwner($this->getUser(), $accommodation))
            return $this->redirect($this->generateUrl('user_403'));

        $session = $request->getSession();

        if($accommodation->getVideos()->count() >= 5) {
            $session->getFlashBag()->add('msgError', $this->get('translator')->trans('video_limit_reached'));
            if($request->get('_route') == 'app_video_step6_new') {
                return $this->redirect($this->generateUrl('app_profile_step_6', array('id' => $accommodation->getId())));
            } else {
                return $this->redirect($this->generateUrl('app_accommodation_edit', array('id' => $accommodation->getId())));
            }
        }

        $em = $this->getDoctrine()->getManager();
        $video = new Video();

        $form = $this->createForm(new VideoType(), $video);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $video->setAccommodation($accommodation);
            $em->persist($video);
            $em->flush();

            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('create_success'));
            if($request->get('_route') == 'app_video_step6_new') {
                return $this->redirect($this->generateUrl('app_profile_step_6', array('id' => $accommodation->getId())));
            } else {
                return $this->redirect($this->generateUrl('app_accommodation_edit', array('id' => $accommodation->getId())));
            }
        }

        $session->getFlashBag()->add('msgError', $this->get('translator')->trans('create_error'));
        if($request->get('_route') == 'app_video_step6_new') {
            return $this->redirect($this->generateUrl('app_profile_step_6', array('id' => $accommodation->getId())));
        } else {
            return $this->redirect($this->generateUrl('app_accommodation_edit', array('id' => $accommodation->getId())));
        }
    }

    /**
     * Delete video
     * @param Request $request
     * @param Video $video
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function videoDeleteAction(Request $request, Video $video) {
        $em = $this->getDoctrine()->getManager();
        $accommodation = $video->getAccommodation();
        $session = $request->getSession();

        try {
            $em->remove($video);
            $em->flush();
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('delete_success'));

        } catch(\Exception $e) {
            $session->getFlashBag()->add('msgError', $e->getMessage());
        }

        if($request->get('_route') == 'app_video_step6_delete') {
            return $this->redirect($this->generateUrl('app_profile_step_6', array('id' => $accommodation->getId())));
        } else {
            return $this->redirect($this->generateUrl('app_accommodation_edit', array('id' => $accommodation->getId())));
        }
    }

    ##############
    ### /VIDEO ###
    ##############

}