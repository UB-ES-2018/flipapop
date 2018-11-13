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

}