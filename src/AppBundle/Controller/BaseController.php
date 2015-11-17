<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Form\SearchType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class BaseController extends Controller {

    /**
     * Home page
     * @return Response
     */
    public function homeAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $accommodationCategories = $em->getRepository('AppBundle:AccommodationCategory')->findAll();
        $discover = $em->getRepository('AppBundle:Discover')->findAll();
        shuffle($discover);

        $aoCategories = $em->getRepository('AppBundle:AdditionalOffer\Category')->findAll();
        $additionalOffers = $em->getRepository('AppBundle:AdditionalOffer\AdditionalOffer')->findAll();
        shuffle($additionalOffers);

        $banners = $em->getRepository('AppBundle:Banner')->getBanners($request->getLocale());
        shuffle($banners);

        $posts = $em->getRepository('AppBundle:Post')->findBy(
            array(),
            array('createdAt' => 'DESC'),
            2
        );

        $accommodations = $em->getRepository('AppBundle:Accommodation')->getAccommodationsHome();
        $searchForm = $this->createForm(new SearchType($accommodationCategories));

        return $this->render('AppBundle:Home:index.html.twig', array(
            'searchForm'        => $searchForm->createView(),
            'discover'          => $discover,
            'aoCategories'      => $aoCategories,
            'additionalOffers'  => $additionalOffers,
            'accommodations'    => $accommodations,
            'posts'             => $posts,
            'banners'           => $banners
        ));
    }


    /**
     * Partner / preregistration
     * @return Response
     */
    public function partnerAction() {
        return $this->render('AppBundle:Home:partner.html.twig' );
    }

    /**
     * contact us page
     * @return Response
     */
    public function contactAction() {
        return $this->render('AppBundle:Home:contact.html.twig' );
    }

    /**
     * about us page
     * @return Response
     */
    public function aboutAction() {
        return $this->render('AppBundle:Home:about.html.twig' );
    }

    /**
     * private policy page
     * @return Response
     */
    public function privateAction() {
        return $this->render('AppBundle:Home:private.html.twig' );
    }

    /**
     * terms of use page
     * @return Response
     */
    public function termsAction() {
        return $this->render('AppBundle:Home:terms.html.twig' );
    }

    /**
     * FAQ page
     * @return Response
     */
    public function faqAction() {
        return $this->render('AppBundle:Home:faq.html.twig' );
    }


	/**
	 * Change currency on page
	 * @param Request $request
	 * @param         $code
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function changeCurrencyAction(Request $request, $code) {
		$this->get("currencyService")->setCurrency($code);
		$request->setLocale($code);

		return $this->redirect($request->headers->get('referer'));
	}

    /**
     * Change language on page
     * @param Request $request
     * @param $code
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function changeLanguageAction(Request $request, $locale) {
        $router = $this->get('router');
        $referer = $request->headers->get('referer');

        // Create URL path to pass it to matcher
        $urlParts = parse_url($referer);
        $basePath = $request->getBaseUrl();
        $path = str_replace($basePath, '', $urlParts['path']);

        // Match route and get it's arguments
        $route = $router->match($path);
        $routeAttrs = array_replace($route, array('_locale' => $locale));
        $routeName = $routeAttrs['_route'];
        unset($routeAttrs['_route']);

        return $this->redirect($router->generate($routeName, $routeAttrs));
    }

    /**
     * Search Accommodations by Discover location
     * @param Request $request
     * @param $location
     * @return Response
     */
    public function discoverSearchAction(Request $request, $location) {
        $em               = $this->getDoctrine()->getManager();
        $session = $request->getSession();

        $accommodationRep = $em->getRepository('AppBundle:Accommodation');
        $accommodationCategories = $em->getRepository('AppBundle:AccommodationCategory')->findAll();

        $searchForm       = $this->createForm(new SearchType($accommodationCategories));

        # Remove everything after first comma in location
        $data['location'] = $location;
        # Requested page
        $data['page'] = ($request->request->get('page')) ? $request->request->get('page') : 1;

        # Location type for banners && additional offers
        $locationType = $this->get('locationService')->getLocationType($location);
        $locationObj = $this->get('locationService')->getLocationObject($locationType, $location);
        # Header photo
        $headerPhoto = $this->get('locationService')->getLocationPhoto($locationType, $locationObj);

        # Additional offers
        $additionalOffers = $em->getRepository('AppBundle:AdditionalOffer\AdditionalOffer')->getByLocation($location, $locationType);
        shuffle($additionalOffers);
        # Banners
        $banners = $em->getRepository('AppBundle:Banner')->getBanners($request->getLocale(), $locationType, $locationObj);
        shuffle($banners);
        # Discover
        $discover = $em->getRepository('AppBundle:Discover')->findAll();
        shuffle($discover);

        $accommodations = $accommodationRep->search($data, $locationType, $locationObj);
        $mapJSON = $this->get('accommodationService')->getJSONMap($accommodations);
        # Number of result pages
        $numPages = intval(count($accommodations) / 22);
        # Current page
        $currentPage = $this->get('accommodationService')->getCurrentPaginationPage($data['page'], $numPages);

        $session->getFlashBag()->add('formErrors', (string) $searchForm->getErrors(true, false));
        return $this->render('AppBundle:Search:searchResult.html.twig', array(
            'accommodations'    => $accommodations,
            'searchForm'        => $searchForm->createView(),
            'additionalOffers'  => $additionalOffers,
            'banners'           => $banners,
            'discover'          => $discover,
            'location'          => $locationObj ? $locationObj : '',
            'numPages'          => $numPages,
            'currentPage'       => $currentPage,
            'mapJSON'           => $mapJSON,
            'headerPhoto'       => $headerPhoto
        ));
    }


    public function mailchimpSubscribeAction() {
        $mailChimp = $this->get('MailChimp');

        $list = $mailChimp->getList();

//        exit(\Doctrine\Common\Util\Debug::dump($mailChimp->getAPIkey()));

        if($list->Subscribe('nebojsa.stojanovic0@gmail.com')) {
            $response = 'TRUE';
        } else {
            $response = 'FALSE';
        }

        return new Response($response);
    }
}
