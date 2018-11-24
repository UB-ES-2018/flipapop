<?php

namespace App\Controller;


use App\Entity\Product;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AjaxController extends AbstractController
{
    /**
     *
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
  
    /**  
     *
     * @Route("/like/product", name="ajax_like_product", options={"expose"=true})
     */
    public function likeProduct(Request $request){
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        $id = $request->request->get('id');
        $product = $em->getRepository(Product::class)->find($id);

        if ($request->isXmlHttpRequest()){
            if($product->isLikedBy($user)){
                $product->removeLikedUser($user);
            }else{
                $product->addLikedUser($user);
            }
            $em->persist($product);
            $em->flush();
            return new JsonResponse(['liked' => $product->isLikedBy($user), "nlikes" => $product->getLikedUsers()->count()], 200);
        }
        return $this->redirectToRoute('landing_page');
    }

    /**
     *
     * @Route("/sell/product", name="ajax_sell_product", options={"expose"=true})
     */
    public function sellProduct(Request $request){
        $em = $this->getDoctrine()->getManager();
        
        $id = $request->request->get('id');
        $product = $em->getRepository(Product::class)->find($id);
        $product->changeSold();
        $em->persist($product);
        $em->flush();

        return new JsonResponse(['sold' => $product->getSold()], 200);

    }

}