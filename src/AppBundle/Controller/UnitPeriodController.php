<?php

namespace AppBundle\Controller;


use AppBundle\Entity\UnitPeriod;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\PeriodType;


class UnitPeriodController extends Controller {

    /**
     * New Period for unit
     * @param Request $request
     * @param $unitId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function newAction(Request $request, $unitId) {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $unit = $em->getRepository('AppBundle:Unit')->find($unitId);
        $period = new UnitPeriod();

        $form = $this->createForm(new PeriodType($unit), $period);
        $form->handleRequest($request);

        if($form->isValid()) {
            $amount = $form->getData()->getAmount();
            $sign = $request->request->get('sign');

            $period->setUnit($unit);
            $period->setAmount(intval($sign . $amount));
            $em->persist($period);
            $em->flush();

            $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('create_success'));
        } else {
            $session->getFlashBag()->add('msgError', (string)$form->getErrors(true, false));
        }

        return $this->redirect($this->generateUrl('app_unit_edit', array(
            'id' => $unitId
        )));
    }

    /**
     * Delete period
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $period = $em->getRepository('AppBundle:UnitPeriod')->find($id);

        $session = $request->getSession();

        $em->remove($period);
        $em->flush();
        $session->getFlashBag()->add('msgSuccess', $this->get('translator')->trans('delete_success'));

        return $this->redirect($this->generateUrl('app_unit_edit', array(
            'id' => $period->getUnit()->getId()
        )));
    }

}