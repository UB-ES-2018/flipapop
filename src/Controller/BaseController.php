<?php

namespace App\Controller;


use FOS\ElasticaBundle\Finder\TransformedFinder;
use Symfony\Component\Routing\Annotation\Route;
use function sizeof;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BaseController
 * @package App\Controller
 * @Route("/index")
 */
class BaseController extends AbstractController
{
    /**
     * @Route("/", name="landing_page")
     */
    public function index(Request $request, TransformedFinder $finder){

        $searchData = $request->get('searchbar');

        $products = [];
        if(!is_null($searchData) && $searchData !== ''){
            $products = $finder->find($searchData);

        }
        if(sizeof($products) == 0){
            $products = $this->getDoctrine()->getManager()->getRepository('App:Product')->findBy(['sold'=>false], ['numLikes'=>'DESC']);
        }

        return $this->render('landingPage.html.twig', ['products' => $products]);
    }

}