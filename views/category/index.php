<?php
/** @var array $categories */

use core\Core;
use models\User;

Core::getInstance()->pageParams['title'] = 'Categories';
?>

<h1 class="mb-3">Categories</h1>
<?php if (User::isAdmin()) : ?>
    <div class="mb-2">
        <a href="/category/add" class="btn btn-primary btn-success">Add Category</a>
        <a href="/category/update" class="btn btn-primary">Edit and Delete</a>
    </div>
<?php endif; ?>
<div class="m-sm-auto categories-list">
    <?php foreach ($categories as $category) : ?>
        <a href="/category/view/<?= $category['id'] ?>">
            <div class="card text-bg-dark card-width mb-3 category align-content-center">
                <img <?php $filePath = 'files/category/' . $category['photo'];
                if (is_file($filePath)) : ?>
                    src="/<?= $filePath ?>"
                <?php else : ?>
                    src="/files/category/default.jpg"
                <?php endif; ?>
                        class="card-img opacity-50" alt="<?= $category['name'] ?>">
                <div class="card-img-overlay">
                    <h2 class="card-title card-title-shadow category-card-title"><?= $category['name'] ?></h2>
                </div>
            </div>
        </a>
    <?php endforeach; ?>
</div>
