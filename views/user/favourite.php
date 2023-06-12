<?php
/** @var array $recipes */
/** @var array $pages */

use core\Core;
use models\Favourite;
use models\User;

Core::getInstance()->pageParams['title'] = 'Favourites';
?>

<h1 class="mb-3">Favourites</h1>
<?php if (empty($recipes)) : ?>
    <div class="display-5 mx-auto sorry-message w-100 text-center">Looks like you haven't liked anything yet.
        <br>Want to take a look on some some
        <a href="/recipe" class="fw-semibold fst-italic link-dark text-decoration-none">recipes</a>?
    </div>
<?php else : ?>
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
        $rowJs .= ", true, true);\n";
        echo $rowJs;
    }
    echo "</script>";
    endif;
    ?>
</div>

<?php if ($pages['count'] > 1) : ?>
    <div class="d-flex justify-content-center mt-5">
        <nav class="">
            <ul class="pagination">
                <li class="page-item <?php if ($pages['current'] == 1) echo 'disabled'; ?>">
                    <a class="page-link green" href="/user/favourite?page=<?= $pages['current'] - 1 ?>">Previous</a>
                </li>
                <?php for ($i = 1; $i <= $pages['count']; $i++) : ?>
                    <li class="page-item <?php if ($i == $pages['current']) echo 'active green'; ?>"><a
                                class="page-link green" href="/user/favourite?page=<?= $i ?>"><?= $i ?></a></li>
                <?php endfor; ?>
                <li class="page-item <?php if ($pages['current'] == $pages['count']) echo 'disabled'; ?>">
                    <a class="page-link green" href="/user/favourite?page=<?= $pages['current'] + 1 ?>">Next</a>
                </li>
            </ul>
        </nav>
    </div>
<?php endif; ?>