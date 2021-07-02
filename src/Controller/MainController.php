<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Form\NewsActualitesFormType;
use Symfony\Component\HttpFoundation\Request;
use \DateTime;


class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(): Response
    {
        // Cette page appellera la vue templates/main/index.html.twig
        return $this->render('home.html.twig');
    }


    /**
     * @Route("/les-actualites", name="actualites")
     */
    public function actualites(): Response
    {
        $articleRepo = $this->getDoctrine()->getRepository(Article::class);


        $ArticleList = $articleRepo->findBy([], ['id' => 'DESC']);

        return $this->render('actualites.html.twig', [
            'ArticleList' => $ArticleList
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
        $newArticle->setPublicationDate(new DateTime());


        $form->handleRequest($request);

        // Pour savoir si le formulaire a été envoyé, on a accès à cette condition :
            if($form->isSubmitted() && $form->isValid()){

                // Extraction de l'objet de la photo envoyée dans le formulaire
                $photo = $form->get('image')->getData();

                // Création d'un nouveau nom aléatoire pour la photo avec son extension (récupérée via la méthode guessExtension() )
                $newFileName = md5(time() . rand() . uniqid() ) . '.' . $photo->guessExtension();

                $newArticle->setImage($newFileName);

                // Déplacement de la photo dans le dossier que l'on avait paramétré dans le fichier services.yaml, avec le nouveau nom qu'on lui a généré
                $photo->move(
                    $this->getParameter('app.user.photos.directory'),     // Emplacement de sauvegarde du fichier
                    $newFileName    // Nouveau nom du fichier
                );


                // récupération du manager des entités et sauvegarde en BDD de $newArticle
                $em = $this->getDoctrine()->getManager();
                
                $em->persist($newArticle);
                
                $em->flush();
            
                // Si le formulaire a été envoyé, on dump notre article, qui est pré-rempli automatiquement avec les données provenant du formulaire !

                return $this->redirectToRoute('actualites');

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

}