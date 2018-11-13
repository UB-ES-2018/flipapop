<?php
/**
 * Created by PhpStorm.
 * User: carlesmagallon
 * Date: 13/11/2018
 * Time: 16:14
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AjaxController extends AbstractController
{
    /**
     * @Route("/producto/visibilidad", name="ajax_producto_visibilidad", options={"expose"=true})
     */
    public function cambiarVisibilidadProducto(Request $request){

        $productID = $request->request->all()['id'];
        $visibility = $request->request->all()['visibility'];
        $Product = $this->getDoctrine()->getManager()->getRepository('App:Product')->find($productID);
        $Product->setVisibility($visibility);
        $em = $this->getDoctrine()->getManager();
        $em->persist($Product);
        $em->flush();

        return new JsonResponse(['visibility' => $visibility],200);
    }

}