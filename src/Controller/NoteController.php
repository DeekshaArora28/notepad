<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request; 
use App\Repository\NoteRepository;
use App\Entity\Note;
use App\Form\NoteType;

    /**
     * @Route("/notes", name="notes.")
     */
class NoteController extends AbstractController
{
    /**
     * @Route("/", name="all")
     */
    public function notes(NoteRepository $nRepository): Response
    {
        $notes = $nRepository->findAll();
        $user = $this->getUser();
        return $this->render('note/index.html.twig', [
            'notes' =>  $notes, 'user' => $user ,
        ]);
    }
    /**
     * @Route("/create", name="create")
     */
    public function notes_create(Request $request): Response
    {
        $note = new Note();
        $form= $this->createForm(NoteType::class, $note);
        

        $form->handleRequest($request);

        if($form->isSubmitted()){
        
            $em = $this->getDoctrine()->getManager();
             

             $em->persist($note);// insert into notes
             
        $em->flush();

       return $this->redirect($this->generateUrl('notes.all'));
        }
         return $this->render('note/create.html.twig',['form' =>$form->createView()]);

    }
    #[Route('/{id}', name:'show')]
 public function show($id,NoteRepository $nRepository){
    $note =$nRepository->find($id);

    return $this->render('note/show.html.twig',['note' =>$note]);

 }
}
