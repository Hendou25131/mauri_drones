<?php 
$con=mysqli_connect("localhost","root","","mauri_drones");
$message='';

if(isset($_POST['ajouter'])){
  $nom= isset($_POST['nom']) ? trim(htmlspecialchars($_POST['nom'])) : '';
  $type=isset($_POST['type']) ? trim(htmlspecialchars($_POST['type'])) : '';
      if(!empty($nom) and !empty($type)){
      $req="insert into categorie (nom,type) values (?,?)";
      $stmt=$con->prepare($req);
      $stmt->bind_param("ss",$nom,$type);
      if($stmt->execute()){
       header("location:categorie_formulaire.php?succes=1");
       exit();
      }
      else echo "<script>alert('Erreur lors de la sauvagarde dans la base !')</script>";
    }
}

if(isset($_POST['modifier'])){
  $id= isset($_POST['id']) ? trim(htmlspecialchars($_POST['id'])) : '';
  $nom= isset($_POST['nom1']) ? trim(htmlspecialchars($_POST['nom1'])) : '';
  $type=isset($_POST['type1']) ? trim(htmlspecialchars($_POST['type1'])) : '';
  $req="update categorie set nom=?, type=? where id_categorie=?";
  $stmt=$con->prepare($req);
  $stmt->bind_param('ssi',$nom,$type,$id);
  if($stmt->execute()){
    header("location:categorie_formulaire.php?succes=2");
    exit();
  }
  else echo "<script>alert('Erreur lors de la sauvagarde dans la base !')</script>";
}

if(isset($_POST['supprimer'])){
  $id= isset($_POST['id']) ? trim(htmlspecialchars($_POST['id'])) : '';
  $req="delete from categorie where id_categorie=?";
  $stmt=$con->prepare($req);
  $stmt->bind_param('i',$id);
  if($stmt->execute()){
    header("location:categorie_formulaire.php?succes=3");
    exit();
  }
}
$req="select * from categorie";
$res=mysqli_query($con,$req);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Gestion Catégories — Mauri-Drones</title>
  <style>
    :root{
  --bg:#0d0f14;
  --surface:#13161e;
  --card:#181c27;
  --border:#1f2436;
  --border2:#2a2f45;
  --blue:#3b9eff;
  --blue-d:#1a7de8;
  --blue-glow:rgba(59,158,255,.2);
  --red:#ff4b4b;
  --red-glow:rgba(255,75,75,.18);
  --green:#2fd98e;
  --green-glow:rgba(47,217,142,.18);
  --text:#e9ecf5;
  --muted:#6b7490;
  --label:#8892b0;
  --r:14px;
  --r-sm:8px;
}
    * { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }
    body {
      font-family: 'Segoe UI', system-ui, sans-serif;
      background: #0f1117;
      color: #e2e8f0;
      min-height: 100vh;
    }

    /* ── NAV ── */
    nav {
      background: #13151e;
      border-bottom: 1px solid #1e2333;
      padding: 0 2rem;
      height: 56px;
      display: flex;
      align-items: center;
    }
    
    .logo {
      display: flex;
      align-items: center;
      gap: 10px;
      font-weight: 700;
      font-size: 1.1rem;
      color: #e2e8f0;
      text-decoration: none;
    }
    .logo-icon {
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .logo-icon img { width: 40px; height: 40px; object-fit: contain; }
.site-header {
  justify-content: space-between;
}
/* Style du bouton Retour Accueil */
.btn-back-home {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: rgba(59, 158, 255, 0.08);
  border: 1px solid rgba(59, 158, 255, 0.2);
  padding: 8px 16px;
  border-radius: var(--r-sm);
  color: var(--blue);
  font-family: 'Barlow', sans-serif;
  font-size: 0.85rem;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.btn-back-home svg {
  width: 18px;
  height: 18px;
  fill: var(--blue);
  transition: fill 0.2s;
}

/* Effet au survol (Hover) */
.btn-back-home:hover {
  background: var(--blue);
  color: #fff;
  border-color: var(--blue);
  box-shadow: 0 4px 12px var(--blue-glow);
  text-decoration: none;
}

.btn-back-home:hover svg {
  fill: #fff;
}
    /* ── MAIN ── */
    main {
      max-width: 900px;
      margin: 0 auto;
      padding: 3rem 2rem;
    }

    /* ── PAGE HEADER : TITRE GLOBAL ET BARRE DE RECHERCHE ── */
    .page-header {
      display: flex;
      align-items: flex-end;
      justify-content: space-between;
      margin-bottom: 2rem;
      flex-wrap: wrap;
      gap: 20px;
    }
    .page-header-text {
      flex: 1;
      min-width: 250px;
    }
    .page-label {
      font-size: 0.7rem;
      font-weight: 700;
      letter-spacing: 0.12em;
      color: #3b82f6;
      text-transform: uppercase;
      margin-bottom: 0.5rem;
    }

    h1 {
      font-size: 2rem;
      font-weight: 800;
      color: #e2e8f0;
      margin: 0;
    }
    h1 span { color: #3b82f6; }

    /* Style de la zone de recherche en face du titre */
    .search-container {
      width: 280px;
    }
    .search-container input[type=text] {
      width: 100%;
      background: #0f1117;
      border: 1px solid #2a2f45;
      border-radius: 8px;
      padding: 0.65rem 0.9rem;
      color: #e2e8f0;
      font-size: 0.9rem;
      outline: none;
    }

    /* ── TOP BAR AVANT TABLEAU ── */
    .table-section-top-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin: 2.5rem 0 1.5rem 0;
      flex-wrap: wrap;
      gap: 16px;
    }

    /* ── BUTTONS GENERALS ── */
    .btn-global-add {
      padding: 0.65rem 1.4rem;
      border-radius: 8px;
      background: #3b82f6;
      color: #fff;
      font-size: 0.88rem;
      font-weight: 700;
      border: none;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: background 0.18s, transform 0.1s;
    }
    .btn-global-add:hover { background: #1d4ed8; }
    .btn-global-add:active { transform: scale(0.97); }

    /* ── CARD ── */
    .card {
      background: #13151e;
      border: 1px solid #1e2333;
      border-radius: 16px;
      padding: 2rem;
    }
    .card-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 1.75rem;
    }
    .card-title-group { display: flex; align-items: center; gap: 12px; }
    .card-icon {
      width: 38px;
      height: 38px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1rem;
      flex-shrink: 0;
    }
    .card-icon.add    { background: #1e3a5f; }
    .card-icon.edit   { background: #1e2d1e; }
    .card-icon.delete { background: #3b1616; }

    .card-title { font-size: 1rem; font-weight: 700; color: #e2e8f0; }
    .card-sub   { font-size: 0.78rem; color: #64748b; margin-top: 2px; }

    .badge {
      font-size: 0.65rem;
      font-weight: 700;
      letter-spacing: 0.08em;
      padding: 3px 10px;
      border-radius: 20px;
      background: #1e2333;
      color: #64748b;
      border: 1px solid #2a2f45;
    }

    /* ── FIELDS ── */
    .field-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
      margin-bottom: 1rem;
    }
    .field-row.single { grid-template-columns: 1fr; }
    .field-group { display: flex; flex-direction: column; gap: 6px; }

    label {
      font-size: 0.7rem;
      font-weight: 700;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      color: #94a3b8;
      display: flex;
      align-items: center;
      gap: 4px;
    }
    label .req { color: #3b82f6; font-size: 0.85rem; }

    input[type="text"],
    input[type="number"],
    select {
      width: 100%;
      background: #0f1117;
      border: 1px solid #2a2f45;
      border-radius: 8px;
      padding: 0.65rem 0.9rem;
      color: #e2e8f0;
      font-size: 0.9rem;
      outline: none;
      transition: border-color 0.18s;
      appearance: none;
    }
    input::placeholder { color: #334155; }
    input:focus, select:focus { border-color: #3b82f6; }
    select { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; padding-right: 2rem; }

    /* ── SUBMIT / CANCEL ── */
    .form-footer { margin-top: 1.5rem; display: flex; justify-content: flex-end; gap: 10px; }
    .btn-submit {
      padding: 0.65rem 1.8rem;
      border-radius: 9px;
      border: none;
      font-size: 0.9rem;
      font-weight: 700;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: opacity 0.18s, transform 0.1s;
    }
    .btn-submit:hover { opacity: 0.88; transform: translateY(-1px); }
    .btn-submit:active { transform: translateY(0); }
    .btn-submit.add    { background: #3b82f6; color: #fff; }
    .btn-submit.edit   { background: #22c55e; color: #fff; }
    .btn-submit.delete { background: #dc2626; color: #fff; }

    .btn-cancel {
      background: transparent;
      border: 1px solid #2a2f45;
      color: #64748b;
    }
    .btn-cancel:hover { background: #1a1f2e; color: #e2e8f0; }

    /* ── PANELS GESTION VISIBILITE ── */
    .panel { display: none; margin-top: 2.5rem; animation: fadeUp 0.2s ease both; }
    .panel.active { display: block; }
    @keyframes fadeUp { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }

    /* ── TABLE CATÉGORIES ── */
    .table-section {
      margin-top: 1rem;
      margin-bottom: 2rem;
    }
    .table-section-title {
      font-size: 1.25rem;
      font-weight: 700;
      color: #e2e8f0;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .table-section-title::before {
      content: '';
      display: inline-block;
      width: 3px;
      height: 1.1rem;
      background: #3b82f6;
      border-radius: 2px;
    }
    .table-count {
      font-size: 0.72rem;
      font-weight: 700;
      background: #1e2333;
      border: 1px solid #2a2f45;
      color: #64748b;
      padding: 3px 10px;
      border-radius: 20px;
    }
    .table-wrap {
      background: #13151e;
      border: 1px solid #1e2333;
      border-radius: 16px;
      overflow: hidden;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 0.875rem;
    }
    thead tr {
      background: #0f1117;
      border-bottom: 1px solid #1e2333;
    }
    thead th {
      padding: 0.85rem 1.25rem;
      text-align: left;
      font-size: 0.65rem;
      font-weight: 700;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      color: #475569;
    }
    tbody tr {
      border-bottom: 1px solid #1a1f2e;
      transition: background 0.15s;
    }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: #1a1f2e; }
    tbody tr.selected { background: rgba(59, 158, 255, 0.08) !important; border-left: 3px solid #3b82f6; }
    tbody td {
      padding: 0.9rem 1.25rem;
      color: #cbd5e1;
      vertical-align: middle;
    }
    .td-id {
      font-family: 'Courier New', monospace;
      font-size: 0.8rem;
      color: #3b82f6;
      font-weight: 700;
      background: #1e2333;
      border: 1px solid #2a2f45;
      border-radius: 6px;
      padding: 3px 8px;
      display: inline-block;
    }
    .td-nom { font-weight: 600; color: #e2e8f0; }
    
    .type-badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      font-size: 0.72rem;
      font-weight: 600;
      padding: 4px 10px;
      border-radius: 20px;
      border: 1px solid;
    }
    .type-badge.Drone       { background: #1e3a5f22; border-color: #3b82f644; color: #60a5fa; }
    .type-badge.Camera      { background: #2d1b4e22; border-color: #a855f744; color: #c084fc; }
    .type-badge.Accessoire  { background: #2d1f0022; border-color: #f59e0b44; color: #fbbf24; }

    /* Boutons actions en ligne */
    .actions-cell { display: flex; gap: 6px; }
    .btn-row-action {
      font-size: 0.75rem;
      font-weight: 600;
      padding: 5px 10px;
      border-radius: 6px;
      cursor: pointer;
      border: 1px solid transparent;
      transition: all 0.15s;
    }
    .btn-row-edit { background: rgba(34, 197, 94, 0.15); color: #4ade80; border-color: rgba(34, 197, 94, 0.2); }
    .btn-row-edit:hover { background: #22c55e; color: #fff; }
    .btn-row-del { background: rgba(220, 38, 38, 0.15); color: #f87171; border-color: rgba(220, 38, 38, 0.2); }
    .btn-row-del:hover { background: #dc2626; color: #fff; }

    @media(max-width: 600px) {
      .field-row { grid-template-columns: 1fr; }
      .page-header { flex-direction: column; align-items: stretch; }
      .search-container { width: 100%; }
      .table-section-top-bar { flex-direction: column; align-items: stretch; }
      .btn-global-add { justify-content: center; }
    }
  </style>
</head>
<body>

<nav class="site-header">
  <a class="logo" href="#">
    <div class="logo-icon">
      <img src="logoPI.png" alt="Mauri-Drones logo" />
    </div>
    Mauri-Drones
  </a>
  <a href="dashboard.php" class="btn-back-home">
    <svg viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
    Retour Accueil
  </a>
</nav>

<main>
  <div class="page-header">
    <div class="page-header-text">
      <p class="page-label">Panneau d'administration</p>
      <h1>Gestion des <span>catégories</span></h1>
    </div>
    <div class="search-container">
      <input type="text" id="search-category" placeholder="🔍 Rechercher une catégorie, un type...">
    </div>
  </div>

  <div class="table-section-top-bar">
    <div class="table-section-title">Catégories disponibles</div>
    <button type="button" class="btn-global-add" id="btn-trigger-add">＋ Ajouter une catégorie</button>
  </div>

  <div class="table-section">
    <div class="table-wrap">
      <table id="categories-table">
        <thead>
          <tr>
            <th style="width: 140px;">Actions</th>
            <th style="width: 80px;">ID</th>
            <th>Nom</th>
            <th>Type</th>
          </tr>
        </thead>
        <tbody id="categories-tbody">
          <?php   
          while($tab=mysqli_fetch_assoc($res)){
          echo "<tr data-id='".$tab['id_categorie']."' data-nom='".htmlspecialchars($tab['nom'])."' data-type='".htmlspecialchars($tab['type'])."'>";
          echo " <td class='actions-cell'>
                    <button type='button' class='btn-row-action btn-row-edit'>✎ Modifier</button>
                    <button type='button' class='btn-row-action btn-row-del'>✕ Suppr.</button>
                 </td>";
          echo "<td><span class='td-id'>".$tab['id_categorie']."</span></td>";
          echo "<td class='td-nom'>".$tab['nom']."</td>";
          echo "<td><span class='type-badge ".$tab['type']."'>".$tab['type']."</span></td>";
          echo "</tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
    <div style="margin-top: 10px; text-align: right;">
       <span class="table-count" id="cat-count">0 entrée(s)</span>
    </div>
  </div>

  <form action="" method="post" name="f1">
  <div class="panel" id="panel-add">
    <div class="card">
      <div class="card-header">
        <div class="card-title-group">
          <div class="card-icon add">➕</div>
          <div>
            <div class="card-title">Nouvelle catégorie</div>
            <div class="card-sub">Ajouter une catégorie au catalogue</div>
          </div>
        </div>
        <span class="badge">ID AUTO</span>
      </div>

      <div class="field-row single">
        <div class="field-group">
          <label>Nom <span class="req">*</span></label>
          <input type="text" id="add-nom" placeholder="Ex : Caméras thermiques" name="nom">
        </div>
      </div>
      <div class="field-row single">
        <div class="field-group">
          <label>Type<span class="req">*</span></label>
          <select id="add-type" name="type" required>
            <option value="" disabled selected>— Sélectionner —</option>
            <option value="Drone">Drone</option>
            <option value="Accessoire">Accessoire</option>
            <option value="Camera">Caméra</option>
          </select>
        </div>
      </div>

      <div class="form-footer">
        <button class="btn-submit btn-cancel" type="button">Annuler</button>
        <button class="btn-submit add" type="submit" name="ajouter">
          ＋ Ajouter la catégorie
        </button>
      </div>
    </div>
  </div>
  </form>

  <form action="" method="post" name="f2">
  <div class="panel" id="panel-edit">
    <div class="card">
      <div class="card-header">
        <div class="card-title-group">
          <div class="card-icon edit">✎</div>
          <div>
            <div class="card-title">Modifier une catégorie</div>
            <div class="card-sub">Mettre à jour les informations d'une catégorie</div>
          </div>
        </div>
      </div>

      <div class="field-row single">
        <div class="field-group">
          <label>ID Catégorie <span class="req">*</span></label>
          <input type="number" id="edit-id" readonly placeholder="Ex : 3" name="id">
        </div>
      </div>
      <div class="field-row single">
        <div class="field-group">
          <label>Nouveau nom <span class="req">*</span></label>
          <input type="text" id="edit-nom" placeholder="Ex : Drones agricoles" name="nom1">
        </div>
      </div>
      <div class="field-row single">
        <div class="field-group">
          <label>Nouveau type <span class="req">*</span></label>
          <select id="edit-type" name="type1">
            <option value="" disabled selected>— Sélectionner —</option>
            <option value="Drone">Drone</option>
            <option value="Camera">Caméra</option>
            <option value="Accessoire">Accessoire</option>
          </select>
        </div>
      </div>

      <div class="form-footer">
        <button class="btn-submit btn-cancel" type="button">Annuler</button>
        <button class="btn-submit edit" name="modifier" type="submit">
          ✔ Enregistrer les modifications
        </button>
      </div>
    </div>
  </div>
  </form>

  <form action="" name="f3" method="post" onsubmit="return confirm('Supprimer définitivement cette catégorie ?')">
  <div class="panel" id="panel-delete">
    <div class="card">
      <div class="card-header">
        <div class="card-title-group">
          <div class="card-icon delete">✕</div>
          <div>
            <div class="card-title">Supprimer une catégorie</div>
            <div class="card-sub">Cette action est définitive et irréversible</div>
          </div>
        </div>
      </div>

      <div class="field-row single">
        <div class="field-group">
          <label>ID Catégorie à supprimer <span class="req">*</span></label>
          <input type="number" id="del-id" readonly name="id">
        </div>
      </div>
      
      <div class="field-row single">
        <div class="field-group">
          <label>Nom de la catégorie visée</label>
          <input type="text" id="del-nom-preview" readonly style="opacity: 0.6; cursor: not-allowed;">
        </div>
      </div>

      <div class="form-footer">
        <button class="btn-submit btn-cancel" type="button">Annuler</button>
        <button class="btn-submit delete" name="supprimer" type="submit">
          ✕ Supprimer la catégorie
        </button>
      </div>
    </div>
  </div>
  </form>

</main>

<script>
  const panelAdd = document.getElementById('panel-add');
  const panelEdit = document.getElementById('panel-edit');
  const panelDelete = document.getElementById('panel-delete');

  // Fonction pour mettre à jour le compteur du tableau
  function updateCount(count) {
    document.getElementById('cat-count').textContent = count + ' entrée(s)';
  }

  // Initialisation du compteur au chargement
  const initialRows = document.querySelectorAll('#categories-tbody tr');
  updateCount(initialRows.length);

  // Fonction pour masquer tous les formulaires
  function hideAllPanels() {
    panelAdd.classList.remove('active');
    panelEdit.classList.remove('active');
    panelDelete.classList.remove('active');
    document.querySelectorAll('#categories-tbody tr').forEach(r => r.classList.remove('selected'));
  }

  // Clic sur le bouton global "＋ Ajouter une catégorie"
  document.getElementById('btn-trigger-add').addEventListener('click', () => {
    hideAllPanels();
    panelAdd.classList.add('active');
    panelAdd.scrollIntoView({ behavior: 'smooth' });
  });

  // Clic sur "Modifier" dans le tableau
  document.querySelectorAll('.btn-row-edit').forEach(btn => {
    btn.addEventListener('click', () => {
      hideAllPanels();
      const row = btn.closest('tr');
      row.classList.add('selected');

      // Récupération des données de la ligne
      const id = row.dataset.id;
      const nom = row.dataset.nom;
      const type = row.dataset.type;

      // Injection dans le formulaire Modifier
      document.getElementById('edit-id').value = id;
      document.getElementById('edit-nom').value = nom;
      document.getElementById('edit-type').value = type;

      panelEdit.classList.add('active');
      panelEdit.scrollIntoView({ behavior: 'smooth' });
    });
  });

  // Clic sur "Supprimer" dans le tableau
  document.querySelectorAll('.btn-row-del').forEach(btn => {
    btn.addEventListener('click', () => {
      hideAllPanels();
      const row = btn.closest('tr');
      row.classList.add('selected');

      const id = row.dataset.id;
      const nom = row.dataset.nom;

      // Injection dans le formulaire Supprimer
      document.getElementById('del-id').value = id;
      document.getElementById('del-nom-preview').value = nom;

      panelDelete.classList.add('active');
      panelDelete.scrollIntoView({ behavior: 'smooth' });
    });
  });

  // Gestion des boutons Annuler
  document.querySelectorAll('.btn-cancel').forEach(btn => {
    btn.addEventListener('click', () => {
      hideAllPanels();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  });

  // SCRIPT FILTRAGE BARRE DE RECHERCHE DYNAMIQUE
  document.getElementById('search-category').addEventListener('input', function(e) {
    const term = e.target.value.toLowerCase().trim();
    const rows = document.querySelectorAll('#categories-tbody tr');
    let visibleCount = 0;

    rows.forEach(row => {
      const text = row.textContent.toLowerCase();
      if (text.includes(term)) {
        row.style.display = '';
        visibleCount++;
      } else {
        row.style.display = 'none';
      }
    });

    updateCount(visibleCount);
  });
</script>

<?php if(isset($_GET['succes']) && $_GET['succes']==1): ?>
  <script>
    window.addEventListener('DOMContentLoaded', () => {
      setTimeout(() => {
      alert("✅ Ligne Ajoutée dans la base !");
      window.history.replaceState({},document.title,"categorie_formulaire.php");
    }, 100);
  }); 
  </script>
<?php endif; ?>

<?php if(isset($_GET['succes']) && $_GET['succes']==2): ?>
  <script>
    window.addEventListener('DOMContentLoaded', () => {
      setTimeout(() => {
      alert("✎ Ligne modifiée avec succès !");
      window.history.replaceState({},document.title,"categorie_formulaire.php");
    }, 100);
  });
  </script>
<?php endif; ?>

<?php if(isset($_GET['succes']) && $_GET['succes']==3): ?>
  <script>
    window.addEventListener('DOMContentLoaded', () => {
      setTimeout(() => {
      alert("✅ Ligne supprimée avec succès !");
      window.history.replaceState({},document.title,"categorie_formulaire.php");
    }, 100);
  });
  </script>
<?php endif; ?>
</body>
</html>