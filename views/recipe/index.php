<?php
/** @var array $recipes */
/** @var array $favourites */
/** @var array $model */
/** @var array $categories */
/** @var string $search */

/** @var array $pages */

use core\Core;
use models\User;

$authenticated = User::isUserAuthenticated();


Core::getInstance()->pageParams['title'] = 'Recipes';
?>

<h1 class="mb-3">Recipes</h1>
<?php if (User::isAdmin()) : ?>
    <div class="mb-2">
        <a href="/recipe/add" class="btn btn-primary btn-success">Add Recipe</a>
    </div>
<?php endif; ?>

<div class="filters">
    <form action="" method="get" enctype="multipart/form-data">
        <div>
            <label for="category" class="form-label">Category</label>
            <select class="form-control" id="category" name="category_id">
                <option name="category"></option>
                <?php foreach ($categories as $category) : ?>
                    <option name="category" value="<?= $category['id'] ?>"
                        <?php if ($model['category_id'] == $category['id']) echo 'selected'; ?>>
                        <?= $category['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="time" class="form-label">Time (less than)</label>
            <select class="form-control" id="time" name="time">
                <option name="time"></option>
                <?php $times = [15, 30, 45, 60, 90, 120];
                foreach ($times as $time) : ?>
                    <option name="time" value="<?= $time ?>" <?php if ($model['time'] == $time) echo 'selected'; ?>>
                        <?php if ($time < 60) echo "{$time} min"; else {
                            $time /= 60;
                            echo "{$time} hr";
                        } ?> </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="calories" class="form-label">Calories</label>
            <div class="minmax">
                <input type="number" class="form-control" placeholder="min" id="calories" name="cal_min" min="0"
                       value="<?php if (!is_null($model['cal_min']) && $model['cal_min'] != 0) echo $model['cal_min']; else echo 'NULL'; ?>">
                <input type="number" class="form-control" placeholder="max" name="cal_max" min="0"
                       value="<?php if (!is_null($model['cal_max']) && $model['cal_max'] != 0) echo $model['cal_max']; else echo 'NULL'; ?>">
            </div>
        </div>
        <div>
            <label for="servings" class="form-label">Servings</label>
            <div class="minmax">
                <input type="number" class="form-control" placeholder="min" id="servings" name="ser_min" min="0"
                       value="<?php if (!is_null($model['ser_min']) && $model['ser_min'] != 0) echo $model['ser_min']; else echo 'NULL'; ?>">
                <input type="number" class="form-control" placeholder="max" name="ser_max" min="0"
                       value="<?php if (!is_null($model['ser_max']) && $model['ser_max'] != 0) echo $model['ser_max']; else echo 'NULL'; ?>">
            </div>
        </div>
        <div class="order">
            <div class="button">
                <button type="submit" class="btn btn-success">Filter</button>
            </div>
            <div><label for="order" class="form-label">Order by</label>
                <select class="form-control" id="order" name="order">
                    <option name="order" value="desc" <?php if ($model['order'] == 'desc') echo 'selected'; ?>>Newest
                    </option>
                    <option name="order" value="asc" <?php if ($model['order'] == 'asc') echo 'selected'; ?>>Oldest
                    </option>
                </select>
            </div>
        </div>
    </form>
</div>

<?php if (!empty($search) && empty($recipes)) : ?>
    <div class="display-5 mx-auto sorry-message w-100 text-center">Sorry, looks like there is no match for "<span
                class="fw-bold"><?= $search ?></span>" :(
    </div>
<?php endif; ?>
<?php if (!empty($model['category_id']) && empty($recipes)) : ?>
    <div class="display-5 mx-auto sorry-message w-100 text-center">Sorry, looks like no recipe is matching your request.
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

<?php
if ($pages['count'] != 0) :

$str = "";
if (!is_null($model['category_id']))
    $str .= "&category_id={$model['category_id']}&time={$model['time']}&cal_min={$model['cal_min']}&cal_max={$model['cal_max']}&ser_min={$model['ser_min']}&ser_max={$model['ser_max']}&order={$model['order']}";
if (!empty($model['search']))
    $str .= "&search={$model['search']}"
?>
<div class="d-flex justify-content-center mt-5">
    <nav class="">
        <ul class="pagination">
            <li class="page-item <?php if ($pages['current'] == 1) echo 'disabled'; ?>">
                <a class="page-link green" href="/recipe?page=<?= $pages['current'] - 1 ?><?= $str ?>">Previous</a>
            </li>
            <?php for ($i = 1; $i <= $pages['count']; $i++) : ?>
                <li class="page-item <?php if ($i == $pages['current']) echo 'active green'; ?>"><a
                            class="page-link green" href="/recipe?page=<?= $i ?><?= $str ?>"><?= $i ?></a></li>
            <?php endfor; ?>
            <li class="page-item <?php if ($pages['current'] == $pages['count']) echo 'disabled'; ?>">
                <a class="page-link green" href="/recipe?page=<?= $pages['current'] + 1 ?><?= $str ?>">Next</a>
            </li>
        </ul>
    </nav>
</div>

<?php endif;?>