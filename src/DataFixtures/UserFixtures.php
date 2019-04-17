<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;
    
    /**
     * UserFixtures constructor.
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    
    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $admin->setFirstname('Olivier');
        $admin->setLastname('Valette');
        $admin->setCreatedAt(new DateTime());
        $admin->setEmail("olivier.valette@spi10.com");
        $admin->setPassword($this->passwordEncoder->encodePassword($admin, "ovalette"));
        $admin->setRoles(["ROLE_ADMIN"]);
        $manager->persist($admin);
        
        $user = new User();
        $user->setFirstname('John');
        $user->setLastname('Doe');
        $user->setCreatedAt(new DateTime());
        $user->setEmail('john.doe@gmail.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user, "1234"));
        $user->setRoles(["ROLE_USER"]);
        $manager->persist($user);
        $this->addReference('user-1', $user);
        
        $manager->flush();
    }
}