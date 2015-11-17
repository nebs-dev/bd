<?php
namespace AdminBundle\Controller;

use AdminBundle\Form\FeeType;
use AppBundle\Entity\Accommodation;
use AppBundle\Entity\AccommodationFee;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Swift_Message;
use Swift_Mailer;
use Swift_SmtpTransport;

class FeeController extends Controller {


    /**
     * get all Fees
     * @return Response
     */
    public function allFeesAction() {
        $em = $this->getDoctrine()->getManager();
        $fees = $em->getRepository('AppBundle:AccommodationFee')->findBy(
            array(),
            array('validUntil' => 'DESC')
        );

        return $this->render('AdminBundle:Fee:allFees.html.twig', array(
            'fees' => $fees
        ));
    }

    /**
     * Get all pending fees
     * @return Response
     */
    public function pendingFeesAction() {
        $em = $this->getDoctrine()->getManager();
        $fees = $em->getRepository('AppBundle:AccommodationFee')->getPendingFees();

        return $this->render('AdminBundle:Fee:allFees.html.twig', array(
            'fees' => $fees
        ));
    }

    /**
     * list Accommodation fees
     * @param Accommodation $accommodation
     * @return Response
     */
    public function feesAction(Request $request, Accommodation $accommodation) {
        $em = $this->getDoctrine()->getManager();
        $fees = $em->getRepository('AppBundle:AccommodationFee')->findBy(
            array('accommodation' => $accommodation),
            array('status' => 'DESC')
        );

        return $this->render('AdminBundle:Fee:fees.html.twig', array(
            'fees' => $fees,
            'accommodation' => $accommodation
        ));
    }

    /**
     * New fee
     * @param Request $request
     * @param Accommodation $accommodation
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request, Accommodation $accommodation) {
        $em = $this->getDoctrine()->getManager();
        $fee = new AccommodationFee();

        $form = $this->createForm(new FeeType(), $fee);

        $form->handleRequest($request);
        if ($form->isValid()) {
            # Unactivate all of existing fees for this accommodation
            $em->getRepository('AppBundle:AccommodationFee')->changeStatus($accommodation, 0);

            $fee->setAccommodation($accommodation);
            $em->persist($fee);
            $em->flush();

            $session = $request->getSession();
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('create_success'));

            return $this->redirect($this->generateUrl('admin_accommodation_fees', array('id' => $accommodation->getId())));
        }

        return $this->render('AdminBundle:Fee:new.html.twig', array(
            'form'  => $form->createView()
        ));
    }

    /**
     * Edit fee
     * @param Request $request
     * @param Accommodation $accommodation
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, AccommodationFee $fee) {
        $em = $this->getDoctrine()->getManager();
        $accommodation = $fee->getAccommodation();

        $form = $this->createForm(new FeeType(), $fee);

        $form->handleRequest($request);
        if ($form->isValid()) {
            if($form->getData()->getStatus()) {
                # Unactivate all of existing fees for this accommodation
                $em->getRepository('AppBundle:AccommodationFee')->changeStatus($accommodation, 0);
                $fee->setStatus(1);
            }

            $em->persist($fee);
            $em->flush();

            $session = $request->getSession();
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('create_success'));

            return $this->redirect($this->generateUrl('admin_fee_edit', array('id' => $fee->getId())));
        }

        return $this->render('AdminBundle:Fee:edit.html.twig', array(
            'form'  => $form->createView(),
            'fee'   => $fee
        ));
    }

    /**
     * Activate fee
     * @param AccommodationFee $fee
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function feeActivateAction(Request $request, AccommodationFee $fee) {
        $em = $this->getDoctrine()->getManager();
        # Unactivate all of existing fees for this accommodation
        $em->getRepository('AppBundle:AccommodationFee')->changeStatus($fee->getAccommodation(), 0);

        $fee->setStatus(1);
        $em->persist($fee);
        $em->flush();

        $session = $request->getSession();
        $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('change_status_success'));

        $this->approveAccommodationEmail($fee->getAccommodation());
        return $this->redirect($this->generateUrl('admin_accommodation_fees', array('id' => $fee->getAccommodation()->getId())));
    }

    /**
     * Delete fee
     * @param Request $request
     * @param AccommodationFee $fee
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function feeDeleteAction(Request $request, AccommodationFee $fee) {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();

        $em->remove($fee);
        $em->flush();
        $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('delete_success'));

        return $this->redirect($this->generateUrl('admin_accommodation_fees', array('id' => $fee->getAccommodation()->getId())));
    }



    private function approveAccommodationEmail($accommodation) {
        try {
            $fee = $this->get('accommodationService')->getLastFee($accommodation);

            $message = \Swift_Message::newInstance()
                ->setSubject('Best-destination.com – '.$accommodation->getName().' has been published on best-destination.com')
                ->setFrom($this->get('emailService')->getSiteEmail())
                ->setTo($accommodation->getUser()->getEmail())
                ->setBody($this->renderView(
                    'AppBundle:Emails:accommodation_approval.html.twig',
                    array(
                        'accommodation' => $accommodation,
                        'fee' => $fee
                    )
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