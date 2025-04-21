<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - BPC Registrar</title>
  <script src="asset/jquery.js"></script>
  <script src="asset/bootstrap/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="asset/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="asset/css/main-style.css">
  <script src="asset/sweetalert.js"></script>
</head>
<body>
  
  <style>
    body {
      position: relative;
      min-height: 100vh;
    }

    body::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
  
      background-image: url('images/bpc-background.jpg');
      background-size: cover;
      background-repeat: no-repeat;
      filter: brightness(30%);
      z-index: -1;
  }

    main {
      display: grid;
      place-items: center;
    }
  </style>

  <main class="container-sm">
    <div style="border-radius: 4px; max-width: 450px; margin-top: 2%; padding: 2rem; background-color: #fff;" class="d-flex flex-column w-100">
      <img width="128px" height="128px" src="images/bpc-logo.png" alt="BPC LOGO" class="align-self-center">
      <h2 class="mt-4">Register</h2>
      <form id="loginForm" class="mt-4">
        <div class="mb-3">
          <label for="stuid" class="form-label">Student ID</label>
          <input type="text" class="form-control" id="stuid" placeholder="04-2000-1923" required>
        </div>
        <div class="mb-3">
          <label for="stuname" class="form-label">Student Name</label>
          <input type="text" class="form-control" id="stuname" placeholder="Juan Dela Cruz" required>
        </div>
        <div class="mb-3">
          <label for="stuemail" class="form-label">Email</label>
          <input type="email" class="form-control" id="stuemail" placeholder="j.delacruz@gmail.com" required>
        </div>
        <div class="mb-3">
          <label for="stupassword" class="form-label">Password</label>
          <input type="password" class="form-control" id="stupassword" placeholder="**********" required>
        </div>
        <div class="mb-3">
          <label for="sturetypepassword" class="form-label">Re-type Password</label>
          <input type="password" class="form-control" id="sturetypepassword" placeholder="**********" required>
        </div>
        <button type="submit" class="btn btn-success btn-lg w-100">Register</button>
      </form>
      <a href="index.php" class="btn btn-link mt-3">Log In</a>
    </div>
    <h6 class="mt-4 text-white">BPC E-Registrar 2024</h6>
  </main>

  <script>
    $("#loginForm").on("submit", function(event){
      event.preventDefault()

      let stuid = $("#stuid").val();
      let stuname = $("#stuname").val();
      let stuemail = $("#stuemail").val();
      let stupassword = $("#stupassword").val();
      let sturetypepassword = $("#sturetypepassword").val();

      if(stupassword !== sturetypepassword){
        Swal.fire({
          title: "Password Mismatch!",
          text: "Make sure your password are matched correctly",
          icon: "error"
        });
      }else {
        $.ajax({
          type: "POST",
          url: "api/register.php",
          data: {
            stuid: stuid,
            stuname: stuname,
            stuemail: stuemail,
            stupassword: stupassword
          },
          success: response => {
            let json = JSON.parse(response)

            Swal.fire({
              title: json.message,
              text: json.description,
              icon: json.status
            });
          }
          
        })
      }
    })

    $(document).ready(function(){

      
    })
  </script>
</body>
</html>