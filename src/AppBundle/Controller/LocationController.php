<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class LocationController extends Controller {

    /**
     * Get regions by country AJAX
     * @param Request $request
     * @return Response
     */
    public function getRegionsByCountryAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $countryId = $request->get('countryId');
        $type = $request->get('type');
        $typeClass = $request->get('typeClass');

        $regions = $em->getRepository('AppBundle:Region')->getByCountry($countryId);

        $response = $this->render('AppBundle:Location/ajax:regions.html.twig', array(
            'regions'  => $regions,
            'type'     => $type,
            'typeClass'=> $typeClass
        ))->getContent();

        return new Response(json_encode($response));
    }

    /**
     * Get subregions by region AJAX
     * @param Request $request
     * @return Response
     */
    public function getSubegionsByRegionAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $regionId = $request->get('regionId');
        $type = $request->get('type');
        $typeClass = $request->get('typeClass');

        $subregions = $em->getRepository('AppBundle:Subregion')->getByRegion($regionId);

        $response = $this->render('AppBundle:Location/ajax:subregions.html.twig', array(
            'subregions'  => $subregions,
            'type'        => $type,
            'typeClass'=> $typeClass
        ))->getContent();

        return new Response(json_encode($response));
    }

    /**
     * Get cities by subregion AJAX
     * @param Request $request
     * @return Response
     */
    public function getCitiesBySubregionAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $subregionId = $request->get('subregionId');
        $type = $request->get('type');
        $typeClass = $request->get('typeClass');

        $cities = $em->getRepository('AppBundle:City')->getBySubregion($subregionId);

        $response = $this->render('AppBundle:Location/ajax:cities.html.twig', array(
            'cities'  => $cities,
            'type'    => $type,
            'typeClass'=> $typeClass
        ))->getContent();

        return new Response(json_encode($response));
    }

    /**
     * Get cities by subregion AJAX
     * @param Request $request
     * @return Response
     */
    public function getCitiesByRegionAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $type = $request->get('type');
        $typeClass = $request->get('typeClass');

        $cities = $em->getRepository('AppBundle:City')->getByRegion($id);

        $response = $this->render('AppBundle:Location/ajax:cities.html.twig', array(
            'cities'  => $cities,
            'type'    => $type,
            'typeClass'=> $typeClass
        ))->getContent();

        return new Response(json_encode($response));
    }

}