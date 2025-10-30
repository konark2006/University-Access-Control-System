<?php
require_once("db_connect.php");

$searchTerm = "";
$results = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $searchTerm = trim($_POST["searchTerm"]);
  if (!empty($searchTerm)) {
    $query = "SELECT request_id, status, made_by_user_id, resource_id
              FROM Request
              WHERE request_id LIKE ? OR status LIKE ? OR made_by_user_id LIKE ?";
    $stmt = $conn->prepare($query);
    $like = "%{$searchTerm}%";
    $stmt->bind_param("sss", $like, $like, $like);
    $stmt->execute();
    $results = $stmt->get_result();
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Search Requests — UACS</title>
  <link rel="stylesheet" href="site_style.css?v=12">
</head>
<body>
  <!-- Header -->
  <header class="site-header">
    <div class="container header-inner">
      <a class="brand" href="../HW4/index.html">
        <img class="brand-logo" src="../HW4/img/UACS_logo.png" alt="UACS logo" />
        <span class="brand-text">UACS</span>
      </a>
      <nav class="nav">
        <a href="../HW4/index.html">🏠 Home</a>
        <a href="../HW5/maintenance.html">⚙️ Maintenance</a>
        <a class="active" href="index.html">🔍 Search</a>
        <button id="theme-toggle" class="btn small-btn" aria-label="Toggle dark mode">🌙</button>
      </nav>
    </div>
  </header>

  <!-- Search Form -->
  <main>
    <section class="hero">
      <h1>Search Access Requests</h1>
      <p>Enter a request ID, status (PENDING / APPROVED / DENIED), or user ID to find matching requests.</p>

      <form method="POST" action="">
        <input type="text" name="searchTerm" placeholder="Enter keyword..." value="<?= htmlspecialchars($searchTerm) ?>" required>
        <button type="submit">Search</button>
      </form>
    </section>

    <!-- Results -->
    <?php if ($_SERVER["REQUEST_METHOD"] === "POST"): ?>
      <section class="results">
        <?php if ($results && $results->num_rows > 0): ?>
          <h2>Results Found (<?= $results->num_rows ?>)</h2>
          <table>
            <thead>
              <tr>
                <th>Request ID</th>
                <th>Status</th>
                <th>User ID</th>
                <th>Resource ID</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $results->fetch_assoc()): ?>
                <tr>
                  <td><?= htmlspecialchars($row['request_id']) ?></td>
                  <td><?= htmlspecialchars($row['status']) ?></td>
                  <td><?= htmlspecialchars($row['made_by_user_id']) ?></td>
                  <td><?= htmlspecialchars($row['resource_id']) ?></td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        <?php else: ?>
          <p class="err">❌ No matching requests found.</p>
        <?php endif; ?>
      </section>
    <?php endif; ?>
  </main>

  <footer class="footer">
    <p>© 2025 UACS — Homework 6 | <a href="../HW4/imprint.html">Imprint / Disclaimer</a></p>
  </footer>

  <!-- Dark mode toggle -->
  <script>
    const toggle = document.getElementById("theme-toggle");
    toggle.addEventListener("click", () => {
      document.body.classList.toggle("dark-mode");
      toggle.textContent = document.body.classList.contains("dark-mode") ? "☀️" : "🌙";
    });
  </script>
</body>
</html>