<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>Tableau de Bord — Mauri-Drones</title>
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
  --text:#e9ecf5;
  --muted:#6b7490;
  --label:#8892b0;
  --r:14px;
  --r-sm:8px;
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{
  background:var(--bg);
  color:var(--text);
  font-family:'Barlow',sans-serif;
  font-size:15px;
  line-height:1.6;
  min-height:100vh;
}

.site-header{
  background:rgba(13,15,20,.95);
  border-bottom:1px solid var(--border);
  padding:0 40px;height:70px;
  display:flex;align-items:center;
  position:sticky;top:0;z-index:100;
  backdrop-filter:blur(10px);
}
.logo{display:flex;align-items:center;gap:12px;text-decoration:none;}
.logo-img{width:42px;height:42px;object-fit:contain;border-radius: 5px;}
.logo-text{font-family:'Barlow Condensed',sans-serif;font-size:1.3rem;font-weight:800;letter-spacing:.3px;color:var(--text);}
.logo-dash{color:var(--blue)}

.page{max-width:1200px;margin:60px auto;padding:0 24px}

.page-header{
  margin-bottom: 48px;
  animation: fadeIn 0.4s ease both;
}
.page-eyebrow{font-size:.75rem;font-weight:700;letter-spacing:2.5px;text-transform:uppercase;color:var(--blue);margin-bottom:6px;}
.page-title{font-family:'Barlow Condensed',sans-serif;font-size:2.6rem;font-weight:800;letter-spacing:-.4px;line-height:1.1;color:var(--text);margin:0;}
.page-title span{color:var(--blue)}

/* Grille des modules */
.dashboard-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  gap: 24px;
  animation: fadeUp 0.5s ease 0.1s both;
}

.menu-card {
  background: var(--card);
  border: 1px solid var(--border2);
  border-radius: var(--r);
  padding: 32px;
  display: flex;
  flex-direction: column;
  transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
  text-decoration: none;
  color: inherit;
  position: relative;
  overflow: hidden;
}

.menu-card::before {
  content: '';
  position: absolute;
  top: 0; left: 0; width: 4px; height: 100%;
  background: var(--blue);
  transform: scaleY(0);
  transition: transform 0.25s ease;
}

.menu-card:hover {
  transform: translateY(-5px);
  border-color: var(--blue);
  box-shadow: 0 12px 30px rgba(13, 15, 20, 0.5), 0 0 20px rgba(59, 158, 255, 0.05);
}

.menu-card:hover::before {
  transform: scaleY(1);
}

.card-icon {
  width: 52px;
  height: 52px;
  border-radius: var(--r-sm);
  background: rgba(59, 158, 255, 0.05);
  border: 1px solid rgba(59, 158, 255, 0.15);
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 20px;
  transition: all 0.25s;
}

.card-icon svg {
  width: 26px;
  height: 26px;
  fill: var(--blue);
  transition: all 0.25s;
}

.menu-card:hover .card-icon {
  background: var(--blue);
  border-color: var(--blue);
  box-shadow: 0 4px 12px var(--blue-glow);
}

.menu-card:hover .card-icon svg {
  fill: #fff;
}

.card-title {
  font-family: 'Barlow Condensed', sans-serif;
  font-size: 1.5rem;
  font-weight: 800;
  letter-spacing: -0.2px;
  color: var(--text);
  margin-bottom: 8px;
}

.card-desc {
  color: var(--muted);
  font-size: 0.9rem;
  line-height: 1.5;
  margin-bottom: 24px;
  flex-grow: 1;
}

.card-action {
  font-size: 0.82rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1px;
  color: var(--blue);
  display: flex;
  align-items: center;
  gap: 8px;
  margin-top: auto;
  transition: gap 0.2s;
}

.menu-card:hover .card-action {
  gap: 12px;
  color: #fff;
}

@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
@keyframes fadeUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
</style>
</head>
<body>

<header class="site-header">
  <a class="logo" href="#">
    <img src="logoPI.png" alt="Logo Mauri-Drones" class="logo-img"/>
    <span class="logo-text">Mauri<span class="logo-dash">-</span>Drones</span>
  </a>
</header>

<main class="page">
  <div class="page-header">
    <p class="page-eyebrow">Système Central d'Administration</p>
    <h1 class="page-title">Tableau de <span>Bord</span></h1>
  </div>

  <div class="dashboard-grid">
    
    <a href="drones_formulaires.php" class="menu-card">
  <div class="card-icon">
    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
      <rect x="10" y="10" width="4" height="4" rx="1" />
      <path d="M10 10 L6 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
      <path d="M4 6 H8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
      <path d="M14 10 L18 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
      <path d="M16 6 H20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
      <path d="M10 14 L6 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
      <path d="M4 18 H8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
      <path d="M14 14 L18 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
      <path d="M16 18 H20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
      <circle cx="12" cy="12" r="1" fill="#fff"/>
    </svg>
  </div>
      <h2 class="card-title">Gestion des Drones</h2>
      <p class="card-desc">Ajouter, modifier ou retirer les modèles de drones de l'inventaire. Configuration des fiches techniques globales.</p>
      <div class="card-action">Ouvrir le module ➔</div>
    </a>

    <a href="accessoires_formulaires.php" class="menu-card">
      <div class="card-icon">
        <svg viewBox="0 0 24 24"><path d="M19 9h-4V3H9v6H5c-1.1 0-2 .9-2 2v8c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2v-8c0-1.1-.9-2-2-2zm-9-4h4v4h-4V5zm9 14H5v-8h14v8z"/></svg>
      </div>
      <h2 class="card-title">Gestion des Accessoires</h2>
      <p class="card-desc">Administrer le catalogue des pièces de rechange, batteries et extensions. Suivi des compatibilités et prix.</p>
      <div class="card-action">Ouvrir le module ➔</div>
    </a>

    <a href="cameras_formulaires.php" class="menu-card">
      <div class="card-icon">
        <svg viewBox="0 0 24 24"><path d="M9 2L7.17 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2h-3.17L15 2H9zm3 15c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/></svg>
      </div>
      <h2 class="card-title">Gestion des Caméras</h2>
      <p class="card-desc">Contrôler la liste des modules d'imagerie, capteurs thermiques et nacelles embarquées de la plateforme.</p>
      <div class="card-action">Ouvrir le module ➔</div>
    </a>

    <a href="categories_formulaires.php" class="menu-card">
      <div class="card-icon">
        <svg viewBox="0 0 24 24"><path d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H8V4h12v12z"/></svg>
      </div>
      <h2 class="card-title">Gestion des Catégories</h2>
      <p class="card-desc">Organiser l'architecture de navigation du site. Gérer les classifications pour les drones et les accessoires.</p>
      <div class="card-action">Ouvrir le module ➔</div>
    </a>

    <a href="utilisateurs_formulaires.php" class="menu-card">
      <div class="card-icon">
        <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5-4-8-4z"/></svg>
      </div>
      <h2 class="card-title">Gestion des Utilisateurs</h2>
      <p class="card-desc">Superviser les comptes clients enregistrés, coordonner les profils et administrer les accès de sécurité.</p>
      <div class="card-action">Ouvrir le module ➔</div>
    </a>

  </div>
</main>

</body>
</html>