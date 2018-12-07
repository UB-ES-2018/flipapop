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


        $this->createMany(User::class, 1, function (User $user,$i) {
            $user->setEmail('pmartinez@prueba.com');
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'randomPassword'
            ));
            $user->setName('Pablo');
            $user->setDescription('Alegre y amigo de mis amigos.');
            $user->setSurname('Martinez');
            return $user;
        });


        $manager->flush();
    }

}