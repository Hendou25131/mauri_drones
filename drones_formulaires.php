<?php
$con = mysqli_connect("localhost", "root", "", "mauri_drones");

// ── AJOUTER UN DRONE ──
if (isset($_POST['ajouter'])) {
    $marque = isset($_POST['marque']) ? htmlspecialchars(trim($_POST['marque'])) : '';
    $modele = isset($_POST['modele']) ? htmlspecialchars(trim($_POST['modele'])) : '';
    $categorie = isset($_POST['categorie']) ? htmlspecialchars(trim($_POST['categorie'])) : ''; 
    $autonomie = isset($_POST['autonomie_m']) ? (int)$_POST['autonomie_m'] : 0;
    $capteur = isset($_POST['capteur_k']) ? (int)$_POST['capteur_k'] : 0;
    $portee = isset($_POST['portee_m']) ? (int)$_POST['portee_m'] : 0;
    $prix_mru = isset($_POST['prix_final_MRU']) ? (float)$_POST['prix_final_MRU'] : 0.0;
    $statut = isset($_POST['statut_stock']) ? htmlspecialchars(trim($_POST['statut_stock'])) : '';
    $lien = isset($_POST['lien_image']) ? htmlspecialchars(trim($_POST['lien_image'])) : '';

    if (!empty($marque) and !empty($modele) and !empty($categorie) and !empty($statut) and !empty($lien)) {
        $sql = "INSERT INTO drones (marque, modele, id_categorie, autonomie_m, capteur_k, portee_m, prix_final_MRU, statut_stock, lien_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssiiiidss", $marque, $modele, $categorie, $autonomie, $capteur, $portee, $prix_mru, $statut, $lien);
        if ($stmt->execute()) {
            header("location:drones_formulaires.php?ajout=1");
            exit();
        } else {
            echo "<script> alert('erreur lors de la sauvegarde dans la base !') </script>";
        }
    }
}

// ── MODIFIER UN DRONE ──
if (isset($_POST['modifier'])) {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $marque = isset($_POST['marque']) ? htmlspecialchars(trim($_POST['marque'])) : '';
    $modele = isset($_POST['modele']) ? htmlspecialchars(trim($_POST['modele'])) : '';
    $categorie = isset($_POST['categorie']) ? htmlspecialchars(trim($_POST['categorie'])) : ''; 
    $autonomie = isset($_POST['autonomie_m']) ? (int)$_POST['autonomie_m'] : 0;
    $capteur = isset($_POST['capteur_k']) ? (int)$_POST['capteur_k'] : 0;
    $portee = isset($_POST['portee_m']) ? (int)$_POST['portee_m'] : 0;
    $prix_mru = isset($_POST['prix_final_MRU']) ? (float)$_POST['prix_final_MRU'] : 0.0;
    $statut = isset($_POST['statut_stock']) ? htmlspecialchars(trim($_POST['statut_stock'])) : '';
    $lien = isset($_POST['lien_image']) ? htmlspecialchars(trim($_POST['lien_image'])) : '';

    if (!empty($id) and !empty($marque) and !empty($modele) and !empty($categorie) and !empty($statut) and !empty($lien)) {
        $sql = "UPDATE drones SET marque=?, modele=?, id_categorie=?, autonomie_m=?, capteur_k=?, portee_m=?, prix_final_MRU=?, statut_stock=?, lien_image=? WHERE id_drone=?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssiiiidssi", $marque, $modele, $categorie, $autonomie, $capteur, $portee, $prix_mru, $statut, $lien, $id);
        if ($stmt->execute()) {
            header("location:drones_formulaires.php?modification=1");
            exit();
        } else {
            echo "<script> alert('erreur lors de la sauvegarde dans la base !') </script>";
        }
    }
}

// ── SUPPRIMER UN DRONE ──
if (isset($_POST['supprimer'])) {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $modele_confirme = isset($_POST['modele_confirm']) ? htmlspecialchars(trim($_POST['modele_confirm'])) : '';
    if (!empty($id) and !empty($modele_confirme)) {
        $sql = "DELETE FROM drones WHERE id_drone=? AND modele=?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("is", $id, $modele_confirme);
        if ($stmt->execute()) {
            header("location:drones_formulaires.php?suppression=1");
            exit();
        } else {
            echo "<script> alert('erreur lors de la sauvegarde dans la base !') </script>";
        }
    }
}

$sql = "SELECT * FROM drones";
$res = mysqli_query($con, $sql);

// Extraction des catégories typées 'Drone' ou génériques
$sql_cat = "SELECT * FROM categorie WHERE type='drone'";
$res1 = mysqli_query($con, $sql_cat);
$res2 = mysqli_query($con, $sql_cat);
?> 
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>Gestion Catalogue Drones — Mauri-Drones</title>
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

.page{max-width:1140px;margin:48px auto 48px;padding:0 24px}

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

input[type=text],input[type=number],input[type=url],select,textarea{
  background:#0f1219; border:1px solid var(--border2); border-radius:var(--r-sm);
  color:var(--text); font-family:'Barlow',sans-serif; font-size:.92rem; padding:11px 14px; width:100%; outline:none;
}
input:focus,select:focus,textarea:focus{border-color:var(--blue);box-shadow:0 0 0 3px var(--blue-glow)}
#form-edit input:focus,#form-edit select:focus{border-color:var(--green);box-shadow:0 0 0 3px var(--green-glow)}
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
a{ font-weight: bold; color: white; text-decoration: none; }
a:hover{ text-decoration: underline; }

.catalogue-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;}
.catalogue-count{
  background:rgba(59,158,255,.1);border:1px solid #3b9eff38; color:var(--blue);border-radius:20px; font-size:.7rem;font-weight:700;padding:4px 12px;
}
.table-wrap{background:var(--card);border:1px solid var(--border2);border-radius:var(--r);overflow:hidden;overflow-x:auto;}
table{width:100%;border-collapse:collapse;min-width:1050px}
thead tr{background:rgba(59,158,255,.06);border-bottom:1px solid var(--border2)}
thead th{padding:12px 14px;text-align:left;font-size:.68rem;font-weight:700;color:var(--label);text-transform:uppercase;}
tbody tr{border-bottom:1px solid var(--border)}
tbody tr.selected{background:rgba(59,158,255,.07)!important;border-left:3px solid var(--blue)}
td{padding:12px 14px;font-size:.88rem;vertical-align:middle}
td.td-id{color:var(--muted);font-size:.78rem;font-weight:600}
.td-cat{ font-size:.75rem;font-weight:700; background:rgba(59,158,255,.08);color:var(--blue); border-radius:20px;padding:3px 10px;}
td.td-prix{font-weight:700;color:var(--green)}
.badge-stock{ font-size:.72rem; padding:3px 8px; border-radius:4px; font-weight:600;}
.badge-en-stock{ background:rgba(47,217,142,.15); color:var(--green); }
.badge-sur-commande{ background:rgba(259,158,255,.12); color:var(--blue); }

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
      <h1 class="page-title">Gestion des <span>drones</span></h1>
    </div>
    <div class="search-container">
      <input type="text" id="search-drone" placeholder="🔍 Rechercher un drone...">
    </div>
  </div>

  <div class="catalogue-top-bar">
    <h2 class="section-title">Catalogue <span>actuel</span></h2>
    <button type="button" class="btn btn-add" id="btn-trigger-add">＋ Ajouter un drone</button>
  </div>

  <div class="catalogue-section">
    <div class="catalogue-header">
      <span class="catalogue-count" id="row-count">Articles</span>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Actions</th>
            <th>ID</th>
            <th>Marque</th>
            <th>Modèle</th>
            <th>Catégorie</th>
            <th>Autonomie (min)</th>
            <th>Capteur (K)</th>
            <th>Portée (m)</th>
            <th>Prix (MRU)</th>
            <th>Statut stock</th>
            <th>Lien image</th>
          </tr>
        </thead>
        <tbody>
          <?php
          while($tab=mysqli_fetch_assoc($res)){
            $stockClass = ($tab['statut_stock'] == 'En stock') ? 'badge-en-stock' : 'badge-sur-commande';
            echo "<tr data-id='".$tab['id_drone']."' data-marque='".htmlspecialchars($tab['marque'])."' data-modele='".htmlspecialchars($tab['modele'])."'>";
            echo " <td class='actions-cell'> 
                    <button class='btn-action-row btn-row-edit'>✎ Modifier</button>
                    <button class='btn-action-row btn-row-del'>✕ Suppr.</button>
                   </td>";
            echo " <td class='td-id'>".$tab['id_drone']."</td>";
            echo " <td>".$tab['marque']."</td>";
            echo " <td style='font-weight:600;'>".$tab['modele']."</td>";
            echo " <td><span class='td-cat'>".$tab['id_categorie']."</span></td>";
            echo " <td>".$tab['autonomie_m']." min</td>";
            echo " <td>".$tab['capteur_k']." K</td>";
            echo " <td>".$tab['portee_m']." m</td>";
            echo " <td class='td-prix'>".number_format($tab['prix_final_MRU'], 2, '.', '')."</td>";
            echo " <td><span class='badge-stock ".$stockClass."'>".$tab['statut_stock']."</span></td>";
            echo " <td style='font-size:0.75rem; color:var(--muted); max-width:150px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;'><a href='".$tab['lien_image']."' target='_blank'>Voir l'image</a></td>";
            echo " </tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <section id="panel-add" class="panel panel-hidden">
    <h2 class="section-title panel-title-margin">Nouveau <span>drone</span></h2>
    <div class="form-card">
      <div class="card-head">
        <div class="head-icon head-icon-add">➕</div>
        <div class="head-text">
          <h2>Ajouter un modèle</h2>
          <p>Enregistrer un nouveau drone au catalogue</p>
        </div>
        <span class="head-chip">ID auto</span>
      </div>
      <div class="card-body">
        <form id="form-add" method="post">
          <div class="fgrid">
            <div class="field">
              <label for="a-marque">Marque <span class="req">*</span></label>
              <input type="text" id="a-marque" name="marque" placeholder="Ex : DJI" required maxlength="120">
            </div>
            <div class="field">
              <label for="a-modele">Modèle <span class="req">*</span></label>
              <input type="text" id="a-modele" name="modele" placeholder="Ex : Matrice 350 RTK" required maxlength="255">
            </div>
            <div class="field">
              <label for="a-categorie">Catégorie <span class="req">*</span></label>
              <select id="a-categorie" name="categorie" required>
                <option value="" disabled selected>— Sélectionner —</option>
                <?php 
                 while($tab=mysqli_fetch_assoc($res1)){
                  echo "<option value='".$tab['id_categorie']."'>".$tab['nom']."</option>";
                } ?>
              </select>
            </div>
            <div class="field">
              <label for="a-autonomie">Autonomie (minutes)</label>
              <input type="number" id="a-autonomie" name="autonomie_m" placeholder="Ex : 55" min="0">
            </div>
            <div class="field">
              <label for="a-capteur">Résolution Capteur (K)</label>
              <input type="number" id="a-capteur" name="capteur_k" placeholder="Ex : 4" min="0">
            </div>
            <div class="field">
              <label for="a-portee">Portée de transmission (m)</label>
              <input type="number" id="a-portee" name="portee_m" placeholder="Ex : 20000" min="0">
            </div>
            <div class="field">
              <label for="a-prix">Prix Final (MRU) <span class="req">*</span></label>
              <input type="number" id="a-prix" name="prix_final_MRU" placeholder="Ex : 480000" min="0" step="0.01" required/>
            </div>
            <div class="field">
              <label for="a-stock">Statut du stock <span class="req">*</span></label>
              <select id="a-stock" name="statut_stock" required>
                <option value="En stock">En stock</option>
                <option value="Sur commande">Sur commande</option>
              </select>
            </div>
            <div class="field full">
              <label for="a-lien">Lien Image (URL ou Nom de fichier) <span class="req">*</span></label>
              <input type="text" id="a-lien" name="lien_image" placeholder="https://... ou drone.jpg" required maxlength="500"/>
            </div>
          </div>
          <div class="divider"></div>
          <div class="form-actions">
            <button type="button" class="btn btn-ghost btn-cancel">Annuler</button>
            <button type="submit" class="btn btn-add" name="ajouter">＋ Ajouter le drone</button>
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
          <h2>Modifier un drone</h2>
          <p>Mettre à jour les spécifications techniques et tarifaires</p>
        </div>
      </div>
      <div class="card-body">
        <form id="form-edit" method="post">
          <div class="fgrid">
            <div class="field full">
              <label for="e-id">ID du drone <span class="req">*</span></label>
              <input type="number" id="e-id" name="id" required readonly/>
            </div>
            <div class="field">
              <label for="e-marque">Marque <span class="req">*</span></label>
              <input type="text" id="e-marque" name="marque" required maxlength="120">
            </div>
            <div class="field">
              <label for="e-modele">Modèle <span class="req">*</span></label>
              <input type="text" id="e-modele" name="modele" required maxlength="255">
            </div>
            <div class="field">
              <label for="e-categorie">Catégorie <span class="req">*</span></label>
              <select id="e-categorie" name="categorie" required>
                <option value="" disabled>— Sélectionner —</option>
                <?php
                 while($tab=mysqli_fetch_assoc($res2)){
                  echo "<option value='".$tab['id_categorie']."'>".$tab['nom']."</option>";
                } ?>
              </select>
            </div>
            <div class="field">
              <label for="e-autonomie">Autonomie (minutes)</label>
              <input type="number" id="e-autonomie" name="autonomie_m" min="0">
            </div>
            <div class="field">
              <label for="e-capteur">Résolution Capteur (K)</label>
              <input type="number" id="e-capteur" name="capteur_k" min="0">
            </div>
            <div class="field">
              <label for="e-portee">Portée de transmission (m)</label>
              <input type="number" id="e-portee" name="portee_m" min="0">
            </div>
            <div class="field">
              <label for="e-prix">Prix Final (MRU) <span class="req">*</span></label>
              <input type="number" id="e-prix" name="prix_final_MRU" min="0" step="0.01" required/>
            </div>
            <div class="field">
              <label for="e-stock">Statut du stock <span class="req">*</span></label>
              <select id="e-stock" name="statut_stock" required>
                <option value="En stock">En stock</option>
                <option value="Sur commande">Sur commande</option>
              </select>
            </div>
            <div class="field full">
              <label for="e-lien">Lien Image (URL) <span class="req">*</span></label>
              <input type="text" id="e-lien" name="lien_image" required maxlength="500"/>
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
          <h2>Supprimer un drone</h2>
          <p>Retirer définitivement cet appareil du catalogue</p>
        </div>
        <span class="head-chip red">Irréversible</span>
      </div>
      <div class="card-body">
        <div class="alert">
          <div><strong>Attention.</strong> Cette action supprime le drone ainsi que sa configuration associée.</div>
        </div>
        <form id="form-delete" method="POST" onsubmit="return confirm('Supprimer définitivement ce drone ?')">
          <div class="fgrid">
            <div class="field">
              <label for="d-id">ID du drone <span class="req">*</span></label>
              <input type="number" id="d-id" name="id" required readonly/>
            </div>
            <div class="field">
              <label for="d-confirm">Confirmer le modèle exact <span class="req">*</span></label>
              <input type="text" id="d-confirm" name="modele_confirm" required/>
            </div>
          </div>
          <div class="divider"></div>
          <div class="form-actions">
            <button type="button" class="btn btn-ghost btn-cancel">Annuler</button>
            <button type="submit" class="btn btn-del" name="supprimer">✕ Supprimer le drone</button>
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
      
      // Extraction dynamique des données de la ligne pour alimenter le formulaire d'édition
      document.getElementById('e-id').value = row.dataset.id;
      document.getElementById('e-marque').value = row.dataset.marque;
      document.getElementById('e-modele').value = row.dataset.modele;
      
      const cells = row.getElementsByTagName('td');
      document.getElementById('e-autonomie').value = parseInt(cells[5].textContent);
      document.getElementById('e-capteur').value = parseInt(cells[6].textContent);
      document.getElementById('e-portee').value = parseInt(cells[7].textContent);
      document.getElementById('e-prix').value = parseFloat(cells[8].textContent);
      
      const statusText = cells[9].textContent.trim();
      document.getElementById('e-stock').value = statusText;
      
      const imgLink = cells[10].querySelector('a') ? cells[10].querySelector('a').getAttribute('href') : '';
      document.getElementById('e-lien').value = imgLink;
      
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
      document.getElementById('d-confirm').value = row.dataset.modele;
      
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
    document.getElementById('row-count').textContent = count + (count > 1 ? " drones" : " drone");
  }
  updateCount(document.querySelectorAll('tbody tr').length);

  document.getElementById('search-drone').addEventListener('input', function(e) {
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
      alert("✅ Drone ajouté dans la base avec succès !");
      window.history.replaceState({}, document.title, "drones_formulaires.php");
    }, 100);
  });
</script>
<?php endif; ?>

<?php if(isset($_GET['modification']) && $_GET['modification']==1): ?>
<script>
  window.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
      alert("✎ Spécifications du drone mises à jour !");
      window.history.replaceState({}, document.title, "drones_formulaires.php");
    }, 100);
  });
</script>
<?php endif; ?>

<?php if(isset($_GET['suppression']) && $_GET['suppression']==1): ?>
<script>
  window.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
      alert("🗑️ Drone retiré du catalogue.");
      window.history.replaceState({}, document.title, "drones_formulaires.php");
    }, 100);
  });
</script>
<?php endif; ?>
</body>
</html>