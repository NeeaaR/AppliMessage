<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\GroupeType;
use App\Entity\Groupe;
use App\Entity\Message;
use App\Entity\User;
use App\Form\MessageType;

class GroupesController extends AbstractController
{

    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {}
        else{
            return $this -> redirectToRoute('login');
        }


        $repository = $this -> getDoctrine() -> getRepository(User::class);
        $repo = $this -> getDoctrine() -> getRepository(Groupe::class);
        
        $users = $repository -> findOneBy(['id' => $this -> getUser()]);

        $groupes = $repo -> findAll();

        return $this->render('groupes/index.html.twig', [
            'controller_name' => 'GroupesController',
            'user' => $users,
            'groupes' => $groupes
        ]);
    }

    /**
     * @Route("/groupe/{id}", name="groupes_display")
     */
    public function groupe($id,Request $request)
    {
        $manager = $this -> getDoctrine() -> getManager(); //Même chose qu'au-dessus
        $groupe = $manager -> find(Groupe::class, $id);

        $message = new Message;

        $form = $this -> createForm(MessageType::class, $message);

        //lie definitivement les infos saiies dans le formulaire à notre objet $post. Récupere le $_POST
        $form -> handleRequest($request);

        if($form -> isSubmitted()&& $form ->isValid()){

            $manager = $this -> getDoctrine() -> getManager();

            $message -> setUser($this -> getUser());
            $message -> setDateTime(new \DateTime('now'));
            $groupe -> addMessage($message);
            $message -> setState(0);
            $manager -> persist($message); 
            $manager -> flush();
            $this -> addFlash('success', 'Le message ' . $message -> getID() . ' a bien été envoyé');   
        }

        $repository = $this -> getDoctrine() -> getRepository(User::class);
        $users = $repository -> findOneBy(['id' => $this -> getUser()]);

        return $this->render('groupes/show.html.twig', [
            'groupe' => $groupe,
            'user' => $users,
            'MessageForm' => $form -> createView()
        ]);
    }

    /**
     * @Route("/groupes/add", name="groupes_add")
     */
    public function addgroupe(Request $request){

        $groupe = new Groupe;

        $form = $this -> createForm(GroupeType::class, $groupe);
        $repository = $this -> getDoctrine() -> getRepository(User::class);
        $users = $repository -> findOneBy(['id' => $this -> getUser()]);

        $form -> handleRequest($request);

        if($form -> isSubmitted() && $form -> isValid()){
            $manager = $this -> getDoctrine() -> getManager();
            $manager -> persist($groupe);
            $groupe -> setDate(new \DateTime('now')); 
            $groupe -> setUsersP($this -> getUser());
            foreach($groupe -> getUsers() as $userg){
                
                $userg -> addGroupe($groupe);
            }

            if($groupe->getFile()) {
                $groupe -> removeFile();
                $groupe -> fileUpload();
            }

            $groupe -> addUser($this -> getUser()); 

           $manager -> flush(); 
           return $this -> redirectToRoute('home'); 
        }
        
        return $this -> render('groupes/groupe_form.html.twig', [
            'user' => $users,
            'groupeForm' => $form -> createView()
        ]);
    }
}
