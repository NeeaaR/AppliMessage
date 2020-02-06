<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Groupe;
use Symfony\Component\HttpFoundation\Request;
use App\Form\GroupeType;

class GroupesController extends AbstractController
{
    /**
     * @Route("/groupes", name="groupes")
     */
    public function index()
    {
        return $this->render('groupes/index.html.twig', [
            'controller_name' => 'GroupesController',
        ]);
    }

    /**
     * @Route("/groupe/{id}", name="groupes_display")
     */
    public function groupe($id)
    {
        $repo = $this -> getDoctrine() -> getRepository(Groupe::class);
        $post = $repo -> find($id);

        $manager = $this -> getDoctrine() -> getManager(); //Même chose qu'au-dessus
        $post = $manager -> find(Groupe::class, $id);

        return $this->render('groupes/show.html.twig', [
            'groupe' => $post
        ]);
    }

    /**
     * @Route("/groupes/add", name="groupes_add")
     */
    public function addgroupe(Request $request){

        $groupe = new Groupe;

        $form = $this -> createForm(GroupeType::class, $groupe);

        $form -> handleRequest($request);

        if($form -> isSubmitted() && $form -> isValid()){
            $manager = $this -> getDoctrine() -> getManager();
            $manager -> persist($groupe);
            $groupe -> setDate(new \DateTime('now'));
            
            $manager -> flush(); //HELLO
        }
        
        return $this -> render('groupes/groupe_form.html.twig', [
            'groupeForm' => $form -> createView()
        ]);
    }
}
