<section id="learn" class="p-5">
  <div class="container">
    <div class="row pt-3 pb-3">
      <div class="col-lg-8 offset-lg-1">
        <h2>Frequently Asked Questions</h2>
      </div>
    </div>
    <?php if($faqs != NULL) {
    foreach ($faqs as $key => $faq) {?>
    <?php if($faq['id_user_type'] != 3) { ?>
      <div class="row pt-2">
        <div class="col-lg-9 offset-lg-1">
          <p class="lead fw-bold">
            <?php echo $faq['theq']; ?></p>
          <p><?php echo $faq['thea']; ?></p>
        </div>
      </div>
    <?php }
      }
    } ?>
  </div>
</section>