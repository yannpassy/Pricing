<?php

namespace App\Controller;

use App\Entity\Jeux;
use App\Entity\Concurrent;
use App\Helper\EtatHelper;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

class DatabaseController extends AbstractController
{
    /** dataInitiate : ajoute les données de base pour les tables "concurrent" et "jeux"
     *
     * @Route("/data/initiate", name="initiate")
     */
    public function dataInitiate(EntityManagerInterface $entityManager): Response
    {
          //jeux
          $jeu1 = new Jeux();
          $jeu2 = new Jeux();
          $jeu3 = new Jeux();
          $jeu4 = new Jeux();

          $jeu1->setNom("Pokemon Jaune")->setEditeur("Nintendo")->setType("rpg, aventure");
          $jeu2->setNom("GTA V")->setEditeur("Rockstar")->setType("open world");
          $jeu3->setNom("Watch dogs")->setEditeur("Ubisoft")->setType("aventure, open world");
          $jeu4->setNom("Tekken 7")->setEditeur("Bandai Namco")->setType("combat");

          //concurrents
          $concurrent1 = new Concurrent();
          $concurrent2 = new Concurrent();
          $concurrent3 = new Concurrent();
          $concurrent4 = new Concurrent();

          $concurrent1->setVendeur("Abc jeux")->setPrix(20.25)->setEtat(EtatHelper::ETAT_COMME_NEUF);
          $concurrent2->setVendeur("Games-planete")->setPrix(10.50)->setEtat(EtatHelper::ETAT_BON);
          $concurrent3->setVendeur("Micro-jeux")->setPrix(39.99)->setEtat(EtatHelper::ETAT_NEUF);
          $concurrent4->setVendeur("Abc jeux")->setPrix(12.99)->setEtat(EtatHelper::ETAT_MOYEN);

          $entityManager->persist($concurrent1);
          $entityManager->persist($concurrent2);
          $entityManager->persist($concurrent3);
          $entityManager->persist($concurrent4);

          $jeu1->addConcurrent($concurrent1)->addConcurrent($concurrent2);
          $jeu2->addConcurrent($concurrent3);
          $jeu3->addConcurrent($concurrent4);

          $entityManager->persist($jeu1);
          $entityManager->persist($jeu2);
          $entityManager->persist($jeu3);
          $entityManager->persist($jeu4);
          $entityManager->flush();

          return new Response('les jeux et les concurrents ont été ajoutés');

    }

    //TODO: Créer fonctions permettant d'ajouter un jeu ou un concurent dans la database
    // en utilisant le donnée du body de la requète (POST)
}
