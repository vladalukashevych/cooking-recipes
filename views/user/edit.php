<?php
/** @var array $errors */
/** @var array $user */
/** @var array $model */

if (empty($model)) $model = $user;

use core\Core;
use models\User;

Core::getInstance()->pageParams['title'] = 'Edit Profile';
?>

<div class="row d-flex flex-wrap justify-content-evenly mt-5">
    <div class="col-4 clearfix">
        <form method="post" action="">
            <h1 class="h3 mb-3 fw-normal text-center edit-form">Edit Profile</h1>
            <div class="mb-3">
                <label class="form-label" for="login">Login</label>
                <input type="email" disabled class="form-control" name="login" id="login" aria-describedby="emailHelp"
                       value="<?= $user['login'] ?>"/>
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
            <?php if (User::isAdmin()) : ?>
                <div class="mb-3">
                    <label class="form-label" for="access_level">Access Level</label>
                    <input type="number" min="1" class="form-control" name="access_level" id="access_level"
                           aria-describedby="access_levelHelp"
                           value="<?= $model['access_level'] ?>"/>
                </div>
            <?php endif; ?>
            <button name="profile-edit" class="btn btn-primary float-right" type="submit">Submit</button>
        </form>
    </div>
    <div class="col-4 clearfix">
        <form method="post" action="">
            <h1 class="h3 mb-3 fw-normal text-center edit-form">Change Password</h1>
            <div class="mb-3">
                <label class="form-label" for="password">Password</label>
                <input type="password" class="form-control" name="password" id="password"
                       aria-describedby="passwordHelp"/>
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
            <button name="password-change" class="btn btn-primary float-right" type="submit">Submit</button>
        </form>
    </div>
</div>
