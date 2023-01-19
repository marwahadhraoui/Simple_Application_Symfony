<?php

namespace App\Controller;

//use ApiPlatform\State\Pagination\PaginatorInterface;

use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Article;
use App\Form\ArticleFormType;
use App\Repository\ArticleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article')]
    public function index(Request $request ,ArticleRepository $repository): Response
    {
        //  we define the number of elements per page
        $limit =3;
       //   we get the page number
        $page =(int)$request->query->get("page",1);
        // we get the articles of the page
        $articles = $repository->getPaginatedArticle($page,$limit);
       
        //we get the total number of articles
        $total =count($repository->findAll());
        

      
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'articles' => $articles,
            'page'=>$page,
            'total'=>$total,
            'limit'=>$limit,
        ]);
    }

    #[Route('/article/new', name: 'new_article')]
    #[Route('/article/{id}/edit', name: 'edit_article')]
    public function form(Request $request,ManagerRegistry $manager,Article $article=null): Response
    {
        if(!$article){
            $article = new Article();
        }

        $form =$this->createForm(ArticleFormType::class,$article);

        $form->handleRequest($request);
       
        if($form->isSubmitted() && $form->isValid()){

            //If the article doesn't have an Id it means it is new so we set the date
            //else the article exists so we don't set a neew date

            if(!$article->getCreatedAt()){
                $article->setCreatedAt(new \DateTime());
            }
          
            $em = $manager->getManager();
            $em->persist($article);
            $em->flush();
            
            return $this->redirectToRoute('article_show',['id' =>$article->getId()
        ]);

        }
        return $this->render('article/addArticle.html.twig', [
            'controller_name' => 'ArticleController',
            'form_article'=>$form->createView(),
            'editMode'=>$article->getId() !== null
           
        ]);
    }

    #[Route('/article/{id}', name: 'article_show')]
    public function show($id,ArticleRepository $repository): Response
    {
        $article = $repository->find($id);

        return $this->render('article/show.html.twig', [
            'controller_name' => 'ArticleController',
            'article' =>$article
        ]);

    }

    #[Route('/delete/{id}', name:'delete_article')]
    public function delete($id,ArticleRepository $repository,ManagerRegistry $manager): Response
    {
        $article = $repository->find($id);
        $em = $manager->getManager();

        $em->remove($article);
        $em->flush();

        return $this->redirectToRoute('app_article');
            
        
    }


    #[Route('/home', name: 'home')]
    public function home(): Response
    {
        

        return $this->render('article/home.html.twig', [
            'controller_name' => 'ArticleController',
            
        ]);

    }
}
