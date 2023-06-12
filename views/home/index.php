<?php
/** @var array $recipes */

use core\Core;

Core::getInstance()->pageParams['title'] = 'Home';
?>

    <div class="row mb-3">
    <div class="col px-0 text-bg-secondary p-4 p-md-5 rounded">
        <h1 class="display-4 fst-italic fw-semibold">Welcome to Cooking Recipes</h1>
        <p class="lead my-3 clearfix">With everything from creamy pastas to burgers and beyond, these vegan recipes will
            get you
            excited to cook plant-based meals at home.</p>
        <p class="lead mb-0"><a href="/recipe" class="text-white text-decoration-none">Start your journey right now!</a>
        </p>
    </div>
<?php if (!empty($recipes)) : ?>
    <div class="col-4">
        <a href="/recipe/view/<?= $recipes[0]['id'] ?>" class="text-decoration-none text-dark">
            <div class="card">
                <?php if (!empty($recipes[0]['photo'])) : ?>
                    <img src="/files/recipe/<?= $recipes[0]['photo'] ?>"
                         class="card-img-top home-recipe-img object-fit-cover" alt="<?= $recipes[0]['name'] ?>">
                <?php endif; ?>
                <div class="card-body">
                    <h5 class="card-title"><?= $recipes[0]['name'] ?></h5>
                    <p class="card-text"><?= $recipes[0]['description'] ?></p>
                </div>
            </div>
        </a>
    </div>
    </div>
    <div class="row mb-3">
        <div class="col-3 p-4 bg-light rounded home-tip">
            <h4 class="fst-italic">A Little Tip</h4>
            <p class="mb-0">
                Implement new ingredients. Most vegan dishes are easy to create with
                familiar ingredients. A world of new ingredients is presented to
                you, from superfood powders to new fruits and vegetable, that you may never have heard of before.</p>
        </div>
        <div class="col card home-recipe-long">
            <a href="/recipe/view/<?= $recipes[1]['id'] ?>" class="text-decoration-none text-dark">
                <div class="row g-0 ">
                    <?php if (!empty($recipes[1]['photo'])) : ?>
                        <div class="col-md-4">
                            <img src="/files/recipe/<?= $recipes[1]['photo'] ?>"
                                 class="home-recipe-long-img rounded-start" alt="<?= $recipes[1]['name'] ?>">
                        </div>
                    <?php endif; ?>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title"><?= $recipes[1]['name'] ?></h5>
                            <p class="card-text"><?= $recipes[1]['description'] ?></p>
                            <p class="card-text"><small class="text-muted">Cooking time: <?= $recipes[1]['time'] ?>
                                    minutes</small></p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="row mb-3">
        <div class="card-group">
            <div class="card card-group-recipe-card">
                <a href="/recipe/view/<?= $recipes[2]['id'] ?>" class="text-decoration-none text-dark">
                <?php if (!empty($recipes[2]['photo'])) : ?>
                    <div class="col p-2">
                        <img src="/files/recipe/<?= $recipes[2]['photo'] ?>"
                             class="card-group-recipe-img card-img" alt="<?= $recipes[2]['name'] ?>">
                    </div>
                <?php endif; ?>
                <div class="card-body">
                    <h5 class="card-title"><?= $recipes[2]['name'] ?></h5>
                    <p class="card-text"><?= $recipes[2]['description'] ?></p>
                    <p class="card-text"><small class="text-muted">Cooking time: <?= $recipes[2]['time'] ?>
                            minutes</small></p>
                </div>
                </a>
            </div>
            <div class="card card-group-recipe-card">
                <a href="/recipe/view/<?= $recipes[3]['id'] ?>" class="text-decoration-none text-dark">
                <div class="card-body">
                    <h5 class="card-title"><?= $recipes[3]['name'] ?></h5>
                    <p class="card-text"><?= $recipes[3]['description'] ?></p>
                    <p class="card-text"><small class="text-muted">Cooking time: <?= $recipes[3]['time'] ?>
                            minutes</small></p>
                </div>
                <?php if (!empty($recipes[3]['photo'])) : ?>
                    <div class="col p-2">
                        <img src="/files/recipe/<?= $recipes[3]['photo'] ?>"
                             class="card-group-recipe-img card-img" alt="<?= $recipes[3]['name'] ?>">
                    </div>
                <?php endif; ?>
                </a>
            </div>
            <div class="card card-group-recipe-card">
                <a href="/recipe/view/<?= $recipes[4]['id'] ?>" class="text-decoration-none text-dark">
                <?php if (!empty($recipes[4]['photo'])) : ?>
                    <div class="col p-2">
                        <img src="/files/recipe/<?= $recipes[4]['photo'] ?>"
                             class="card-group-recipe-img card-img" alt="<?= $recipes[4]['name'] ?>">
                    </div>
                <?php endif; ?>
                <div class="card-body">
                    <h5 class="card-title"><?= $recipes[4]['name'] ?></h5>
                    <p class="card-text"><?= $recipes[4]['description'] ?></p>
                    <p class="card-text"><small class="text-muted">Cooking time: <?= $recipes[4]['time'] ?>
                            minutes</small></p>
                </div>
            </div>
        </div>
    </div>


<?php else : ?>
    </div>
<?php endif; ?>