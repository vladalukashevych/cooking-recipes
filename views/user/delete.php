<?php
/** @var array $user */

core\Core::getInstance()->pageParams['title'] = 'Delete Profile - ' . $user['name'];
?>

<div class="alert alert-danger" role="alert">
    <h4 class="alert-heading">Do you really want to delete this profile?</h4>
    <p>You won't be able to restore it.</p>
    <hr>
    <a href="/user/delete/<?= $user['id'] ?>/yes" class="btn btn-danger">Delete</a>
    <a href="/user/profile/<?= $user['id'] ?>" class="btn btn-light">Cancel</a>
</div>