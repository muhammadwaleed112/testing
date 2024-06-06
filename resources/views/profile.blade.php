@include('attachments.header')
@include('attachments.sidebar')
<div class="container-fluid">
     <!-- Page Heading -->
     <div class="d-sm-flex align-items-center justify-content-between mb-4">
         <div class="d-flex align-items-center justify-content-between">
             <h1 class="h3 mb-0 text-gray-800">Admin Profile <span class="ms-2"> | </span> </h1>
             <i class="fa-solid fa-house text-primary fs-6 ms-3"></i> 
             <i class="fa-solid fa-greater-than ms-3 me-3"></i> <h4>Profile Setting </h4>
         </div>
    </div>
<div>
    <div class="mb-4" style="background: rgb(246, 246, 246);  box-shadow: 5px 5px 10px 10px rgb(236, 236, 236);">
        <form class="p-3" id="profile" enctype="multipart/form-data">
          <div class="row text-dark">
                <h3 class="text-dark">Update Profile </h3>
              <div class="col-lg-12">
                <label for="first Name"> Name <span class="text-danger">*</span> </label>
                <input type="text" class="form-control" name="name" value={{Auth::user()->name}}>
              </div>
              <div class="col-lg-6">
                <label for="first Name"> Email <span class="text-danger">*</span> </label>
                <input type="email" class="form-control" name="email" value={{Auth::user()->email}} @readonly(true)>
              </div>
              <div class="col-lg-6">
                <label for="first Name"> Profile Image <span class="text-danger">*</span> </label>
                <input type="file" class="form-control" name="profile_image">
              </div>
            </div>
              <div class="col-lg-6 d-flex justify-content-start mt-3" >
                <input type="submit" class="btn btn-primary" value="Update">
              </div>
            </form>   
        </div>
        <hr>
        <div class="mb-4" style="background: rgb(246, 246, 246);  box-shadow: 5px 5px 10px 10px rgb(236, 236, 236);">
            <form class="p-3" id="updated_password">
              <div class="row text-dark">
                    <h3 class="text-dark">Change Password </h3>
                  <div class="col-lg-12">
                    <label for="first Name"> Password: <span class="text-danger">*</span> </label>
                    <input type="password" class="form-control" name="old_password" placeholder="Enter your Current password">
                  </div>
                  <div class="col-lg-12">
                    <label for="first Name"> New Password: <span class="text-danger">*</span> </label>
                    <input type="password" value="" class="form-control" name="password" placeholder="Enter New Password at least 8 charcters long">
                  </div>
                  <div class="col-lg-12">
                  
                    <label for="first Name"> Confirm Password: <span class="text-danger">*</span> </label>
                    <input type="password" value="" class="form-control" name="password_confirmation" placeholder="Enter Confirm Password at least 8 charcters long">
                  </div>
                </div>
                  <div class="col-lg-6 d-flex justify-content-start mt-3" >
                    <input type="submit" class="btn btn-primary" value="Update">
                  </div>
                </form>   
                
            </div>
</div>
    
</div>
</div>
@include('attachments.footer')
<script>
    $("#profile").submit(function(event){
        event.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type:'post',
            url:"profile_update",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
              if (response.status == true) {
                Swal.fire({
                icon: "success",
                title: "Success",
                html: `<span class="text-primary"> ${response.message} </span>`,
            });
              }
            },
            error:function(){
                alert("Error Occur");
            }
        });
    });
    $("#updated_password").submit(function(event) {
    event.preventDefault();
    $.ajax({
        type: 'POST',
        url: 'updatePassword',
        data: $("#updated_password").serialize(),
        success: function(response) {
            if (response.status === true) {
                Swal.fire({
                    icon: "success",
                    title: "Success",
                    html: `<span class="text-primary">${response.message}</span>`,
                });
                $("#updated_password")[0].reset();
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    html: `<span class="text-primary">${response.message}</span>`,
                });
            }
        },
        error: function() {
            alert("Error updating password");
        }
    });
});

  </script>