<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ComentarioRepository;
use App\Entity\Comentario;
use Doctrine\ORM\EntityManagerInterface;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('principal/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    #[Route('/admin/comments', name: 'admin_comments')]
    public function commentList(ComentarioRepository $commentRepository): Response
    {
        return $this->render('admin/commentsList.html.twig', [
            'commentList' => $commentRepository->findCommentsDisapproved(),
        ]);
    }
    #[Route('/admin/info', name: 'info')]
    public function info(): Response
    {
        return $this->render('admin/info.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    #[Route('/admin/comments/{id}', name: 'comment_ok')]
    public function commentOk(Comentario $comment, EntityManagerInterface $entityManager): Response
    {
        $comment->setAprobado(true);
        $entityManager->persist($comment);
        $entityManager->flush();
        return $this->redirectToRoute('admin_comments', [], Response::HTTP_SEE_OTHER);
    }
}
