<?php

namespace App\Controller;


use App\Entity\ComentarioProducto;
use App\Entity\Product;

use App\Entity\User;
use DateTime;
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
        if($request->isXmlHttpRequest()){
            $productID = $request->request->all()['id'];
            $visibility = $request->request->all()['visibility'];
            $Product = $this->getDoctrine()->getManager()->getRepository('App:Product')->find($productID);
            $Product->setVisibility($visibility);
            $em = $this->getDoctrine()->getManager();
            $em->persist($Product);
            $em->flush();

            return new JsonResponse(['visibility' => $visibility],200);
        }
        return $this->redirectToRoute('landing_page');
    }
  
    /**  
     *
     * @Route("/like/product", name="ajax_like_product", options={"expose"=true})
     */
    public function likeProduct(Request $request)
    {
        if ($request->isXmlHttpRequest()) {

            $em = $this->getDoctrine()->getManager();

            $user = $this->getUser();
            $id = $request->request->get('id');
            $product = $em->getRepository(Product::class)->find($id);

            if(is_null($user)){
                return $this->redirectToRoute('landing_page');
            }

            if ($product->isLikedBy($user)) {
                $product->removeLikedUser($user);
            } else {
                $product->addLikedUser($user);
            }
            $em->persist($product);
            $em->flush();
            return new JsonResponse(['liked' => $product->isLikedBy($user), "nlikes" => $product->getLikedUsers()->count()], 200);
        }

        return $this->redirectToRoute('landing_page');
    }

    /**
     * @param Request $request
     * @Route("/add/comment", name="ajax_add_comment", options={"expose" = true})
     */
    public function addComment(Request $request){
        if($request->isXmlHttpRequest()){
            $idProd = $request->request->get('product');
            $idUser = $request->request->get('user');
            $text  = $request->request->get('text');
            $em = $this->getDoctrine()->getManager();

            $product = $em->getRepository(Product::class)->find($idProd);
            $user = $em->getRepository(User::class)->find($idUser);

            $comment = new ComentarioProducto();
            $comment->setFechaCreacion(new DateTime());
            $comment->setProduct($product);
            $comment->setUsuario($user);
            $comment->setTexto($text);

            $em->persist($comment);
            $em->flush();
            $data = [
                'id' => $comment->getId(),
                'name' => $comment->getUsuario()->getFullUserName(),
                'text' => $comment->getTexto(),
                'datetime' => $comment->getFechaCreacion()->format('Y-m-d H:i'),
            ];
            return new JsonResponse([$data],200);
        }
        return $this->redirectToRoute('landing_page');
    }

    /**
     * @param Request $request
     * @Route("/add/reply", name="ajax_add_reply", options={"expose" = true})
     */
    public function addReply(Request $request){
        if($request->isXmlHttpRequest()){
            $idProd = $request->request->get('product');
            $idUser = $request->request->get('user');
            $text  = $request->request->get('text');
            $parent = $request->request->get('parent');
            $em = $this->getDoctrine()->getManager();

            $product = $em->getRepository(Product::class)->find($idProd);
            $user = $em->getRepository(User::class)->find($idUser);

            $comment = new ComentarioProducto();
            $comment->setFechaCreacion(new DateTime());
            $comment->setProduct($product);
            $comment->setUsuario($user);
            $comment->setTexto($text);
            $comment->setPadre($parent);

            $em->persist($comment);
            $em->flush();
            $data = [
                'id' => $comment->getId(),
                'name' => $comment->getUsuario()->getFullUserName(),
                'text' => $comment->getTexto(),
                'datetime' => $comment->getFechaCreacion()->format('Y-m-d H:i'),
            ];
            return new JsonResponse([$data],200);
        }
        return $this->redirectToRoute('landing_page');
    }

    /**
     *
     * @Route("/sell/product", name="ajax_sell_product", options={"expose"=true})
     */
    public function sellProduct(Request $request){
        if($request->isXmlHttpRequest()){
            $em = $this->getDoctrine()->getManager();

            $id = $request->request->get('id');
            $product = $em->getRepository(Product::class)->find($id);
            $product->changeSold();
            $em->persist($product);
            $em->flush();

            return new JsonResponse(['sold' => $product->getSold()], 200);
        }
        return $this->redirectToRoute('landing_page');
    }

}