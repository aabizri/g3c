<?php

namespace Controllers;

use Repositories;
use Entities;

/**
 * Class Role
 * @package Controllers
 */

    class Role
    {
        public function getProperties(array $get, array $post): void
        {
            /*Vérifier que l'utilisateur existe */
            if (empty($user_id)) {
                echo "FUCK THIS SHIT";
                return;
            }

            // Récupérer liste des propriétés
            $role_ids = \Repositories\Roles::findAllByUserID($user_id);
            $properties = [];
            foreach ($role_ids as $rid) {
                // Retrieve role
                $role = \Repositories\Roles::retrieve($rid);

                // Retrieve property ID
                $pid = $role->getPropertyID();

                // Retrieve property
                $property = \Repositories\Properties::retrieve($pid);

                // Store
                $properties[] = $property;
            }

            // Appeler la vue
            \Helpers\DisplayManager::display("selectproperty",[$properties]);
        }
        
    }