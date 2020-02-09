<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\Groupe;
use App\Entity\User;
use App\Entity\Message;

class AppFixtures extends Fixture
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

            for($i = 1; $i <= 4; $i++){
                $groupe = new Groupe;
                $groupe -> setName('groupe ' . $i);
                $groupe -> setDate(new \DateTime('now'));
                $groupe -> setPhoto('default'.$i.'.jpg');
                $manager -> persist($groupe);
                
            }
                for($j = 1;$j <=3; $j++){
                $user = new User;
                $user -> setUsername('user' . $j);
                $password = $this->encoder->encodePassword($user, 'Ynov2020');
                $user -> setPassword($password);
                $user -> setPhoto('default'.$j.'.jpg');
                $user -> addGroupe($groupe);
                $user -> setEmail('user' . $j . '@ynov.com' );
                $manager -> persist($user);
                
                $message = new Message;
                $message -> setContent('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi eu scelerisque metus, ut egestas ligula. Phasellus vitae varius mi. Pellentesque at ipsum orci. Suspendisse faucibus metus nec purus tristique sodales');
                $message -> setState('3');
                $message -> setDateTime(new \DateTime('now'));
                $message -> setUser($user);
                $message -> setGroupe($groupe);
                $manager -> persist($message);
            }   
            
            $manager -> flush();
    }
}