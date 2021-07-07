<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Form\NewsActualitesFormType;
use App\Form\EditFormType;

use Symfony\Component\HttpFoundation\Request;
use \DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;



class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(): Response
    {
        $articleRepo = $this->getDoctrine()->getRepository(Article::class);


        $ArticleList = $articleRepo->findBy([], ['id' => 'DESC']);


        
        

        return $this->render('actualites.html.twig', [
            'ArticleList' => $ArticleList,
        ]);
        
    }

    /**
     * @Route("/nouvelle-actualite", name="newsactualites")
     */
    public function newsactualites(Request $request): Response
    {

        // Création d'un nouvel objet de la classe Article, vide pour le moment
        $newArticle = new Article();

        // Création d'un nouveau formulaire à partir de notre formulaire NewsActualitesFormTypee et de notre nouvel article encore vide
        $form = $this->createForm(NewsActualitesFormType::class, $newArticle);
        $newArticle->setAddDate(new DateTime());


        $form->handleRequest($request);

        // Pour savoir si le formulaire a été envoyé, on a accès à cette condition :
            if($form->isSubmitted() && $form->isValid()){




                // récupération du manager des entités et sauvegarde en BDD de $newArticle
                $em = $this->getDoctrine()->getManager();

                $em->persist($newArticle);

                $em->flush();

                // Si le formulaire a été envoyé, on dump notre article, qui est pré-rempli automatiquement avec les données provenant du formulaire !

                return $this->redirectToRoute('main');

            } 

        // Pour que la vue puisse afficher le formulaire, on doit lui envoyer le formulaire généré, avec $form->createView()
        return $this->render('newsactualites.html.twig', [
             'newactualitesForm' => $form->createView()
        ]);


    }

    /**
     * @Route("/actualite/{slug}", name="actualite")
     */
    public function actualite(Article $article, Request $request): Response
    {
        dump($article);
        
        return $this->render('actualite.html.twig', [
            'article' => $article,
        ]);
    }

     /**
     * Page admin servant à supprimer un article via son id passé dans l'url
     *
     * @Route("/suppression/{id}/", name="delete")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function delete(Article $article, Request $request): Response
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($article);
        $em->flush();

        // Redirection de l'utilisateur sur la liste des articles
        return $this->redirectToRoute('main');
    }

    /**
     * Page admin permettant de modifier un article existant via son id passé dans l'url
     *
     * @Route("/modifier/{id}/", name="edit")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function publicationEdit(Article $article, request $request): Response
    {
        // Création du formulaire de modification de livre 
        $form = $this->createForm(EditFormType::class, $article);
        if($article->getImage() != null){

            // Suppression de l'ancienne photo
            unlink($this->getParameter('app.user.photos.directory') . $article->getImage());
            }dump($article);
        // Liaison des données de requête (POST) avec le formulaire
        $form->handleRequest($request);

        // Si le formulaire est envoyé et n'a pas d'erreur
        if($form->isSubmitted() && $form->isValid()){


            

            // Sauvegarde des changements faits dans l'article via le manager général des entités
            $em = $this->getDoctrine()->getManager();
            $em->flush();


            // Redirection vers la page de l'article modifié
            return $this->redirectToRoute('main');

        }

        // Appel de la vue en lui envoyant le formulaire à afficher
        return $this->render('edit.html.twig', [
            'form' => $form->createView(),
            'article' => $article
        ]);

    }

}