<main>
    <h1>Console d'Administration</h1>
    <h2>Console</h2>

    <div id="modules">
        <!-- Les modules vont ici -->
        <div class="module" id="users" onclick="window.location.href = 'index.php?c=Admin&a=Users'">
            <img class="module_image" src="Views/Admin/console/images/users.svg"/>
            <p class="module_title">Utilisateurs</p>
            <p class="module_description">Visualiser, modifier et supprimer des utilisateurs</p>
        </div>
        <div class="module" id="properties" onclick="window.location.href = 'index.php?c=Admin&a=Properties'">
            <img class="module_image" src="Views/Admin/console/images/properties.svg"/>
            <p class="module_title">Propriétés</p>
            <p class="module_description">Visualiser, modifier et supprimer des propriétés</p>
        </div>
    </div>
</main>