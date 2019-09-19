<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EntityTableRepository;
use App\Helpers\Filters\Client as ClientFilters;

class ClientController extends AbstractController
{
    /**
     * @Route("/client", name="client_list")
     */
    public function index(EntityManagerInterface $em)
    {

        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
            'filters' => ClientFilters::$filterFields,
        ]);
    }

    /**
     *
     * @Route("client/{id}", methods={"GET"}, name="client_show", requirements={"id"="\d+"})
     *
     * @param EntityManagerInterface $em
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show($id, EntityManagerInterface $em)
    {
        $repository = $em->getRepository(Client::class);
        $clients = $repository->findOneBy(['id' => $id]);

        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
            'clients' => $clients,
        ]);
    }

    /**
     * @Route("client/ajax", methods={"POST"}, name="client_ajax")
     *
     * @param EntityManagerInterface $em
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAjax(EntityManagerInterface $em)
    {
        $request = Request::createFromGlobals();
        $filters = $request->request->get('filters', []);
        $order = $request->request->get('order', 'client_name_asc');
        $pageNr = $request->request->get('pageNr', 1);
        $perPage = $request->request->get('perPage', 2);

        $repository = $em->getRepository(Client::class);

        $clientEntityRepository = new ClientFilters($repository, $filters, $order, $pageNr, $perPage);

        return $this->render('client/table.html.twig', [
            'clients' => $clientEntityRepository->findAllByFilters(),
            'pageCount' => $clientEntityRepository->getPageCount(),
            'pageNr' => $clientEntityRepository->getPageNr(),
            'order' => $clientEntityRepository->getOrder(),
            'columns' => ClientFilters::$columns,
        ]);
    }

    /**
     * @Route("client/edit/{id}", name="client_edit")
     *
     * @param EntityManagerInterface $em
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(EntityManagerInterface $em, Request $request, $id = null)
    {
        if (is_null($id)) {
            $client = new Client();
            $message = 'Dodano nowego klienta';
        } else {
            $client = $em->getRepository(Client::class)->find($id);
            $message = 'Zapisano dane klienta';
        }

        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', $message);
            $client = $form->getData();
            $em->persist($client);
            $em->flush();

            return $this->redirectToRoute('client_list');
        }

        return $this->render('client/edit.html.twig', [
            'form' => $form->createView(),
            'clientId' => $id,
        ]);

    }


    /**
     * @Route("client/sort", methods={"GET"}, name="client_test")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function test()
    {
        $patternArray = [
            'pierwszy',
            'drugi',
            'trzeci',
        ];

        $arrayToSort = [
            [
                'name' => 'drugi',
                'value' => 'war 2',
            ],
            [
                'name' => 'pierwszy',
                'value' => 'war 1',
            ],
            [
                'name' => 'trzeci',
                'value' => 'war 3',
            ],
            [
                'name' => 'czwarty',
                'value' => 'war 4',
            ],
            [
                'name' => 'pierwszy',
                'value' => 'war 11',
            ],
        ];

        dump($arrayToSort);

        $rest = [];
        foreach ($arrayToSort as $key => $single) {
            if (!in_array($single['name'], $patternArray)) {
                $rest[] = $single;
                unset($arrayToSort[$key]);
            }
        }

        $flippedArray = array_flip($patternArray);
        uasort($arrayToSort, function ($a, $b) use ($flippedArray) {
            $first = $flippedArray[$a['name']];
            $second = $flippedArray[$b['name']];

            if ($first == $second) {
                return 0;
            }

            return ($first < $second) ? -1 : 1;
        });

        dd(array_merge($arrayToSort, $rest));

        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
            'filters' => ClientFilters::$filterFields,
        ]);
    }

}
