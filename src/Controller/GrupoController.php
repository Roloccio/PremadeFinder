<?php

namespace App\Controller;

use App\Entity\Grupo;
use App\Form\GrupoType;
use App\Repository\GrupoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

#[Route('/grupo')]
class GrupoController extends AbstractController
{
    #[Route('/', name: 'app_grupo_index', methods: ['GET'])]
    public function index(GrupoRepository $grupoRepository): Response
    {
        return $this->render('grupo/indexlol.html.twig', [
            'grupos' => $grupoRepository->findAll(),
        ]);
    }
    #[Route('/grupo2', name: 'app_grupo_index2', methods: ['GET'])]
    public function grupo2(GrupoRepository $grupoRepository): Response
    {
        return $this->render('grupo/indexvalorant.html.twig', [
            'grupos' => $grupoRepository->findAll(),
        ]);
    }
    #[Route('/', name: 'app_grupo_show', methods: ['GET'])]
    public function index2(GrupoRepository $grupoRepository): Response
    {
        return $this->render('grupo/show.html.twig', [
            'grupos' => $grupoRepository->findAll(),
        ]);
    }
    #[Route('/{grupo}/unirse', name: 'unirse', methods: ['GET'])]
    public function unirse(Grupo $grupo,EntityManagerInterface $entityManager): Response
    {
            
            $grupo->addMiembro($this->getUser());

            $entityManager->persist($grupo);
            $entityManager->flush();

            return $this->render('grupo/show.html.twig', [
                'grupo' => $grupo,
            ]);
        
    }

    #[Route('/new', name: 'app_grupo_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, GrupoRepository $grupoRepository): Response
    {
        $grupo = new Grupo();
        $form = $this->createForm(GrupoType::class, $grupo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $grupoRepository->add($grupo, true);
            $grupo->setAutor($this->getUser());
            $grupo->addMiembro($this->getUser());

            $entityManager->persist($grupo);
            $entityManager->flush();

            return $this->redirectToRoute('grupos', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('grupo/new.html.twig', [
            'grupo' => $grupo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_grupo_show', methods: ['GET'])]
    public function show(Grupo $grupo): Response
    {
        return $this->render('grupo/show.html.twig', [
            'grupo' => $grupo,
        ]);
    }
    #[Route('show/{id}', name: 'app_grupo_show2', methods: ['GET'])]
    public function show2(Grupo $grupo): Response
    {
        return $this->render('grupo/show2.html.twig', [
            'grupo' => $grupo,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_grupo_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Grupo $grupo, GrupoRepository $grupoRepository): Response
    {
        $form = $this->createForm(GrupoType::class, $grupo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $grupoRepository->add($grupo, true);

            return $this->redirectToRoute('app_grupo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('grupo/edit.html.twig', [
            'grupo' => $grupo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_grupo_delete', methods: ['GET','POST'])]
    public function delete(Grupo $grupo,EntityManagerInterface $entityManager,Request $request, GrupoRepository $grupoRepository): Response
    {
        // User $user,

        $grupo->removeMiembro($this->getUser());
        
            if (count($grupo->getMiembros()) <=0 ) {
                $grupo->setAutor(null);
                $entityManager->remove($grupo);
                $entityManager->flush();
            }
            else{
                $entityManager->persist($grupo);
                $entityManager->flush();
            }
        

        
        // if ($this->isCsrfTokenValid('delete'.$grupo->getId(), $request->request->get('_token'))) {
        //     $grupoRepository->remove($grupo, true);
        // }

        return $this->redirectToRoute('grupos', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}', name: 'app_grupo_delete2', methods: ['POST'])]
    public function delete2(Grupo $grupo,EntityManagerInterface $entityManager,Request $request,User $user, GrupoRepository $grupoRepository): Response
    {
            
        $grupoRepository->remove($grupo);

        // $entityManager->persist($grupo);
        // $entityManager->flush();

        return $this->redirectToRoute('app_grupo_index', [], Response::HTTP_SEE_OTHER);
    }


}
