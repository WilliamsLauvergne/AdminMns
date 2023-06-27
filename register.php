<?php 
session_start();
include 'includes/inc-top.php'; 
?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <form action="/register-POST.php" method="POST">
                <div class="mb-3">
                    <label for="" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="" name="lastname"
                    value="<?= isset($_SESSION['values']['lastname']) ? $_SESSION['values']['lastname'] : '' ?>">
                    <?php if(isset($_SESSION['errors']['lastname'])): ?>
                    <small class="text-danger"><?= $_SESSION['errors']['lastname'] ?></small>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label for="" class="form-label">Prénom</label>
                    <input type="text" class="form-control" id="" name="firstname"
                    value="<?= isset($_SESSION['values']['firstname']) ? $_SESSION['values']['firstname'] : '' ?>">
                    <?php if(isset($_SESSION['errors']['firstname'])): ?>
                    <small class="text-danger"><?= $_SESSION['errors']['firstname'] ?></small>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Email</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email"
                    value="<?= isset($_SESSION['values']['email']) ? $_SESSION['values']['email'] : '' ?>">
                    <?php if(isset($_SESSION['errors']['email'])): ?>
                    <small class="text-danger"><?= $_SESSION['errors']['email'] ?></small>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" name="password">
                    <?php if(isset($_SESSION['errors']['password'])): ?>
                    <small class="text-danger"><?= $_SESSION['errors']['password'] ?></small>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label for="" class="form-label">Confirmer le mot de passe</label>
                    <input type="password" class="form-control" name="password_confirm">
                    <?php if(isset($_SESSION['errors']['password_confirm'])): ?>
                    <small class="text-danger"><?= $_SESSION['errors']['password_confirm'] ?></small>
                    <?php endif; ?>
                </div>
                <input type="submit" name="submit" value="Envoyer" class="btn btn-primary">
            </form>

            <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger mt-4">
                <p class="m-0"><?= $_SESSION['error'] ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php include 'includes/inc-bottom.php'; ?>