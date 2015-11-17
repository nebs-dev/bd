<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\Currency;
use AppBundle\Entity\Subregion;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Country;
use AppBundle\Entity\Region;
use AppBundle\Entity\City;


class ParseController extends Controller {

    /**
     * @return Response
     */
    public function countriesAction() {
        die('nope');
        $em = $this->getDoctrine()->getManager();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://api.geonames.org/countryInfoJSON?username=nebs');
        http://api.geonames.org/countryInfoJSON?username=nebs&lang=hr
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json')); // Assuming you're requesting JSON
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        // If using JSON...
        $data = json_decode($response);

        foreach($data->geonames as $country) {
            $name = $country->countryName;
            $code = $country->countryCode;

            $countryObj = new Country();
            $countryObj->setName($name);
            $countryObj->setCode($code);

            $em->persist($countryObj);
            $em->flush();
        }

        return $this->render('AdminBundle::parse.html.twig', array(
            'data' => $data->geonames
        ));
    }


    public function regionsAction() {
        die('nope');
//        http://ws.geonames.org/search?q=&country=HR&fclass=A&username=nebs&fcode=ADM1
        $em = $this->getDoctrine()->getManager();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://ws.geonames.org/searchJSON?q=&country=HR&fclass=A&username=nebs&fcode=ADM1&maxRows=1000');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json')); // Assuming you're requesting JSON
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        // If using JSON...
        $data = json_decode($response);

        foreach($data->geonames as $region) {
            $country = $em->getRepository('AppBundle:Country')->findOneByName($region->countryName);

            $name = $region->name;
            $geonameId = $region->geonameId;

            $regionObj = new Region();
            $regionObj->setName($name);
            $regionObj->setGeonameId($geonameId);
            $regionObj->setCountry($country);

            $em->persist($regionObj);
            $em->flush();
        }

        return $this->render('AdminBundle::parse.html.twig', array(
            'data' => $data->geonames
        ));
    }


    public function citiesAction() {
        die('nope');
        $em = $this->getDoctrine()->getManager();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://ws.geonames.org/searchJSON?q=&country=HR&fclass=P&username=nebs&maxRows=1000');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json')); // Assuming you're requesting JSON
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        // If using JSON...
        $data = json_decode($response);

        $responseTest = array();
        foreach($data->geonames as $city) {
            $country = $em->getRepository('AppBundle:Country')->findOneByName($city->countryName);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://api.geonames.org/hierarchyJSON?geonameId='.$city->geonameId.'&username=nebs&fclas=A');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json')); // Assuming you're requesting JSON
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $response = curl_exec($ch);

            // If using JSON...
            $hierarchyData = json_decode($response);

            foreach($hierarchyData->geonames as $hierar) {
                if($hierar->fcode == 'ADM1') {
                    $region = $em->getRepository('AppBundle:Region')->findOneByName($hierar->name);

                    $responseTest[] = $region;
                    $cityObj = new City();
                    $cityObj->setName($city->name);
                    $cityObj->setRegion($region);

                    $em->persist($cityObj);
                    $em->flush();
                }
            }
        }


        return $this->render('AdminBundle::parse.html.twig', array(
            'data' => $responseTest
        ));

    }


    public function rawSqlAction($from, $to) {
        die('nope');
        $em = $this->getDoctrine()->getManager();

        $file = __DIR__ . '/../../../Norveska.csv';
        $csv = array_map('str_getcsv', file($file));

        $country = $em->getRepository('AppBundle:Country')->findOneByEn('Norway');
        $countryId = $country->getId();

        if($country) {
            try {
                $i = 0;
                $count = 0;
                foreach ($csv as $c) {
                    if ($c[0] != 'Mjesto' && ($i >= $from && $i <= $to)) {
                        $regionCSV = $c[2];
                        $subregionCSV = $c[1];
                        $cityCSV = $c[0];

                        # insert region
                        $sqlFindRegion = 'SELECT * FROM regions WHERE en = :regionCSV';
                        $sqlInsertRegion = "INSERT INTO regions (en, country_id) VALUES (:regionCSV, :countryId)";

                        $region = $em->getConnection()->executeQuery($sqlFindRegion, array('regionCSV' => $regionCSV))->fetchAll(\PDO::FETCH_COLUMN);
                        if(count($region) == 0) {
                            $em->getConnection()->executeQuery($sqlInsertRegion, array('regionCSV' => $regionCSV, 'countryId' => $countryId));
                            $regionId = $em->getConnection()->lastInsertId();
                        } else {
                            $regionId = $region[0];
                        }

                        # insert subregion
                        $sqlFindSubregion = "SELECT * FROM subregions WHERE en = :subregionCSV";
                        $sqlInsertSubregion = "INSERT INTO subregions (en, region_id) VALUES (:subregionCSV, :regionId)";

                        $subregion = $em->getConnection()->executeQuery($sqlFindSubregion, array('subregionCSV' => $subregionCSV))->fetchAll(\PDO::FETCH_COLUMN);

                        if($subregionCSV != '') {
                            if (count($subregion) == 0) {
                                $em->getConnection()->executeQuery($sqlInsertSubregion, array('subregionCSV' => $subregionCSV, 'regionId' => $regionId));
                                $subregionId = $em->getConnection()->lastInsertId();
                            } else {
                                $subregionId = $subregion[0];
                            }
                        } else {
                            $subregionId = NULL;
                        }


                        # insert city
                        $sqlFindCity = "SELECT * FROM cities WHERE en = :cityCSV";
                        $sqlInsertCity = "INSERT INTO cities (en, region_id, subregion_id) VALUES (:cityCSV, :regionId, :subregionId)";

                        $city = $em->getConnection()->executeQuery($sqlFindCity, array('cityCSV' => $cityCSV))->fetchAll(\PDO::FETCH_COLUMN);
                        if(count($city) == 0) {
                            $em->getConnection()->executeQuery($sqlInsertCity, array('cityCSV' => $cityCSV, 'regionId' => $regionId, 'subregionId' => $subregionId));
                        }

                        $count++;
                    }

                    $i++;
                }
            } catch (\Exception $e) {
                return new Response($e);
            }
        }

        echo'<pre>';
        exit(\Doctrine\Common\Util\Debug::dump($count));
        echo'</pre>';
    }


	function parseCSVAction($from, $to) {
		die('nope');
		$em = $this->getDoctrine()->getManager();

		$file = __DIR__ . '/../../../_Njemacka.csv';
//		$file = __DIR__ . '/../../../regions_subregions.csv';
//		$file = __DIR__ . '/../../../regions_subregions_city2.csv';
		$csv = array_map('str_getcsv', file($file));

//		$csv = str_getcsv(file_get_contents(__DIR__ . '/../../../regions_subregions_city.csv'));


        $country = $em->getRepository('AppBundle:Country')->findOneByEn('Germany');

        if($country) {
            try {
                $i = 0;
                $count = 0;
                foreach ($csv as $c) {
                    if ($c[0] != 'GRAD' && ($i >= $from && $i <= $to)) {
                        $regionCSV = $c[1];
                        $region = $em->getRepository('AppBundle:Region')->findOneByEn($regionCSV);
                        if (!$region) {
                            $region = new Region();
                            $region->setCountry($country);
                            $region->setEn($c[1]);
//                            $region->setDe($c[2]);

                            # Depends on country
//                            $region->setIt($c[2]);

                            $em->persist($region);
                            $em->flush();
                        }

                        $subregion = false;
//                        $subregionCSV = $c[1];
//                        $subregion = $em->getRepository('AppBundle:Subregion')->findOneByEn($subregionCSV);
//                        if (!$subregion) {
//                            $subregion = new Subregion();
//                            $subregion->setRegion($region);
//                            $subregion->setEn($c[1]);
//                            $em->persist($subregion);
//                            $em->flush();
//                        }

                        $cityCSV = $c[0];
                        $city = $em->getRepository('AppBundle:City')->findOneByEn($cityCSV);
                        if (!$city) {
                            $city = new City();
                            $city->setRegion($region);
                            $city->setEn($cityCSV);
                            if ($subregion) {
                                $city->setSubregion($subregion);
                            }
                            $em->persist($city);
                            $em->flush();
                        }

                        $count++;
                    }

                    $i++;
                }
            } catch (\Exception $e) {
                return new Response($e);
            }
        }

        return new Response($count);

//		echo'<pre>';print_r($csv);echo'</pre>';
	}


	public function updateCSVAction() {
		die('nope');
		$em = $this->getDoctrine()->getManager();
		$file = __DIR__ . '/../../../hrvatske_regije.csv';
		$csv = array_map('str_getcsv', file($file));

		foreach($csv as $c) {
			if($c[0] == 'Zemlja') {
				$countryObj = $em->getRepository('AppBundle:Country')->getByHr($c[1]);
				$countryObj->setEn($c[2]);
				$countryObj->setDe($c[3]);
				$countryObj->setIt($c[4]);
				$countryObj->setEs($c[5]);
				$countryObj->setFr($c[6]);
				$countryObj->setCs($c[7]);
				$countryObj->setSl($c[8]);
				$countryObj->setPl($c[9]);
				$countryObj->setHu($c[10]);
				$countryObj->setRu($c[11]);
				$countryObj->setPt($c[12]);
				$countryObj->setNl($c[13]);
				$countryObj->setDa($c[14]);
				$countryObj->setSv($c[15]);
				$countryObj->setSk($c[16]);
				$em->persist($countryObj);
				$em->flush();

			} elseif($c[0] == 'Regija') {
				$regionObj = $em->getRepository('AppBundle:Region')->getByHr($c[1]);
				$regionObj->setEn($c[2]);
				$regionObj->setDe($c[3]);
				$regionObj->setIt($c[4]);
				$regionObj->setEs($c[5]);
				$regionObj->setFr($c[6]);
				$regionObj->setCs($c[7]);
				$regionObj->setSl($c[8]);
				$regionObj->setPl($c[9]);
				$regionObj->setHu($c[10]);
				$regionObj->setRu($c[11]);
				$regionObj->setPt($c[12]);
				$regionObj->setNl($c[13]);
				$regionObj->setDa($c[14]);
				$regionObj->setSv($c[15]);
				$regionObj->setSk($c[16]);
				$em->persist($regionObj);
				$em->flush();

			} elseif($c[0] == '') {
				$subregion = $em->getRepository('AppBundle:Subregion')->getByHr($c[1]);
				$subregion->setEn($c[2]);
				$subregion->setDe($c[3]);
				$subregion->setIt($c[4]);
				$subregion->setEs($c[5]);
				$subregion->setFr($c[6]);
				$subregion->setCs($c[7]);
				$subregion->setSl($c[8]);
				$subregion->setPl($c[9]);
				$subregion->setHu($c[10]);
				$subregion->setRu($c[11]);
				$subregion->setPt($c[12]);
				$subregion->setNl($c[13]);
				$subregion->setDa($c[14]);
				$subregion->setSv($c[15]);
				$subregion->setSk($c[16]);
				$em->persist($subregion);
				$em->flush();
			}

		}

		echo'<pre>';print_r($csv);echo'</pre>';
	}


	public function parseXMLAction() {
		//This is a PHP(4/5) script example on how eurofxref-daily.xml can be parsed
		//Read eurofxref-daily.xml file in memory
		//For this command you will need the config option allow_url_fopen=On (default)
		$XMLContent=file("http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml");
		//the file is updated daily between 2.15 p.m. and 3.00 p.m. CET

		$em = $this->getDoctrine()->getManager();

		foreach($XMLContent as $line){
			if(preg_match("/currency='([[:alpha:]]+)'/",$line,$currencyCode)){
				if(preg_match("/rate='([[:graph:]]+)'/",$line,$rate)){
					//Output the value of 1EUR for a currency code

					//echo'1&euro;='.$rate[1].' '.$currencyCode[1].'<br/>';

					//--------------------------------------------------
					//Here you can add your code for inserting
					//$rate[1] and $currencyCode[1] into your database
					//--------------------------------------------------

					echo $rate[1].' '.$currencyCode[1].'<br/>';

					//$currency = new Currency();
					$currency = $em->getRepository('AppBundle:Currency')->getByCode($currencyCode[1]);

					//$currency->setCode($currencyCode[1]);
					$currency->setRate($rate[1]);
					$em->persist($currency);
					$em->flush();
				}
			}
		}

		return new Response('OK');

		//$ip = $this->container->get('request')->getClientIp();
		//$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
		////echo $details->city; // -> "Mountain View"
		//
		//echo $ip;
		//echo'<pre>';print_r($details);echo'</pre>';
	}


    public function newParseCountry() {

    }

}