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

<script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
      crossorigin="anonymous"
    ></script>

<script>
(function() {
  const modalEl = document.getElementById('parentModal');
  let parentId = null;

  // When a link is clicked, store the id and show spinner
  document.addEventListener('click', function(e) {
    const a = e.target.closest('.parent-link');
    if (!a) return;

    e.preventDefault();
    parentId = a.getAttribute('data-id');

    document.getElementById('parent-loading').classList.remove('d-none');
    document.getElementById('parent-data').classList.add('d-none');
    document.getElementById('parent-error').classList.add('d-none');
  });

  // When modal is shown, fetch the JSON
  modalEl.addEventListener('shown.bs.modal', async function() {
    if (!parentId) return;

    try {
      const url = '<?= site_url('members/parent') ?>/' + encodeURIComponent(parentId);
      const res = await fetch(url, { headers: { 'Accept': 'application/json' } });

      if (!res.ok) {
        throw new Error('HTTP ' + res.status);
      }

      const json = await res.json();
      if (json.status !== 'ok') {
        throw new Error(json.message || 'Unknown error');
      }

      const d = json.data || {};
      document.getElementById('p-id').textContent    = d.id_members ?? '';
      document.getElementById('p-fname').textContent = d.fname ?? '';
      document.getElementById('p-lname').textContent = d.lname ?? '';
      document.getElementById('p-email').textContent = d.email ?? '';

      document.getElementById('parent-loading').classList.add('d-none');
      document.getElementById('parent-error').classList.add('d-none');
      document.getElementById('parent-data').classList.remove('d-none');

    } catch (err) {
      document.getElementById('parent-loading').classList.add('d-none');
      const box = document.getElementById('parent-error');
      box.textContent = 'Could not load parent details: ' + err.message;
      box.classList.remove('d-none');
      document.getElementById('parent-data').classList.add('d-none');
    }
  });

  // Reset id when modal hides
  modalEl.addEventListener('hidden.bs.modal', function() {
    parentId = null;
  });
})();
</script>

  </body>
</html>