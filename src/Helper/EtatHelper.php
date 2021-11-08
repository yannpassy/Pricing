<?php

namespace App\Helper;

class EtatHelper{

    const ETAT_MOYEN = "moyen";
    const ETAT_BON = "bon";
    const ETAT_TRES_BON = "très bon";
    const ETAT_COMME_NEUF = "comme neuf";
    const ETAT_NEUF = "neuf";

    /** getEtatValue: permet d'avoir la valeur numerique d'un état afin de les comparer
    * $etat : string
    * return: integer
    * return -1 si l'état est inconnu
    *
    */
    public static function getEtatValue($etat){
        $result = -1;

        switch($etat){
            case self::ETAT_MOYEN:
                $result = 1;
                break;

            case self::ETAT_BON:
                $result = 2;
                break;

            case self::ETAT_TRES_BON:
                $result = 3;
                break;

            case self::ETAT_COMME_NEUF:
                $result = 4;
                break;

            case self::ETAT_NEUF:
                $result = 5;
                break;
        }

        return $result;
    }
}
