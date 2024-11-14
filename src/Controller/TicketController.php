<?php

namespace App\Controller;

use App\Entity\Tickets;
use App\Form\TicketType;
use App\Repository\TicketsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TicketController extends AbstractController
{
    #[Route('/tickets', name: 'ticket_list')]
    public function index(Request $request, EntityManagerInterface $entityManager, TicketsRepository $ticketRepository): Response
    {
        $ticket = new Tickets();
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ticket);
            $entityManager->flush();

            return $this->redirectToRoute('ticket_list');
        }

        $tickets = $ticketRepository->findAll();
        return $this->render('ticket/index.html.twig', [
            'tickets' => $tickets,
            'form' => $form->createView(),
            'editForm' => $form->createView(),
        ]);
    }

    #[Route('/ticket/create', name: 'ticket_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ticket = new Tickets();
        $form = $this->createForm(TicketType::class, $ticket);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ticket);
            $entityManager->flush();

            return $this->json(['success' => true, 'ticket' => $ticket]);
        }

        return $this->render('ticket/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/ticket/update-status/{ticketId}', name: 'ticket_update_status')]
    public function updateStatus($ticketId, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $newStatus = $data['status'];

        $ticket = $entityManager->getRepository(Tickets::class)->find($ticketId);

        if (!$ticket) {
            return new JsonResponse(['error' => 'Ticket not found'], 404);
        }

        $ticket->setStatus($newStatus);

        $entityManager->flush();

        return new JsonResponse(['success' => true, 'status' => $ticket->getStatus()]);
    }

    #[Route('/ticket/{id}/edit', name: 'ticket_edit')]
    public function editTicket(Request $request, Tickets $ticket, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);
        $entityManager->flush();
        return $this->redirectToRoute('ticket_list');
    }

    #[Route('/ticket/{id}/delete', name: 'ticket_delete', methods: ['POST'])]
    public function deleteTicket(Request $request, Tickets $ticket, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $ticket->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ticket);
            $entityManager->flush();
        }
        return $this->redirectToRoute('ticket_list');
    }

}
