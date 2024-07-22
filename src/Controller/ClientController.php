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

    #[Route('/clients', name: 'client_list')]
    public function index(Request $request)
    {
        $clientRepository = $this->em->getRepository(Client::class);

        // Retrieve sorting parameter from the request
        $sortBy = $request->query->get('sort', 'totalEmailSent');
        $sortDirection = $request->query->get('direction', 'DESC');

        // Retrieve filtering params from the request
        $filterGroup = $request->query->get('group', null);
        $filterClientId = $request->query->get('client_id', null);



        // return $this->render('client/index.html.twig', [
        //     'clients' => $clients,
        // ]);
    }

}