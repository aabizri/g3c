<main>
    <h1>Console d'Administration</h1>
    <h2>Console</h2>

    <div id="modules">
        <!-- Les modules vont ici -->
        <div class="module" id="users" onclick="window.location.href = 'admin/users'"
             title="Visualiser, modifier et supprimer des utilisateurs">
            <img class="module_image" src="Views/Admin/console/images/users.svg"/>
            <p class="module_title">Utilisateurs</p>
            <p class="module_description">Visualiser, modifier et supprimer des utilisateurs</p>
        </div>
        <div class="module" id="properties" onclick="window.location.href = 'admin/properties'"
             title="Visualiser, modifier et supprimer des propriétés">
            <img class="module_image" src="Views/Admin/console/images/properties.svg"/>
            <p class="module_title">Propriétés</p>
            <p class="module_description">Visualiser, modifier et supprimer des propriétés</p>
        </div>
        <div class="module" id="peripherals" onclick="window.location.href = 'admin/peripherals'"
             title="Visualiser, modifier et supprimer des périphériques">
            <img class="module_image" src="Views/Admin/console/images/peripherals.svg"/>
            <p class="module_title">Périphériques</p>
            <p class="module_description">Visualiser, modifier et supprimer des périphériques</p>
        </div>
        <div hidden class="module" id="products" onclick="window.location.href = 'admin/products'"
             title="Visualiser, modifier et supprimer des produits de la boutique">
            <img class="module_image" src="Views/Admin/console/images/products.svg"/>
            <p class="module_title">Produits</p>
            <p class="module_description">Visualiser, modifier et supprimer des produits</p>
        </div>
    </div>
</main>