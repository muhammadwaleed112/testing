@include('attachments.header')
@include('attachments.sidebar')



<div class="container-fluid">
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Product Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div id="edit" class="modal-body">
          <div id="loading-spinner" class="d-none">
            <p>Loading...</p>
          </div>
          <table class="table d-none" id="product-details-table">
            <thead class="thead-dark">
              <tr>
                <th scope="col">Id</th>
                <th scope="col">Product</th>
                <th scope="col">Unit</th>
                <th scope="col">Price</th>
                <th scope="col">Quantity</th>
                <th scope="col">Total</th>
              </tr>
            </thead>
            <tbody id="product-details">
              <!-- Details will be dynamically inserted here -->
            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div class="d-flex align-items-center">
      <h1 class="h3 mb-0 text-gray-800 me-2">{{$title ?? 'Bill'}}</h1>
      <span class="text-gray-800" style="font-size: 1.2em;">|</span>
      <i class="fa-solid fa-house text-primary fs-6 ms-3"></i>
      <i class="fa-solid fa-greater-than ms-3 me-3"></i>
      <h4 class="ms-0 mb-0">Bills</h4>
    </div>
    <a href="/create" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-lg">
      Add Bill
    </a>
  </div>
  {{-- //search by --}}
  <div class="mb-4" style="background: rgb(246, 246, 246);  box-shadow: 5px 5px 10px 10px rgb(236, 236, 236);">
    <form class="p-3" id="product">
      <div class="row text-dark">
          <div class="col-lg-6">
              <label for="search-by">Search By</label>
              <select name="searchBy" id="dates" class="form-control">
                  <option hidden >Choose</option>
                  <option value="date">Date</option>
                  <option value="created_at">Created at</option>
              </select>
          </div>
          <div class="col-lg-6">
              <label for="date">Date</label>
              <input type="date" class="form-control" name="date" id="dateInput">
          </div>
      </div>
      <div class="col-lg-6 d-flex justify-content-start mt-3">
          <input type="submit" class="btn btn-primary" value="Search">
      </div>
  </form>
  </div>

  <div class="mb-4 p-3" style="background: rgb(246, 246, 246);  box-shadow: 5px 5px 10px 10px rgb(236, 236, 236);">
    <h4 style="color:black"> Bills</h4>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th scope="col">S#</th>
          <th scope="col">Supplier</th>
          <th scope="col">Date</th>
          <th scope="col">Total</th>
          <th scope="col">Created_at</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody id="searchData">
        @foreach ($details as $item)
        <tr>
          <td scope="row"> {{$loop->iteration}} </td>
          <td> {{$item->supplier}} </td>
          <td> {{$item->date}} </td>
          <td> {{$item->total}} </td>
          <td> {{$item->created_at}} </td>
          <td>

            <button type="button" style="background:#198fed; padding:7px 10px" ;
              class="btn text-light me-2 view-detail-btn" data-id="{{ $item->id }}" data-bs-toggle="modal"
              data-bs-target="#exampleModal">
              <i class="fa-solid fa-eye"></i>
            </button>


            <a href="/editnow/{{$item->id}}/page" class="btn"
              style="background:#ffc107; padding:7px 10px; border-radius: 5px; margin-right:10px">
              <i class="fa-regular fa-pen-to-square text-dark"></i>
            </a>

            <button class="btn" onclick="deleteour({{$item->id}})"
              style="background:#f02769; padding:7px 10px; border-radius: 5px; margin-right:10px">
              <i class="fa-solid fa-trash text-light"></i>
            </button>

          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

</div>
</div>
@include('attachments.footer')
<script>
  function deleteour(id) {
    deleteAlert().then((result) => {
        if (result.isConfirmed) {
            // Send an AJAX request to delete the item
            $.ajax({
                url: '/deleted', 
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
$(document).ready(function() {
        $('.view-detail-btn').on('click', function() {
			$('#loading-spinner').removeClass('d-none');
            $('#product-details-table').addClass('d-none');

            var billId = $(this).data('id');
            $.ajax({
                url: '/bill/' + billId + '/details',
                method: 'GET',
                success: function(pos) {
                    $('#product-details').empty(); 
				    	pos.bill_items.forEach(function(billItem) {
       					 var product = billItem.product;
						var row = `
						<tr>
							<td>${product.id}</td> 
							<td>${product.name}</td>
							<td>${product.measurment}</td>
							<td>${billItem.price}</td>
							<td>${billItem.qty}</td>
							<td>${pos.total}</td>
						</tr>
                 `;
               $('#product-details').append(row);
                    });
						$('#loading-spinner').addClass('d-none');
                 	   $('#product-details-table').removeClass('d-none');
                },
                error: function(error) {
                    console.error('Error fetching product details:', error);
                }
            });
        });
    });
// $(document).ready(function(){

//   $('#dates').change(function() {
//     var dates = $(this).val(); 
//     alert(dates);
    
//   });
//     });


$(document).ready(function() {
    $('#product').submit(function(event) {
        event.preventDefault(); // Prevent default form submission

        var searchBy = $('#dates').val();
        var date = $('#dateInput').val();

        $.ajax({
            url: '/search', // Update with your actual URL endpoint
            type: 'POST',
            data: {
                searchBy: searchBy,
                date: date
            },
            success: function(response) {
                if (response.status === true) {
                    if (response.data.length > 0) {
                        var html = "";
                        $.each(response.data, function(index, element) {
                            html += `
                                <tr>
                                    <td scope="row">${element.id}</td>
                                    <td>${element.supplier}</td>
                                    <td>${element.date}</td>
                                    <td>${element.total}</td>
                                    <td>${element.created_at}</td>
                                    <td>
                                      <button class btn btn-info> Edit </button>  
                                      <button class btn btn-danger> delete </button>  
                                    </td>

                                </tr>`;
                        });
                        $("#searchData").html(html); // Populate table body with HTML
                    } else {
                        $("#searchData").html("No data found"); // Display message when no data is found
                    }
                  }
                  else {
                  const errors = response.errors;
                  const errorList = errors.map(error => `<li>${error}</li>`).join('');       
              Swal.fire({
                  icon: "error",
                  title: "Error!",
                  html: `<ul style="list-style:none;color:red;">${errorList}</ul>`,
              });
                }
            },
            error: function(xhr, status, error) {
                // Handle AJAX errors
                alert("Error Occurred");
                console.error("AJAX error:", status, error);
                console.error("Response:", xhr.responseText);
            }
        });
    });
});



</script>