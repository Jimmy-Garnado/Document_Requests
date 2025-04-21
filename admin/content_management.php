<?php
  session_start();
  include("api/connection.php");

  if($_SESSION['staffrole'] != "Admin"){
    header("location: staffs.php");
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Content Management - <?php echo $CONTENT['system_name']; ?></title>
  <?php include("static-loader.php"); ?>
</head>
<body>
  <main class="container-fluid d-flex flex-row p-0">
    <?php include("../reusables/admin-sidebar.php"); ?>
    
    <div class="col-10 p-4">
      <h4 class="fw-bold">Content Management</h4>
      <form id="cmsform" class="mt-4" enctype="multipart/form-data">
        <h5 class="fw-bold mb-2">System Images</h5>
        <div class="row mb-4">
          <div class="col-4 d-flex flex-column gap-3">
            <label class="form-label fw-semibold">System Logo</label>
            <img id="sys_logo_preview" style="height: 250px; width: 100%; object-fit: contain;" src="../<?php echo $CONTENT['logo_url'];?>" class="img-fluid" />
            <input accept="image/*" type="file" name="system_logo" id="system_logo" class="form-control" />
          </div>
          <div class="col-4 d-flex flex-column gap-3">
            <label class="form-label fw-semibold">System Background Image</label>
            <img id="sys_back_preview" style="height: 250px; width: 100%; object-fit: cover;" src="../<?php echo $CONTENT['background_url'];?>" class="img-fluid" />
            <input accept="image/*" type="file" name="system_bg" id="system_bg" class="form-control" />
          </div>
          <div class="col-4 d-flex flex-column gap-2">
          </div>
        </div>
        <h5 class="fw-bold mb-2">System Names and Texts</h5>
        <div class="row mb-4">
          <div class="col-4 d-flex flex-column gap-2">
            <label class="form-label fw-semibold">System Sidebar Color</label>
            <input type="color" class="form-control form-control-color w-100" id="system_color" name="system_color" value="<?php echo $CONTENT['sidebar_color'];?>" required>
            <button id="backtodefault" type="button" class="btn btn-ghost btn-sm">Default</button>
          </div>
          <div class="col-4 d-flex flex-column gap-2">
            <label class="form-label fw-semibold">System Name</label>
            <input type="text" value="<?php echo $CONTENT['system_name']; ?>" class="form-control" name="system_name" required />
          </div>
          <div class="col-4 d-flex flex-column gap-2">

          </div>
        </div>
        <div class="row">
          <div class="col-4">
            <button class="btn btn-primary" type="submit">Save Changes</button>
          </div>
        </div>
      </form>
    </div>
  </main>
  <script>
    $("#backtodefault").on("click", function(){
      $("#system_color").val("#073000")
    })

    $("#system_logo").on('change', function(event){
      const file = event.target.files[0];

      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          $('#sys_logo_preview').attr('src', e.target.result);
        };
        
        reader.readAsDataURL(file); // Read the file as a data URL
      }
    })

    $("#system_bg").on('change', function(event){
      const file = event.target.files[0];

      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          $('#sys_back_preview').attr('src', e.target.result);
        };
        
        reader.readAsDataURL(file); // Read the file as a data URL
      }
    })

    $("#cmsform").on("submit", function(e){
      e.preventDefault();

      var formData = new FormData(this)

      Swal.fire({
        title: "Confirm Changes?",
        text: "You can still edit your changes after page reloads",
        icon: "info",
        showCancelButton: false,
        confirmButtonText: "Save Changes"
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: 'api/post_edit_content_management.php',
            type: 'POST',
            data: formData,
            contentType: false, 
            processData: false,
            success: function (response) {
              if(response === "ok"){
                Swal.fire({
                title: 'System Updated Successfully',
                text: 'Please reload the page to see the changes.',
                icon: 'success',
                confirmButtonText: 'Reload Page',
              }).then((result) => {
                if (result.isConfirmed) {
                  location.reload();
                }
              });
              }
            },
          });
        }
      });
    })
  </script>
</body>
</html>