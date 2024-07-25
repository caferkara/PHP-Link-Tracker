<?php
// Include configuration file
$config = include(__DIR__ . '/config/config.php');

// Establish database connection
$mysqli = new mysqli($config['host'], $config['username'], $config['password'], $config['database']);
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

// Function to retrieve all links from database
function getLinks($mysqli) {
    $links = array();
    $result = $mysqli->query("SELECT * FROM links");
    while ($row = $result->fetch_assoc()) {
        $links[] = $row;
    }
    return $links;
}

// Function to increment click count for a link
function trackLinkClick($mysqli, $linkId) {
    session_start();
    if (!isset($_SESSION['clicked_links'])) {
        $_SESSION['clicked_links'] = array();
    }

    // Check if link has already been clicked in this session
    if (!in_array($linkId, $_SESSION['clicked_links'])) {
        // Update click count
        $mysqli->query("UPDATE links SET clicks = clicks + 1 WHERE id = $linkId");

        // Mark link as clicked in this session
        $_SESSION['clicked_links'][] = $linkId;
    }

    // Fetch the URL associated with the link ID
    $result = $mysqli->query("SELECT url FROM links WHERE id = $linkId");
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $url = $row['url'];
        // Redirect to the URL
        header("Location: $url");
        exit();
    } else {
        // Redirect back to tracker.php if link ID is not found
        header("Location: tracker.php");
        exit();
    }
}

// Function to insert a new link into the database
function insertLink($mysqli, $title, $url) {
    $title = $mysqli->real_escape_string($title);
    $url = $mysqli->real_escape_string($url);
    $mysqli->query("INSERT INTO links (title, url) VALUES ('$title', '$url')");
}

// Function to delete a link from the database
function deleteLink($mysqli, $linkId) {
    $linkId = intval($linkId);
    $mysqli->query("DELETE FROM links WHERE id = $linkId");
}

// Handle form submission to add a new link
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $url = $_POST['url'];
    insertLink($mysqli, $title, $url);
    // Redirect to avoid form resubmission on refresh
    header('Location: tracker.php');
    exit();
}

// Handle link click
if (isset($_GET['id'])) {
    $linkId = intval($_GET['id']);
    trackLinkClick($mysqli, $linkId);
    // Exit script after redirecting
    exit();
}

// Handle link deletion
if (isset($_GET['delete'])) {
    $linkId = intval($_GET['delete']);
    deleteLink($mysqli, $linkId);
    // Redirect back to tracker.php after deletion
    header('Location: tracker.php');
    exit();
}

// Display all links
$links = getLinks($mysqli);
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
                <h3 class="fw-bold mb-3">Add Link</h3>
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
                      <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                      <div class="form-group">
                          <label for="largeInput">Title</label>
                          <input type="text" class="form-control form-control" id="title" name="title" required>
                        </div>
                        <div class="form-group">
                          <label for="largeInput">URL</label>
                          <input type="url" class="form-control form-control" id="url" name="url" required>
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
