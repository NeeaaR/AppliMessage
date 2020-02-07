<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Message;
use App\Form\MessageType;
use Symfony\Component\HttpFoundation\Request;

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

    /**
     * @Route("/message", name="messagesToGroupe")
     */

    public function sendMessage(Request $request)
    {
        $message = new Message;

        //formulaire
        $form = $this -> createForm(MessageType::class, $message);

        //lie definitivement les infos saiies dans le formulaire à notre objet $post. Récupere le $_POST
        $form -> handleRequest($request);

        if($form -> isSubmitted()&& $form ->isValid()){

            $manager = $this -> getDoctrine() -> getManager();
            $message -> setUser('1');
            $message -> setContent("YES!");
            $message -> setRegisterDate(new \DateTime('now'));
            $message -> setState('3');
            $manager -> persist($message);

            $manager -> flush();
            $this -> addFlash('success', 'Le message ' . $message -> getID() . 'a bien été envoyé');
    
        }

        // afficher la vue
        return $this->render('message.html.twig', ['messageForm' => $form -> createView()]);
    }

}
