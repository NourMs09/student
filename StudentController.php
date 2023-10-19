<?php

namespace App\Controller;
use App\Repository\StudentRepository;
use App\Entity\Student;
use App\Repository\ClassroomRepository;
use App\Form\StudentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

class StudentController extends AbstractController
{
    #[Route('/student', name: 'app_student')]
    public function index(): Response
    {
        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }

    #[Route('/fetch', name: 'fetch')]
    public function fetch(StudentRepository $repo): Response
    {
        $result=$repo->findAll();
        return $this->render('student/test.html.twig',[
            'response'=>$result,
        ]);
    }

#[Route('/addf', name: 'addf')]
public function addf(ClassroomRepository $repo,  ManagerRegistry $mr, Request $req ):Response
{   

    $s=new Student(); //1-creation d'instance 
     $form=$this->createForm(StudentType::class,$s); //2-houni bech ya3ref bech t3aba l formulaire mte3i 
     $form->handleRequest ($req);
     if( $form->isSubmitted())
     {
    $em=$mr->getManager(); //3-persist-flush
    $em->persist($s);
    $em->flush();
   
    return $this->redirectToRoute('fetch');
    }

    return $this->render('student/add.html.twig', [
        'f' => $form->createView(),
    ]);

}


#[Route('/removef/{id}', name: 'removef')]
    public function removef(StudentRepository $repo,  ManagerRegistry $mr , $id): Response
    {
        $em=$mr->getManager();
        $student=$repo->find($id);
        $em->remove($student);
        $em->flush(); 

        return $this->redirectToRoute('fetch');
    }



#[Route('/update/{id}', name: 'update')]
 
   public function update(Request $request, $id,ManagerRegistry $mr,StudentRepository $repo ):Response
{
    $student = $repo->find($id);
    $form = $this->createForm(StudentType::class, $student);
    $form->handleRequest($request);
    if ($form->isSubmitted() ) {
        $em=$mr->getManager();
        $em->persist($student);
        $em->flush();

        return $this->redirectToRoute('fetch');
    }

    return $this->render('student/update.html.twig', [
        'form' => $form->createView(),
    ]);
}




/*#[Route('/remove/{id}', name: 'remove')]
    public function remove(StudentRepository $repo , $id, ManagerRegistry $mr): Response
    {
        $student=$repo->find($id);
        $em=$mr->getManager();
        $em->remove($student);
        $em->flush(); 

        return new Response('removed');
    }*/

}