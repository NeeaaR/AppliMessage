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
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/connexion", name="connexion")
     */

    public function connexion() {

        return $this -> render('user/connexion.html.twig', []);
    }

    /**
    * @Route("/inscription", name="inscription")
    */

    public function inscription(Request $request) {

        $user = new User;

        $form = $this -> createForm(UserType::class, $user);

        $form -> handleRequest($request);

        if($form -> isSubmitted() && $form -> isValid()){
            $manager = $this -> getDoctrine() -> getManager();
            $manager -> persist($user);
            
            $manager -> flush(); 
        }

       return $this -> render('user/inscription.html.twig', [
            'userForm' => $form -> createView()
       ]);

       return $this -> redirectToRoute('home');
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

    public function register(UserPasswordEncoderInterface $encode){
        $user = new User;

        $form = $this -> createForm(UserTyê::class, $user);
        $form -> handleRequestion($request);

        if($form -> isSubmitted() && $form -> isValid()){
        
            $manager = $this -> getDoctrine() -> getManager();

            $manager -> persist($user);
            $user -> setRole('ROLE_USER');
            $password = $user -> getPassword();
            $newPassword = $encoder -> encodePassword($user, $password);
            $user -> setPassword($newPassword);
            $manager -> flush();
        }

        return $this -> render();
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
