<?php

namespace AppBundle\Controller;

use AppBundle\Entity\AdvertisingPackage;
use AppBundle\Entity\Gallery;
use AppBundle\Entity\Unit;
use AppBundle\Entity\UnitGallery;
use AppBundle\Entity\UnitPriceExtra;
use AppBundle\Form\AdvertisingPackageType;
use AppBundle\Form\VideoType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\UserType;
use AppBundle\Entity\Accommodation;
use AppBundle\Form\AccommodationType;
use AppBundle\Form\UnitType;
use AppBundle\Entity\UnitPeriod;
use AppBundle\Form\PeriodType;
use AppBundle\Form\GalleryType;
use AppBundle\Form\UnitGalleryType;
use Swift_SmtpTransport;
use Swift_Mailer;

class ProfileController extends Controller {

    /**
     * User profile
     * @return Response
     */
    public function indexAction() {
        # If user == admin he has no profile
        if($this->get('security.context')->isGranted('ROLE_ADMIN'))
            return $this->redirect($this->generateUrl('admin_home'));

        # Diferent profiles for host & tourist
        if($this->get('security.context')->isGranted('ROLE_HOST')) {
            return $this->hostProfile();
        } else {
            return $this->guestProfile();
        }
    }

    /**
     * Host profile
     * @return Response
     */
    public function hostProfile() {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $accommodations = $em->getRepository('AppBundle:Accommodation')->getAccommodationsUser($user->getId());

        return $this->render('AppBundle:Profile:host.html.twig', array(
            'user'           => $user,
            'accommodations' => $accommodations
        ));
    }

    /**
     * Guest profile
     * @return Response
     */
    public function guestProfile() {
        $user = $this->getUser();
        $accommodationRep = $this->getDoctrine()->getManager()->getRepository('AppBundle:Accommodation');

        return $this->render('AppBundle:Profile:guest.html.twig', array(
            'user'      => $user,
        ));
    }


    /**
     * User wishlist
     * @return Response
     */
    public function wishlistAction() {
        # If user == admin he has no profile
        if($this->get('security.context')->isGranted('ROLE_ADMIN') || $this->get('security.context')->isGranted('ROLE_HOST'))
            return $this->redirect($this->generateUrl('admin_home'));

        return $this->render('AppBundle:Profile:wishlist.html.twig');
    }


    /**
     * Edit profile
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $roleRep = $em->getRepository('UserBundle:Role');

        $user = $this->getUser();
        $oldPassword = $user->getPassword();

        $form = $this->createForm(new UserType($roleRep, $user, $request->getLocale()), $user)
            ->remove('isActive')
            ->remove('roles');

        $form->handleRequest($request);
        if ($form->isValid()) {
            $postData = $request->request->all();
            $postPassword = $postData['user']['password']['first'];

            # If password field is filled change password
            if (!empty($postPassword) || $postPassword != '') {
                $user->setPassword($postPassword);
                $user->encryptPassword();

            # If not filled, don't change password
            } else {
                $user->setPassword($oldPassword);
            }

            $em->persist($user);
            $em->flush();

            $session = $request->getSession();
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('user_edit_success'));

            return $this->redirect($this->generateUrl('app_profile_edit'));
        }

        return $this->render('AppBundle:Profile:edit.html.twig', array(
            'form' => $form->createView(),
            'user' => $user
        ));
    }

	/**
	 * User change password
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function passwordChangeAction(Request $request) {
		$em = $this->getDoctrine()->getManager();
		$user = $this->getUser();
		$session = $request->getSession();

		try {
			$postData = $request->request->all();
			if(($postData['user_password'] != $postData['user_password_confirm']) || empty($postData['user_password']) || empty($postData['user_password_confirm'])) {
				$session->getFlashBag()->add('msgError', $this->get('translator')->trans('password_not_same'));
				return $this->redirect($this->generateUrl('app_profile'));
			}

			$user->setPassword($postData['user_password']);
			$user->encryptPassword();
			$em->persist($user);
			$em->flush();

			$session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('password_change_success'));

		} catch(\ExportException $e) {
			$session->getFlashBag()->add('msgError', $this->get('translator')->trans('passwords_change_error'));
		}

		return $this->redirect($this->generateUrl('app_profile'));
	}


	/**
	 * Step1 - User data
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
	 */
	public function step1Action(Request $request) {
		$em = $this->getDoctrine()->getManager();
		$roleRep = $em->getRepository('UserBundle:Role');

		$user = $this->getUser();
		$oldPassword = $user->getPassword();

		$form = $this->createForm(new UserType($roleRep, $user, $request->getLocale()), $user)->remove('password');

		$form->handleRequest($request);
		if ($form->isValid()) {
//			$postData = $request->request->all();
//			$postPassword = $postData['user']['password']['first'];
//
//			# If password field is filled change password
//			if (!empty($postPassword) || $postPassword != '') {
//				$user->setPassword($postPassword);
//				$user->encryptPassword();
//
//			# If not filled, don't change password
//			} else {
//				$user->setPassword($oldPassword);
//			}

			$em->persist($user);
			$em->flush();

			return $this->redirect($this->generateUrl('app_profile_step_2'));
		}

		return $this->render('AppBundle:Profile/Steps:step1.html.twig', array(
			'form' => $form->createView(),
			'user' => $user
		));
	}

	/**
	 * Step2 - Accommodation Data
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
	 */
	public function step2Action(Request $request) {
		$em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
		$accommodation = new Accommodation();
        $countries = $em->getRepository('AppBundle:Country')->findAll();
		$feeTypes = $em->getRepository('AppBundle:AccommodationFeeType')->findAll();

//        $regions        = $em->getRepository('AppBundle:Region')->getByCountry($accommodation->getCity()->getSubregion()->getRegion()->getCountry()->getId());
//        $subregions     = $em->getRepository('AppBundle:Subregion')->getByRegion($accommodation->getCity()->getSubregion()->getRegion()->getId());
//        if($accommodation->getCity()->getSubregion()) {
//            $cities         = $em->getRepository('AppBundle:City')->getBySubregion($accommodation->getCity()->getSubregion()->getId());
//        } else {
//            $cities         = $em->getRepository('AppBundle:City')->getByRegion($accommodation->getCity()->getRegion()->getId());
//        }

		$form = $this->createForm(new AccommodationType($request->getLocale(), true), $accommodation)
			->remove('distance')
			->remove('activities')
			->remove('facilities')
			->remove('beaches')
			->remove('additionals')
			->remove('payments')
			->remove('description')
            ->remove('advertisingPackage')
            ->remove('wifi')
//			->remove('fees')
        ;

//        echo'<pre>';
//        exit(\Doctrine\Common\Util\Debug::dump($request->request->get('accommodation')));
//        echo'</pre>';

        if(!is_null($request->request->get('accommodation')) && (!isset($request->request->get('accommodation')['city']) || $request->request->get('accommodation')['city'] == '')) {
            $session->getFlashBag()->add('msgError', $this->get('translator')->trans('city_should_not_be_blank'));
            return $this->redirect($this->generateUrl('app_profile_step_2'));
        }

        $cityPost = $request->request->get('accommodation')['city'];
        $reqAccommodation = $request->request->get('accommodation');
        unset($reqAccommodation['city']);
        $request->request->set('accommodation', $reqAccommodation);

		$form->handleRequest($request);
		if($form->isValid()) {
            $city = $em->getRepository('AppBundle:City')->find($cityPost);
            $accommodation->setCity($city);
			$accommodation->setUser($this->getUser());

			$em->persist($accommodation);
			$em->flush();
			$id = $accommodation->getId();

			$this->newAccommodationEmail($accommodation);
			return $this->redirect($this->generateUrl('app_profile_step_3', array('id' => $id)));
		}

		return $this->render('AppBundle:Profile/Steps:step2.html.twig', array(
            'form' => $form->createView(),
            'countries' => $countries,
			'feeTypes' => $feeTypes
        ));
	}

	/**
	 * Accommodation
	 * @param Request       $request
	 * @param Accommodation $accommodation
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
	 */
	public function step3Action(Request $request, Accommodation $accommodation) {
		$em = $this->getDoctrine()->getManager();

		if($accommodation->getUser() != $this->getUser())
			return $this->redirect($this->generateUrl('app_home'));

		// price for form
		if(!is_null($accommodation->getWifi())) {
			$newPrice = $this->get("currencyService")->fromEuro($accommodation->getWifi());
			$accommodation->setWifi(round($newPrice, 2));
		}

		$form = $this->createForm(new AccommodationType($request->getLocale()), $accommodation)
			->remove('name')
			->remove('accommodationCategory')
			->remove('address')
//			->remove('x')
//			->remove('y')
			->remove('checkIn')
			->remove('checkOut')
			->remove('minimumStay')
			->remove('city')
			->remove('web')
			->remove('accommodationCategory')
			->remove('advertisingPackage')
			->remove('prepay')
			->remove('email')
			->remove('phone')
			->remove('fees');

		$form->handleRequest($request);
		if($form->isValid()) {
            # All POST data
            $postData = $request->request->all();

			if(intval($postData['wifi']) == 0) {
				$accommodation->setWifi(null);
			} else {
				$accommodation->setWifi($this->get("currencyService")->toEuro($postData['accommodation']['wifi']));
			}

			$em->persist($accommodation);
			$em->flush();

			return $this->redirect($this->generateUrl('app_profile_step_4', array('id' => $accommodation->getId())));
		}

		return $this->render('AppBundle:Profile/Steps:step3.html.twig', array(
			'form'          => $form->createView(),
			'accommodation' => $accommodation
		));
	}


	/**
	 * Step4 - Unit Data
	 * @param Request       $request
	 * @param Accommodation $accommodation
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
	 */
	public function step4Action(Request $request, Accommodation $accommodation) {
		$em = $this->getDoctrine()->getManager();
		$unit = new Unit();
        $priceExtraRep = $em->getRepository('AppBundle:PriceExtra');
        $priceExtra = $priceExtraRep->findAll();

        if(!$this->get("accommodationService")->moreUnitAllowedSteps($accommodation)) {
            $request->getSession()->getFlashBag()->add('msgError', $this->get('translator')->trans('max_unit_exceeded'));

            return $this->redirect($this->generateUrl('app_profile_step_4a', array(
                'id' => $accommodation->getId()
            )));
        }

		// price for form
		foreach($unit->getUnitPriceExtra() as $pe) {
			if(!is_null($pe->getPrice())) {
				$newPrice = $this->get("currencyService")->fromEuro($pe->getPrice());
				$pe->setPrice(round($newPrice, 2));
			}
		}

		$form = $this->createForm(new UnitType(), $unit);
		$form->handleRequest($request);

        # All POST data
        $postData = $request->request->all();

		if($form->isValid()) {
			$unit->setAccommodation($accommodation);
            $unit->setBasicPrice($this->get("currencyService")->toEuro($postData['unit']['basicPrice']));
			$em->persist($unit);
			$em->flush();

            # Add price extra to join table with unit
            foreach($postData['priceExtra'] as $priceExtra) {
                $unitPriceExtra = new UnitPriceExtra();
                $pExtra = $priceExtraRep->find($priceExtra['priceExtraId']);

                $unitPriceExtra->setUnit($unit);
                $unitPriceExtra->setPriceExtra($pExtra);
				$price = $this->get("currencyService")->toEuro($priceExtra['price']);
				$unitPriceExtra->setPrice($price);
                $unitPriceExtra->setAvailability($priceExtra['availability']);

                $em->persist($unitPriceExtra);
                $em->flush();
            }

			return $this->redirect($this->generateUrl('app_profile_step_4a', array(
				'id' => $accommodation->getId()
			)));
		}

		return $this->render('AppBundle:Profile/Steps:step4.html.twig', array(
			'form'      => $form->createView(),
			'unit'      => $unit,
            'currencies'=> $this->get("currencyService")->getCurrencies(),
            'priceExtra'=> $priceExtra
		));
	}

	/**
	 * Unit list Step 4a
	 * @param Accommodation $accommodation
	 * @return Response
	 */
	public function step4aAction(Accommodation $accommodation) {
		return $this->render('AppBundle:Profile/Steps:step4a.html.twig', array(
			'accommodation' => $accommodation
		));
	}

	/**
	 * Step 5 - Periods
	 * @param Request       $request
	 * @param Accommodation $accommodation
	 * @return Response
	 */
	public function step5Action(Request $request, Accommodation $accommodation) {
		$period = new UnitPeriod();
		$units = $this->getDoctrine()->getManager()->getRepository('AppBundle:Unit')->getUnitsAccommodation($accommodation);

		# Period form
		$formPeriod = $this->createForm(new PeriodType(null, $units), $period);

		return $this->render('AppBundle:Profile/Steps:step5.html.twig', array(
			'accommodation' => $accommodation,
			'formPeriod'    => $formPeriod->createView()
		));
	}

	/**
	 * New period
	 * @return Response
	 */
	public function step5PeriodNewAction() {
		$em = $this->getDoctrine()->getManager();
		$period = new UnitPeriod();

		parse_str($_POST['period'], $postPeriod);

		$periodPost = $postPeriod['period'];
		$unitId = $_POST['unitId'];
		$fromDate = $periodPost['fromDate'];
		$toDate = $periodPost['toDate'];
		$sign  = $postPeriod['sign'];
		$amount  = intval($sign . $periodPost['amount']);

		try {
			$unit = $em->getRepository('AppBundle:Unit')->find($unitId);
			$period->setUnit($unit);
			if(!empty($fromDate) && !empty($toDate)) {
				$period->setFromDate(new \DateTime($fromDate));
				$period->setToDate(new \DateTime($toDate));
			}
			if(!empty($amount)) {
				$period->setAmount(intval($amount));
			}

			# Validation
			$validator = $this->get('validator');
			$errors = $validator->validate($period);
			$errorsString = (string) $errors;

			if (count($errors) > 0) {
				$response = array();
				$response['error'] = $errorsString;
				return new Response(json_encode($response));
			} else {
				$em->persist($period);
				$em->flush();

				$periods = $em->getRepository('AppBundle:UnitPeriod')->getAll($unitId);
				$response = $this->render('AppBundle:Profile/Steps/ajax:periodsUnit.html.twig', array(
					'periods'  => $periods,
					'unitId'   => $unitId
				))->getContent();
			}

		} catch(\Exception $e) {
			return new Response($e);
		}

		return new Response(json_encode($response));
	}

	/**
	 * Delete period
	 * @param Request $request
	 * @param $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function step5PeriodDeleteAction() {
		$em = $this->getDoctrine()->getManager();
		$periodId = $_POST['periodId'];
		$unitId = $_POST['unitId'];

		$period = $em->getRepository('AppBundle:UnitPeriod')->find($periodId);

		try {
			$em->remove($period);
			$em->flush();

			$periods = $em->getRepository('AppBundle:UnitPeriod')->getAll($unitId);
			$response = $this->render('AppBundle:Profile/Steps/ajax:periodsUnit.html.twig', array(
				'periods'  => $periods,
				'unitId'   => $unitId
			))->getContent();

		} catch(\Exception $e) {
			return new Response($e);
		}

		return new Response(json_encode($response));
	}

	/**
	 * Upload Accommodation photo
	 * @param Request       $request
	 * @param Accommodation $accommodation
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
	 */
	public function step6Action(Request $request, Accommodation $accommodation) {
		$em = $this->getDoctrine()->getManager();

		$gallery = new Gallery();
		$unitGallery = new UnitGallery();

		$form = $this->createForm(new GalleryType(), $gallery);
		$formUnit = $this->createForm(new UnitGalleryType($accommodation->getUnits()), $unitGallery);
		$formVideo = $this->createForm(new VideoType());

		$form->handleRequest($request);
		if($form->isValid()) {
			$gallery->setAccommodation($accommodation);
            $gallery->setFeaturedImage(0);
			$gallery->upload();
			$em->persist($gallery);
			$em->flush();

			return $this->redirect($this->generateUrl('app_profile_step_6', array(
				'id' => $accommodation->getId()
			)));
		}

		return $this->render('AppBundle:Profile/Steps:step6.html.twig', array(
			'form'          => $form->createView(),
			'formUnit'      => $formUnit->createView(),
			'formVideo'     => $formVideo->createView(),
			'accommodation' => $accommodation
		));
	}

	/**
	 * Add unit photo
	 * @param Request       $request
	 * @param Accommodation $accommodation
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function step6UnitGalleryAction(Request $request, Accommodation $accommodation) {
		$em = $this->getDoctrine()->getManager();
		$session = $request->getSession();
		$unitGallery = new UnitGallery();

		$formUnit = $this->createForm(new UnitGalleryType($accommodation->getUnits()), $unitGallery);
		$formUnit->handleRequest($request);

		if($formUnit->isValid()) {
            $unitGallery->setFeaturedImage(0);
			$unitGallery->upload();
			$em->persist($unitGallery);
			$em->flush();

			$session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('create_success'));

		} else {
			$errors = (string) $formUnit->getErrors(true, false);
			$session->getFlashBag()->add('msgSuccess', $errors);

		}

		return $this->redirect($this->generateUrl('app_profile_step_6', array(
			'id' => $accommodation->getId()
		)));
	}

    /**
     * Delete Accommodation photo
     * @param Request $request
     * @param Gallery $photo
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAccommodationPhotoAction(Request $request, Gallery $photo) {
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

        return $this->redirect($this->generateUrl('app_profile_step_6', array(
            'id' => $accommodation->getId()
        )));
    }

	/**
	 * Delete Unit photo
	 * @param Request $request
	 * @param UnitGallery $photo
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
    public function deleteUnitPhotoAction(Request $request, UnitGallery $photo) {
        $em = $this->getDoctrine()->getManager();
        $accommodation = $photo->getUnit()->getAccommodation();
        $session = $request->getSession();

        try {
            $em->remove($photo);
            $em->flush();
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('delete_success'));

        } catch(\Exception $e) {
            $session->getFlashBag()->add('msgError', $e->getMessage());
        }

        return $this->redirect($this->generateUrl('app_profile_step_6', array(
            'id' => $accommodation->getId()
        )));
    }


	public function step7Action(Request $request, Accommodation $accommodation) {
        $em = $this->getDoctrine()->getManager();
        $advertisingPackage = new AdvertisingPackage();

        $typesLocation = $em->getRepository('AppBundle:AdvertisingPackageType')->findBy(array('type' => array('country', 'region', 'subregion', 'city')));
        $typeHome = $em->getRepository('AppBundle:AdvertisingPackageType')->findOneBy(array('type' => 'home'));

        $form = $this->createForm(new AdvertisingPackageType($typesLocation), $advertisingPackage);
        $form->handleRequest($request);

        if($form->isValid()) {
            if($request->request->get('home')) {
                $ap = new AdvertisingPackage();
                $ap->setStatus(0);
                $ap->setType($typeHome);
                $ap->setAccommodation($accommodation);

                $em->persist($ap);
                $em->flush();
            }

            $advertisingPackage->setAccommodation($accommodation);
            $advertisingPackage->setStatus(0);

            $em->persist($advertisingPackage);
            $em->flush();

            return $this->redirect($this->generateUrl('app_profile'));
        }

		return $this->render('AppBundle:Profile/Steps:step7.html.twig', array(
            'accommodation' => $accommodation,
            'form'          => $form->createView(),
            'typeHome'      => $typeHome
        ));
	}



	private function newAccommodationEmail($accommodation) {
		try {
			$fee = $this->get('accommodationService')->getLastFee($accommodation);

			$message = \Swift_Message::newInstance()
				->setSubject('Best-destination.com – '.$accommodation->getName().' has been registered on best-destination.com')
				->setFrom($this->get('emailService')->getSiteEmail())
				->setTo($accommodation->getUser()->getEmail())
				->setBody($this->renderView(
					'AppBundle:Emails:accommodation_new.html.twig',
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
