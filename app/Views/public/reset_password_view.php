<section class="p-5">
  <div class="container" style="max-width: 720px;">
    <h2>Reset Your Password</h2>
    <p class="lead">Hello, <?= esc($user['fname']) ?>. Enter a new password for username <strong><?= esc($user['username']) ?></strong>.</p>
    <p class="text-muted">This recovery link expires 20 minutes after it was requested.</p>

    <?= $msg ?>

    <form action="<?= site_url('reset-password') ?>" method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="token" value="<?= esc($token) ?>">

      <div class="mb-3">
        <label class="form-label" for="pass">New password</label>
        <input class="form-control" id="pass" name="pass" type="password" autocomplete="new-password" aria-describedby="passwordHelp" required>
        <div class="form-text" id="passwordHelp">Use at least 12 characters, including two uppercase letters, two lowercase letters, two numbers, and two special characters.</div>
      </div>

      <div class="mb-4">
        <label class="form-label" for="pass2">Confirm new password</label>
        <input class="form-control" id="pass2" name="pass2" type="password" autocomplete="new-password" required>
      </div>

      <button class="btn btn-primary" type="submit">Reset Password</button>
    </form>
  </div>
</section>
