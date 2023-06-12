<?php
/** @var array $categories */
/** @var array $error */

core\Core::getInstance()->pageParams['title'] = 'Update Categories';
?>

<h1>Edit | Delete</h1>
<?php foreach ($categories as $category) : ?>
    <form method="post" action="" enctype="multipart/form-data">
        <div class="mb-3 d-flex flex-wrap align-items-end">
            <input type="hidden" id="id" name="id" value="<?= $category['id'] ?>">
            <div class="me-2 edit-form-field">
                <input type="text" class="form-control" id="name" name="name" aria-describedby="nameHelp"
                       value="<?= $category['name'] ?>">
            </div>
            <div class="me-2 edit-form-field">
                <input class="form-control" type="file" id="file" name="file" accept="image/jpeg">
            </div>
            <div class="mt-2">
                <button type="submit" name="update" class="btn btn-primary">Update</button>
                <a href="/category/delete/<?= $category['id'] ?>" id="delete" class="btn btn-danger">Delete</a>
            </div>
            <?php if ((!empty($error)) && ($error['id'] == $category['id'])) : ?>
                <span id="nameHelp" class="form-text text-danger"><?= $error['name'] ?></span>
            <?php endif; ?>
        </div>
    </form>
<?php endforeach; ?>
