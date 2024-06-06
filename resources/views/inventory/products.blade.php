@include('attachments.header')
@include('attachments.sidebar')
<div class="container-fluid">
  
  
  {{-- modal edit  --}}
 <!-- Button trigger modal -->

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h1 class="modal-title fs-5" id="staticBackdropLabel">Category Update</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <div class="row text-dark">
                  <form id="updateForm">
                      <input type="hidden" id="productId">
                      <div class="col-lg-12 text-dark">
                          <label for="productName">Name: <span class="text-danger">*</span></label>
                          <input class="form-control" type="text" id="productName" name="name">
                      </div>
                      <div class="col-lg-12 mt-2 text-dark">
                          <label for="productMeasurement">Measurement: <span class="text-danger">*</span></label>
                          <input class="form-control" type="text" id="productMeasurement" name="measurement">
                      </div>
                  </form>
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" form="updateForm" class="btn btn-primary">Save Changes</button>
          </div>
      </div>
  </div>
</div>
  {{-- cloase edit modal --}}
    <!-- Page Heading -->
    <div class="text-info text-center d-none" id="whenupdate">One Row Updated Successfully...</div>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center justify-content-between">
            <h1 class="h3 mb-0 text-gray-800">Inventory <span class="ms-2"> | </span> </h1>
            <i class="fa-solid fa-house text-primary fs-6 ms-3"></i> 
            <i class="fa-solid fa-greater-than ms-3 me-3"></i> <h4> Inventory </h4>
        </div>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
        </div>
    <div class="mb-4" style="background: rgb(246, 246, 246);  box-shadow: 5px 5px 10px 10px rgb(236, 236, 236);">
        <form class="p-3" id="product">
          <div class="row text-dark">
                <h3 class="text-dark"> Add Inventory </h3>
              <div class="col-lg-6">
                <label for="first Name"> Product Name <span class="text-danger">*</span> </label>
                <input type="text" class="form-control" name="name" placeholder="Producst Name">
              </div>
              <div class="col-lg-6">
                <label for="first Name"> Measurment kg/L <span class="text-danger">*</span> </label>
                <input type="text" class="form-control" name="measurement" placeholder="Enter unit of Measurment kg/ltr">
              </div>
            </div>
              <div class="col-lg-6 d-flex justify-content-start mt-3" >
                <input type="submit" class="btn btn-primary" value="Add">
              </div>
            </form>   
        </div>
        <div class="mb-4 p-3" style="background: rgb(246, 246, 246);  box-shadow: 5px 5px 10px 10px rgb(236, 236, 236);">
            <table class="table table-bordered">
                <thead>
                  <tr>
                    <th scope="col">S#</th>
                    <th scope="col">Product Name</th>
                    <th scope="col">Measurment</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody id="display">
                </tbody>
              </table>
        </div>
    </div>
    
</div>
@include('attachments.footer')
<script>
  $("#product").submit(function(event){
        event.preventDefault();
        // alert("waleed");
        $.ajax({
            url: "/addProdcut",
            type: "post",   
            data: $("#product").serialize(),
            success:function (response){
                 getdata()
            if (response.status == true) {
                Swal.fire({
                icon: "success",
                title: "Success",
                html: `<span class="text-primary"> ${response.message} </span>`,
            });
            $('#product').trigger('reset');

            } else {
                const errors = response.errors;
                const errorList = errors.map(error => `<li>${error}</li>`).join('');       
            Swal.fire({
                icon: "error",
                title: "Error!",
                html: `<ul style="list-style:none;color:red;">${errorList}</ul>`,
            });
            }
            },error:function(xhr){
                if (xhr.status == 409) {                    
                Swal.fire({
                icon: "error",
                title: "Error!",
                html: `<ul style="list-style:none;color:red;">${xhr.responseJSON.message}</ul>`,
            });
            }
        }
        });
    });
    getdata()
    function getdata(){
       $.ajax({
        url:'/getdata',
        type: "get",
        success:function(response){
            html = "";
            response.data.forEach(element => {
              html += `
<tr>
    <th scope="row">${element.id}</th>
    <td>${element.name}</td>
    <td>${element.measurment}</td>
    <td>
        <button  type="button" class="btn" onclick="edit(${element.id})" data-bs-toggle="modal" data-bs-target="#staticBackdrop" style="background:#ffc107; padding:7px 10px; border-radius: 5px; margin-right:10px">
            <i class="fa-regular fa-pen-to-square text-dark"></i>
        </button>
        

        <button class="btn" onclick="deleteour(${element.id})" style="background:#f02769; padding:7px 10px; border-radius: 5px; margin-right:10px">
            <i class="fa-solid fa-trash text-light"></i>
        </button>

        ${element.status == 0 ?
            `<button class="btn btn-info" onclick="activateItem(${element.id})" style="padding:7px 10px; border-radius: 5px; margin-right:10px">
                Active
            </button>` :
            `<button class="btn btn-danger" onclick="deactivateItem(${element.id})" style="padding:7px 10px; border-radius:5px; margin-right:10px">
                Inactive
            </button>`}
    </td>
</tr>`;
               $("#display").html(html);
            });
        }
       });
    }
</script>

<script>
function deleteItem(id) {
    deleteAlert().then((result) => {
        if (result.isConfirmed) {
            // Send an AJAX request to delete the item
            $.ajax({
                url: '/status', 
                type: 'POST',
                data: {
                    id: id 
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire(
                        'Deleted!',
                        'move To Trash.',
                        'success'
                    );
                },
                error: function(xhr) {
                    Swal.fire(
                        'Error!',
                        'Failed to delete item.',
                        'error'
                    );
                }
            });
        }
    });
}
function deleteour(id) {
    deleteAlert().then((result) => {
        if (result.isConfirmed) {
            // Send an AJAX request to delete the item
            $.ajax({
                url: '/invdelete', 
                type: 'POST',
                data: {id: id },
                success: function(response) {                    
                    Swal.fire(
                        'Deleted!',
                        'move To Trash.',
                        'success'
                    ).then(function() {
                         location.reload();
                       });
                },
                error: function(xhr) {
                    Swal.fire(
                        'Error!',
                        'Failed to delete item.',
                        'error'
                    );
                }
            });
        }
    });
}
function edit(id) {
    $.ajax({
        type: 'get',
        url: '/edit',
        data: {
            id: id},
        success: function(response) {
            // Populate the modal fields with the response data
            $('#productId').val(response.id);
            $('#productName').val(response.name);
            $('#productMeasurement').val(response.measurment);
            // Show the modal
                $('#staticBackdrop').modal('show');
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}
$('#updateForm').submit(function(event) {
    event.preventDefault();

    $.ajax({
        type: 'POST',
        url: 'invupdate',
        data: {
            id: $('#productId').val(),
            name: $('#productName').val(),
            measurement: $('#productMeasurement').val(),
            },
        success: function(response) {
            if (response.status == true) {
              showAlert('success', 'Success', 'Producte Update Successfully...!');
                $('#staticBackdrop').modal('hide');
                $('#whenupdate').removeClass('d-none').addClass('d-block');
              // Hide the message after a few seconds
              setTimeout(function() {
                  $('#whenupdate').removeClass('d-block').addClass('d-none');
              }, 3000);
                getdata();
              }
              else{
                showAlert('error', 'Error', response.message,);
              }
        },
        error: function(xhr, status, error) {
            alert("this is error")
            console.error('Error:', error);
        }
    });
});
</script>