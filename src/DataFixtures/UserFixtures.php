<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;
    
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    
    
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        
        $user = new User();
        $password = $this->encoder->encodePassword($user, 'abdel');
        
        $user->setUsername('abdel');
        $user->setPassword($password);
        $user->setRole('ROLE_ADMIN');
        
        
        
        $manager->persist($user);
        $manager->flush();
    }
}
