<?php
/** @var array $category */

core\Core::getInstance()->pageParams['title'] = 'Delete Category - ' . $category['name'];
?>

<div class="alert alert-danger" role="alert">
    <h4 class="alert-heading">Do you really want to delete "<?= $category['name'] ?>"?</h4>
    <p>Category of these recipes will be set to NULL.</p>
    <hr>
    <a href="/category/delete/<?= $category['id'] ?>/yes" class="btn btn-danger">Delete</a>
    <a href="/category/update" class="btn btn-light">Cancel</a>
</div>