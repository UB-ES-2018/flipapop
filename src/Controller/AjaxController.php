<?php
/**
 * Created by PhpStorm.
 * User: carlesmagallon
 * Date: 13/11/2018
 * Time: 16:14
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AjaxController extends AbstractController
{
    /**
     * @Route("/producto/visibilidad", name="ajax_producto_visibilidad", options={"expose"=true})
     */
    public function cambiarVisibilidadProducto(Request $request){

        $productID = $request->get('Product');
        $visibility = $request->get('Visibility');
        $Product = $this->getDoctrine()->getManager()->getRepository('App:Product')->find($productID);
        $Product->setVisibility($visibility);
    }

}