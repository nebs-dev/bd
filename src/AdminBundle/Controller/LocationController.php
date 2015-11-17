<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\Country;
use AppBundle\Entity\Region;
use AppBundle\Entity\Subregion;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AdminBundle\Form\CountryType;
use AdminBundle\Form\RegionType;
use AdminBundle\Form\CityType;
use AdminBundle\Form\SubregionType;

class LocationController extends Controller {

    public function indexAction() {

        return $this->render('AdminBundle:Location:index.html.twig');
    }

    /**
     * Countries list
     * @return Response
     */
    public function countriesAction() {
        $em = $this->getDoctrine()->getManager();
        $countries = $em->getRepository('AppBundle:Country')->findAll();

        return $this->render('AdminBundle:Location/Country:list.html.twig', array('countries' => $countries));
    }

    /**
     * New country
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function countriesNewAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $country = new Country();

        $form = $this->createForm(new CountryType(), $country);
        $form->handleRequest($request);

        if($form->isValid()) {
            $country->upload();
            $em->persist($country);
            $em->flush();

            $session = $request->getSession();
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('create_success'));

            return $this->redirect($this->generateUrl('admin_location_countries'));
        }

        return $this->render('AdminBundle:Location/Country:new.html.twig', array('form' => $form->createView()));

    }

	/**
	 * Edit country
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
	 */
	public function countriesEditAction(Request $request, $id) {
		$em = $this->getDoctrine()->getManager();
		$country = $em->getRepository('AppBundle:Country')->find($id);

		$form = $this->createForm(new CountryType(), $country);
		$form->handleRequest($request);

		if($form->isValid()) {
            $country->upload();
			$em->persist($country);
			$em->flush();

			$session = $request->getSession();
			$session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('edit_success'));

			return $this->redirect($this->generateUrl('admin_location_countries_edit', array('id' => $id)));
		}

		return $this->render('AdminBundle:Location/Country:edit.html.twig', array(
            'form' => $form->createView(),
            'country' => $country
        ));

	}

    /**
     * Delete Country
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function countriesDeleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $country = $em->getRepository('AppBundle:Country')->find($id);

        $session = $request->getSession();

        $em->remove($country);
        $em->flush();
        $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('delete_success'));

        return $this->redirect($this->generateUrl('admin_location_countries'));
    }

    /**
     * Regions list
     * @return Response
     */
    public function regionsAction() {
        $em = $this->getDoctrine()->getManager();
        $regions = $em->getRepository('AppBundle:Region')->getAll();

        return $this->render('AdminBundle:Location/Region:list.html.twig', array('regions' => $regions));
    }

    /**
     * New region
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function regionsNewAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $region = new \AppBundle\Entity\Region();
        $country = $em->getRepository('AppBundle:Country');

        $form = $this->createForm(new RegionType($country, $request->getLocale()), $region);
        $form->handleRequest($request);

        if($form->isValid()) {
            $region->upload();
            $em->persist($region);
            $em->flush();

            $session = $request->getSession();
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('create_success'));

            return $this->redirect($this->generateUrl('admin_location_regions'));
        }

        return $this->render('AdminBundle:Location/Region:new.html.twig', array('form' => $form->createView()));
    }

	/**
	 * Edit region
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
	 */
	public function regionsEditAction(Request $request, $id) {
		$em = $this->getDoctrine()->getManager();
		$region = $em->getRepository('AppBundle:Region')->find($id);
		$country = $em->getRepository('AppBundle:Country');

		$form = $this->createForm(new RegionType($country, $request->getLocale()), $region);
		$form->handleRequest($request);

		if($form->isValid()) {
            $region->upload();
			$em->persist($region);
			$em->flush();

			$session = $request->getSession();
			$session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('edit_success'));

			return $this->redirect($this->generateUrl('admin_location_regions_edit', array('id' => $id)));
		}

		return $this->render('AdminBundle:Location/Region:edit.html.twig', array(
            'form' => $form->createView(),
            'region' => $region
        ));
	}

    /**
     * Delete Region
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function regionsDeleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $region = $em->getRepository('AppBundle:Region')->find($id);

        $session = $request->getSession();

        $em->remove($region);
        $em->flush();
        $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('delete_success'));

        return $this->redirect($this->generateUrl('admin_location_regions'));
    }

	/**
	 * Subregions list
	 * @return Response
	 */
	public function subregionsAction(Region $region) {
		$em = $this->getDoctrine()->getManager();
		$subregions = $em->getRepository('AppBundle:Subregion')->getByRegion($region->getId());

		return $this->render('AdminBundle:Location/Subregion:list.html.twig', array('subregions' => $subregions));
	}

    /**
     * Subregions search
     * @return Response
     */
    public function subregionsSearchAction(Request $request) {
        # If form is submited
        if($request->request->get('subregion')) {
            $location = preg_replace('/^([^,]*).*$/', '$1', trim($request->request->get('subregion')));
            $subregionObj = $this->get('locationService')->getLocationObject('subregion', $location);

            if($subregionObj) {
                return $this->redirect($this->generateUrl('admin_location_subregions_edit', array('id' => $subregionObj->getId())));
            }
        }

        return $this->render('AdminBundle:Location/Subregion:search.html.twig');
    }

	/**
	 * New subregion
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
	 */
	public function subregionsNewAction(Request $request) {
		$em = $this->getDoctrine()->getManager();
		$subregion = new \AppBundle\Entity\Subregion();
		$region = $em->getRepository('AppBundle:Region');
        $countries = $em->getRepository('AppBundle:Country')->findAll();

		$form = $this->createForm(new SubregionType($region->findAll(), $request->getLocale()), $subregion);
		$form->handleRequest($request);

		if($form->isValid()) {
            $subregion->upload();
			$em->persist($subregion);
			$em->flush();

			$session = $request->getSession();
			$session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('create_success'));

			return $this->redirect($this->generateUrl('admin_location_subregions_search'));
		}

		return $this->render('AdminBundle:Location/Subregion:new.html.twig', array(
            'form' => $form->createView(),
            'countries' => $countries
        ));
	}

	/**
	 * New subregion
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
	 */
	public function subregionsEditAction(Request $request, $id) {
		$em = $this->getDoctrine()->getManager();
		$subregion = $em->getRepository('AppBundle:Subregion')->find($id);
		$region = $em->getRepository('AppBundle:Region');
        $countries = $em->getRepository('AppBundle:Country')->findAll();
        $regions = $region->getByCountry($subregion->getRegion()->getCountry()->getId());

		$form = $this->createForm(new SubregionType($regions, $request->getLocale()), $subregion);
		$form->handleRequest($request);

		if($form->isValid()) {
            $subregion->upload();
			$em->persist($subregion);
			$em->flush();

			$session = $request->getSession();
			$session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('edit_success'));

			return $this->redirect($this->generateUrl('admin_location_subregions_edit', array('id' => $id)));
		}

		return $this->render('AdminBundle:Location/Subregion:edit.html.twig', array(
            'form' => $form->createView(),
            'countries' => $countries,
            'subregion' => $subregion,
            'regions'   => $regions
        ));
	}

    /**
     * Delete Subregion
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function subregionsDeleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $region = $em->getRepository('AppBundle:Subregion')->find($id);

        $session = $request->getSession();

        $em->remove($region);
        $em->flush();
        $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('delete_success'));

        return $this->redirect($this->generateUrl('admin_location_subregions'));
    }

    /**
     * Cities search
     * @return Response
     */
    public function citiesSearchAction(Request $request) {
        # If form is submited
        if($request->request->get('city')) {
            $location = preg_replace('/^([^,]*).*$/', '$1', trim($request->request->get('city')));
            $cityObj = $this->get('locationService')->getLocationObject('city', $location);

            if($cityObj) {
                return $this->redirect($this->generateUrl('admin_location_cities_edit', array('id' => $cityObj->getId())));
            }
        }

        return $this->render('AdminBundle:Location/City:search.html.twig');
    }

    /**
     * Get cities by Region
     * @param Region $region
     * @return Response
     */
    public function citiesRegionAction(Region $region) {
        $em = $this->getDoctrine()->getManager();
        $cities = $em->getRepository('AppBundle:City')->getByRegion($region->getId());

        return $this->render('AdminBundle:Location/City:list.html.twig', array('cities' => $cities));
    }

    /**
     * Get cities by Subregion
     * @param Region $subregion
     * @return Response
     */
    public function citiesSubregionAction(Subregion $subregion) {
        $em = $this->getDoctrine()->getManager();
        $cities = $em->getRepository('AppBundle:City')->getBySubregion($subregion->getId());

        return $this->render('AdminBundle:Location/City:list.html.twig', array('cities' => $cities));
    }

    /**
     * New city
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function citiesNewAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $city = new \AppBundle\Entity\City();
        $subregion = $em->getRepository('AppBundle:Subregion');
        $countries = $em->getRepository('AppBundle:Country')->findAll();

        $form = $this->createForm(new CityType($subregion->findAll(), $request->getLocale()), $city);
        $form->handleRequest($request);

        if($form->isValid()) {
            $city->upload();
            $em->persist($city);
            $em->flush();

            $session = $request->getSession();
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('create_success'));

            return $this->redirect($this->generateUrl('admin_location_cities_search'));
        }

        return $this->render('AdminBundle:Location/City:new.html.twig', array(
            'form' => $form->createView(),
            'countries' => $countries
        ));
    }

    /**
     * Edit city
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function citiesEditAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $subregion = $em->getRepository('AppBundle:Subregion');
        $city = $em->getRepository('AppBundle:City')->find($id);
        $countries = $em->getRepository('AppBundle:Country')->findAll();
        $subregions = $subregion->getByRegion($city->getRegion()->getId());
//        $subregions = $subregion->findAll();

        $form = $this->createForm(new CityType($subregions, $request->getLocale()), $city);

//        if($request->request->get('subregion') != '') {
        $data = $form->getData();

//            echo'<pre>';
//            exit(\Doctrine\Common\Util\Debug::dump($request->request->all('city[subregion]')));
//            echo'</pre>';
//        }

        $form->handleRequest($request);

        if($form->isValid()) {

            $city->upload();
            $em->persist($city);
            $em->flush();

            $session = $request->getSession();
            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('edit_success'));

            return $this->redirect($this->generateUrl('admin_location_cities_edit', array('id' => $id)));
        }

        return $this->render('AdminBundle:Location/City:edit.html.twig', array(
            'form' => $form->createView(),
            'countries' => $countries,
            'city'      => $city
        ));
    }

    /**
     * Delete Region
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function citiesDeleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $city = $em->getRepository('AppBundle:City')->find($id);

        $session = $request->getSession();

        $em->remove($city);
        $em->flush();
        $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('delete_success'));

        return $this->redirect($this->generateUrl('admin_location_cities'));
    }

}
