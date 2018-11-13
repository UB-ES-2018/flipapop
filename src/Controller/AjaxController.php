<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AjaxController extends AbstractController
{
    /**
     *
     * @Route("/like/product", name="ajax_like_product")
     */
    public function likeProduct(Request $request){

        $user = $this->getUser();
        $product = $request->request->get('product');
        $em = $this->getDoctrine()->getManager();

        if ($request->isXmlHttpRequest()){
            if($request->request->get('likeProduct')){
                $user->addLikedProduct($product);
                $product->addLikedUser($user);
            } else {
                $user->removeLikedProduct($product);
                $product->removeLikedUser($user);
            }
            return new JsonResponse($product);
        }
        return $this->redirectToRoute('landing_page');
    }

}