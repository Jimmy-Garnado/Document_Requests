<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Request - BPC E-Registrar</title>
  <?php include("reusables/client-static-loader.php"); ?>
</head>
<body>
  
  <style>
    main {
      min-height: 100vh;
    }

    section > form {
      background-color: #fff;
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    @media (max-width: 992px) {
      section > form {
        padding: 1rem;
      }

      #calendar {
        padding: 0 !important;
        height: 463px !important;
      }
      .fc .fc-daygrid-body-unbalanced .fc-daygrid-day-events {
        min-height: 0.5em !important;
      }

      .fc .fc-button {
        padding: 0.2em 0.3em !important;
      }

      .fc-toolbar-title {
        font-size: 1em !important;
      }
    }

    #calendar {
      max-width: 100%;
      padding: 1rem;
    }

    .fc-col-header-cell-cushion, .fc-daygrid-day-number {
      text-decoration: none;
      color: #000;
    }

    .fc-day-past {
      background-color: #a1a1a1 !important;
      color: white;
      pointer-events: none;
    }

    .fc-day-today {
      background-color: #fdffa4 !important;
      color: #000;
    }

    .fc-day-number {
      position: relative;
    }

    .remaining-slots {
        position: absolute;
        bottom: 5px;
        right: 5px;
        font-size: 12px;
        color: #fff;
        background-color: rgba(0, 0, 0, 0.5);
        padding: 2px 4px;
        border-radius: 3px;
    }
  </style>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const calendarEl = document.getElementById('calendar')

      const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        dayCellDidMount: function(info) {
          var today = new Date();
          var cellDate = new Date(info.date);
          var formattedDate = cellDate.toISOString().split('T')[0];
          var isToday = (cellDate.toDateString() === today.toDateString()); // Check if it's today
          var remainingSlots = window.remainingSlots[formattedDate];

          if (remainingSlots === undefined) {
            var dayOfWeek = cellDate.getDate() - 1;
            remainingSlots = (dayOfWeek >= 1 && dayOfWeek <= 5) ? 5 : 0; // 5 for Mon-Fri, 0 for weekends
          }
          if (cellDate < today) {
            info.el.classList.add('fc-day-past'); // Style past dates
          } else if (isToday) {
            info.el.classList.add('fc-day-today'); // Style today's date
          } else {
            info.el.classList.add('fc-day-future'); // Style future dates
          }
        },
        dateClick: function(info) {
          var today = new Date();
          var clickedDate = new Date(info.dateStr);

          var eventCountForDate = window.eventCount[info.dateStr] || 0;
          var remainingCount = window.remainingSlots[info.dateStr] || 0;

          if(eventCountForDate === 5){
            Swal.fire({
              icon: 'warning',
              title: 'No Slots Available',
              text: 'There are no slots available for this date. Try different date. [No Slot]',
              confirmButtonText: 'Okay'
            });

            return;
          }

          if (clickedDate >= today) {
            $("input[name='request_date']").val(info.dateStr)
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Oops!',
              text: 'This date is not available for booking. [Past Date]',
              confirmButtonText: 'Okay'
            });
          }
        }
      })

      fetch('api/get_all_requested_counts.php')
        .then(response => response.json())
        .then(data => {
          window.eventCount = data.eventCount;
          window.remainingSlots = data.remainingSlots;
          calendar.render();
        });
    })

  </script>

  <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">CONFIRM DETAILS</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="d-flex flex-column gap-2" id="confirmation-body">
            
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" id="submit-request-btn">Submit Request</button>
        </div>
      </div>
    </div>
  </div>

  <main class="container-fluid d-flex flex-lg-row flex-column p-0">
    <?php include("reusables/client-sidebar.php"); ?>
    
    <section class="col-12 col-lg-10 p-2 p-lg-4">
      <form id="requestForm">
        <div style="display: flex; flex-direction: row; justify-content: space-between; align-items:center;">
          <h4 class="fw-bold">REQUEST FORM</h4>
          <button class="btn btn-success" type="submit">Submit Request</button>
        </div>
        <div class="row">
          <div class="col-12 col-lg-7">
            <h4 class="fw-bold mb-2 mb-lg-4">Choose Date</h4>
            <div id="calendar">

            </div>
          </div>
          <div class="col-12 col-lg-5">
            <h4 class="fw-bold mb-4 mt-4 mt-lg-0">Request Details</h4>
            <div class="form-group mb-2">
              <label class="form-label fw-semibold">Select Date From Calendar</label>
              <input type="hidden" name="request_date">
              <input type="date" class="form-control" name="request_date" disabled required>
            </div>
            <div class="form-group mb-2">
              <label class="form-label fw-semibold">Program</label>
              <select class="form-select" name="program">
                <option value="BSIS">BSIS</option>
                <option value="BSOM">BSOM</option>
                <option value="BSAIS">BSAIS</option>
                <option value="BTVTEd">BTVTEd</option>
                <option value="ACT">ACT</option>
              </select>
            </div>
            <div class="form-group mb-2">
              <label class="form-label fw-semibold">Year Graduated</label>
              <select class="form-select" name="year_graduated">
                <option value="2015">2015</option>
                <option value="2016">2016</option>
                <option value="2017">2017</option>
                <option value="2018">2018</option>
                <option value="2019">2019</option>
                <option value="2020">2020</option>
                <option value="2021">2021</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
                <option value="2024">2024</option>
              </select>
            </div>
            <div class="form-group mb-2">
              <label class="form-label fw-semibold">Document Type</label>
                <?php
                  include("api/connection.php");

                  $document = $conn -> query("SELECT * FROM supported_documents");

                  while($row = $document -> fetch_assoc()){
                    echo "
                      <div class='form-check'>
                        <input class='form-check-input' name='document_type[]' type='checkbox' value='".$row['name']."' />
                        <label class='form-check-label'>".$row['name']."</label>
                      </div>
                    ";
                  }

                  $conn -> close();
                ?>

            </div>
            <div class="form-group mb-2">
              <label class="form-label fw-semibold">Academic Year</label>
              <select class="form-select" aria-label="Default select example" name="academic_year">
                <option value="2015">2015</option>
                <option value="2016">2016</option>
                <option value="2017">2017</option>
                <option value="2018">2018</option>
                <option value="2019">2019</option>
                <option value="2020">2020</option>
                <option value="2021">2021</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
                <option value="2024">2024</option>
              </select>
            </div>
            <div class="form-group mb-2">
              <label class="form-label fw-semibold">Purpose</label>
              <input type="text" class="form-control" placeholder="For employment" name="purpose" required>
            </div>
            
          </div>
        </div>
      </form>
    </section>
  </main>
  <script>
    $("#submit-request-btn").on("click", function(){

      var formData = new FormData($("#requestForm")[0]);
      $.ajax({
        url: 'api/post_request.php',
        type: 'post',
        data: formData,
        contentType: false,
        processData: false,
        success : response => {
          let json = JSON.parse(response);

          if(json.status === "success"){
            Swal.fire({
              title: json.message,
              text: json.description,
              icon: json.status,
              showCancelButton: false,
              confirmButtonText: "View My Request"
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

    $("#requestForm").on("submit", function(event){
      event.preventDefault();

      $("#confirmationModal").modal("toggle")

      var formData = new FormData(this);

      $.ajax({
        url: 'api/post_confirm_request.php',
        type: 'post',
        data: formData,
        contentType: false,
        processData: false,
        success : response => {
          $("#confirmation-body").html(response)
          
        }
      });
    })
  </script>
</body>
</html>