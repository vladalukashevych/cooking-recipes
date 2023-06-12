<?php
include_once("admins-layout.php");



core\Core::getInstance()->pageParams['title'] = 'Backup';
?>

<h1 class="h2">Backup</h1>
<p class="lead text-muted">Choose your option to back up CookingRecipes database.</p>
<p>
    <a href="/administration/backup/server" class="btn btn-primary my-2">Save on server</a>
    <a href="/administration/backup/download" class="btn btn-secondary my-2">Download backup file</a>
</p>

</div>
</div>