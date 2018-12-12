<?php

namespace App\Controller;


use function array_merge;
use FOS\ElasticaBundle\Finder\TransformedFinder;
use Symfony\Component\Routing\Annotation\Route;
use function sizeof;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

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

        $categories_selected = [];
        if($request->isMethod("POST") and !$request->get('searchbar') and sizeof($request->request->all()) > 0){
            $products = [];
            foreach ($request->request->all() as $key => $value){
                $prod = $this->getDoctrine()->getManager()->getRepository('App:Product')->findBy(["category" => $value]);
                $products = array_merge($products, $prod);
            }
            $categories_selected = $request->request->all();


        }else{
            $searchData = $request->get('searchbar');
            $products = [];
            if(!is_null($searchData) && $searchData !== ''){
                $products = $finder->find($searchData);

            }
            if(sizeof($products) == 0){
                $products = $this->getDoctrine()->getManager()->getRepository('App:Product')->findBy(['sold'=>false], ['numLikes'=>'DESC']);
            }
        }




        $categories = $this->getDoctrine()->getManager()->getRepository('App:Category')->findAll();

        return $this->render('landingPage.html.twig', ['products' => $products, 'categories' => $categories, 'categoriesSelected'=> $categories_selected]);
    }

}