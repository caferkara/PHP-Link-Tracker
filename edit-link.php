<?php
// Include configuration file
$config = include(__DIR__ . '/config/config.php');

// Establish database connection
$mysqli = new mysqli($config['host'], $config['username'], $config['password'], $config['database']);
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

// Function to get link by ID
function getLinkById($mysqli, $linkId) {
    $linkId = intval($linkId);
    $result = $mysqli->query("SELECT * FROM links WHERE id = $linkId");
    return $result->fetch_assoc();
}

// Function to update a link
function updateLink($mysqli, $linkId, $title, $url) {
    $linkId = intval($linkId);
    $title = $mysqli->real_escape_string($title);
    $url = $mysqli->real_escape_string($url);
    $mysqli->query("UPDATE links SET title = '$title', url = '$url' WHERE id = $linkId");
}

// Check if link ID is provided
if (isset($_GET['id'])) {
    $linkId = intval($_GET['id']);
    $link = getLinkById($mysqli, $linkId);
    if (!$link) {
        die('Link not found');
    }
} else {
    die('No link ID provided');
}

// Handle form submission to update link
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $url = $_POST['url'];
    updateLink($mysqli, $linkId, $title, $url);
    // Redirect back to tracker.php after updating
    header('Location: tracker.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="robots" content="noindex, nofollow" />
    <title>Link Tracking System</title>
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
    <link
      rel="icon"
      href="assets/img/favicon.ico"
      type="image/x-icon"
    />

    <!-- Fonts and icons -->
    <script src="assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["assets/css/fonts.min.css"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>
    <script>
        function copyToClipboard(linkId) {
            const link = `http://localhost/link-tracker/tracker.php?id=${linkId}`;
            const tempInput = document.createElement('input');
            document.body.appendChild(tempInput);
            tempInput.value = link;
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
            alert('Link copied to clipboard: ' + link);
        }
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/ck.min.css" />

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="assets/css/demo.css" />
  </head>
  <body>
    <div class="wrapper">
      <!-- Sidebar -->
      <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
          <!-- Logo Header -->
          <div class="logo-header" data-background-color="dark">
            <a href="tracker.php" class="logo">
              <img
                src="assets/img/logo.png"
                alt="navbar brand"
                class="navbar-brand"
                height="auto"
                width="auto" 
              />
            </a>
            <div class="nav-toggle">
              <button class="btn btn-toggle toggle-sidebar">
                <i class="gg-menu-right"></i>
              </button>
              <button class="btn btn-toggle sidenav-toggler">
                <i class="gg-menu-left"></i>
              </button>
            </div>
            <button class="topbar-toggler more">
              <i class="gg-more-vertical-alt"></i>
            </button>
          </div>
          <!-- End Logo Header -->
        </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
          <div class="sidebar-content">
            <ul class="nav nav-secondary">
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="tracker.php">
                  <i class="fas fa-link"></i>
                  <p>Links</p>
                </a>
              </li>
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="add-link.php">
                  <i class="fas fa-plus"></i>
                  <p>Add Link</p>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <!-- End Sidebar -->

      <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="dark">
              <a href="index.php" class="logo">
                <img
                  src="assets/img/logo_png"
                  alt="navbar brand"
                  class="navbar-brand"
                  height="auto"
                  width="auto"
                />
              </a>
              <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                  <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                  <i class="gg-menu-left"></i>
                </button>
              </div>
              <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
              </button>
            </div>
            <!-- End Logo Header -->
          </div>
          <!-- Navbar Header -->
          <nav
            class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom"
          >
            <div class="container-fluid">
              <nav
                class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex"
              >
              </nav>
                <span class="op-7">Welcome to Link Tracking System</span>
              </ul>
            </div>
          </nav>
          <!-- End Navbar -->
        </div>

        <div class="container">
          <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Edit Link</h3>
              </div>
            <div class="ms-md-auto py-2 py-md-0">
</div>
              </div>
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                  </div>
                  <div class="card-body">

                    <div class="col-md-6 col-lg-4">
                      <form method="post" action="edit-link.php?id=<?php echo $linkId; ?>">
                      <div class="form-group">
                          <label for="largeInput">Title</label>
                          <input type="text" class="form-control form-control" id="title" name="title" value="<?php echo htmlspecialchars($link['title']); ?>" required>
                        </div>
                        <div class="form-group">
                          <label for="largeInput">URL</label>
                          <input type="url" class="form-control form-control" id="url" name="url" value="<?php echo htmlspecialchars($link['url']); ?>" required>
                        </div>
                        <div class="form-group">
                          <button class="btn btn-success" type="submit">Submit</button>
                        </div>
                        </form>
                      </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <footer class="footer">
          <div class="container-fluid d-flex justify-content-between">
            <nav class="pull-left">
              <ul class="nav">
                <li class="nav-item">
                &copy; <span id="copyright-year"></span> - Link Tracking System - <a target="_blank" href="https://caferkara.com.tr/projects" target="_blank">Support</a>
                </li>
              </ul>
            </nav>
            <div>
              Version 1.1
            </div>
          </div>
        </footer>
      </div>
    </div>
    <!--   Core JS Files   -->
    <script src="assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>
    <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <script src="assets/js/plugin/datatables/datatables.min.js"></script>
    <script src="assets/js/ck.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        var currentYear = new Date().getFullYear();
        document.getElementById('copyright-year').textContent = currentYear;
    }); 

      $(document).ready(function () {
        $("#basic-datatables").DataTable({});
      });

    </script>
  </body>
</html>
