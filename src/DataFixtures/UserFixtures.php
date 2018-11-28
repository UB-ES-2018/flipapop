<?php
/**
 * Created by PhpStorm.
 * User: Jose
 * Date: 27/11/2018
 * Time: 15:18
 */

namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends BaseFixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {

        $this->passwordEncoder = $passwordEncoder;
    }
    
    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(User::class, 4, function (User $user,$i) {
            $this->faker->unique(true);
            $user->setEmail($this->faker->email);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'randomPassword'
            ));
            $user->setName($this->faker->firstName());
            $user->setDescription($this->faker->text);
            $user->setSurname($this->faker->lastName);
            return $user;
        });


        $manager->flush();
    }
}