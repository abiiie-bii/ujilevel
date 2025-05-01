<?php
session_start();
include('./db/koneksi.php');

$userLoggedIn = false;
if (isset($_SESSION['user_id'])) {
    $userLoggedIn = true;
    $userId = $_SESSION['user_id'];
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE id = $userId");
    $userData = mysqli_fetch_assoc($query);
}

$query = "SELECT * FROM resep_makanan ORDER BY id DESC";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ResepBiyu - Temukan Resep Favoritmu</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>

</body>
</html>

<header>
  <div class="auth-buttons">
    <?php if ($userLoggedIn): ?>
      <?php
        $fotoPath = !empty($userData['foto']) ? 'uploads/' . $userData['foto'] : 'uploads/user.jpg';
      ?>
      <div class="profile-info">
        <img src="<?= $fotoPath ?>" alt="Foto Profil">
        <span class="username">Halo, <?= htmlspecialchars($userData['name']) ?></span>
        <form action="logout.php" method="post">
          <button type="submit" class="logout-btn">Logout</button>
        </form>
      </div>
    <?php else: ?>
      <a href="login.php" class="login-btn">Login</a>
      <a href="signup.php" class="signup-btn">Daftar</a>
    <?php endif; ?>
  </div>
  <center>
  <div class="hero-section">
  <!-- Video Background -->
  <video autoplay muted loop class="bg-video">
    <source src="image/Food Intro 🍳 Template 🍳 for Youtube in 4K 🍲 no text (FREE) (no copyright).mp4" type="video/mp4">
    Browser Anda tidak mendukung pemutaran video.
  </video>

  <!-- Konten Di Atas Video -->
  <div class="hero-content">
    <h1>ResepBiyu</h1>
    <p>Temukan berbagai resep masakan favoritmu!</p>
  </div>
</div>
  </center>
</header>

<?php if ($userLoggedIn): ?>
  <div class="profile-form">
    <form action="update_profile.php" method="POST" enctype="multipart/form-data">
      <center>
      <label>Nama Lengkap:</label>
      <input type="text" name="name" value="<?= htmlspecialchars($userData['name']) ?>" required>

      <label>Foto Profil:</label>
      <input type="file" name="foto">

      <button type="submit">Simpan Profil</button>
      </center>
    </form>
  </div>
<?php endif; ?>

<main>
  <section class="search-section">
    <h2>Cari Resep</h2>
    <input 
      type="text" 
      id="searchInput" 
      placeholder="Cari resep di sini..." 
      onkeyup="filterResep()"
    >
    
  </section>

  <section class="recipes" id="recipe-container">
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
      <div class="recipe-card">
        <img src="<?= htmlspecialchars($row['foto']) ?>" alt="<?= htmlspecialchars($row['nama']) ?>" />
        <h3><?= htmlspecialchars($row['nama']) ?></h3>
        <p><?= htmlspecialchars($row['deskripsi']) ?></p>
        <?php if (!empty($row['ingredients']) && !empty($row['steps'])): ?>
          <button onclick='showDetail(<?= json_encode($row, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'><a href="#recipe-detail">Lihat detail</a></button>
        <?php endif; ?>
      </div>
    <?php endwhile; ?>
  </section>

  <div id="recipe-detail" style="display: none;">
  <div class="recipe-content">
    <img id="detail-image" src="" alt="Foto Resep" />
    <div class="recipe-text">
      <h2 id="detail-title"></h2>
      <p id="detail-description"></p>
      <h4>Bahan:</h4>
      <ul id="detail-ingredients"></ul>
      <h4>Langkah-langkah:</h4>
      <ol id="detail-steps"></ol>
    </div>
  </div>
  <button onclick="closeDetail()" class="logout-btn" style="margin-top: 1rem;">Tutup</button>
</div>

</main>
<script>
function filterResep() {
  const input = document.getElementById('searchInput');
  const filter = input.value.toLowerCase();
  const cards = document.querySelectorAll('.recipe-card');

  cards.forEach(card => {
    const title = card.querySelector('h3').innerText.toLowerCase();
    card.style.display = title.includes(filter) ? 'block' : 'none';
  });
}

document.getElementById('searchInput').addEventListener('keydown', function(event) {
  if (event.key === 'Enter') {
    event.preventDefault(); // mencegah reload halaman jika dalam form
    filterResep(); // panggil fungsi filter
  }
});

function closeDetail() {
  document.getElementById('recipe-detail').style.display = 'none';
}

function showDetail(data) {
  document.getElementById('recipe-detail').style.display = 'block';
  document.getElementById('detail-title').innerText = data.nama;
  document.getElementById('detail-image').src = data.foto;
  document.getElementById('detail-description').innerText = data.deskripsi;
  
  const ingredients = JSON.parse(data.ingredients || '[]');
  const steps = JSON.parse(data.steps || '[]');

  const ingList = document.getElementById('detail-ingredients');
  ingList.innerHTML = '';
  ingredients.forEach(ing => {
    const li = document.createElement('li');
    li.innerText = ing;
    ingList.appendChild(li);
  });

  const stepList = document.getElementById('detail-steps');
  stepList.innerHTML = '';
  steps.forEach(step => {
    const li = document.createElement('li');
    li.innerText = step;
    stepList.appendChild(li);
  });
}
</script>

</body>
</html>
