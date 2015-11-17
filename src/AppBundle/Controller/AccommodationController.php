<?php

namespace AppBundle\Controller;

use AppBundle\Entity\AccommodationFee;
use AppBundle\Entity\Booking;
use AppBundle\Entity\Review;
use AppBundle\Form\AccommodationType;
use AppBundle\Entity\Accommodation;
use AppBundle\Form\AdvertisingPackageType;
use AppBundle\Form\FeeType;
use AppBundle\Form\ReviewType;
use AppBundle\Form\VideoType;
use Doctrine\ORM\Tools\Export\ExportException;
use Proxies\__CG__\AppBundle\Entity\AdvertisingPackage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\GalleryType;
use AppBundle\Entity\Gallery;
use AppBundle\Form\BookingType;
use UserBundle\Entity\User;
use UserBundle\Form\RegistrationType;
use Swift_SmtpTransport;
use Swift_Mailer;


class AccommodationController extends Controller {

    /**
     * Get all Accommodations
     * @return Response
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $accommodations = $em->getRepository('AppBundle:Accommodation')->getAll();
        return $this->render('AppBundle:Accommodation:list.html.twig', array('accommodations' => $accommodations));
    }

    /**
     * Get all accommodations for user
     * @return Response
     */
    public function listUserAction() {
        $em = $this->getDoctrine()->getManager();
        $accommodations = $em->getRepository('AppBundle:Accommodation')->getAccommodationsUser($this->getUser());

        return $this->render('AppBundle:Accommodation:list.html.twig', array(
            'accommodations' => $accommodations
        ));
    }

    /**
     * New Accommodation Request
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function newAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $accommodation = new Accommodation();

        $ac         = $em->getRepository('AppBundle:AccommodationCategory')->findAll();
        $cities     = $em->getRepository('AppBundle:City')->getAll();

        $form = $this->createForm(new AccommodationType($ac, $cities), $accommodation);
        $form->handleRequest($request);

        if($form->isValid()) {
            $accommodation->setIsActive(0);
            $accommodation->setUser($this->getUser());

            $em->persist($accommodation);
            $em->flush();

            $session = $request->getSession();
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('create_success'));

            return $this->redirect($this->generateUrl('app_profile'));
        }

        return $this->render('AppBundle:Accommodation:new.html.twig', array('form' => $form->createView()));
    }

    /**
     * Edit Accommodation
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction(Request $request, Accommodation $accommodation) {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $gallery = new Gallery();

        # If user isn't owner -> 403
        if($this->getUser()->getId() != $accommodation->getUser()->getId())
            return $this->redirect($this->generateUrl('user_403'));

        # Units of this accommodation
        $units = $em->getRepository('AppBundle:Unit')->getUnitsAccommodation($accommodation->getId());

        $countries = $em->getRepository('AppBundle:Country')->findAll();
        $regions = $em->getRepository('AppBundle:Region')->getByCountry($accommodation->getCity()->getRegion()->getCountry()->getId());
        $subregions = $em->getRepository('AppBundle:Subregion')->getByRegion($accommodation->getCity()->getRegion()->getId());
        if($accommodation->getCity()->getSubregion()) {
            $cities         = $em->getRepository('AppBundle:City')->getBySubregion($accommodation->getCity()->getSubregion()->getId());
        } else {
            $cities         = $em->getRepository('AppBundle:City')->getByRegion($accommodation->getCity()->getRegion()->getId());
        }

        // price for form
        if(!is_null($accommodation->getWifi())) {
            $newPrice = $this->get("currencyService")->fromEuro($accommodation->getWifi());
            $accommodation->setWifi(round($newPrice, 2));
        }

        $form = $this->createForm(new AccommodationType($request->getLocale()), $accommodation);
        $formGallery = $this->createForm(new GalleryType(), $gallery);
        $formVideo = $this->createForm(new VideoType());

        if(!is_null($request->request->get('accommodation')) && (!isset($request->request->get('accommodation')['city']) || $request->request->get('accommodation')['city'] == '')) {
            $session->getFlashBag()->add('msgError', $this->get('translator')->trans('city_should_not_be_blank'));
            return $this->redirect($this->generateUrl('app_accommodation_edit', array('id' => $accommodation->getId())));
        }

        $cityPost = $request->request->get('accommodation')['city'];
        $reqAccommodation = $request->request->get('accommodation');
        unset($reqAccommodation['city']);
        $request->request->set('accommodation', $reqAccommodation);

        $form->handleRequest($request);
        if ($form->isValid()) {
            # All POST data
            $postData = $request->request->all();

//            echo'<pre>';
//            exit(\Doctrine\Common\Util\Debug::dump(intval($postData['wifi'])));
//            echo'</pre>';

            if(intval($postData['wifi']) == 0) {
                $accommodation->setWifi(null);
            } else {
                $accommodation->setWifi($this->get("currencyService")->toEuro($postData['accommodation']['wifi']));
            }
            $city = $em->getRepository('AppBundle:City')->find($cityPost);
            $accommodation->setCity($city);
            $em->persist($accommodation);
            $em->flush();

            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('edit_success'));

            return $this->redirect($this->generateUrl('app_accommodation_edit', array('id' => $accommodation->getId())));
        }

//        echo'<pre>';
//        exit(\Doctrine\Common\Util\Debug::dump($accommodation->getWifi()));
//        echo'</pre>';

        return $this->render('AppBundle:Accommodation:edit.html.twig', array(
            'form'          => $form->createView(),
            'formGallery'   => $formGallery->createView(),
            'formVideo'     => $formVideo->createView(),
            'accommodation' => $accommodation,
            'units'         => $units,
            'countries'     => $countries,
            'regions'       => $regions,
            'subregions'    => $subregions,
            'cities'        => $cities
        ));
    }

    /**
     * Delete Accommodation
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Accommodation $accommodation) {
        $em = $this->getDoctrine()->getManager();

        # If user isn't owner -> 403
        if($this->getUser() != $accommodation->getUser())
            return $this->redirect($this->generateUrl('user_403'));

        $session = $request->getSession();

        try {
            $em->remove($accommodation);
            $em->flush();
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('delete_success'));
        } catch(\Exception $e) {
            $session->getFlashBag()->add('msgError', $this->get('translator')->trans('accommodation_has_units'));
        }

        return $this->redirect($this->generateUrl('app_accommodations'));
    }

    /**
     * Accommodation Single page
     * @param Request $request
     * @param Accommodation $accommodation
     * @return Response
     */
    public function singleAction(Accommodation $accommodation) {
        $em = $this->getDoctrine()->getManager();
        $checkOutForm = $this->createForm(new BookingType($accommodation))->remove('price');
        $reviewForm = $this->createForm(new ReviewType())->createView();

        $reviews = $em->getRepository('AppBundle:Review')->findBy(
            array('accommodation' => $accommodation, 'status' => 1));

        $accommodation->setViews($accommodation->getViews() + 1);
        $em->persist($accommodation);
        $em->flush();

//        $accommodation = $em->getRepository('AppBundle:Accommodation')->getSingle($accommodation->getId());

        return $this->render('AppBundle:Accommodation:single.html.twig', array(
            'accommodation' => $accommodation,
            'reviews'       => $reviews,
            'checkOutForm'  => $checkOutForm->createView(),
            'reviewForm'    => $reviewForm
        ));
    }

    /**
     * Create new Review
     * @param Request $request
     * @param Accommodation $accommodation
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function reviewAddAction(Request $request, Accommodation $accommodation) {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $review = new Review();

        $reviewForm = $this->createForm(new ReviewType());
        $reviewForm->handleRequest($request);

        if($reviewForm->isValid()) {
            $data = $reviewForm->getData();
            $total = array(
                $data->getCleanliness(),
                $data->getComfort(),
                $data->getLocation(),
                $data->getFacilities(),
                $data->getStaff(),
                $data->getValueForMoney()
            );

            # If review is active - depanding on rate
            $total = array_sum($total) / 6;
            $status = ($total < 4) ? 0 : 1;

            $review->setTotal($total);
            $review->setAccommodation($accommodation);
            $review->setTourist($this->getUser());
            $review->setText($data->getText());
            $review->setCleanliness($data->getCleanliness());
            $review->setComfort($data->getComfort());
            $review->setLocation($data->getLocation());
            $review->setFacilities($data->getFacilities());
            $review->setStaff($data->getStaff());
            $review->setValueForMoney($data->getValueForMoney());
            $review->setStatus($status);
            $em->persist($review);
            $em->flush();

            # Calculate total accommodation rate with new review
            if($status) {
                $this->get('AccommodationService')->calculateReviewRate($review);
            }

            return $this->redirect($this->generateUrl('app_accommodation_single', array('id' => $accommodation->getId())));
        }

        $session->getFlashBag()->add('reviewFormErrors', (string)$reviewForm->getErrors(true, false));
        return $this->redirect($this->generateUrl('app_accommodation_single', array('id' => $accommodation->getId())));
    }

    /**
     * Show checkout && booking form
     * @param Request $request
     * @param Accommodation $accommodation
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function checkoutAction(Request $request, Accommodation $accommodation) {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();

        if ($this->container->get('security.context')->isGranted('ROLE_HOST') || $this->container->get('security.context')->isGranted('ROLE_ADMIN')) {
            $session->getFlashBag()->add('formErrors', $this->get('translator')->trans('you_are_not_allowed_checkout'));
            return $this->redirect($this->generateUrl('app_accommodation_single', array(
                    'id' => $accommodation->getId()
                )
            ));
        }

        # If user is authenticated we don't need his data
        if ($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $bookingForm = $this->createForm(new RegistrationType(array()))
                ->remove('isActive')
                ->remove('roles')
                ->remove('address')
                ->remove('phone')
                ->remove('username')
                ->remove('name')
                ->remove('surename')
                ->remove('email')
                ->remove('address')
                ->remove('phone')
                ->remove('password');
        } else {
            $bookingForm = $this->createForm(new RegistrationType(array()))
                ->remove('isActive')
                ->remove('roles')
                ->remove('address')
                ->remove('phone');
        }

        $checkOutForm = $this->createForm(new BookingType($accommodation));
        $checkOutForm->handleRequest($request);

        if($checkOutForm->isValid()) {
            # Array with form fields
            $data = $checkOutForm->getData();

            $checkIn = date_create_from_format('d/m/Y', $data['checkIn']);
            $checkOut = date_create_from_format('d/m/Y', $data['checkOut']);

            $checkOutData = $em->getRepository('AppBundle:UnitPeriod')->getCheckoutData($data['unit'], $checkIn, $checkOut);
            return $this->render('AppBundle:Accommodation:checkout.html.twig', array(
                'accommodation' => $accommodation,
                'data'          => $checkOutData,
                'checkOutForm'  => $bookingForm->createView()
            ));
        }

        $session->getFlashBag()->add('formErrors', (string)$checkOutForm->getErrors(true, false));
        return $this->redirect($this->generateUrl('app_accommodation_single', array(
                'id' => $accommodation->getId()
            )
        ));
    }


    /**
     * Booking Action
     * @param Request $request
     * @param Accommodation $accommodation
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function bookingAction(Request $request, Accommodation $accommodation) {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();

        try {
            # User
            $role = $em->getRepository('UserBundle:Role')->findOneByName('guest');
            $unit = $em->getRepository('AppBundle:Unit')->find($request->request->get('unit'));
            $checkIn = new \DateTime($request->request->get('checkIn'));
            $checkOut = new \DateTime($request->request->get('checkOut'));
            $price = $request->request->get('price');

            $bookingForm = $this->createForm(new RegistrationType(array()));
            $bookingForm->handleRequest($request);

            # Array with form fields
            $data = $bookingForm->getData();

            if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
                $user = new User();
                $user->setRoles($role);
                $user->setUsername($data['username']);
                $user->setName($data['name']);
                $user->setSurename($data['surename']);
                $user->setEmail($data['email']);
                $user->setPassword($data['password']);
                $user->setIsActive(0);
                $user->encryptPassword();

                # User validation
                $validator = $this->get('validator');
                $errors = $validator->validate($user);
                $errorsString = (string)$errors;

                if (count($errors) > 0) {
                    $response = array();
                    $response['error'] = $errorsString;
                    $session->getFlashBag()->add('formErrors', (string)$errorsString);

                    return $this->redirect($this->generateUrl('app_accommodation_single', array(
                            'id' => $accommodation->getId()
                        )
                    ));
                }
            } else {
                $user = $this->getUser();
            }

            # Booking
            $booking = new Booking();
            $booking->setUnit($unit);
            $booking->setStatus(0);
            $booking->setPrice($price);
            $booking->setUser($user);
            $booking->setFromDate($checkIn);
            $booking->setToDate($checkOut);

            # Booking validation
            $validator = $this->get('validator');
            $errorsBooking = $validator->validate($booking);
            $errorsString = (string)$errorsBooking;

            if (count($errorsBooking) > 0) {
                $response = array();
                $response['error'] = $errorsString;
                $session->getFlashBag()->add('formErrors', (string)$errorsString);

                return $this->redirect($this->generateUrl('app_accommodation_single', array(
                        'id' => $accommodation->getId()
                    )
                ));
            }


            # Booking not allowed
            if (!$this->get('bookingService')->bookingAllowed($booking))
                return $this->redirect($this->generateUrl('user_403'));


            $em->persist($user);
            $em->flush();

            $em->persist($booking);
            $em->flush();

            $session->getFlashBag()->add('formSuccess', $this->get('translator')->trans('booking_success'));
            $this->bookingRequestEmail($accommodation->getUser(), $booking, 'host');
            $this->bookingRequestEmail($user, $booking, 'guest');

            if ($user->getIsActive()) {
                return $this->redirect($this->generateUrl('app_profile'));
            } else {
                return $this->redirect($this->generateUrl('app_home'));
            }
        } catch(ExportException $e) {
            $session->getFlashBag()->add('formError', $e->getMessage());
            return $this->redirect($this->generateUrl('app_home'));
        }
    }


    /**
     * Get all fees for accommodation
     * @param Request $request
     * @param Accommodation $accommodation
     * @return Response
     */
    public function feesAction(Accommodation $accommodation) {
        $em = $this->getDoctrine()->getManager();

        $fees = $em->getRepository('AppBundle:AccommodationFee')->findBy(
            array('accommodation' => $accommodation),
            array('status' => 'DESC')
        );

        return $this->render('AppBundle:Accommodation:fees.html.twig', array(
            'accommodation' => $accommodation,
            'fees' => $fees
        ));
    }

    /**
     * New fee request
     * @param Request $request
     * @param Accommodation $accommodation
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function newFeeAction(Request $request, Accommodation $accommodation) {
        if(!$this->get('permissionsService')->isOwner($this->getUser(), $accommodation))
            return $this->redirect($this->generateUrl('user_403'));

        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $fee = new AccommodationFee();

        $form = $this->createForm(new FeeType(), $fee);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $fee->setAccommodation($accommodation);
            $em->persist($fee);
            $em->flush();

            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('new_fee_success'));
            return $this->redirect($this->generateUrl('app_accommodation_fees', array('id' => $accommodation->getId())));
        }

        return $this->render('AppBundle:Accommodation:fee_new.html.twig', array(
            'form'          => $form->createView(),
            'accommodation' => $accommodation
        ));
    }

    /**
     * Get all advertising packages for accommodation
     * @param Request $request
     * @param Accommodation $accommodation
     * @return Response
     */
    public function advertisingPackagesAction(Accommodation $accommodation) {
        $em = $this->getDoctrine()->getManager();

        $advertisingPackages = $em->getRepository('AppBundle:AdvertisingPackage')->findBy(
            array('accommodation' => $accommodation),
            array('createdAt' => 'DESC')
        );

        return $this->render('AppBundle:Accommodation:advertising_packages.html.twig', array(
            'accommodation' => $accommodation,
            'advertisingPackages' => $advertisingPackages
        ));
    }

    /**
     * New fee request
     * @param Request $request
     * @param Accommodation $accommodation
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function newAdvertisingPackageAction(Request $request, Accommodation $accommodation) {
        if(!$this->get('permissionsService')->isOwner($this->getUser(), $accommodation))
            return $this->redirect($this->generateUrl('user_403'));

        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $advertisingPackage = new AdvertisingPackage();

        $form = $this->createForm(new AdvertisingPackageType(), $advertisingPackage);

        $form->handleRequest($request);
        if ($form->isValid()) {

//            if($request->request->get('home')) {
//                $typeHome = $em->getRepository('AppBundle:AdvertisingPackageType')->findOneBy(array('type' => 'home'));
//                $ap = new AdvertisingPackage();
//                $ap->setStatus(0);
//                $ap->setType($typeHome);
//                $ap->setAccommodation($accommodation);
//
//                $em->persist($ap);
//                $em->flush();
//            }

            $advertisingPackage->setAccommodation($accommodation);
            $em->persist($advertisingPackage);
            $em->flush();

            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('create_success'));
            return $this->redirect($this->generateUrl('app_accommodation_advertising_packages', array('id' => $accommodation->getId())));
        }

        return $this->render('AppBundle:Accommodation:advertising_package_new.html.twig', array(
            'form'          => $form->createView(),
            'accommodation' => $accommodation
        ));
    }


    private function bookingRequestEmail($user, $booking, $role) {
        try {
            if($role == 'host') {
                $subject = $this->get('translator')->trans('Best-Destination.com – '.$booking->getId().' Reservation REQUEST for accommodation unit ' . $booking->getUnit()->getName());
            } else {
                $subject = $this->get('translator')->trans('Best-destination.com – RESERVATION REQUEST for '.$booking->getUnit()->getAccommodation()->getName().' has been sent to host');
            }

            $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($this->get('emailService')->getSiteEmail())
                ->setTo($user->getEmail())
                ->setBody($this->renderView(
                    'AppBundle:Emails:booking_request.html.twig',
                    array(
                        'user' => $user,
                        'role' => $role,
                        'booking' => $booking
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