<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UserType;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserController extends AbstractController
{

    /**
    * @Route("/profil", name="profil")
    */

    public function profil() {
        //1: Récupérer les données dans la BDD (username, email)

        //2: Afficher les données

        return $this -> render('user/index.html.twig', []);
    }

    /**
    * @Route("/login", name="login")
    */

    public function login(AuthenticationUtils $auth){

        $lastUsername = $auth -> getLastUsername(); 
        $error =  $auth -> getLastAuthenticationError();

        if($error){
            
            $this -> addFlash('errors', 'Erreur d\'identifiant !');
        }

        return $this -> render('user/login.html.twig', [
            'lastUsername' => $lastUsername
        ]);
    }

    /**
    * @Route("/register", name="register")
    */

    public function register(UserPasswordEncoderInterface $encoder, Request $request){
        $user = new User;

        $form = $this -> createForm(UserType::class, $user);
        $form -> handleRequest($request);

        if($form -> isSubmitted() && $form -> isValid()){
        
            $manager = $this -> getDoctrine() -> getManager();

            $manager -> persist($user);
            $user -> setRoles('ROLE_USER');
            $password = $user -> getPassword();
            $newPassword = $encoder -> encodePassword($user, $password);
            $user -> setPassword($newPassword);
            $manager -> flush();
            return $this -> redirectToRoute('login');
        }

        return $this -> render('user/register.html.twig', [
            'registerForm' => $form -> createView()
        ]);
    }

    /**
    * @Route("/logout", name="logout")
    */

    public function logout(){

    }
    
    /**
    * @Route("/logincheckt", name="login_check")
    */

    public function loginCheck(){

    }
}
