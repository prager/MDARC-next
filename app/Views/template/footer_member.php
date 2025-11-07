<!-- Footer -->
<footer class="p-5 bg-dark text-white text-center position-relative">
           <div class="container">
               <p class="lead">Copyright &copy; <a href="http://mdarc.org" class="text-decoration-none" target="_blank"><span class="link-warning">1947 - <script type="text/javascript">
        var today = new Date();
        document.write(today.getFullYear() );
     </script> MDARC</span></a> | <small><a href="<?php echo base_url(); ?>/index.php/terms" class="text-decoration-none" target="_blank">Terms of Service</a> |  <a href="https://www.mdarc.org/about-us/official-documents/privacy-policy" class="text-decoration-none" target="_blank">Privacy Policy</a></small></p>
               <a href="#" class="position-absolute bottom-0 end-0 p-5">
                   <i class="bi bi-arrow-up-circle h1"></i>
               </a>
           </div>
       </footer>

       <!-- Modals udpate-->
       <?php include 'modal-tech.php'; ?>
       <?php include 'modal-reset-pass.php'; ?>
       <?php include 'mod-renew.php'; ?>
       <?php include 'mod-donate.php'; ?>

       <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
       <script>
              // Initialize and add the map
              function initMap() {
               // The location of Uluru
               const uluru = { lat: 37.97741, lng: -122.05190 };
               // The map, centered at Uluru
               const map = new google.maps.Map(document.getElementById("map"), {
                 zoom: 4,
                 center: uluru,
               });
               // The marker, positioned at Uluru
               const marker = new google.maps.Marker({
                 position: uluru,
                 map: map,
               });
              }
       </script>

       <!-- Google tag (gtag.js) -->
      <script async src="https://www.googletagmanager.com/gtag/js?id=G-VRTPC72FVB"></script>
      <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-VRTPC72FVB');
      </script>
      
   </body>
</html>