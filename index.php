<?php
  include("api/connection.php");

  $system_settings = $conn -> query("SELECT * FROM content_management");
  $system_settings = $system_settings -> fetch_assoc();

  $conn -> close();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Log In - BPC Registrar</title>
    <script src="asset/jquery.js"></script>
    <script src="asset/bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="asset/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="asset/css/main-style.css" />
    <script src="asset/sweetalert.js"></script>
  </head>
  <body>
    <style>
      body {
        position: relative;
        min-height: 100vh;
      }

      body::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;

        background-image: url("<?php echo $system_settings["background_url"]; ?>");
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
      <div
        style="
          border-radius: 4px;
          max-width: 400px;
          margin-top: 5%;
          padding: 2rem;
          background-color: #fff;
        "
        class="d-flex flex-column w-100"
      >
        <img
          width="110px"
          height="110px"
          src="<?php echo $system_settings["logo_url"]; ?>"
          alt="BPC LOGO"
          class="align-self-center mb-2"
        />
        <form id="loginForm" class="mt-4">
          <div class="mb-2">
            <label for="stuid" class="form-label fw-semibold">User ID</label>
            <input type="text" class="form-control" name="user_id" />
          </div>
          <div class="mb-4">
            <label for="stupassword" class="form-label fw-semibold"
              >Password</label
            >
            <input type="password" class="form-control" name="user_password" />
          </div>
          <button
            type="submit"
            id="btnLOGIN"
            class="btn btn-primary btn-lg btn-success w-100 fw-bold"
          >
            Login
          </button>
          <button
            type="button"
            class="mt-4 btn btn-link btn-sm w-100"
            id="forgot_password_button"
          >
            Forgot Password?
          </button>
        </form>
      </div>
      <h6 class="mt-4 text-white"><?php echo $system_settings["system_name"]; ?></h6>
    </main>

    <div
      class="modal fade"
      id="privacyModal"
      tabindex="-1"
      aria-labelledby="privacyModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="privacyModalLabel">Privacy Notice</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body p-4">
            <p class="mt-2">
              Welcome to BPC Registrar, the online registrar system for Bulacan
              Polytechnic College.
            </p>
            <p class="mt-4">
              We are committed to protecting your privacy and ensuring that your
              personal information is handled in a safe and responsible manner.
              This Privacy Notice outlines how we collect, use, and protect your
              personal data.
            </p>
            <h6 class="mt-4">Information We Collect</h6>
            <p>
              We may collect personal information such as your name, contact
              details, and other relevant information for academic and
              administrative purposes.
            </p>
            <h6 class="mt-4">How We Use Your Information</h6>
            <p>
              Your data will be used solely for processing your registration,
              providing requested services, and complying with legal
              obligations.
            </p>
            <h6 class="mt-4">Data Protection</h6>
            <p>
              We implement security measures to ensure your data is protected
              from unauthorized access or disclosure.
            </p>
            <h6 class="mt-4">Contact Us</h6>
            <p>
              If you have any questions regarding this Privacy Notice, please
              contact us at [email address].
            </p>

            <div class="form-check mt-4">
              <input
                class="form-check-input"
                type="checkbox"
                id="privacyCheckbox"
              />
              <label class="form-check-label" for="privacyCheckbox">
                I have read and accept the Agreement and Privacy Policy of Bulacan Polytechnic College
              </label>
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-success"
              data-bs-dismiss="modal"
              id="agreeButton"
              disabled
            >
              I Agreed To Privacy Policy
            </button>
          </div>
        </div>
      </div>
    </div>

    <div
      class="modal fade"
      id="VerificationModal"
      tabindex="-1"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h6 class="modal-title fw-bold">Enter Verification Code (OTP)</h6>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body p-4">
            <form id="verification_form">
              <p>In order to proceed, enter the code sent to your email.</p>
              <input type="hidden" name="stuid_v" value="" />
              <input type="hidden" name="stupassword_v" value="" />
              <input type="text" name="otp" class="form-control" />
              <button class="mt-4 btn btn-success">Check</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div
      class="modal fade"
      id="forgotPasswordModal"
      tabindex="-1"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h6 class="modal-title fw-bold">Forgot Password</h6>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body p-4">
            <form id="forgot_password_form">
              <p><strong>Students:</strong> Please enter your Student ID (ex. MA233211). </p>
              <p><strong>Staffs:</strong> If you are a staff member, please contact your administrator to reset your password.</p>
              <input type="text" placeholder="MA23321175" name="email_fp" class="mt-4 form-control" />
              <button class="mt-4 btn btn-success" id="reset_password_button">Reset Password</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <script>
      $(document).ready(function () {
        $('#privacyCheckbox').on('change', function () {
          $('#agreeButton').prop('disabled', !this.checked);
        });
      });
      
      $("#verification_form").on("submit", function (event) {
        event.preventDefault();

        var formdata = new FormData(this);

        $.ajax({
          type: "POST",
          url: "api/login-verify.php",
          processData: false,
          contentType: false,
          data: formdata,
          success: (response) => {
            let json = JSON.parse(response);

            if (json.status === "error") {
              alert(json.description);
              location.reload();
              return;
            }

            Swal.fire({
              title: json.message,
              text: json.description,
              icon: json.status,
              showCancelButton: false,
              confirmButtonText: "Close",
            }).then((result) => {
              if (result.isConfirmed) {
                location.href = "v2-request.php";
              }
            });
          },
        });
      });

      $("#forgot_password_form").on("submit", function (event) {
        event.preventDefault();

        var formdata = new FormData(this);

        $.ajax({
          type: "POST",
          url: "api/forgot_password.php",
          processData: false,
          contentType: false,
          data: formdata,
          beforeSend: () => {
            $("#reset_password_button").attr("disabled", true)
            $("#reset_password_button").text("Resetting... (please don't close tab or page)")
          },
          success: (response) => {
            if (response === "ok") {
              Swal.fire({
                title: "Success!",
                text: "Password updated successfully.",
                icon: "success",
              });
            } else {
              Swal.fire({
                title: "Not Found",
                text: "The email address does not exist in our records.",
                icon: "warning",
              });
            }
          },
          complete: () => {
            $("#reset_password_button").attr("disabled", false);
            $("#reset_password_button").text("Reset Password");
            $("#forgotPasswordModal").modal("toggle");
          },
        });
      });

      $("#forgot_password_button").on("click", function () {
        $("#forgotPasswordModal").modal("toggle");
      });

      $("#loginForm").on("submit", function (event) {
        event.preventDefault();

        var formdata = new FormData(this);

        $.ajax({
          type: "POST",
          url: "api/login-verify.php",
          processData: false,
          contentType: false,
          data: formdata,
          beforeSend: () => {
            $("#btnLOGIN").attr("disabled", true); // Disable the button
            $("#btnLOGIN").html("Loading..."); // Change button text
          },
          success: (response) => {
            let json = JSON.parse(response);
            console.log(json);

            if (json.status === "error" || json.status === "not-found") {
              Swal.fire({
                title: json.message,
                text: json.description,
                icon: "error",
              })
            }
            
            if (json.status === "success") {
              Swal.fire({
                title: json.message,
                text: json.description,
                icon: "success",
                confirmButtonText: "OK",
              }).then((result) => {
                if (result.isConfirmed) {
                  location.href = json.location;
                }
              });
            }

            if (json.status === "otp-sent") {
              alert(json.description);

              var stuid_v = $("input[name='user_id']").val();
              var stupassword_v = $("input[name='user_password']").val();

              $("input[name='stuid_v']").val(stuid_v);
              $("input[name='stupassword_v']").val(stupassword_v);

              $("#VerificationModal").modal("toggle");
            }

            $("#btnLOGIN").attr("disabled", false); // Disable the button
            $("#btnLOGIN").html("LOGIN"); // Change button text
          },
        });
      });

      $(document).ready(function () {
        $("#privacyModal").modal("show");
      });
    </script>
  </body>
</html>
