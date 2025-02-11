<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notifications - BPC E-Registrar</title>
  <script src="asset/jquery.js"></script>
  <script src="asset/bootstrap/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="asset/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="asset/css/main-style.css">
</head>
<body>
  
  <style>
    main {
      min-height: 100vh;
    }
  </style>

  <main class="container-fluid d-flex flex-row p-0">
    <?php include("reusables/client-sidebar.php"); ?>
    
    <div class="col-10 p-4">
      <h2>Notifications</h2>
      <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">First</th>
            <th scope="col">Last</th>
            <th scope="col">Handle</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th scope="row">1</th>
            <td>Mark</td>
            <td>Otto</td>
            <td>@mdo</td>
          </tr>
          <tr>
            <th scope="row">2</th>
            <td>Jacob</td>
            <td>Thornton</td>
            <td>@fat</td>
          </tr>
          <tr>
            <th scope="row">3</th>
            <td colspan="2">Larry the Bird</td>
            <td>@twitter</td>
          </tr>
        </tbody>
      </table>
    </div>
  </main>
</body>
</html>