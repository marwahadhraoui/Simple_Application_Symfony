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
        $clients = $repository->findAll();
      
     
        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
            'clients' => $clients
        ]);
    }

    #[Route('/add_client', name: 'add_client')]
    public function addClient(Request $request,ManagerRegistry $doctrine,ApplicationRepository $repository): Response
    {
        
       $em = $doctrine->getManager();
       $client = new Client();
       $client->setNom($_GET['nom']);
       $client->setprenom($_GET['prenom']);
       $id_of_application=$request->query->get('app');
       $app = $repository->find($id_of_application);
       $client->addApplication($app);
       

       $em->persist($client);
       $em->flush();
        
        return $this->redirectToRoute('app_client');     
    }

    #[Route('/update_client', name: 'update_client')]
    public function updateClient(Request $request,ManagerRegistry $doctrine,ClientRepository $repository,ApplicationRepository $repo): Response
    {
       $em = $doctrine->getManager();
       $Client = new Client();
       $client=$repository->find($_GET['id']);
       $client->setNom($_GET['nom']);
       $client->setprenom($_GET['prenom']);
       $id_of_application=$request->query->get('app');
       $app = $repo->find($id_of_application);
       $client->addApplication($app);
       

       
       $em->flush();
        
        return $this->redirectToRoute('app_client');     
    }
    
    
    
    
    
    
    #[Route('/update_client_form/{id}', name: 'update_client_form')]
    public function RenderupdateClient($id,ClientRepository $repository,ApplicationRepository $repo): Response
    {
        $client = $repository->find($id);
        $name=$client->getNom();
        $lastname=$client->getprenom();

        $applications = $repo->findAll();
    
        return $this->render('client/updateClient.html.twig', [
            'controller_name' => 'ClientController',
            'name' => $name,
            'lastname' => $lastname,
            'id' => $id,
            'applications' => $applications
            
        ]);
    }

    #[Route('/delete_client/{id}', name: 'delete_client')]
    public function deleteApplication(ClientRepository $repository,$id,ManagerRegistry $doctrine): Response
    {
        $em  = $doctrine->getManager();
        $client = $repository->find($id);
       
        $em->remove($client);
        $em->flush();
        

        return $this->redirectToRoute('app_client'); 
            
        
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
