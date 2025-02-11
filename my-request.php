<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Request - BPC E-Registrar</title>
  <?php include("reusables/client-static-loader.php"); ?>
</head>
<body>
  
  <style>
    main {
      min-height: 100vh;
    }

    section > div {
      background-color: #fff;
      padding: 2rem 4rem;
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    @media (max-width: 992px) {
      section > div {
        padding: 1rem;
      }
    }

    td > a {
      font-weight: 500;
    }

    .row-action {
      display: flex;
      flex-direction: row;
      gap: 0.5rem;
      align-items: center;
    }
  </style>

  <main class="container-fluid d-flex flex-lg-row flex-column p-0">
    <?php include("reusables/client-sidebar.php"); ?>
    <section class="col-12 col-lg-10">
      <div>
        <div style="display: flex; flex-direction: row; justify-content: space-between; align-items:center;">
          <h1>My Request</h1>
          <a href="request.php" class="btn btn-primary">Request New</a>
        </div>
        <hr />
        <p>You can click the transaction ID to view request details.</p>
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th scope="col">Transaction ID</th>
                <th scope="col">Document Type</th>
                <th scope="col">Date Requested</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody class="table-body">
              <tr>
                <td class="align-middle">
                  <a href="">0978222XLLSSA</a>
                </td>
                <td class="align-middle">Transcript Of Records</td>
                <td class="align-middle">October 6, 2024</td>
                <td class="align-middle">Waiting For Approval</td>
                <td class="align-middle">
                  <div class="row-action">
                    <button class="btn btn-sm btn-success">Follow Up</button>
                    <button class="btn btn-sm btn-danger">Cancel</button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </main>
  <script>
    $(document).on("click", '.cancelButton', function(){
      let target = $(this).attr('data-target');

      $.ajax({
        url: 'api/post_cancel_request.php',
        type: 'post',
        data: {
          requestid : target
        },
        success : response => {
          let json = JSON.parse(response);

          if(json.status === "success"){
            Swal.fire({
              title: json.message,
              text: json.description,
              icon: json.status,
              showCancelButton: false,
              confirmButtonText: "Reload Page"
            }).then((result) => {
              if (result.isConfirmed) {
                location.href = "my-request.php"
              }
            });
          }else {
            Swal.fire({
              title: json.message,
              text: json.description,
              icon: json.status
            })
          }
        }
      });
    })

    $(document).ready(function(){
      $.ajax({
        type: "get",
        url: "api/get_all_my_request.php",
        success: response => {
          $(".table-body").html(response)
        }
      })
    })
  </script>
</body>
</html>