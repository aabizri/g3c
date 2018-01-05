# LiveWell by G3C Technologies for DomISEP

## Auteurs

- Dinesh Anthonipillai
- Randy Arnold
- Bryan Au
- Eytan Azria
- Jérémy Berda
- Alexandre Bizri

## Documentation interne

### Queries

Les *Queries* sont la méthode principale pour communiquer avec la BDD. Pour chaque type d'entité il existe un type de query correspondant. Celles-ci fonctionnent et sont construites de la manière suivante:

Il faut commencer par créer la query

````php
$peripherals_query = new \Queries\Peripherals;
````

De base, cette query n'a aucun filtre: elle s'applique à toutes les lignes de la table. Imaginons que nous voulons récupérer les 200 premiers périphériques liés à une propriétée.
On commence par filtrer par cette propriété.

````php
// Si on a déja en PHP l'entité \Entities\Property
$peripherals_query->filterByProperty($myproperty);

// Sinon par l'ID de la propriété
$peripherals_query->filterByPropertyID($mypropertyid);
````

Ensuite on peut les classer par ordre decroissant ou croissant de la date de création

````php
// Par ordre croissant
$peripherals_query->orderBy("creation_date",true); // Croissant, false pour décroissant
````

Finalement, on veut limiter à 200 le nombre de résultats, donc

````php
// Limitons à 200
$peripherals_query->limit(200);
````

Et récupérons les résultats

````php
$peripherals_list = $peripherals_query->find();
````

Et voilà, on a récupéré les 200 premiers périphériques liés à une propriétée. On peut ensuite condenser le code ainsi:

````php
$peripherals_list = (new \Queries\Peripherals)->filterByProperty($myproperty)->orderBy("creation_date",true)->limit(200)->find();
````

Bien sûr **ce n'est qu'un exemple, pour plus d'informations regarder le code des queries, et leur utilisation dans les controlleurs**.
