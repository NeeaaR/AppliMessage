<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Groupe;
use App\Entity\User;
use App\Entity\Message;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        for($i = 1; $i < 2; $i++){
            $user = new User;
            $user -> setUsername('user' . $i);
            $user -> setPassword('123456');
            $user -> setEmail('user' . $i . '@gmail.com' );
            $manager -> persist($user);
        }

        $manager -> flush();

        for($j = 1; $j <= 4; $j++){
            $groupe = new Groupe;
            $groupe -> setName('groupe' . $j);
            $groupe -> setDate(new \DateTime('now'));
            $manager -> persist($groupe);
        }

        $manager -> flush();

        $manager -> flush();

        for($k = 1; $j < 2; $k++){
            $groupe = new Message;
            $groupe -> setContent('message' . $k);
            $groupe -> setState('3');
            $groupe -> setDate(new \DateTime('now'));
            $manager -> persist($message);
        }

        $manager -> flush();
    }
}
