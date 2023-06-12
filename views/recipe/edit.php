<?php
/** @var array $errors */
/** @var array $categories */
/** @var array $model */
/** @var array $ingredients */
core\Core::getInstance()->pageParams['title'] = 'Edit Recipe - ' . $model['name'];
?>

<h1>Edit Recipe</h1>
<form method="post" action="" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" name="name" aria-describedby="nameHelp"
               value="<?= $model['name'] ?>">
        <?php if (!empty($errors['name'])) : ?>
            <div id="nameHelp" class="form-text text-danger"><?= $errors['name'] ?></div>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label for="name" class="form-label">Description</label>
        <textarea type="text" class="form-control" id="description" name="description"
                  aria-describedby="descriptionHelp"><?= $model['description'] ?></textarea>
        <?php if (!empty($errors['description'])) : ?>
            <div id="descriptionHelp" class="form-text text-danger"><?= $errors['description'] ?></div>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label for="time" class="form-label">Time (minutes)</label>
        <input type="text" class="form-control" id="time" name="time" aria-describedby="timeHelp"
               value="<?= $model['time'] ?>">
        <?php if (!empty($errors['time'])) : ?>
            <div id="timeHelp" class="form-text text-danger"><?= $errors['time'] ?></div>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label for="servings" class="form-label">Servings</label>
        <input type="text" class="form-control" id="servings" name="servings" aria-describedby="servingsHelp"
               value="<?= $model['servings'] ?>">
        <?php if (!empty($errors['servings'])) : ?>
            <div id="servingsHelp" class="form-text text-danger"><?= $errors['servings'] ?></div>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label for="calories" class="form-label">Calories per serving</label>
        <input type="text" class="form-control" id="calories" name="calories" aria-describedby="caloriesHelp"
               value="<?= $model['calories'] ?>">
        <?php if (!empty($errors['calories'])) : ?>
            <div id="caloriesHelp" class="form-text text-danger"><?= $errors['calories'] ?></div>
        <?php endif; ?>
    </div>
    <div class="mb-3 clearfix">
        <label for="ingredient-name" class="form-label">Ingredient</label>
        <?php if (!empty($errors['ingredients'])) : ?>
            <div class="form-text text-danger mb-2"><?= $errors['ingredients'] ?></div>
        <?php endif; ?>
        <div id="ingredients">
            <?php
            echo "<script>";
            if (!empty($ingredients))
                foreach ($ingredients as $ingredient)
                    echo "addIngredientFields(\"" . $ingredient['id'] . "\", \"" . $ingredient['name'] . "\", \"" . $ingredient['amount'] . "\");";
            else
                foreach ($model['ingredient-id'] as $key => $id)
                    echo "addIngredientFields(\"" . $id . "\", \"" . $model['ingredient-name'][$key] . "\", \"" . $model['ingredient-amount'][$key] . "\");";
            echo "</script>";
            ?>
        </div>
        <input type="button" class="btn btn-success mt-1 float-right" id="add-ingredient-field-btn" value="Add Field"
               onclick="addIngredientFields()">
    </div>
    <div class="mb-3">
        <label for="instructions" class="form-label">Instructions</label>
        <textarea class="form-control ckeditor" id="instructions" name="instructions"
                  aria-describedby="instructionsHelp"><?= $model['instructions'] ?></textarea>
        <?php if (!empty($errors['instructions'])) : ?>
            <div id="instructionsHelp" class="form-text text-danger"><?= $errors['instructions'] ?></div>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label for="file" class="form-label">Image</label>
        <?php $filePath = 'files/recipe/' . $model['photo'];
        if (is_file($filePath)) : ?>
            <div class="recipe-form-img-box">
                <img src="/<?= $filePath ?>" class="recipe-form-img">
            </div>
        <?php endif; ?>
        <input class="form-control" type="file" id="file" name="file" accept="image/jpeg">
    </div>
    <div class="mb-3">
        <label for="category" class="form-label">Category</label>
        <select class="form-control" id="category" name="category_id" aria-describedby="categoryHelp">
            <option name="category" value="NULL"></option>
            <?php foreach ($categories as $category) : ?>
                <option <?php if ($category['id'] == $model['category_id']) echo 'selected'; ?>
                        name="category" value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
            <?php endforeach; ?>
        </select>
        <?php if (!empty($errors['category'])) : ?>
            <div id="categoryHelp" class="form-text text-danger"><?= $errors['category'] ?></div>
        <?php endif; ?>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>

<script src="https://cdn.ckeditor.com/ckeditor5/35.4.0/classic/ckeditor.js"></script>
<script>
    let ckeditors = document.querySelectorAll('.ckeditor');
    for (let ckeditor of ckeditors) {
        ClassicEditor
            .create(ckeditor)
            .catch(error => {
                console.error(error);
            });
    }

</script>
