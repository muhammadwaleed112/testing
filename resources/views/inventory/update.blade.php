@include('attachments.header')
@include('attachments.sidebar')

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center">
            <h1 class="h3 mb-0 text-gray-800 me-2">{{$title ?? 'Bill'}}</h1>
            <span class="text-gray-800" style="font-size: 1.2em;">|</span>
            <i class="fa-solid fa-house text-primary fs-6 ms-3"></i> 
            <i class="fa-solid fa-greater-than ms-3 me-3"></i>
            <h4 class="ms-0 mb-0">Bills</h4>
        </div>
        <a href="/bill" class="d-none d-sm-inline-block btn btn-lg py-1 px-4 btn-primary shadow-sm">
                 Back
        </a>
    </div>
 
    {{-- @foreach ($products as $item)
    <span> {{$item->bill_items->qty}}</span>
    {{-- @foreach ($item->bill_items as $bill)
    <span> {{$bill->qty}}</span>
    @endforeach --}}
    {{-- @endforeach --}} 
        

    {{-- //search by --}}
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form id="suppliers" class="user">
                        <label for="">Add Supplier Detail</label>
                        <input class="form-control   mb-3" type="text" name="supplier" value="{{$bill->supplier}}" required>
                        <input class="form-control   mb-3" type="date" name="date" value="{{$bill->date}}" required>
                        <table id="productsTable" class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="select-container">
                               
                                <tr>
                                    <td width="20%">
                                        <select name="products[0][product_id]" class="form-control mb-3 product-select" required>
                                         @foreach ($allProducts as $product)
                                            <option value="{{ $product->id }}" {{ $product->id == $bill->billItems[0]->product_id ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                          @endforeach

                                        </select>
                                    </td>

                                    <td> 
                                        <input type="text" name="products[0][price]" value="{{$bill->billItems[0]->price}}"  class="form-control   mb-3 price" placeholder="Enter Price" required>
                                    </td>
                                    
                                    <td>
                                            <input type="hidden">
                                        <input type="number" name="products[0][qty]"  value="{{$bill->billItems[0]->price}}" class="form-control   mb-3 quantity" placeholder="Enter Qty" required>
                                    
                                    
                                    </td>
                                    <td>
                                        <input type="text" class="form-control mb-3 total" value="{{$bill->billItems[0]->price}}"    placeholder="Total" readonly>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <i class="fa-solid fa-plus fs-3 mb-3 color-red" onclick="addMore()"></i>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3"><strong>Total Amount All:</strong></td>
                                    <td><input type="text" id="totalAmountAll" value="{{$bill->total}}" class="form-control   mb-3" readonly></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                        <div class="container d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-5 ">Bill Create</button>
                        </div>
                    </form>
                </div> 
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@include('attachments.footer')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
 <script>
    
    var counter = 1; // Initialize counter

function calculateRowTotal(row) {
    var price = parseFloat(row.find('.price').val()) || 0;
    var quantity = parseInt(row.find('.quantity').val()) || 0;
    var total = price * quantity;
    row.find('.total').val(total.toFixed(2));
}

function calculateTotal() {
    $('.price, .quantity').off('input').on('input', function() {
        var row = $(this).closest('tr');
        calculateRowTotal(row);
        calculateTotalAmountAll();
    });
}

function calculateTotalAmountAll() {
    var totalAmountAll = 0;
    $('.total').each(function() {
        totalAmountAll += parseFloat($(this).val()) || 0;
    });
    $('#totalAmountAll').val(totalAmountAll.toFixed(2));
}

function addMore() {
    counter++; // Increment counter for unique IDs
    let html = `
        <tr>                
            <td width="20%">
                <select name="products[${counter}][product_id]" class="form-control mb-3 product-select" required>
                    <option hidden disabled selected>Select Any Product</option>
                    @foreach ($allProducts as $product)
                    <option value="{{ $product->id }}" {{ $product->id == $bill->billItems[ 0]->product_id ? 'selected' : '' }}>
                        {{ $product->name }}
                    </option>   
                    @endforeach
                </select>
            </td>
            <td>
                <input type="text" name="products[${counter}][price]"  value="${bill.billItems[0]->price}" class="form-control   mb-3 price" placeholder="Enter Price" required>
            </td>
            <td>
                <input type="number" name="products[${counter}][qty]" value="${bill.billItems[0]->qty}" class="form-control   mb-3 quantity" placeholder="Enter Qty" required>
            </td>
            <td>
                <input type="text" class="form-control   mb-3 total" placeholder="Total" value="${bill.total}" readonly>
            </td>
            <td>
                <div class="d-flex">
                    <i class="fa-solid fa-minus fs-3 mb-3 color-red" onclick="removeRow(this)"></i>
                </div>
            </td>
        </tr>`;
    $('#productsTable tbody').append(html);
    calculateTotal();
}

function removeRow(element) {
    var row = $(element).closest('tr');

    // Get price and quantity values from the row
    var price = parseFloat(row.find('.price').val()) || 0;
    var quantity = parseInt(row.find('.quantity').val()) || 0;

    // Subtract price * quantity from total amount
    var totalAmountAll = parseFloat($('#totalAmountAll').val()) || 0;
    totalAmountAll -= price * quantity;

    // Update total amount in the input field
    $('#totalAmountAll').val(totalAmountAll.toFixed(2));

    // Remove the row
    row.remove();
    calculateTotalAmountAll();
}

$(document).ready(function() {
    calculateTotal();
});

</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#suppliers").submit(function(event) {
        
        event.preventDefault();
        var formData = new FormData($("#suppliers")[0]);
        
        $.ajax({
            url: '/updatedata',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                
                Swal.fire({
                title: "Successfully!",
                text: "Bill Updated Successfully!",
                icon: "success"
                });
                $("#suppliers").trigger('reset');
                window.location.href = 'bill';
            },
            error: function(xhr, status, error) {
                alert('Variants Not Added!');
                Swal.fire({
                title: "Error Occur!",
                text: "Bill Updated Successfully!",
                icon: "error"
                });
            }
        });
    });
    // $(document).ready(function() {
    //         $('.product-select').change(function() {
    //             var productId = $(this).val(); // Select ka value lo
    //             var $inputField = $(this).closest('tr').find('td#unit input'); // Input field find karo

    //             // AJAX request bhejo
    //             $.ajax({
    //                 url: '/product/' + productId,
    //                 method: 'GET',
    //                 success: function(response) {
    //                     $inputField.val(response.name); // Input field mein name set karo
    //                 },
    //                 error: function() {
    //                     $inputField.val('Error fetching name');
    //                 }
    //             });
    //         });
    //     });
        
</script>
</div>

