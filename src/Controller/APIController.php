<?php

namespace App\Controller;

use App\Entity\Libro;
use App\Entity\Comentario;
use App\Entity\Valoracion;
use App\Repository\LibroRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/* #[Route('api')] */
class APIController extends AbstractController
{
    //#[Route('/libros', name: 'api_libros')]
    public function libros(LibroRepository $libroRepository):Response
    {
        $libros = $libroRepository
            ->findAll();

        return $this->json($libros, Response::HTTP_OK, [], ['groups' => 'infoLibros']);
    }

    //#[Route('/libros/{id}', name: 'api_libro_individual', methods: ['GET'])]
    public function libro(Libro $libro):Response
    {
        return $this->json($libro, Response::HTTP_OK, [], ['groups' => 'infoLibroIndividual']);
    }

    //#[Route('/anadirComentario', name: 'api_comentario')]
    public function addComentario(LibroRepository $libroRepository, EntityManagerInterface $entityManager, Request $request):Response
    {
        try {
            $data = json_decode($request->getContent(), true);
            $libroId = $data['libroId']; //viene del json
            $contenidoComentario = $data['comentario']; //viene del json
            
            $comentario = new Comentario();
            $libro = $libroRepository
                ->findOneBy(
                    ['id' => $libroId]
                );
    
            $comentario->setLibro($libro);
            $comentario->setAutor($this->getUser());
            $comentario->setFechaPublicacion(new \DateTime());
            $comentario->setComentario($contenidoComentario);
    
            $entityManager->persist($comentario);
            $entityManager->flush();
            $message = 'OK';
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }
        
        return $this->json([
            'message' => $message,
        ]);
    }

    //#[Route('/anadirValoracion', name: 'api_valoracion')]
    public function addValoracion(LibroRepository $libroRepository, EntityManagerInterface $entityManager, Request $request):Response
    {
        try {
            $data = json_decode($request->getContent(), true);
            $libroId = $data['libroId']; //viene del json
            $puntuacion = $data['puntuacion']; //viene del json
            
            $valoracion = new Valoracion();
            $libro = $libroRepository
                ->findOneBy(
                    ['id' => $libroId]
                );
    
            $valoracion->setLibro($libro);
            $valoracion->setAutor($this->getUser());
            $valoracion->setFechaPublicacion(new \DateTime());
            $valoracion->setPuntuacion($puntuacion);
    
            $entityManager->persist($valoracion);
            $entityManager->flush();
            $message = 'OK';
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }
        
        return $this->json([
            'message' => $message,
        ]);
    }

    #[Route('api/login', name: 'api_login')]
    public function login(): Response
    {
        if ($this->getUser()) {
            return $this->json($this->getUser(), Response::HTTP_OK, [], ['groups' => 'infoUser']);
        } 
    }

    #[Route('api/descargar', name: 'libro_download')]
    public function download(LibroRepository $libroRepository, UserRepository $userRepository, EntityManagerInterface $entityManager, Request $request): Response
    {
        try {
            $data = json_decode($request->getContent(), true);
            $url = $data['url']; //viene del json
            $idUser = $data['idUser']; //viene del json
            $idLibro = $data['idLibro']; //viene del json
            $tituloLibro = $data['tituloLibro']; //viene del json

            $user = $userRepository->findOneById($idUser); //objeto user
            $libro = $libroRepository->findOneById($idLibro); //objeto libro

            $user->addLibrosLeido($libro);
            $entityManager->persist($user);
            $entityManager->flush();

            $file = $url;
            $response = new BinaryFileResponse($file);
            // Intentar asignar nombre al archivo, pero no funciona
            $disposition = HeaderUtils::makeDisposition(
                HeaderUtils::DISPOSITION_ATTACHMENT,
                $tituloLibro.'.epub'
            );
            $response->headers->set('Content-Disposition', $disposition);

            return $response;

        } catch (\Exception $e) {
            $message = $e->getMessage();
            return $this->json([
                'message' => $message,
            ]);
        }
    }
    
}