<?php
/** @var array $errors */

core\Core::getInstance()->pageParams['title'] = 'Add Category';
?>

<h1>Add Category</h1>
<form method="post" action="" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" name="name" aria-describedby="nameHelp">
        <?php if (!empty($errors['name'])) : ?>
            <div id="nameHelp" class="form-text text-danger"><?= $errors['name'] ?></div>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label for="file" class="form-label">Image</label>
        <input class="form-control" type="file" id="file" name="file" accept="image/jpeg">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>