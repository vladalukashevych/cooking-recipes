<?php
/** @var array $errors */
/** @var array $model */

core\Core::getInstance()->pageParams['title'] = 'Sing Up';
?>

<main class="form-login w-100 m-auto">
    <form method="post" action="">
        <h1 class="h3 mb-3 fw-normal text-center edit-form">Sing Up</h1>
        <div class="mb-3">
            <label class="form-label" for="login">Login</label>
            <input type="email" class="form-control" name="login" id="login" aria-describedby="emailHelp"
                   value="<?= $model['login'] ?>"/>
            <?php if (!empty($errors['login'])): ?>
                <div id="emailHelp" class="form-text text-danger"><?= $errors['login'] ?></div>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label class="form-label" for="password">Password</label>
            <input type="password" class="form-control" name="password" id="password" aria-describedby="passwordHelp"/>
            <?php if (!empty($errors['password'])): ?>
                <div id="passwordHelp" class="form-text text-danger"><?= $errors['password'] ?></div>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label class="form-label" for="password2">Repeat password</label>
            <input type="password" class="form-control" name="password2" id="password2"
                   aria-describedby="password2Help"/>
            <?php if (!empty($errors['password2'])): ?>
                <div id="password2Help" class="form-text text-danger"><?= $errors['password2'] ?></div>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label class="form-label" for="firstname">First name</label>
            <input type="text" class="form-control" name="firstname" id="firstname" aria-describedby="firstnameHelp"
                   value="<?= $model['firstname'] ?>"/>
            <?php if (!empty($errors['firstname'])): ?>
                <div id="firstnameHelp" class="form-text text-danger"><?= $errors['firstname'] ?></div>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label class="form-label" for="lastname">Last name</label>
            <input type="text" class="form-control" name="lastname" id="lastname" aria-describedby="lastnameHelp"
                   value="<?= $model['lastname'] ?>"/>
            <?php if (!empty($errors['lastname'])): ?>
                <div id="lastnameHelp" class="form-text text-danger"><?= $errors['lastname'] ?></div>
            <?php endif; ?>
        </div>
        <button class="btn btn-primary" type="submit">Submit</button>
    </form>
</main>