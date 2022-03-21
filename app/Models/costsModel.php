<?php

namespace Antoineg\Omniscient\App\Models;

use Antoineg\Omniscient\Core\Traits\ModelTrait;

class costsModel
{

    use ModelTrait;

    private $table = 'costs';
    private $names = [
      'Gaz',
      'Courses Leclerc',
      'Macif',
      'P.E.L.',
      'SFR',
      'Pharmacie',
      'Repas midi',
      'Total',
      'Location voiture',
      'Resto',
      'Ubereats',
      'Cadeau Noémie',
      'Parking',
      'Péage',
      'Retrait',
      'Sncf aller',
      'Sncf retour'
    ];

    public function get()
    {
      return $this->jds->select([
        't' => $this->table
      ]);
    }

    public function insert_rand()
    {
      $budgets = count($this->jds->select([
        't' => 'budgets'
      ]));
      $cost = new \stdClass();
      $cost->name = $this->names[array_rand($this->names)];
      $cost->amount = rand(10,100);
      $cost->budgetId = rand(1,$budgets);
      $this->jds->insert($this->table,$cost);
    }

}