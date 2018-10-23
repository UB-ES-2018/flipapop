<?php

namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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

        if(!is_null($searchData)){
            $products = $finder->find($searchData);
        }else{
            $products = $this->getDoctrine()->getManager()->getRepository('App: Product')-> findAll();
        }

        return $this->render('landingPage.html.twig', ['products' => $products]);
    }

}