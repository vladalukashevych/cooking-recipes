<?php
/** @var string|null $error */
/** @var array $model */

core\Core::getInstance()->pageParams['title'] = 'Log In';
?>


<main class="form-login w-100 m-auto">
    <form method="post" action="">
        <h1 class="h3 mb-3 fw-normal text-center">Login</h1>
        <?php if (!empty($error)) : ?>
            <div class="form-text text-danger mb-2"><?= $error ?></div>
        <?php endif; ?>
        <div class="form-floating">
            <input type="email" class="form-control" name="login" id="login" value="<?= $model['login'] ?>"
                   placeholder="name@example.com">
            <label for="login">Email address</label>
        </div>
        <div class="form-floating">
            <input type="password" class="form-control" name="password" id="password" placeholder="password">
            <label for="password">Password</label>
        </div>
        <button class="w-100 btn btn-lg btn-primary" type="submit">Login</button>
    </form>
</main>
