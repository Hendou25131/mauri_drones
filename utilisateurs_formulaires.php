<?php
$con = mysqli_connect("localhost", "root", "", "mauri_drones");

// ── AJOUTER UN UTILISATEUR ──
if (isset($_POST['ajouter'])) {
    $prenom = isset($_POST['prenom']) ? htmlspecialchars(trim($_POST['prenom'])) : '';
    $nom = isset($_POST['nom']) ? htmlspecialchars(trim($_POST['nom'])) : '';
    $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
    $telephone = isset($_POST['telephone']) ? htmlspecialchars(trim($_POST['telephone'])) : '';
    $ville = isset($_POST['ville']) ? htmlspecialchars(trim($_POST['ville'])) : '';
    $mdp = isset($_POST['mot_de_passe']) ? trim($_POST['mot_de_passe']) : '';

    if (!empty($prenom) and !empty($nom) and !empty($email) and !empty($mdp)) {
        // Hachage sécurisé du mot de passe
        $mdp_hache = password_hash($mdp, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO utilisateur (prenom, nom, email, telephone, ville, mot_de_passe, date_inscription) VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssssss", $prenom, $nom, $email, $telephone, $ville, $mdp_hache);
        if ($stmt->execute()) {
            header("location:utilisateurs_formulaires.php?ajout=1");
            exit();
        } else {
            echo "<script> alert('Erreur lors de la sauvegarde dans la base !') </script>";
        }
    }
}

// ── MODIFIER UN UTILISATEUR ──
if (isset($_POST['modifier'])) {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $prenom = isset($_POST['prenom']) ? htmlspecialchars(trim($_POST['prenom'])) : '';
    $nom = isset($_POST['nom']) ? htmlspecialchars(trim($_POST['nom'])) : '';
    $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
    $telephone = isset($_POST['telephone']) ? htmlspecialchars(trim($_POST['telephone'])) : '';
    $ville = isset($_POST['ville']) ? htmlspecialchars(trim($_POST['ville'])) : '';
    $mdp = isset($_POST['mot_de_passe']) ? trim($_POST['mot_de_passe']) : '';

    if (!empty($id) and !empty($prenom) and !empty($nom) and !empty($email)) {
        if (!empty($mdp)) {
            // Si un nouveau mot de passe est renseigné, on le met à jour
            $mdp_hache = password_hash($mdp, PASSWORD_DEFAULT);
            $sql = "UPDATE utilisateur SET prenom=?, nom=?, email=?, telephone=?, ville=?, mot_de_passe=? WHERE id=?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("ssssssi", $prenom, $nom, $email, $telephone, $ville, $mdp_hache, $id);
        } else {
            // Sinon, on conserve l'ancien mot de passe
            $sql = "UPDATE utilisateur SET prenom=?, nom=?, email=?, telephone=?, ville=? WHERE id=?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("sssssi", $prenom, $nom, $email, $telephone, $ville, $id);
        }

        if ($stmt->execute()) {
            header("location:utilisateurs_formulaires.php?modification=1");
            exit();
        } else {
            echo "<script> alert('Erreur lors de la sauvegarde dans la base !') </script>";
        }
    }
}

// ── SUPPRIMER UN UTILISATEUR ──
if (isset($_POST['supprimer'])) {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $email_confirme = isset($_POST['email_confirm']) ? htmlspecialchars(trim($_POST['email_confirm'])) : '';
    
    if (!empty($id) and !empty($email_confirme)) {
        $sql = "DELETE FROM utilisateur WHERE id=? AND email=?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("is", $id, $email_confirme);
        if ($stmt->execute()) {
            header("location:utilisateurs_formulaires.php?suppression=1");
            exit();
        } else {
            echo "<script> alert('Erreur lors de la sauvegarde dans la base !') </script>";
        }
    }
}

$sql = "SELECT * FROM utilisateur ORDER BY date_inscription DESC";
$res = mysqli_query($con, $sql);
?> 
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>Gestion des Utilisateurs — Mauri-Drones</title>
<link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700;800&family=Barlow+Condensed:wght@600;700;800&display=swap" rel="stylesheet"/>
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
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{background:var(--bg);color:var(--text);font-family:'Barlow',sans-serif;font-size:15px;line-height:1.6;min-height:100vh}

.site-header{
  background:rgba(13,15,20,.95);
  border-bottom:1px solid var(--border);
  padding:0 40px;height:64px;
  display:flex;align-items:center;
  position:sticky;top:0;z-index:100;
  backdrop-filter:blur(10px);
}
.logo{display:flex;align-items:center;gap:10px;text-decoration:none;}
.logo-text{font-family:'Barlow Condensed',sans-serif;font-size:1.2rem;font-weight:800;letter-spacing:.3px;color:var(--text);}
.logo-dash{color:var(--blue)}
.logo-img{width:42px;height:42px;object-fit:contain;border-radius: 5px;}

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

.page{max-width:1100px;margin:48px auto 48px;padding:0 24px}

.page-header{
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  margin-bottom: 36px;
  flex-wrap: wrap;
  gap: 20px;
}
.page-header-text { flex: 1; min-width: 250px; }
.page-eyebrow{font-size:.7rem;font-weight:700;letter-spacing:2.5px;text-transform:uppercase;color:var(--blue);margin-bottom:6px;}
.page-title{font-family:'Barlow Condensed',sans-serif;font-size:2.2rem;font-weight:800;letter-spacing:-.4px;line-height:1.1;color:var(--text);margin:0;}
.page-title span{color:var(--blue)}

.search-container { width: 280px; }
.search-container input[type=text] {
  padding: 11px 14px; font-size: 0.88rem; background:#0f1219;
  border:1px solid var(--border2); border-radius:var(--r-sm); color:var(--text); width: 100%;
}

.catalogue-top-bar {
  display: flex; align-items: center; justify-content: space-between;
  margin: 50px 0 20px 0; flex-wrap: wrap; gap: 16px;
}
.section-title {
  font-family:'Barlow Condensed',sans-serif; font-size:1.6rem;font-weight:800;letter-spacing:-.2px; color:var(--text);margin:0;
}
.section-title span{color:var(--blue)}
.panel-title-margin { margin:40px 0 20px 0; }

.panel{margin-top:24px;animation:fadeUp .2s ease both}
.panel-hidden{display:none} 
@keyframes fadeUp{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)}}

.form-card{background:var(--card);border:1px solid var(--border2);border-radius:var(--r);overflow:hidden;}
.card-head{padding:22px 28px 18px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:14px;}
.head-icon{width:40px;height:40px;border-radius:var(--r-sm);display:grid;place-items:center;font-size:18px;flex-shrink:0;}
.head-icon-add {background:rgba(59,158,255,.12)}
.head-icon-edit {background:rgba(47,217,142,.12)}
.head-icon-delete{background:rgba(255,75,75,.12)}
.head-text h2{font-family:'Barlow Condensed',sans-serif;font-size:1.15rem;font-weight:800;}
.head-text p{color:var(--muted);font-size:.82rem;margin-top:2px}
.head-chip{
  margin-left:auto; background:rgba(59,158,255,.1);border:1px solid rgba(59,158,255,.22);
  color:var(--blue);border-radius:20px; font-size:.68rem;font-weight:700;padding:3px 11px;
}
.head-chip.red{background:rgba(255,75,75,.1);border-color:rgba(255,75,75,.22);color:var(--red)}

.card-body{padding:28px}
.fgrid{display:grid;grid-template-columns:1fr 1fr;gap:18px 24px}
.fgrid .full{grid-column:1/-1}
.field{display:flex;flex-direction:column;gap:6px}
.field label{font-size:.72rem;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:var(--label);}
.field label .req{color:var(--blue);margin-left:2px}

input[type=text],input[type=number],input[type=email],input[type=tel],input[type=password],select{
  background:#0f1219; border:1px solid var(--border2); border-radius:var(--r-sm);
  color:var(--text); font-family:'Barlow',sans-serif; font-size:.92rem; padding:11px 14px; width:100%; outline:none;
}
input:focus,select:focus{border-color:var(--blue);box-shadow:0 0 0 3px var(--blue-glow)}
#form-edit input:focus{border-color:var(--green);box-shadow:0 0 0 3px var(--green-glow)}
#form-delete input:focus{border-color:var(--red); box-shadow:0 0 0 3px var(--red-glow)}

.divider{height:1px;background:var(--border);margin:24px 0}
.form-actions{display:flex;justify-content:flex-end;gap:10px}

.btn{
  padding:11px 22px;border-radius:var(--r-sm); font-family:'Barlow',sans-serif;font-size:.88rem;font-weight:700;border:none;cursor:pointer;
  display:inline-flex;align-items:center;gap:7px; transition:all .18s;
}
.btn:active{transform:scale(.97)}
.btn-ghost{background:transparent;border:1px solid var(--border2);color:var(--muted)}
.btn-ghost:hover{background:var(--surface);color:var(--text)}
.btn-add {background:var(--blue);color:#fff}
.btn-add:hover{background:var(--blue-d);box-shadow:0 4px 18px var(--blue-glow)}
.btn-edit{background:var(--green);color:#0d1a10}
.btn-edit:hover{filter:brightness(1.1);box-shadow:0 4px 18px var(--green-glow)}
.btn-del {background:var(--red);color:#fff}
.btn-del:hover{filter:brightness(1.08);box-shadow:0 4px 18px var(--red-glow)}

.alert{
  background:rgba(255,75,75,.08);border:1px solid rgba(255,75,75,.22); border-radius:var(--r-sm);padding:13px 16px;
  display:flex;gap:10px; font-size:.84rem;color:#f5a4a4;margin-bottom:20px;
}

.catalogue-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;}
.catalogue-count{
  background:rgba(59,158,255,.1);border:1px solid #3b9eff38; color:var(--blue);border-radius:20px; font-size:.7rem;font-weight:700;padding:4px 12px;
}
.table-wrap{background:var(--card);border:1px solid var(--border2);border-radius:var(--r);overflow:hidden;overflow-x:auto;}
table{width:100%;border-collapse:collapse;min-width:1000px}
thead tr{background:rgba(59,158,255,.06);border-bottom:1px solid var(--border2)}
thead th{padding:12px 14px;text-align:left;font-size:.68rem;font-weight:700;color:var(--label);text-transform:uppercase;}
tbody tr{border-bottom:1px solid var(--border)}
tbody tr.selected{background:rgba(59,158,255,.07)!important;border-left:3px solid var(--blue)}
td{padding:12px 14px;font-size:.88rem;vertical-align:middle}
td.td-id{color:var(--muted);font-size:.78rem;font-weight:600}
.td-date{color:var(--label);font-size:.82rem;}

.actions-cell {display:flex;gap:6px;}
.btn-action-row { font-size:.75rem;font-weight:700; padding:5px 10px;border-radius:var(--r-sm);cursor:pointer;border:none;}
.btn-row-edit {background:rgba(47,217,142,.15);color:var(--green);border:1px solid rgba(47,217,142,.3);}
.btn-row-edit:hover {background:var(--green);color:#0d1a10;}
.btn-row-del {background:rgba(255,75,75,.15);color:var(--red);border:1px solid rgba(255,75,75,.3);}
.btn-row-del:hover {background:var(--red);color:#fff;}
</style>
</head>
<body>

<header class="site-header">
  <a class="logo" href="#">
    <img src="logoPI.png" alt="Logo Mauri-Drones" class="logo-img"/>
    <span class="logo-text">Mauri<span class="logo-dash">-</span>Drones</span>
  </a>
  <a href="dashboard.php" class="btn-back-home">
    <svg viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
    Retour Accueil
  </a>
</header>

<main class="page">
  <div class="page-header">
    <div class="page-header-text">
      <p class="page-eyebrow">Panneau d'administration</p>
      <h1 class="page-title">Gestion des <span>utilisateurs</span></h1>
    </div>
    <div class="search-container">
      <input type="text" id="search-user" placeholder="🔍 Rechercher un utilisateur...">
    </div>
  </div>

  <div class="catalogue-top-bar">
    <h2 class="section-title">Comptes <span>enregistrés</span></h2>
    <button type="button" class="btn btn-add" id="btn-trigger-add">＋ Créer un utilisateur</button>
  </div>

  <div class="catalogue-section">
    <div class="catalogue-header">
      <span class="catalogue-count" id="row-count">Comptes</span>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Actions</th>
            <th>ID</th>
            <th>Prénom</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Téléphone</th>
            <th>Ville</th>
            <th>Date d'inscription</th>
          </tr>
        </thead>
        <tbody>
          <?php
          while($tab=mysqli_fetch_assoc($res)){
            echo "<tr data-id='".$tab['id']."' data-prenom='".htmlspecialchars($tab['prenom'])."' data-nom='".htmlspecialchars($tab['nom'])."' data-email='".htmlspecialchars($tab['email'])."'>";
            echo " <td class='actions-cell'> 
                    <button class='btn-action-row btn-row-edit'>✎ Modifier</button>
                    <button class='btn-action-row btn-row-del'>✕ Suppr.</button>
                   </td>";
            echo " <td class='td-id'>".$tab['id']."</td>";
            echo " <td style='font-weight:600;'>".$tab['prenom']."</td>";
            echo " <td style='font-weight:600; text-transform:uppercase;'>".$tab['nom']."</td>";
            echo " <td style='color:var(--blue);'>".$tab['email']."</td>";
            echo " <td>".($tab['telephone'] ? $tab['telephone'] : '—')."</td>";
            echo " <td>".($tab['ville'] ? $tab['ville'] : '—')."</td>";
            echo " <td class='td-date'>".$tab['date_inscription']."</td>";
            echo " </tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <section id="panel-add" class="panel panel-hidden">
    <h2 class="section-title panel-title-margin">Nouvel <span>utilisateur</span></h2>
    <div class="form-card">
      <div class="card-head">
        <div class="head-icon head-icon-add">👤</div>
        <div class="head-text">
          <h2>Créer un compte</h2>
          <p>Enregistrer les coordonnées d'un nouvel utilisateur</p>
        </div>
        <span class="head-chip">ID auto</span>
      </div>
      <div class="card-body">
        <form id="form-add" method="post">
          <div class="fgrid">
            <div class="field">
              <label for="a-prenom">Prénom <span class="req">*</span></label>
              <input type="text" id="a-prenom" name="prenom" placeholder="Ex : Ahmed" required maxlength="100">
            </div>
            <div class="field">
              <label for="a-nom">Nom <span class="req">*</span></label>
              <input type="text" id="a-nom" name="nom" placeholder="Ex : Ould..." required maxlength="100">
            </div>
            <div class="field">
              <label for="a-email">Adresse Email <span class="req">*</span></label>
              <input type="email" id="a-email" name="email" placeholder="Ex : contact@domaine.mr" required maxlength="150">
            </div>
            <div class="field">
              <label for="a-tel">Téléphone</label>
              <input type="tel" id="a-tel" name="telephone" placeholder="Ex : 44332211" maxlength="20">
            </div>
            <div class="field">
              <label for="a-ville">Ville</label>
              <input type="text" id="a-ville" name="ville" placeholder="Ex : Nouakchott" maxlength="100">
            </div>
            <div class="field">
              <label for="a-mdp">Mot de passe <span class="req">*</span></label>
              <input type="password" id="a-mdp" name="mot_de_passe" placeholder="••••••••" required>
            </div>
          </div>
          <div class="divider"></div>
          <div class="form-actions">
            <button type="button" class="btn btn-ghost btn-cancel">Annuler</button>
            <button type="submit" class="btn btn-add" name="ajouter">＋ Créer le compte</button>
          </div>
        </form>
      </div>
    </div>
  </section>
  
  <section id="panel-edit" class="panel panel-hidden">
    <h2 class="section-title panel-title-margin">Mise à <span>jour</span></h2>
    <div class="form-card">
      <div class="card-head">
        <div class="head-icon head-icon-edit">✎</div>
        <div class="head-text">
          <h2>Modifier l'utilisateur</h2>
          <p>Mettre à jour les informations et coordonnées de l'utilisateur</p>
        </div>
      </div>
      <div class="card-body">
        <form id="form-edit" method="post">
          <div class="fgrid">
            <div class="field full">
              <label for="e-id">ID Utilisateur <span class="req">*</span></label>
              <input type="number" id="e-id" name="id" required readonly/>
            </div>
            <div class="field">
              <label for="e-prenom">Prénom <span class="req">*</span></label>
              <input type="text" id="e-prenom" name="prenom" required maxlength="100">
            </div>
            <div class="field">
              <label for="e-nom">Nom <span class="req">*</span></label>
              <input type="text" id="e-nom" name="nom" required maxlength="100">
            </div>
            <div class="field">
              <label for="e-email">Adresse Email <span class="req">*</span></label>
              <input type="email" id="e-email" name="email" required maxlength="150">
            </div>
            <div class="field">
              <label for="e-tel">Téléphone</label>
              <input type="tel" id="e-tel" name="telephone" maxlength="20">
            </div>
            <div class="field">
              <label for="e-ville">Ville</label>
              <input type="text" id="e-ville" name="ville" maxlength="100">
            </div>
            <div class="field">
              <label for="e-mdp">Mot de passe (Laisser vide pour ne pas changer)</label>
              <input type="password" id="e-mdp" name="mot_de_passe" placeholder="Nououveau mot de passe facultatif">
            </div>
          </div>
          <div class="divider"></div>
          <div class="form-actions">
            <button type="button" class="btn btn-ghost btn-cancel">Annuler</button>
            <button type="submit" class="btn btn-edit" name="modifier">✎ Enregistrer les modifications</button>
          </div>
        </form>
      </div>
    </div>
  </section>
  
  <section id="panel-delete" class="panel panel-hidden">
    <h2 class="section-title panel-title-margin">Zone de <span>danger</span></h2>
    <div class="form-card">
      <div class="card-head">
        <div class="head-icon head-icon-delete">🗑</div>
        <div class="head-text">
          <h2>Supprimer le compte</h2>
          <p>Désactiver et retirer définitivement cet utilisateur</p>
        </div>
        <span class="head-chip red">Irréversible</span>
      </div>
      <div class="card-body">
        <div class="alert">
          <div><strong>Attention.</strong> L'utilisateur perdra l'accès complet à l'application e-commerce immédiatement.</div>
        </div>
        <form id="form-delete" method="POST" onsubmit="return confirm('Supprimer définitivement cet utilisateur ?')">
          <div class="fgrid">
            <div class="field">
              <label for="d-id">ID Utilisateur <span class="req">*</span></label>
              <input type="number" id="d-id" name="id" required readonly/>
            </div>
            <div class="field">
              <label for="d-confirm">Confirmer l'email exact <span class="req">*</span></label>
              <input type="text" id="d-confirm" name="email_confirm" required/>
            </div>
          </div>
          <div class="divider"></div>
          <div class="form-actions">
            <button type="button" class="btn btn-ghost btn-cancel">Annuler</button>
            <button type="submit" class="btn btn-del" name="supprimer">✕ Supprimer l'utilisateur</button>
          </div>
        </form>
      </div>
    </div>
  </section>
</main>

<script>
  const panelAdd = document.getElementById('panel-add');
  const panelEdit = document.getElementById('panel-edit');
  const panelDelete = document.getElementById('panel-delete');

  function hideAllPanels() {
    panelAdd.classList.add('panel-hidden');
    panelEdit.classList.add('panel-hidden');
    panelDelete.classList.add('panel-hidden');
    document.querySelectorAll('tbody tr').forEach(r => r.classList.remove('selected'));
  }

  document.getElementById('btn-trigger-add').addEventListener('click', () => {
    hideAllPanels();
    panelAdd.classList.remove('panel-hidden');
    panelAdd.scrollIntoView({ behavior: 'smooth' });
  });

  document.querySelectorAll('.btn-row-edit').forEach(btn => {
    btn.addEventListener('click', () => {
      hideAllPanels();
      const row = btn.closest('tr');
      row.classList.add('selected');
      
      // Extraction dynamique
      document.getElementById('e-id').value = row.dataset.id;
      document.getElementById('e-prenom').value = row.dataset.prenom;
      document.getElementById('e-nom').value = row.dataset.nom;
      document.getElementById('e-email').value = row.dataset.email;
      
      const cells = row.getElementsByTagName('td');
      document.getElementById('e-tel').value = cells[5].textContent === '—' ? '' : cells[5].textContent;
      document.getElementById('e-ville').value = cells[6].textContent === '—' ? '' : cells[6].textContent;
      document.getElementById('e-mdp').value = ''; // On laisse vide par défaut
      
      panelEdit.classList.remove('panel-hidden');
      panelEdit.scrollIntoView({ behavior: 'smooth' });
    });
  });

  document.querySelectorAll('.btn-row-del').forEach(btn => {
    btn.addEventListener('click', () => {
      hideAllPanels();
      const row = btn.closest('tr');
      row.classList.add('selected');
      
      document.getElementById('d-id').value = row.dataset.id;
      document.getElementById('d-confirm').value = row.dataset.email;
      
      panelDelete.classList.remove('panel-hidden');
      panelDelete.scrollIntoView({ behavior: 'smooth' });
    });
  });

  document.querySelectorAll('.btn-cancel').forEach(btn => {
    btn.addEventListener('click', () => {
      hideAllPanels();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  });

  function updateCount(count) {
    document.getElementById('row-count').textContent = count + (count > 1 ? " utilisateurs" : " utilisateur");
  }
  updateCount(document.querySelectorAll('tbody tr').length);

  document.getElementById('search-user').addEventListener('input', function(e) {
    const term = e.target.value.toLowerCase().trim();
    let visibleCount = 0;
    document.querySelectorAll('tbody tr').forEach(row => {
      if (row.textContent.toLowerCase().includes(term)) {
        row.style.display = ''; visibleCount++;
      } else {
        row.style.display = 'none';
      }
    });
    updateCount(visibleCount);
  });
</script>

<?php if(isset($_GET['ajout']) && $_GET['ajout']==1): ?>
<script>
  window.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
      alert("✅ Utilisateur créé avec succès !");
      window.history.replaceState({}, document.title, "utilisateurs_formulaires.php");
    }, 100);
  });
</script>
<?php endif; ?>

<?php if(isset($_GET['modification']) && $_GET['modification']==1): ?>
<script>
  window.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
      alert("✎ Profil utilisateur mis à jour !");
      window.history.replaceState({}, document.title, "utilisateurs_formulaires.php");
    }, 100);
  });
</script>
<?php endif; ?>

<?php if(isset($_GET['suppression']) && $_GET['suppression']==1): ?>
<script>
  window.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
      alert("🗑️ Le compte utilisateur a été supprimé.");
      window.history.replaceState({}, document.title, "utilisateurs_formulaires.php");
    }, 100);
  });
</script>
<?php endif; ?>
</body>
</html>