<?php
/** @var array $recipe */
/** @var array|null $category */
/** @var array $ingredients */
/** @var string $favourite */
/** @var int $favourite_count */


use core\Core;
use models\User;

Core::getInstance()->pageParams['title'] = $recipe['name'];
?>
<div class="container-xxs">
    <?php if (!empty($favourite)) : ?>
        <div class="recipe-favourite-btn float-right mt-3" id="recipe-favourite-btn">
            <?php echo "<script>";
            $rowJs = "addFavouriteButton({$recipe['id']}, {$favourite});\n";
            echo $rowJs;
            echo "</script>";
            ?>
            <p class="mt-2 fw-bold"><?=$favourite_count?></p>
        </div>
    <?php endif; ?>
    <h1 class="display-3 fw-semibold pt-3 pr-4"><?= $recipe['name'] ?></h1>
    <?php if (User::isAdmin()) : ?>
        <div class="my-3">
            <a href="/recipe/edit/<?= $recipe['id'] ?>" class="btn btn-primary">Edit</a>
            <a href="/recipe/delete/<?= $recipe['id'] ?>" class="btn btn-danger">Delete</a>
        </div>
    <?php endif; ?>
    <div class="recipe-info mt-3">
        <div class="text-muted fw-semibold recipe-description mb-3"><?= $recipe['description'] ?></div>
    </div>
    <?php if (!empty($recipe['photo'])) : ?>
        <div class="recipe-img-box my-3">
            <img src="/files/recipe/<?= $recipe['photo'] ?>"
                 class="recipe-img img-fluid rounded-1 object-fit-cover">
        </div>
    <?php endif; ?>
    <div class="recipe-nutrition mb-5">
        <table class="recipe-short-info rounded-1 d-block align-self-center">
            <?php if (!is_null($category)): ?>
                <tr>
                    <td colspan="3" class="recipe-category-td"><a href="/category/view/<?= $category['id'] ?>"
                                                                  class="text-decoration-none recipe-category text-uppercase"><?= $category['name'] ?></a>
                    </td>
                </tr>
            <?php endif; ?>
            <tr>
                <th>Time</th>
                <th>Servings</th>
                <th>Calories</th>
            </tr>
            <tr>
                <td><?= $recipe['time'] ?> mins</td>
                <td><?= $recipe['servings'] ?> persons</td>
                <td><?= $recipe['calories'] ?> cal</td>
            </tr>
        </table>
    </div>
    <div class="recipe-ingredients">
        <h2 class="mb-3 title-underline">Ingredients</h2>
        <ul>
            <?php if (empty($ingredients)) : ?>
                <p>Whooops, no ingredients in the recipe.</p>
            <?php else :
                foreach ($ingredients as $ingredient) : ?>
                    <li class="fs-5 mb-3"><?= $ingredient['amount'] . ' ' . $ingredient['name'] ?></li>
                <?php endforeach;
            endif; ?>
        </ul>
    </div>

    <div class="recipe-instructions mt-6">
        <h2 class="mb-3 title-underline">Instructions</h2>
        <div class="fs-5">
            <?php if (empty($recipe['instructions'])) : ?>
                <p>Whooops, somebody forgot to tell us how to make this.</p>
            <?php else : ?>
                <?= $recipe['instructions'] ?>
            <?php endif; ?>
        </div>
    </div>
</div>

