<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MessagesController extends AbstractController
{



    
    /**
     * @Route("/messages", name="messages")
     */
    public function index()
    {

        $repository = $this -> getDoctrine() -> getRepository(Message::class);
        $messages = $repository -> findAll();

        return $this->render('messages/index.html.twig', [
            'controller_name' => 'MessagesController',
        ]);
    }

    

    /**
     * @Route("/user/{id}", name="messagesTo")
     */
    public function convo()
    {
        $repo = $this -> getDoctrine() -> getRepository(User::class);
        $user = $repo -> find($id);

        $manager = $this ->getDoctrine() -> getManager();
        $user = $manager ->find(User::class, $id);

        return $this->render('user/show.html.twig', ['user' => $user]);
    }

}
