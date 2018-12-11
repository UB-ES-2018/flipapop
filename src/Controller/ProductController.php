<?php
/**
 * Created by PhpStorm.
 * User: luna
 * Date: 13/10/18
 * Time: 16:34
 */

namespace App\Controller;


use App\Entity\Product;
use App\Form\Type\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     *
     * @Route("/product/new", name="new_product")
     */
    public function newProduct(Request $request){

        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $em = $this->getDoctrine()->getManager();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user = $this->getUser();
            $product->setUsuario($user);
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('user_profile', ['tab' => 'products']);

        }


        return $this->render('profile.html.twig',
            array(
                'form' => $form->createView(),
                'user' => $this->getUser(),
                'tab' => 'new'
            ));
    }

    /**
     * @param Request $request
     * @param $idProducto
     * @Route("/product/{idProducto}", name="view_product")
     */
    public function viewProduct(Request $request, $idProducto){
        if(is_null($idProducto)){
            // TODO: Excepciones bonitas
            return new Exception();
        }
        $em = $this->getDoctrine()->getManager();
        $producto = $em->getRepository(Product::class)->find($idProducto);

        if(is_null($producto)){
            // TODO: Excepciones bonitas
            return new Exception();
        }

        if(!$producto->canView($this->getUser())){
            return new Exception();
        }

        $comentarios = $em->getRepository('App:ComentarioProducto')->findBy(['product' => $producto], ["fechaCreacion" => "ASC"]);
        $comentarios = $this->orderCommentsForView($comentarios);
        return $this->render('viewProduct.html.twig', array(
            'comentarios' => $comentarios,
            'product' => $producto
        ));

    }


    private function orderCommentsForView($comentarios){
        $result = [];
        foreach($comentarios as $comentario){
            if(is_null($comentario->getPadre())){
                $result[$comentario->getId()] = ["comment" => $comentario, "sons" => []];
            }else{
                $result[$comentario->getPadre()]["sons"][]= $comentario;
            }

        }

        return $result;
    }

}