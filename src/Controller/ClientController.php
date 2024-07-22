<?php

namespace App\Controller;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ClientController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/', name: 'client_list')]
    public function index(Request $request)
    {
        $clientRepository = $this->em->getRepository(Client::class);

        $clients = $clientRepository->getList($request);

        $typicalClients = $this->em->getRepository(Client::class)->getTypicalClients();


        return $this->render('Client/index.html.twig', [
            'clients' => $clients,
            'sortField' => $request->query->get('sort', 'totalEmailSent'),
            'sortDirection' => $request->query->get('direction', 'DESC'),
            'filterCategory' => $request->query->get('category', null),
            'filterClientId' => $request->query->get('client_id', null),
            'typicalClients' => $typicalClients
        ]);
    }

}