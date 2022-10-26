<?php

namespace App\Data;

class SearchAdmin
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
    public $userCreator = '';
}

