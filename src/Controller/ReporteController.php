<?php

namespace App\Controller;

use App\Entity\Reporte;
use App\Entity\User;
use App\Form\ReporteType;
use App\Repository\ReporteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/reporte')]
class ReporteController extends AbstractController
{
    #[Route('/', name: 'app_reporte_index', methods: ['GET'])]
    public function index(ReporteRepository $reporteRepository): Response
    {
        return $this->render('reporte/index.html.twig', [
            'reportes' => $reporteRepository->findAll(),
        ]);
    }

    #[Route('/{id}/new', name: 'app_reporte_new', methods: ['GET', 'POST'])]
    public function new( User $user, EntityManagerInterface $entityManager, Request $request, ReporteRepository $reporteRepository): Response
    {

        $reporte = new Reporte();
        $form = $this->createForm(ReporteType::class, $reporte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reporteRepository->add($reporte);
            $reporte->setAutor($this->getUser());
            $reporte->setReportado($user);

            $entityManager->persist($reporte);
            $entityManager->flush();
            return $this->redirectToRoute('app_grupo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reporte/new.html.twig', [
            'reporte' => $reporte,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reporte_show', methods: ['GET'])]
    public function show(Reporte $reporte): Response
    {
        return $this->render('reporte/show.html.twig', [
            'reporte' => $reporte,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reporte_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reporte $reporte, ReporteRepository $reporteRepository): Response
    {
        $form = $this->createForm(ReporteType::class, $reporte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reporteRepository->add($reporte, true);

            return $this->redirectToRoute('app_reporte_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reporte/edit.html.twig', [
            'reporte' => $reporte,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reporte_delete', methods: ['POST'])]
    public function delete(Request $request, Reporte $reporte, ReporteRepository $reporteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reporte->getId(), $request->request->get('_token'))) {
            $reporteRepository->remove($reporte, true);
        }

        return $this->redirectToRoute('app_reporte_index', [], Response::HTTP_SEE_OTHER);
    }
}
