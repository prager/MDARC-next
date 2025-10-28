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
  const BASE = "<?= rtrim(site_url(), '/') ?>";              // e.g. http://localhost:8080 or /myapp/index.php
  const PARENT_URL   = BASE + "/members/parent";             // we’ll append /{id}
  const CHILDREN_URL = BASE + "/members/children";           // we’ll append /{id}
</script>


<script>
(function () {
  const modalEl   = document.getElementById('parentModal');
  const loadingEl = document.getElementById('parent-loading');
  const dataEl    = document.getElementById('parent-data');
  const errorEl   = document.getElementById('parent-error');
  const kidsBody  = document.getElementById('childrenBody');
  const kidsEmpty = document.getElementById('childrenEmpty');

  const fmtDate = (d) => {
    if (!d) return '';
    const t = new Date(String(d).replace(' ', 'T'));
    if (isNaN(t)) return d;
    const mm = String(t.getMonth()+1).padStart(2,'0');
    const dd = String(t.getDate()).padStart(2,'0');
    const yy = t.getFullYear();
    return `${mm}-${dd}-${yy}`;
  };

  modalEl.addEventListener('show.bs.modal', async function (ev) {
    const trigger = ev.relatedTarget;
    const parentId = trigger?.getAttribute('data-parent-id');
    if (!parentId) return;

    // reset UI
    errorEl.classList.add('d-none');
    dataEl.classList.add('d-none');
    kidsEmpty.classList.add('d-none');
    kidsBody.innerHTML = '';
    loadingEl.classList.remove('d-none');

    try {
      const parentUrl   = PARENT_URL   + '/' + encodeURIComponent(parentId);
      const childrenUrl = CHILDREN_URL + '/' + encodeURIComponent(parentId);
      console.log('FETCH', parentUrl, childrenUrl); // <-- temporary debug


      const [parentRes, kidsRes] = await Promise.all([
        fetch(parentUrl,   { headers: { 'Accept': 'application/json' } }),
        fetch(childrenUrl, { headers: { 'Accept': 'application/json' } }),
      ]);

      if (!parentRes.ok) throw new Error('Parent HTTP ' + parentRes.status);
      if (!kidsRes.ok)   throw new Error('Children HTTP ' + kidsRes.status);

      const parentJson = await parentRes.json();
      const kidsJson   = await kidsRes.json();

      if (parentJson.status !== 'ok') {
        throw new Error(parentJson.message || 'Parent endpoint error');
      }

      const d = parentJson.data || {};
      document.getElementById('p-id').textContent    = d.id_members ?? '';
      document.getElementById('p-fname').textContent = d.fname ?? '';
      document.getElementById('p-lname').textContent = d.lname ?? '';
      document.getElementById('p-email').textContent = d.email ?? '';

      // Update hidden field and form action with parent ID
      const formEl = document.getElementById('parentForm');
      const hiddenInput = document.getElementById('parent_id_input');
      if (formEl && hiddenInput) {
        hiddenInput.value = d.id_members ?? '';
        formEl.action = '<?= site_url('master/add-fam') ?>/' + encodeURIComponent(d.id_members ?? '');
      }

      const kids = Array.isArray(kidsJson.children) ? kidsJson.children : [];
      if (kids.length === 0) {
        kidsEmpty.classList.remove('d-none');
      } else {
        kidsBody.innerHTML = kids.map(r => `
          <tr>
            <td>${r.id_members ?? ''}</td>
            <td>${(r.lname ?? '')}, ${(r.fname ?? '')}</td>
            <td>${r.callsign ?? ''}</td>
            <td>${r.email ?? ''}</td>
            <td>${r.license ?? ''}</td>
            <td>${fmtDate(r.mem_since)}</td>
            <td>${fmtDate(r.pay_date)}</td>
          </tr>
        `).join('');
      }

      loadingEl.classList.add('d-none');
      dataEl.classList.remove('d-none');
    } catch (err) {
      loadingEl.classList.add('d-none');
      dataEl.classList.add('d-none');
      errorEl.textContent = 'Could not load family details: ' + err.message;
      errorEl.classList.remove('d-none');
      console.error(err);
    }
  });
})();
</script>
  </body>
</html>