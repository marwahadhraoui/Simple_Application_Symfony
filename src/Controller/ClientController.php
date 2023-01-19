<?php

namespace App\Controller;

use App\Entity\Application;
use App\Entity\Client;
use App\Repository\ApplicationRepository;
use App\Repository\ClientRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    #[Route('/client', name: 'app_client')]
    public function index(ClientRepository $repository): Response
    {
        //  get all data

        $clients = $repository->findAll();
      
     
        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
            'clients' => $clients
        ]);
    }

    #[Route('/add_client', name: 'add_client')]
    public function addClient(Request $request,ManagerRegistry $doctrine,ApplicationRepository $repository): Response
    {
        //  1. connect to  MySQL database

       $em = $doctrine->getManager();

         //   2.set data 

       $client = new Client();
       $client->setNom($_GET['nom']);
       $client->setprenom($_GET['prenom']);

        // 3.get the id of the application which the user choose

       $id_of_application=$request->query->get('app');

       //   4.get the application 

       $app = $repository->find($id_of_application);

       //   5.add the application
       $client->addApplication($app);
       
        // 6.save data
       $em->persist($client);
       $em->flush();
        
        return $this->redirectToRoute('app_client');     
    }

    #[Route('/update_client', name: 'update_client')]
    public function updateClient(Request $request,ManagerRegistry $doctrine,ClientRepository $repository,ApplicationRepository $repo): Response
    {
         //  1. connect to  MySQL database
       $em = $doctrine->getManager();

        //   2.set data 
       $Client = new Client();
       $client=$repository->find($_GET['id']);
       $client->setNom($_GET['nom']);
       $client->setprenom($_GET['prenom']);
       // 3.get the id of the application which the user choose
       $id_of_application=$request->query->get('app');
        //   4.get the application 
       $app = $repo->find($id_of_application);
       //   5.add the application
       $client->addApplication($app);
       // 6.save data
           $em->flush();
        
        return $this->redirectToRoute('app_client');     
    }
    

    #[Route('/delete_client/{id}', name: 'delete_client')]
    public function deleteApplication(ClientRepository $repository,$id,ManagerRegistry $doctrine): Response
    {
        //  1. connect to  MySQL database
        $em  = $doctrine->getManager();
        //  2.find the client
        $client = $repository->find($id);
       //   3.remove the client and save 
        $em->remove($client);
        $em->flush();
        

        return $this->redirectToRoute('app_client');       
    }
    
    
    
    
    
    #[Route('/update_client_form/{id}', name: 'update_client_form')]
    public function RenderupdateClient($id,ClientRepository $repository,ApplicationRepository $repo): Response
    {
        //  1.get the id of the user
        $client = $repository->find($id);
        //  2.get the data
        $name=$client->getNom();
        $lastname=$client->getprenom();
        //  3.get all applications
        $applications = $repo->findAll();
    
        return $this->render('client/updateClient.html.twig', [
            'controller_name' => 'ClientController',
            'name' => $name,
            'lastname' => $lastname,
            'id' => $id,
            'applications' => $applications
            
        ]);
    }

    #[Route('/add_client_Form', name: 'add_client_form')]
    public function RenderapplicationForm(ApplicationRepository $repository): Response
    {
        $applications = $repository->findAll();

        return $this->render('client/addClient.html.twig', [
            'controller_name' => 'ClientController',
            'applications' => $applications
           
        ]);
    }
}
