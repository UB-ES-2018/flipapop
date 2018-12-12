<?php
/**
 * Created by PhpStorm.
 * User: Jose
 * Date: 27/11/2018
 * Time: 15:17
 */

namespace App\DataFixtures;


use App\Entity\Category;
use App\Entity\Product;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;

class ProductFixtures extends BaseFixture
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    private $cat;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    protected function loadData(ObjectManager $manager)
    {

        $this->createMany(Category::class, 1, function(Category $category, $i){
            $category->setName("Juguetes");
            $this->cat = $category;
            return $category;
        });


        $this->createMany(Product::class, 10, function (Product $product,$i) {
            $product->setUsuario($this->userRepository->getRandomUser());
            $product->setName("product".$i);
            $product->setDescription($this->faker->text);
            $product->setPrice($this->faker->randomNumber());
            $product->setVisibility(Product::VISIBLE_ALL);
            $product->setCategory($this->cat);
            return $product;
        });

        $this->createMany(Product::class, 10, function (Product $product,$i) {
            $product->setUsuario($this->userRepository->getRandomUser());
            $product->setName("product".$i);
            $product->setDescription($this->faker->text);
            $product->setPrice($this->faker->randomNumber());
            $product->setVisibility(Product::VISIBLE_LOGGED);
            $product->setCategory($this->cat);
            return $product;
        });

        $this->createMany(Product::class, 10, function (Product $product,$i) {
            $product->setUsuario($this->userRepository->getRandomUser());
            $product->setName("product".$i);
            $product->setDescription($this->faker->text);
            $product->setPrice($this->faker->randomNumber());
            $product->setVisibility(Product::VISIBLE_ME);
            $product->setCategory($this->cat);
            return $product;
        });

        $manager->flush();
    }

}