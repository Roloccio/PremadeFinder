<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Curso;
use App\Form\UserType;
use App\Form\UserType2;
use App\Form\UserType3;
use App\Form\UsersType;
use App\Repository\UserRepository;
// use App\Repository\CursoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/admin/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'user_list', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('admin/usersList.html.twig', [
            'listUsers' => $userRepository->findAll(),
        ]);
    }
    #[Route('/banned', name: 'user_list2', methods: ['GET'])]
    public function baneados(UserRepository $userRepository): Response
    {
        return $this->render('admin/userList2.html.twig', [
            'listUsers' => $userRepository->findAll(),
        ]);
    }
    #[Route('/{id}/ban', name: 'user_ban', methods: ['GET'])]
    public function ban(EntityManagerInterface $entityManager, User $user): Response
    {

        $user->setBaneado(true);
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('user_list', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}/unban', name: 'user_unban', methods: ['GET'])]
    public function unban(EntityManagerInterface $entityManager, User $user): Response
    {

        $user->setBaneado(false);
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('user_list', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/new', name: 'user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, UserPasswordHasherInterface $userPasswordHasher, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        $usersForm = $this->createForm(UsersType::class, $user);

        $usersCsv = $usersForm->get('users')->getData();
        if ($form->isSubmitted() && $form->isValid()) {   

            $user->setUsername($form->get('username')->getData());
            // $user->setDni($form->get('DNI')->getData());
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('Password')->getData()
                    )
                );
            $user->setEmail($form->get('email')->getData());
            // $user->setCurso($form->get('curso')->getData());
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }

            if ($usersForm->isSubmitted() && $usersForm->isValid()) {

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($usersCsv) {
                try {
                $originalFilename = pathinfo($usersCsv->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$usersCsv->guessExtension();
 
                // Move the file to the directory where brochures are stored
                
                    $usersCsv->move(
                        $this->getParameter('userscsv_directory'),
                        $newFilename
                    );
                
            }
                 catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'imageFilename' property to store the PDF file name
                // instead of its contents
            }
            if (($open = fopen("userscsv/" . $newFilename , "r"))!==false) {
                while (($data = fgetcsv($open, 1000, ",")) !== FALSE) {
                    //Si el usuario existe por DNI
                    if ($user= $userRepository->findOneByDni($data[0])) {
                        $user->setDni($data[0]);
                        $user->setUsername($data[1]); 
                        $user->setPassword(
                            $userPasswordHasher->hashPassword(
                                $user,
                                $data[2],
                            )
                        );
                        // $datosCurso = explode("-", $data[3]);
    
                        // if ($curso= $cursoRepository->findOneByName($datosCurso[0])) {
                        //     $user->setCurso($curso);
                        // }
                        // else {
                    
                        //     $curso = new Curso();
                        //     $curso->setNombre($datosCurso[0]);
                        //     $curso->setAbreviatura(strtoupper($datosCurso[1]));
                        //     $curso->setEdicion($datosCurso[2]);
                        //     $curso->addAlumno($user);
    
                        //     $entityManager->persist($curso);
                        //     $entityManager->flush();
                        // }
                        
                        $user->setEmail($data[4]);
                        // $user->setRoles([ $data[5] ]);
                        $entityManager->persist($user);
                        $entityManager->flush();
                    }
                    
                    //Si el usuario no existe por DNI
                    else {
                        $user = new User();
                        $user->setDni($data[0]);
                        $user->setUsername($data[1]); 
                        $user->setPassword(
                            $userPasswordHasher->hashPassword(
                                $user,
                                $data[2],
                            )
                        );
                        // $datosCurso = explode("-", $data[3]);

                        // if ($curso= $cursoRepository->findOneByName($datosCurso[0])) {
                        //         $user->setCurso($curso);
                        // }
                        // else {
                        
                        //     $curso = new Curso();
                        //     $curso->setNombre($datosCurso[0]);
                        //     $curso->setAbreviatura(strtoupper($datosCurso[1]));
                        //     $curso->setEdicion($datosCurso[2]);
                        //     $curso->addAlumno($user);

                        //     $entityManager->persist($curso);
                        //     $entityManager->flush();
                        // }

                        $user->setEmail($data[4]);
                        // $user->setRoles([ $data[5] ]);
                        $entityManager->persist($user);
                        $entityManager->flush();
                    }
                }
            }
            fclose($open);


            return $this->redirectToRoute('user_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/newUser.html.twig', [
            'user' => $user,
            'form' => $form,
            'usersForm' => $usersForm,
        ]);
    }

    #[Route('/{id}', name: 'user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType2::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin');
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    #[Route('/{id}/edit2', name: 'user_edit2', methods: ['GET', 'POST'])]
    public function edit2(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType3::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin');
        }

        return $this->renderForm('user/edit2.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
    }


}
