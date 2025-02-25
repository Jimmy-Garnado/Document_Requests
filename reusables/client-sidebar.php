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
  <a href="request.php">
    <i class="fa-regular fa-file"></i>
    <span>Request</span>
  </a>
  <a href="my-request.php">
    <i class="fa-regular fa-file"></i>
    <span>My Requests</span>
  </a>
  <a href="profile.php">
    <i class="fa-regular fa-user"></i>
    <span>Profile</span>
  </a>
  <a class="logoutButton">
    <i class="fa-solid fa-power-off"></i>
    <span>Log Out</span>
  </a>

  <h6>BPC E-Registrar 2024</h6>
</div>

<nav class="navbar d-lg-none" id="mobile-header">
  <div class="container-fluid">
    <a class="navbar-brand" href="request.php">BPC E-Registrar 2024</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mobileMenu" aria-controls="mobileMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mobileMenu">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="request.php">
            <i class="fa-regular fa-file mr-4"></i>
            <span>Request</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="my-request.php">
            <i class="fa-regular fa-file mr-4"></i>
            <span>My Requests</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="profile.php">
            <i class="fa-regular fa-user mr-4"></i>
            <span>Profile</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link logoutButton">
            <i class="fa-solid fa-power-off mr-4"></i>
            <span>Log Out</span>
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

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
        location.href = "index.html"
      }
    });
  })
</script>