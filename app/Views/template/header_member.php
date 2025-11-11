<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap CSS (v5.3.8) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="shortcut icon" href="/img/mdarc-icon.ico" type="image/x-icon">
    <title>MDARC Membership v. Dev</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="shortcut icon" href="/img/mdarc-icon.ico" type="image/x-icon">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://www.google.com/recaptcha/api.js?render=<?= getenv('GOOGLE_RECAPTCHAV3_SITEKEY') ?>"></script>
  </head>  
  <body>
  <nav class="navbar navbar-expand-lg bg-light navbar-light py-2 fixed-top">
  <div class="container">
      <a href="<?php echo base_url(); ?>" class="navbar-brand"><img src="/img/mdarc-avatar.jpg" alt="Logo" style="width:40px;" class="rounded-pill"> MDARC Members Portal</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
          <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navmenu">
          <ul class="navbar-nav ms-auto">
              <li class="nav-item">
                  <a href="<?php echo base_url(); ?>" class="nav-link">Home</a>
              </li>
              <li class="nav-item">
                  <a href="<?php echo base_url() . 'index.php/contact'; ?>" class="nav-link">Contact</a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="helpMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  About
                </a>
                <ul class="dropdown-menu" aria-labelledby="helpMenu">
                  <li><a class="dropdown-item"><a href="<?php echo base_url() . 'index.php/faqs'; ?>" class="nav-link"> &nbsp; FAQs</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item"><a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#tech"> &nbsp; About</a></li>
                </ul>
              </li>
              <li class="nav-item">
                  <a href="<?php echo base_url() . 'index.php/logout/'; ?>" class="nav-link"><i class="bi bi-box-arrow-up-right"></i> Logout </a>
              </li>
              <form action="<?php echo base_url() . 'index.php/member-search'; ?>" method="post" class="d-flex px-3">
                <input class="form-control me-4" type="search" name="search" placeholder="Search Members Database" aria-label="Search">
                <button class="btn btn-outline-secondary" type="submit">Search</button>
              </form>
          </ul>
      </div>
  </div>
</nav>