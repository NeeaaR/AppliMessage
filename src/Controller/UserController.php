<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UserType;

class UserController extends AbstractController
{

    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/inscription", name="inscription")
     */

    public function inscription() {

        return $this -> render('user/inscription.html.twig', []);
    }

    /**
    * @Route("/connexion", name="connexion")
    */

    public function connexion(Request $request) {

        $user = new User;

        $form = $this -> createForm(UserType::class, $user);

        $form -> handleRequest($request);

        if($form -> isSubmitted() && $form -> isValid()){
            $manager = $this -> getDoctrine() -> getManager();
            $manager -> persist($user);
            
            $manager -> flush(); 
        }

       return $this -> render('user/connexion.html.twig', [
            'userForm' => $form -> createView()
       ]);

       return $this -> redirectToRoute('index');
   }

    /**
    * @Route("/profil", name="profil")
    */

    public function profil() {
        //1: Récupérer les données dans la BDD (username, email)


        //2: Afficher les données

        return $this -> render('user/profil.html.twig', []);
    }

    /**
    * @Route("/deconnexion", name="deconnexion")
    */

    public function deconnexion() {
        return $this -> render('user/deconnexion.html.twig', []);
    }

}
