<?php
/**
 * Created by PhpStorm.
 * User: carlesmagallon
 * Date: 24/11/2018
 * Time: 11:07
 */

namespace App\Controller;


use App\Entity\Product;
use App\Repository\ProductRepository;
use PHPUnit\Framework\TestCase;

class BaseControllerTest extends TestCase
{

    public function testCalculateTotalSalary()
    {
        $product = new Product();
        $product.setNumLikes(100);

        $product2 = new Product();
        $product2.setNumLikes(100);

        $products = [];
        $products.add($product);
        $products.add($product2);

        // Now, mock the repository so it returns the mock of the employee
        $productRepository = $this->createMock(ProductRepository::class);
        // use getMock() on PHPUnit 5.3 or below
        // $employeeRepository = $this->getMock(ObjectRepository::class);
        $productRepository->expects($this->any())
            ->method('findBy')
            ->willReturn($products);

        // Last, mock the EntityManager to return the mock of the repository
        $objectManager = $this->createMock(ObjectManager::class);
        // use getMock() on PHPUnit 5.3 or below
        // $objectManager = $this->getMock(ObjectManager::class);
        $objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($productRepository);

        $baseCtrl = new BaseController();
        $this->assertEquals($baseCtrl->index());
    }
}