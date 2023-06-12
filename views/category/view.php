<?php
/** @var array $category */
/** @var array $recipes */
/** @var array $favourites */

/** @var array $pages */

use core\Core;
use models\User;

$authenticated = User::isUserAuthenticated();
Core::getInstance()->pageParams['title'] = $category['name'];
?>

<h1 class="mb-3"><?= $category['name'] ?></h1>
<?php if (User::isAdmin()) : ?>
    <div class="mb-3">
        <a href="/recipe/add/<?= $category['id'] ?>" class="btn btn-success">Add Recipe</a>
    </div>
<?php endif; ?>

<?php if (empty($recipes)) : ?>
    <div class="display-5 mx-auto sorry-message w-100 text-center">Seems like there are no recipes in this category yet!<br>Want to
        <a href="/recipe/add/<?= $category['id'] ?>" class="fw-semibold fst-italic link-dark text-decoration-none">add</a> one?
    </div>
<?php endif; ?>

<div class="m-lg-auto recipes-list" id="recipes-list">
    <?php echo "<script>";
    foreach ($recipes as $recipe) {
        $rowJs = "addRecipeCard(\"{$recipe['id']}\", \"{$recipe['name']}\", \"{$recipe['description']}\", 
            \"{$recipe['time']}\"";
        $filePath = 'files/recipe/' . $recipe['photo'];
        if (is_file($filePath))
            $rowJs .= ", '{$filePath}'";
        else
            $rowJs .= ", null";
        if ($authenticated)
            $rowJs .= ", true";
        if (!empty($favourites)) {
            foreach ($favourites as $favourite)
                if (isset($favourite['recipe_id']) && $favourite['recipe_id'] == $recipe['id'])
                    $rowJs .= ", true";
        }
        $rowJs = $rowJs . ");\n";
        echo $rowJs;
    }
    echo "</script>";
    ?>
</div>

<?php if ($pages['count'] > 1) : ?>
    <div class="d-flex justify-content-center mt-5">
        <nav class="">
            <ul class="pagination">
                <li class="page-item <?php if ($pages['current'] == 1) echo 'disabled'; ?>">
                    <a class="page-link green" href="/category/view/<?= $category['id'] ?>/?page=<?= $pages['current'] - 1 ?>">Previous</a>
                </li>
                <?php for ($i = 1; $i <= $pages['count']; $i++) : ?>
                    <li class="page-item <?php if ($i == $pages['current']) echo 'active green'; ?>"><a
                                class="page-link green" href="/category/view/<?= $category['id'] ?>/?page=<?= $i ?>"><?= $i ?></a></li>
                <?php endfor; ?>
                <li class="page-item <?php if ($pages['current'] == $pages['count']) echo 'disabled'; ?>">
                    <a class="page-link green" href="/category/view/<?= $category['id'] ?>/?page=<?= $pages['current'] + 1 ?>">Next</a>
                </li>
            </ul>
        </nav>
    </div>
<?php endif; ?>

