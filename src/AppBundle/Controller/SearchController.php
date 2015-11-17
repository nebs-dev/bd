<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Form\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends Controller {

	/**
	 * @return Response
	 */
	public function searchPageAction() {
        /**
         * TODO: fix missing variable error
         */



		$em = $this->getDoctrine()->getManager();
		$accommodationCategories = $em->getRepository('AppBundle:AccommodationCategory')->findAll();

		$searchForm = $this->createForm(new SearchType($accommodationCategories));
		return $this->render('AppBundle:Search:searchResult.html.twig', array(
			'searchForm' => $searchForm->createView()
		));
	}

	/**
	 * Search action
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
	 */
	public function searchAction(Request $request) {
		$em               = $this->getDoctrine()->getManager();
		$accommodationRep = $em->getRepository('AppBundle:Accommodation');
		$accommodationCategories = $em->getRepository('AppBundle:AccommodationCategory')->findAll();

		$searchForm       = $this->createForm(new SearchType($accommodationCategories));

		if($request->isMethod('POST')) {

			$searchForm->handleRequest($request);

			if($searchForm->isValid()) {
				# Array with form fields
				$data = $searchForm->getData();

//                echo'<pre>';
//                exit(\Doctrine\Common\Util\Debug::dump($data));
//                echo'</pre>';

                # Remove everything after first comma in location
                $location = preg_replace('/^([^,]*).*$/', '$1', trim($data['location']));
                $data['location'] = $location;
                # Requested page
                $data['page'] = $request->request->get('page');

                # Location type for banners && additional offers && search
                $locationType = $this->get('locationService')->getLocationType($location);
                # location Object
                $locationObj = $this->get('locationService')->getLocationObject($locationType, $location);

                # Additional offers
                $additionalOffers = $em->getRepository('AppBundle:AdditionalOffer\AdditionalOffer')->getByLocation($location, $locationType);
                shuffle($additionalOffers);
                # Banners
                $banners = $em->getRepository('AppBundle:Banner')->getBanners($request->getLocale(), $locationType, $locationObj);
                shuffle($banners);

//                echo'<pre>';
//                exit(\Doctrine\Common\Util\Debug::dump($banners));
//                echo'</pre>';

                # Discover
                $discover = $em->getRepository('AppBundle:Discover')->findAll();
                shuffle($discover);

                # Header photo
                $headerPhoto = $this->get('locationService')->getLocationPhoto($locationType, $locationObj);

				$accommodations = $accommodationRep->search($data, $locationType, $locationObj);
                $mapJSON = $this->get('accommodationService')->getJSONMap($accommodations);

                # Number of result pages
                $numPages = intval(ceil(count($accommodations) / 22));
                # Current page
                $currentPage = $this->get('accommodationService')->getCurrentPaginationPage($data['page'], $numPages);

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

			$session = $request->getSession();
			$session->getFlashBag()->add('formErrors', (string)$searchForm->getErrors(true, false));
		}

		return $this->redirect($this->generateUrl('app_search_page'));
	}

	/**
	 * Locations for search autocomplete AJAX
	 * @return Response
	 */
	public function searchAutocompleteLocationAction() {
		$em = $this->getDoctrine()->getManager();
		
		$countries  = $em->getRepository('AppBundle:Country')->searchCountryByString($_POST['query']);
		$regions    = $em->getRepository('AppBundle:Region')->searchRegionByString($_POST['query']);
		$subregions = $em->getRepository('AppBundle:Subregion')->searchSubregionByString($_POST['query']);
		$cities     = $em->getRepository('AppBundle:City')->searchCityByString($_POST['query']);

		$suggestions = array();

		# Countries
		foreach($countries as $country) {
			$suggestions[] = array(
				"value" => $country->getEn(),
				"data"  => $country->getEn()
			);
		}
		# Regions
		foreach($regions as $region) {
            $country = $region->getCountry()->getEn();

			$suggestions[] = array(
				"value" => $region->getEn().', '.$country,
				"data"  => $region->getEn().', '.$country
			);
		}
		# Subregions
		foreach($subregions as $subregion) {
            $region = $subregion->getRegion()->getEn();
            $country = $subregion->getRegion()->getCountry()->getEn();

			$suggestions[] = array(
				"value" => $subregion->getEn().', '.$region.', '.$country,
				"data"  => $subregion->getEn().', '.$region.', '.$country
			);
		}
		# Cities
		foreach($cities as $city) {
            $subregion = ($city->getSubregion()) ? $city->getSubregion()->getEn() : '';
            $region = $city->getRegion()->getEn();
            $country = $city->getRegion()->getCountry()->getEn();

            $subregionString = ($subregion != '') ? $subregion . ', ' : '';

			$suggestions[] = array(
				"value" => $city->getEn() .', '. $subregionString .$region.', '.$country,
				"data"  => $city->getEn() .', '.$subregion.', '.$region.', '.$country
			);
		}
		return new Response(json_encode(array('suggestions' => $suggestions)));
	}

    /**
     * Countries for search autocomplete AJAX
     * @return Response
     */
    public function searchAutocompleteCountriesAction() {
        $em = $this->getDoctrine()->getManager();

        $countries  = $em->getRepository('AppBundle:Country')->searchCountryByString($_POST['query']);
        $suggestions = array();

        # Countries
        foreach($countries as $country) {
            $suggestions[] = array(
                "value" => $country->getEn(),
                "data"  => $country->getEn()
            );
        }

        return new Response(json_encode(array('suggestions' => $suggestions)));
    }

    /**
     * Regions for search autocomplete AJAX
     * @return Response
     */
    public function searchAutocompleteRegionsAction() {
        $em = $this->getDoctrine()->getManager();

        $regions    = $em->getRepository('AppBundle:Region')->searchRegionByString($_POST['query']);
        $suggestions = array();

        # Regions
        foreach($regions as $region) {
            $country = $region->getCountry()->getEn();

            $suggestions[] = array(
                "value" => $region->getEn().', '.$country,
                "data"  => $region->getEn().', '.$country
            );
        }

        return new Response(json_encode(array('suggestions' => $suggestions)));
    }

    /**
     * Subregions for search autocomplete AJAX
     * @return Response
     */
    public function searchAutocompleteSubregionsAction() {
        $em = $this->getDoctrine()->getManager();

        $subregions = $em->getRepository('AppBundle:Subregion')->searchSubregionByString($_POST['query']);
        $suggestions = array();

        # Subregions
        foreach($subregions as $subregion) {
            $region = $subregion->getRegion()->getEn();
            $country = $subregion->getRegion()->getCountry()->getEn();

            $suggestions[] = array(
                "value" => $subregion->getEn().', '.$region.', '.$country,
                "data"  => $subregion->getEn().', '.$region.', '.$country
            );
        }

        return new Response(json_encode(array('suggestions' => $suggestions)));
    }

    /**
     * Cities for search autocomplete AJAX
     * @return Response
     */
    public function searchAutocompleteCitiesAction() {
        $em = $this->getDoctrine()->getManager();

        $cities     = $em->getRepository('AppBundle:City')->searchCityByString($_POST['query']);
        $suggestions = array();

        # Cities
        foreach($cities as $city) {
            $subregion = ($city->getSubregion()) ? $city->getSubregion()->getEn() : '';
            $region = $city->getRegion()->getEn();
            $country = $city->getRegion()->getCountry()->getEn();

            $subregionString = ($subregion != '') ? $subregion . ', ' : '';

            $suggestions[] = array(
                "value" => $city->getEn() .', '. $subregionString .$region.', '.$country,
                "data"  => $city->getEn() .', '.$subregion.', '.$region.', '.$country
            );
        }

        return new Response(json_encode(array('suggestions' => $suggestions)));
    }
}