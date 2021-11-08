<?php

namespace App\Controller;

use App\Entity\Jeux;
use App\Entity\Concurrent;
use App\EstimationResponse;
use App\Helper\EtatHelper;
use App\Helper\PriceReductionHelper;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;



class PricingController extends AbstractController
{

    /** pricingEstimation : trouve un prix pour un jeu en se basant sur la concurrence
     *
     * @Route("/pricing", name="princing")
     *
     * Body expected example:
     * {
     *    "jeuID" : 2,
     *    "prixMin" : 10,
     *    "prixMax" : 29.99,
     *    "etat" : "neuf"
     * }
     */
    public function pricingEstimation(Request $request, EntityManagerInterface $entityManager): Response
    {
        $params = json_decode($request->getContent(), true);
        $response = new EstimationResponse();

        // permet d'avoir la valeur numérique de l'état afin de le comparer à d'autre
        // si c'est égal à -1, l'état n'est pas reconnu
        $etatJeuValue = EtatHelper::getEtatValue($params["etat"]);

        //si l'etat n'est pas reconnu, on arrète l'estimation
        if( $etatJeuValue === -1){
          $response->setMessage("l'etat ''".$params['etat']."'' n'est pas reconnu.");
          $response->setPrix(0);
          return new Response($response->toJson());
        }

        $repository = $entityManager->getRepository(Jeux::class);
        $jeu = $repository->findOneBy(['id' => $params["jeuID"]]);

        // si le jeu n'existe pas en base de données, on peut dire que le vendeur est le seul à l'avoir
        // donc prixMax
        if(!isset($jeu)){
          $response->setMessage("Le jeu n'est pas en base de données");
          $response->setPrix($params["prixMax"]);
          return new Response($response->toJson());
        }

        $concurrentList = $jeu->getConcurrents();

        // si les concurrents ne vendent pas le jeu, alors prixMax
        if (count($concurrentList) == 0){
          $response->setMessage("aucun concurrent n'a le jeu");
          $response->setPrix($params["prixMax"]);
          return new Response($response->toJson());
        }

        // ces variables servent à avoir respectivement le plus petit prix parmi les qualité supérieur et parmi ceux de même qualité
        // si c'est égal à -1, c'est qu'on a rien trouvé.
        $prixEtatSup = -1;
        $prixMemeEtat = -1;

        foreach ($concurrentList as $row){
          // si c'est de même état
          if($etatJeuValue == EtatHelper::getEtatValue($row->getEtat())){
              $prixMemeEtat = ($prixMemeEtat != -1) ? min($prixMemeEtat, $row->getPrix()) : $row->getPrix();
          }
          // si l'état du concurent est superieur
          else if( $etatJeuValue < EtatHelper::getEtatValue($row->getEtat())){
              $prixEtatSup = ($prixEtatSup != -1) ? min($prixEtatSup, $row->getPrix()) : $row->getPrix();
          }
        }

        // si on a trouvé un concurrent qui a le jeu du même état que nous
        if($prixMemeEtat != -1){
          $response->setMessage("un concurrent possédant le jeu au même état le vend ".$prixMemeEtat." euros");
          $response->setPrix(max($params["prixMin"],$prixMemeEtat - PriceReductionHelper::REDUCTION_MEME_ETAT));
        }
        // si on a trouvé un concurrent qui a le jeu d'un meilleure état que nous
        else if($prixEtatSup != -1){
          $response->setMessage("un concurrent possédant le jeu dans un meilleur état le vend ".$prixEtatSup." euros");
          $response->setPrix(max($params["prixMin"],$prixEtatSup - PriceReductionHelper::REDUCTION_ETAT_SUP));
        }
        // enfin si on est le seul à posséder le jeu dans un meilleur état que tous les autres
        else{
          $response->setMessage("vous êtes le seul à avoir le jeu en meilleure qualité que les autres");
          $response->setPrix($params["prixMax"]);
        }

        return new Response($response->toJson());
    }

}
