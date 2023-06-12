<?php
/** @var string $title */
/** @var string $content */
/** @var string $siteName */

use models\Favourite;
use models\User;

if (User::isUserAuthenticated())
    $user = User::getCurrentAuthenticatedUser();
else
    $user = null;

if (!empty($_POST['recipe_id'])) {
    $favourite = Favourite::getFavouriteByUserAndRecipeId($user['id'], $_POST['recipe_id']);
    if (empty($favourite))
        Favourite::addFavourite($user['id'], $_POST['recipe_id']);
    else
        Favourite::deleteFavourite($user['id'], $_POST['recipe_id']);
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $siteName ?> | <?= $title ?></title>
    <link rel="icon" href="/themes/img/logo.png">
    <link rel="stylesheet" href="/themes/light/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script src="/themes/light/script/script.js"></script>
</head>
<body>
<header class="p-3 mb-3 border-bottom">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="/home" class="d-flex align-items-center mb-2 mb-lg-0 text-dark text-decoration-none">
                <img alt="logo" src="/themes/img/logo.png"/>
            </a>

            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0 main-nav">
                <li><a href="/home" class="nav-link px-2 link-secondary main-nav-btn">Home</a></li>
                <li><a href="/recipe" class="nav-link px-2 link-dark main-nav-btn">Recipes</a></li>
                <li><a href="/category" class="nav-link px-2 link-dark main-nav-btn">Categories</a></li>
                <li><a href="/about" class="nav-link px-2 link-dark main-nav-btn">About Us</a></li>
            </ul>
            <? if (User::isAdmin()) : ?>
                <a href="/administration" class="btn btn-primary mx-3">Administration</a>
            <? endif; ?>

            <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" role="search" action="/recipe" method="get">
                <input type="search" name="search" class="form-control" placeholder="Search recipe..."
                       aria-label="Search">
            </form>

            <? if (User::isUserAuthenticated()) : ?>
                <div class="dropdown text-end">
                    <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown"
                       aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                             class="bi bi-person" viewBox="0 0 16 16">
                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z"/>
                        </svg>
                    </a>
                    <ul class="dropdown-menu text-small mt-2">
                        <li>
                            <div class="dropdown-item-text dropdown-username fw-bold"><?= $user['firstname'] . ' ' . $user['lastname'] ?></div>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="/user/favourite">Favourites</a></li>
                        <li><a class="dropdown-item" href="/user/edit/<?= $user['id'] ?>">Settings</a></li>
                        <li><a class="dropdown-item fw-bold" href="/user/logout">Logout</a></li>
                    </ul>
                </div>
            <? else: ?>
                <div class="text-end">
                    <a href="/user/signup" class="btn btn-primary">Sign Up</a>
                    <a href="/user/login" class="btn btn-light text-dark me-2">Login</a>
                </div>
            <? endif; ?>
        </div>
    </div>
</header>


<div class="container">
    <?= $content ?>
</div>


<footer class="py-3 my-3 mt-5">
    <ul class="nav justify-content-center border-bottom pb-3 mb-3">
        <li class="nav-item"><a href="/" class="nav-link px-2 text-muted">Home</a></li>
        <li class="nav-item"><a href="/recipe" class="nav-link px-2 text-muted">Recipes</a></li>
        <li class="nav-item"><a href="/category" class="nav-link px-2 text-muted">Categories</a></li>
        <li class="nav-item"><a href="/about" class="nav-link px-2 text-muted">About Us</a></li>
    </ul>
    <p class="text-center text-muted">© 2022-2023 Vlada Lukashevych, Slay Queen</p>
</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
        crossorigin="anonymous"></script>
</body>
</html>