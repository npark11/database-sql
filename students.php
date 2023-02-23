<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Hello, world!</title>
  </head>

  <body>
    <!-- Modal ADD Student -->
    <div class="modal fade" id="studentAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Student</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="saveStudent">
            <div class="modal-body">
              <div id="errorMessage" class="alert alert-warning d-none"></div>

              <div class="mb-3">
                <label for="">Name</label>
                <input type="text" name="name" class="form-control">
              </div>
              <div class="mb-3">
                <label for="">Email</label>
                <input type="text" name="email" class="form-control">
              </div>
              <div class="mb-3">
                <label for="">Phone</label>
                <input type="text" name="phone" class="form-control">
              </div>
              <div class="mb-3">
                <label for="">Course</label>
                <input type="text" name="course" class="form-control">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary"       data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save Student</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Modal EDIT Student -->
    <div class="modal fade" id="studentEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit Student</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="updateStudent">
            <div class="modal-body">
              
              <div id="errorMessageUpdate" class="alert alert-warning d-none"></div>

              <input type="hidden" name="student_id" id="student_id">
               
              <div class="mb-3">
                <label for="">Name</label>
                <input type="text" name="name" id="name"  class="form-control">
              </div>
              <div class="mb-3">
                <label for="">Email</label>
                <input type="text" name="email" id="email" class="form-control">
              </div>
              <div class="mb-3">
                <label for="">Phone</label>
                <input type="text" name="phone" id="phone" class="form-control">
              </div>
              <div class="mb-3">
                <label for="">Course</label>
                <input type="text" name="course" id="course" class="form-control">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary"       data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Update Student</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h4>PHP Ajax CRUD without page reload using Bootstrap Modal
                <!-- Button trigger modal -->
                <button 
                  type="button" 
                  class="btn btn-primary float-end" 
                  data-bs-toggle="modal" 
                  data-bs-target="#studentAddModal">
                    Add Student
                </button>
              </h4>
            </div>
            <div class="card-body">
              <table id="studentTable" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Course</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  require 'dbcon.php';

                  $query = "SELECT * FROM students";
                  $query_run = mysqli_query($con, $query);

                  if (mysqli_num_rows($query_run) > 0) {
                    foreach($query_run as $student) {
                      ?>
                      <tr>
                        <td><?= $student['id']; ?></td>
                        <td><?= $student['name']; ?></td>
                        <td><?= $student['email']; ?></td>
                        <td><?= $student['phone']; ?></td>
                        <td><?= $student['course']; ?></td>
                        <td>
                          <a href="" class="btn btn-info">View</a>
                          <button 
                            type="button" 
                            value="<?= $student['id']; ?>"  
                            class="editStudentBtn btn btn-success">
                              Edit
                          </button>
                          <a href="" class="btn btn-danger">Delete</a>
                        </td>
                      </tr>
                      <?php
                    }
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      // Add a Student's info
      $(document).on('submit', '#saveStudent', function (e) {
        e.preventDefault();

        var formData = new FormData(this);
        formData.append("save_student", true);

        $.ajax({
          type: "POST",
          url: "code.php",
          data: formData,
          processData: false,
          contentType: false,
          success: function (response) {
            var res = jQuery.parseJSON(response);
            if (res.status == 422) {
              $('#errorMessage').removeClass('d-none');
              $('#errorMessage').text(res.message);

            } else if (res.status == 200) {
              $('#errorMessage').addClass('d-none');
              $('#studentAddModal').modal('hide');
              $('#saveStudent')[0].reset();

              $('#studentTable').load(location.href + " #studentTable");
            }
          }
        });
      });
      
      // Get info a Students
      $(document).on('click', '.editStudentBtn', function () {
        var student_id = $(this).val();
        // alert(student_id);
        $.ajax({
          type: "GET",
          url: "code.php?student_id=" + student_id,
          success: function (response) {
            var res = jQuery.parseJSON(response);
            if (res.status == 422) {
              alert(res.message);

            } else if (res.status == 200) {
              $('#student_id').val(res.data.id);
              $('#name').val(res.data.name);
              $('#email').val(res.data.email);
              $('#phone').val(res.data.phone);
              $('#course').val(res.data.course);

              $('#studentEditModal').modal('show');
            }
          }
        });

      });

      // Update
      $(document).on('submit', '#updateStudent', function (e) {
        e.preventDefault();

        var formData = new FormData(this);
        formData.append("update_student", true);

        $.ajax({
          type: "POST",
          url: "code.php",
          data: formData,
          processData: false,
          contentType: false,
          success: function (response) {
            var res = jQuery.parseJSON(response);
            if (res.status == 422) {
              $('#errorMessageUpdate').removeClass('d-none');
              $('#errorMessageUpdate').text(res.message);

            } else if (res.status == 200) {
              $('#errorMessageUpdate').addClass('d-none');

              $('#studentEditModal').modal('hide');
              $('#updateStudent')[0].reset();

              $('#studentTable').load(location.href + " #studentTable");
            }
          }
        });
      });

    </script>

  </body>
</html>