<?php
/** @var array $recipe */

core\Core::getInstance()->pageParams['title'] = 'Delete Recipe - ' . $recipe['name'];
?>

<div class="alert alert-danger" role="alert">
    <h4 class="alert-heading">Do you really want to delete this recipe "<?= $recipe['name'] ?>"?</h4>
    <p>You won't be able to restore it.</p>
    <hr>
    <a href="/recipe/delete/<?= $recipe['id'] ?>/yes" class="btn btn-danger">Delete</a>
    <a href="/recipe/view/<?= $recipe['id'] ?>" class="btn btn-light">Cancel</a>
</div>