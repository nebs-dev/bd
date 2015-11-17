<?php
namespace AdminBundle\Controller;

use AdminBundle\Form\AdvertisingPackageType;
use AdminBundle\Form\AdvertisingPackageTypeType;
use AppBundle\Entity\Accommodation;
use AppBundle\Entity\AdvertisingPackage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdvertisingPackageController extends Controller {


    /**
     * get all Advertising Packages
     * @return Response
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $advertisingPackages = $em->getRepository('AppBundle:AdvertisingPackage')->findBy(
            array(),
            array('createdAt' => 'DESC')
        );

        return $this->render('AdminBundle:AdvertisingPackage:index.html.twig', array(
            'advertisingPackages' => $advertisingPackages
        ));
    }

    /**
     * get all Advertising Packages for accommodation
     * @return Response
     */
    public function accommodationListAction(Accommodation $accommodation) {
        $em = $this->getDoctrine()->getManager();

        $advertisingPackages = $em->getRepository('AppBundle:AdvertisingPackage')->findBy(
            array('accommodation' => $accommodation),
            array('createdAt' => 'DESC')
        );

        return $this->render('AdminBundle:AdvertisingPackage:accommodationList.html.twig', array(
            'accommodation' => $accommodation,
            'advertisingPackages' => $advertisingPackages
        ));
    }

    /**
     * Change status
     * @param Request $request
     * @param AdvertisingPackage $ap
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function statusChangeAction(Request $request, AdvertisingPackage $ap) {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();

        try {
            $newStatus = ($ap->getStatus()) ? 0 : 1;
            $ap->setStatus($newStatus);
            $em->persist($ap);
            $em->flush();

            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('change_status_success'));

        } catch(\Exception $e) {
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('change_status_error'));
        }

        return $this->redirect($this->generateUrl('admin_accommodation_advertising_packages', array('id' => $ap->getAccommodation()->getId())));
    }

    /**
     * New fee
     * @param Request $request
     * @param Accommodation $accommodation
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request, Accommodation $accommodation) {
        $em = $this->getDoctrine()->getManager();
        $advertisingPackage = new AdvertisingPackage();

        $form = $this->createForm(new AdvertisingPackageType(), $advertisingPackage);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $advertisingPackage->setAccommodation($accommodation);
            $em->persist($advertisingPackage);
            $em->flush();

            $session = $request->getSession();
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('create_success'));

            return $this->redirect($this->generateUrl('admin_accommodation_advertising_packages', array('id' => $accommodation->getId())));
        }

        return $this->render('AdminBundle:AdvertisingPackage:new.html.twig', array(
            'form'  => $form->createView()
        ));
    }

    /**
     * Edit Advertising package
     * @param Request $request
     * @param AdvertisingPackage $ap
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, AdvertisingPackage $ap) {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new AdvertisingPackageType(), $ap);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em->persist($ap);
            $em->flush();

            $session = $request->getSession();
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('create_success'));

            return $this->redirect($this->generateUrl('admin_advertisingPackage_edit', array('id' => $ap->getId())));
        }

        return $this->render('AdminBundle:AdvertisingPackage:edit.html.twig', array(
            'form'  => $form->createView(),
            'advertisingPackage'   => $ap
        ));
    }

    /**
     * Delete advertising pšackage
     * @param Request $request
     * @param AccommodationFee $fee
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, AdvertisingPackage $ap) {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();

        try {
            $em->remove($ap);
            $em->flush();
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('delete_success'));
        } catch(\Exception $e) {
            $session->getFlashBag()->add('msgError', $e->getMessage());
        }

        return $this->redirect($this->generateUrl('admin_accommodation_advertising_packages', array('id' => $ap->getAccommodation()->getId())));
    }


    /**
     * Get all pending advertising packages
     * @return Response
     */
    public function pendingAction() {
        $em = $this->getDoctrine()->getManager();
        $advertisingPackages = $em->getRepository('AppBundle:AdvertisingPackage')->findBy(
            array('status' => 0, 'validUntil' => null),
            array('createdAt' => 'DESC')
        );

        return $this->render('AdminBundle:AdvertisingPackage:index.html.twig', array(
            'advertisingPackages' => $advertisingPackages
        ));
    }


    /**
     * Advertising types list
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function typesAction() {
        $em = $this->getDoctrine()->getManager();
        $types = $em->getRepository('AppBundle:AdvertisingPackageType')->findAll();
        return $this->render('AdminBundle:AdvertisingPackage:types.html.twig', array(
            'types' => $types
        ));
    }

    public function typeEditAction(Request $request, \AppBundle\Entity\AdvertisingPackageType $type) {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new AdvertisingPackageTypeType(), $type);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($type);
            $em->flush();

            $session = $request->getSession();
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('edit_success'));

            return $this->redirect($this->generateUrl('admin_accommodation_advertising_package_type_edit', array('id' => $type->getId())));
        }

        return $this->render('AdminBundle:AdvertisingPackage:type_edit.html.twig', array(
            'form'  => $form->createView(),
            'type'   => $type
        ));
    }

}