<?php
/**
 * Created by PhpStorm.
 * User: Luna
 * Date: 11/11/2018
 * Time: 15:36
 */

namespace App\Controller;


use App\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WebsiteController extends AbstractController
{
    /**
     *@Route("/sitemap/sitemap.xml", name="sitemap", defaults={"_format"="xml"})
     */
    public function sitemapAction(Request $request)
    {
        // We define an array of urls
        $urls = [];
        // We store the hostname of our website
        $hostname = $request->getHost();

        $urls[] = ['loc' => $this->get('router')->generate('landing_page'), 'changefreq' => 'weekly', 'priority' => '1.0'];

        // Then, we will find all our products stored in the database
        $products = $this->getDoctrine()->getRepository('App:Product')->findAll();

        // We loop on them
        foreach ($products as $product) {
            if($product->getVisibility()==1){
                $urls[] = ['loc' => $this->get('router')->generate('view_product', array('idProducto' => $product->getId())), 'changefreq' => 'weekly', 'priority' => '1.0'];
            }
        }

        // Once our array is filled, we define the controller response
        $response = new Response();
        $response->headers->set('Content-Type', 'xml');

        return $this->render('sitemap.xml.twig',[
            'urls' => $urls,
            'hostname' => $hostname
        ]);
    }
}