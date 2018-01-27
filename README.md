# LiveWell by G3C Technologies for DomISEP

## Auteurs

- Dinesh Anthonipillai
- Randy Arnold
- Bryan Au
- Eytan Azria
- Jérémy Berda
- Alexandre Bizri

## URL Valides et controlleurs associés
| URL					   | Controlleur			| Paramètre GET |
| ---------------------------------------- | ---------------------------------- | ------------- |
| `^admin$`                                | \Controllers\Admin::Console	| |
| `^admin/users$`                          | \Controllers\Admin::Users		| |
| `^admin/user/([0-9]+)$`                  | \Controllers\Admin::User		| ["uid" => $1] |
| `^admin/user/([0-9]+)/sessions$`         | \Controllers\Admin::UserSessions	| ["uid" => $1] |
| `^admin/user/([0-9]+)/requests$`         | \Controllers\Admin::UserRequests	| ["uid" => $1] |
| `^admin/user/([0-9]+)/properties$`       | \Controllers\Admin::UserProperties	| ["uid" => $1] |
| `^admin/properties$`                     | \Controllers\Admin::Properties	| ["pid" => $1] |
| `^admin/property/([0-9]+)$`              | \Controllers\Admin::Property	| ["pid" => $1] |
| `^admin/property/([0-9]+)/users$`        | \Controllers\Admin::PropertyUsers	| ["pid" => $1] |
| `^admin/property/([0-9]+)/peripherals$`  | \Controllers\Admin::PropertyPeripherals | ["pid" => $1] |
| `^login$`                                | \Controllers\User::Login		| |
| `^logout$`                               | \Controllers\User::Logout		| |
| `^join$`                                 | \Controllers\User::Join		| |
| `^account$`                              | \Controllers\User::Account		| |
| `^account/subscription$`                 | \Controllers\Subscription::Status	| |
| `^properties$`                           | \Controllers\Properties::Select	| |
| `^properties/([0-9]+)$`                  | \Controllers\Properties::Dashboard | ["pid" => $1] |
| `^properties/([0-9]+)/rooms$`            | \Controllers\Room::Rooms		| ["pid" => $1] |
| `^properties/([0-9]+)/rooms/([0-9]+)$`   | \Controllers\Rooms::Room		| ["pid" => $1] |&rid=$2
| `^properties/([0-9]+)/peripherals$`      | \Controllers\Peripherals::List	| ["pid" => $1] |
| `^properties/([0-9]+)/peripherals/([0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12})/remove` | \Controllers\Peripherals::Create | ["pid" => $1, "puuid" => $2] |
| `^properties/([0-9]+)/parameters$`       | \Controllers\Property::Parameters	| ["pid" => $1] |
| `^properties/([0-9]+)/consignes$`        | \Controllers\Property::Consignes	| ["pid" => $1] |

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
