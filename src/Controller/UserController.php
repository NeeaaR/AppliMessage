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
    * @Route("/profile/{id}", name="profile")
    */
    public function profile($id) {
        //1: Récupérer les données dans la BDD
        $repo = $this -> getDoctrine() -> getRepository(User::class);
        $user = $repo -> find($id);

        //2: Afficher les données
        return $this -> render("user/profile.html.twig", array(
            'user' => $user
        ));
    }

    /**
    * @Route("/profile/update/{id}", name="profileUpdate")
    */
    public function profileUpdate($id, Request $request) {
        $manager = $this -> getDoctrine() -> getManager();
        //2 : Récupérer l'objet
        $post = $manager -> find(User::class, $id);
        $form = $this -> createForm(UserType::class, $post);
        // Notre objet hydrate le formulaire

        $form -> handleRequest($request);

        if($form -> isSubmitted() && $form -> isValid()){

          //3 : Modifier (formulaire)
          $manager -> persist($user);

          if($user  -> getFile()){
              $user -> removeFile();
              $user -> uploadFile();
          }

          $manager -> flush();
          //4 : Message
          $this -> addFlash('success', 'Votre profil a bien été modifié !');
          return $this -> redirectToRoute('profile');
        }

        //5 : Vue
        return $this -> render('user/profile.html.twig', [
            'userForm' => $form -> createView()
        ]);
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
            if($user->getFile()) {
                $user -> removeFile();
                $user -> fileUpload();
            }
            
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
