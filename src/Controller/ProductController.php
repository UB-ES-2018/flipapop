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
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     *
     *
     * @Route("/product/{idProduct}", name="view_product")
     */

    public function viewProduct(Request $request, $idProduct){

        if (is_null($idProduct)){
            //Nueva excepcion bonita
            return  new Exception();
        }

        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class )-> find($idProduct);

        if(is_null($product)){
            return new Exception();
        }

        if (!$product->canView($this->getUser())){
            return new Exception();
        }

        return $this->render('viewProduct.html.twig', ['product' => $product]);

    }

}