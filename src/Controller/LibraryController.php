<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\BookRepository;
use App\Entity\Book;
use PHPUnit\Util\Json;

class LibraryController extends AbstractController
{
    #[Route('/library', name: 'library')]
    public function index(BookRepository $library): Response
    {

        $books = $library->findAll();

        return $this->render('library/index.html.twig', ['books' => $books]);
    }

    #[Route("/library/{id<\d+>}", name: "library/book", methods: ['GET'])]
    public function getBook(int $id, BookRepository $library): Response
    {
        if (!$id) {
            return $this->redirectToRoute('library');
        }

        $book = $library->find($id);

        // If book does not exist, redirect to library
        if (!$book) {
            return $this->redirectToRoute('library');
        }

        return $this->render('library/book.html.twig', ['book' => $book]);
    }

    #[Route("/library/delete/{id<\d+>}", name: "library/delete", methods: ['POST'])]
    public function delBook(int $id, BookRepository $library, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        if (!$id) {
            return $this->redirectToRoute('library');
        }

        $book = $library->find($id);

        // If book does not exist, redirect to library
        if (!$book) {
            return $this->redirectToRoute('library');
        }

        // Delete
        $entityManager->remove($book);
        $entityManager->flush();

        $this->addFlash('notice', 'Book deleted!');
        return $this->redirectToRoute('library');
    }

    #[Route("/library/edit/{id}", name: "library/edit", methods: ['GET'])]
    public function getEdit(string $id, BookRepository $library): Response
    {
        $id = intval($id);
        $book = null;

        // If book exists, get it
        if ($id) {
            $book = $library->find($id);
        }

        // If book does not exist, create a new one
        if (!$id || !$book) {
            $book = new Book();
        }

        return $this->render('library/edit.html.twig', ['book' => $book]);
    }

    #[Route("/library/edit/{id}", name: "library/editPOST", methods: ['POST'])]
    public function postEdit(string $id, BookRepository $library, Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $id = intval($id);
        $book = null;

        // If book exists, get it
        if ($id) {
            $book = $library->find($id);
        }

        // If book does not exist, create a new one
        if (!$id || !$book) {
            $book = new Book();
        }

        // Update book
        $book->setTitle($request->get('title', ''));
        $book->setAuthor($request->get('author', ''));
        $book->setIsbn($request->get('isbn', ''));

        // If we have new image save it
        
        $image = $request->files->get('image');
        if ($image) {
            $imageData = 'data:'.$image->getClientMimeType().';base64,' . base64_encode($image->getContent());
            $book->setImage($imageData);
        }

        // Save book
        $entityManager->persist($book);
        $entityManager->flush();

        $this->addFlash('notice', 'Book saved!');
        return $this->redirectToRoute('library');
    }

    #[Route("/api/library/books")]
    public function apiBooks(BookRepository $library): Response
    {
        $books = $library->findAll();

        $data = [];
        foreach ($books as $book) {
            $data[] = [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'author' => $book->getAuthor(),
                'isbn' => $book->getIsbn(),
                // 'image' => $book->getImage(),
            ];
        }

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/library/book/{isbn}")]
    public function apiBook(string $isbn, BookRepository $library): Response
    {
        $data = [];
        $book = $library->findOneBy(['isbn' => $isbn]);

        if ($book) {
            $data = [
               'id' => $book->getId(),
               'title' => $book->getTitle(),
               'author' => $book->getAuthor(),
               'isbn' => $book->getIsbn(),
               // 'image' => $book->getImage(),
            ];
        }

        $response = new JsonResponse((object) $data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
