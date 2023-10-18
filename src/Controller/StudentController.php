<?php

namespace App\Controller;
use App\Entity\Student; 
use App\Entity\Classroom;
use App\Form\StudentType;
use App\Repository\ClassroomRepository;
use App\Repository\StudentRepository;
use Doctrine\Persistence\ManagerRegistry; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
        $result = $repo->findAll();

        return $this->render('student/list.html.twig', [
            'response' => $result,
        ]);
    }
    // #[Route('/add', name: 'add')] 
    // public function add(ManagerRegistry $mr,ClassroomRepository $repo): Response
    // {
    //     $c=$repo->find('1');
    //     $s = new Student();
    //     $s->setName('test');
    //     $s->setEmail('test@gmail.com');
    //     $s->setAge('28');
    //     $s->setClassroom($c);
    //     $em = $mr->getManager();
    //     $em->persist($s);
    //     $em->flush();
    //     return $this ->redirectToRoute('fetch');
    // }
    #[Route('/addF', name: 'addF')] 
    public function addF(ManagerRegistry $mr,ClassroomRepository $repo,Request $req): Response
    {
       
        $s = new Student();
        $form=$this->createform(StudentType::class,$s);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            $em = $mr->getManager(); 
            $em->persist($s);
            $em->flush();
            return $this ->redirectToRoute('fetch');    
        }
        
        return $this-> render('student/add.html.twig',[
            'f'=>$form->createView()
        ]);
    }

    #[Route('/update/{id}', name: 'update')]
    public function updateStudent(int $id, ManagerRegistry $mr, Request $req, StudentRepository $repo): Response
    {
        $s = $repo->find($id);
    
        if (!$s) {
            throw $this->createNotFoundException('Student not found.');
        }
    
        $form = $this->createForm(StudentType::class, $s); 
    
        $form->handleRequest($req);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $mr->getManager();
           
            $em->flush();
    
            return $this->redirectToRoute('fetch'); 
        }
    
        return $this->render('student/add.html.twig', [
            'f' => $form->createView()
        ]);
    }
#[Route('/remove/{id}', name: 'remove')]
public function remove(StudentRepository $repo, $id, ManagerRegistry $mr):Response
{
    $student = $repo->find($id);
    $em = $mr->getManager();
    $em->remove($student);
    $em->flush();

    return $this ->redirectToRoute('fetch');
}
}