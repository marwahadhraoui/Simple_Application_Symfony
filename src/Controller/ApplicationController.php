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

class ApplicationController extends AbstractController
{
    #[Route('/', name: 'app_application')]
    public function index(ApplicationRepository $repository,ClientRepository $repo): Response
    {
        
        $applications = $repository->findAll();
      
        return $this->render('application/index.html.twig', [
            'controller_name' => 'ApplicationController',
            'applications' => $applications,
            
         
        ]);
    }



    #[Route('/add_application', name: 'add_application')]
    public function addApplication(ManagerRegistry $doctrine): Response
    {
       $em = $doctrine->getManager();
       $application = new Application();
       $application->setNom($_GET['name']);
       $application->setUrl($_GET['url']);

       $em->persist($application);
       $em->flush();
        
        return $this->redirectToRoute('app_application');     
    }

    #[Route('/update_application', name: 'update_application')]
    public function updateApplication(ManagerRegistry $doctrine,ApplicationRepository $repository): Response
    {
       $em = $doctrine->getManager();
       $application = new Application();
       $application=$repository->find($_GET['id']);
       $application->setNom($_GET['name']);
       $application->setUrl($_GET['url']);

       
       $em->flush();
        
        return $this->redirectToRoute('app_application');     
    }
    
    
    
    
    
    
    #[Route('/update_application_form/{id}', name: 'update_application_form')]
    public function RenderupdateApplication(Request $request,$id,ApplicationRepository $repository): Response
    {
        $application = $repository->find($id);
        $name=$application->getNom();
        $url=$application->getUrl();
    
        return $this->render('application/updateApplication.html.twig', [
            'controller_name' => 'ApplicationController',
            'name' => $name,
            'url' => $url,
            'id' => $id
            
        ]);
    }

    #[Route('/delete_application/{id}', name: 'delete_application')]
    public function deleteApplication(ApplicationRepository $repository,$id,ManagerRegistry $doctrine): Response
    {
        $em  = $doctrine->getManager();
        $application = $repository->find($id);

        $em->remove($application);
        $em->flush();
        

        return $this->redirectToRoute('app_application'); 
            
        
    }





    #[Route('/add_application_Form', name: 'add_application_form')]
    public function RenderapplicationForm(): Response
    {
        
        return $this->render('application/ajouterApplication.html.twig', [
            'controller_name' => 'ApplicationController',
           
           
        ]);
    }


}
