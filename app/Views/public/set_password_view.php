<section class="p-5">
  <div class="container" style="max-width: 720px;">
    <h2>Finish Your Registration</h2>
    <p class="lead">
      Welcome, <?= esc($user['fname'] . ' ' . $user['lname']) ?>. Choose your username and password below.
    </p>

    <?= $msg ?>

    <form action="<?= site_url('set-pass') ?>" method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="token" value="<?= esc($token) ?>">

      <div class="mb-3">
        <label class="form-label" for="username">Username</label>
        <input class="form-control" id="username" name="username" type="text" value="<?= esc($username) ?>" autocomplete="username" required>
      </div>

      <div class="mb-3">
        <label class="form-label" for="pass">Password</label>
        <input class="form-control" id="pass" name="pass" type="password" autocomplete="new-password" aria-describedby="passwordHelp" required>
        <div class="form-text" id="passwordHelp">Use at least 12 characters, including two uppercase letters, two lowercase letters, two numbers, and two special characters.</div>
      </div>

      <div class="mb-4">
        <label class="form-label" for="pass2">Confirm password</label>
        <input class="form-control" id="pass2" name="pass2" type="password" autocomplete="new-password" required>
      </div>

      <button class="btn btn-primary" type="submit">Finish Registration</button>
    </form>
  </div>
</section>
