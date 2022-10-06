<?php

namespace App\Data;

use DateTime;

class SearchData
{

    /**
     * 
     * @var int
    */
    public $page = 1;

    /**
     * Propriété en lien avec la classe SearchType (formulaire pour le filtre des évènements),
     * elle permettra de filtrer les évènements correspondant avec le mot clef saisi par l'utilisateur en lien avec le titre de l'évènement.
     * 
     * @var null|string
     */
    public $q = '';

    /**
     * Propriété en lien avec la classe SearchType (formulaire pour le filtre des évènements),
     * elle permettra de filtrer les évènements correspondant avec la date de début que l'utilisateur aura indiqué
     * 
     * @var null|DateTime
     */
    public $startDate;

    /**
     * 
     * Propriété en lien avec la classe SearchType (formulaire pour le filtre des évènements),
     * elle permettra de filtrer les évènements correspondant avec la date de fin que l'utilisateur aura indiqué
     * @var null|DateTime
     */
    public $endDate;

    /**
     * 
     * Propriété en lien avec la classe SearchType (formulaire pour le filtre des évènements),
     * elle permettra de filtrer les évènements correspondant avec la ville que l'utilisateur aura indiqué
     * 
     * @var null|string
     */ 
    public $town;

    /**
     * 
     * Propriété en lien avec la classe SearchType (formulaire pour le filtre des évènements),
     * elle permettra de filtrer les évènements correspondant avec le code postal que l'utilisateur aura indiqué
     * 
     * @var null|string
     */ 
    public $zipCode;

}

