<style>
  @media (max-width: 992px) {
    .sidebar {
      display: none !important;
    }
  }
  .sidebar > a {
    padding: 1rem;
    cursor: pointer;
    color: #fff !important;
    font-size: 18px;
    text-decoration: none;
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 0.75rem;
  }
  .sidebar > a:hover {
    background-color: #073000;
  }
  .sidebar {
    display: flex;
    flex-direction: column;
    background-color: #073000;
    position: relative;
    top: 0;
    left: 0;
  }
  .sidebar > img {
    margin-top: 2rem;
  }
  .sidebar > h6 {
    color: #fff;
    margin-top: 1rem;
    text-align: center;
  }

  #mobile-header {
    background-color: #073000;
    color: #fff;
    position: sticky; /* changed from fixed */
    top: 0;
    left: 0;
    width: 100%;
    z-index: 5;
  }

  #mobileOffcanvas {
    width: 85%;
    color: white;
    background-color: #073000;
  }

  .navbar-brand {
    display: flex;
    flex-direction: row;
    gap: 0.5rem;
    color: #fff;
  }

  .nav-link {
    color: #fff;
    font-size: 18px;
    padding: 1rem;
    display: flex;
    flex-direction: row;
    gap: 1rem;
    align-items: center;
  }
  .navbar-toggler {
    border-radius: 4px;
    background-color: #fff;
    font-size: 12px;
  }
</style>

<div class="sidebar col-2">
  <img src="images/bpc-logo.png" width="120px" height="120px" class="align-self-center">
  <h2 class="mt-4 mb-4 align-self-center text-white ">Menu</h2>
  <!-- <a href="request.php">
    <i class="fa-regular fa-file"></i>
    <span>Request Document</span>
  </a> -->
  <a href="v2-request.php">
    <i class="fa-regular fa-file"></i>
    <span>Request Document</span>
  </a>
  <a href="profile.php">
    <i class="fa-regular fa-user"></i>
    <span>Account</span>
  </a>
  <a class="logoutButton">
    <i class="fa-solid fa-power-off"></i>
    <span>Log Out</span>
  </a>

  <h6>BPC E-Registrar 2025</h6>
</div>

<nav class="navbar d-lg-none" id="mobile-header">
  <div class="container-fluid p-2">
    <img src="images/bpc-logo.png" width="48px" height="48px">
    <a class="navbar-brand" href="request.php">BPC E-Registrar 2025</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileOffcanvas" aria-controls="mobileOffcanvas">
      <span class="navbar-toggler-icon"></span>
    </button>
    
  </div>
</nav>

<!-- Offcanvas Menu -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="mobileOffcanvas" aria-labelledby="mobileOffcanvasLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="mobileOffcanvasLabel">Menu</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="v2-request.php">
          <i class="fa-regular fa-file me-2"></i>
          <span>Request Document</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="profile.php">
          <i class="fa-regular fa-user me-2"></i>
          <span>Account</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link logoutButton">
          <i class="fa-solid fa-power-off me-2"></i>
          <span>Log Out</span>
        </a>
      </li>
    </ul>
  </div>
</div>

<script>
  $(".logoutButton").on("click", function(){
    Swal.fire({
      title: "Are you sure you want to logout?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, logout"
    }).then((result) => {
      if (result.isConfirmed) {
        location.href = "index.php"
      }
    });
  })
</script>