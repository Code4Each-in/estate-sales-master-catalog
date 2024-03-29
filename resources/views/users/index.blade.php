@extends('layouts.app')
@section('title', 'Users')
@section('sub-title', 'Users')
@section('content')
<section class="section dashboard">
    <div class="row">
        <!-- Left side columns -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <button class="btn btn-default my-3" onClick="openusersModal()" href="javascript:void(0)">Add User</button>
                    <!-- <h5 class="card-title">Table with stripped rows</h5> -->
                    <form id="filter-data-form" method="GET" action="{{ route('users.index') }}">
                        <div class="row mt-3 mx-auto">
                            <div class="col-md-4 filtersContainer d-flex p-0">
                                <div style="margin-right:20px;">
                                    <input type="checkbox" class="form-check-input" name="all_users" id="all_users"
                                    {{ $allUsersFilter == 'on' ? 'checked' : '' }}  > 
                                        <label for="all_users">All</label>
                                </div>
                            </div>
                            <div class="col-md-8 form-group1">
                                    <div class="main-input">
                                        <label for="roleFilterselectBox">Role:</label>
                                   
                                        <select class="form-control" id="roleFilterselectBox" name="role_filter">
                                            <option value="" selected >Select Role</option>
                                            @foreach ( $roles as $role)
                                            <option value="{{$role->id}}" {{ request()->input('role_filter') == $role->id ? 'selected' : '' }} >{{$role->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if ($errors->has('role_filter'))
                                        <span style="font-size: 10px;" class="text-danger">{{ $errors->first('role_filter') }}</span>
                                    @endif
                            </div>
                        </div>
                    </form>
                    <br/>

                    <!-- Table with stripped rows -->
                    <div class="box-header with-border" id="filter-box">
                        <div class="box-body table-responsive" style="margin-bottom: 5%">
                            <table class="datatable table table-striped my-2" id="users_table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">Role</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $index => $data)
                                    <tr>
                                        <th scope="row">{{ $index + 1 }}</th>
                                        <td>{{ucfirst($data->first_name) ?? ''}} {{ucfirst($data->last_name) ?? ''}}</td>
                                        <td>{{$data->email ?? ''}}</td>
                                        <td>{{$data->phone}}</td>
                                        <td>{{$data->role->name ?? ''}}</td>
                                        <td>
                                             @if($data->status == 'inactive')
                                            <span class="badge rounded-pill bg-danger">{{ucfirst($data->status)}}</span>
                                            @else
                                            <span class="badge rounded-pill  bg-success">{{ucfirst($data->status)}}</span>
                                            @endif
                                        </td>
                                        <td> 
                                            <i onClick="editUsers('{{ $data->id }}')" href="javascript:void(0)" class="fa fa-edit fa-fw pointer btn-fa-catalog"></i>

                                            <i onClick="deleteUsers('{{ $data->id }}')" href="javascript:void(0)" class="fa fa-trash fa-fw pointer btn-fa-catalog"></i></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- End Table with stripped rows -->


                    <!--start: Add users Modal -->
                    <div class="modal fade" id="addUsers" tabindex="-1" aria-labelledby="role" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content" style="width:505px;">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="role">Add User</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form id="addUsersForm">
                                    @csrf
                                    <div class=" modal-body">
                                        <div class="alert alert-danger" style="display:none"></div>
                                        <div class="row mb-3 mt-4">
                                            <label for="first_name" class="col-sm-3 col-form-label required">First Name</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="first_name" id="first_name">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="last_name" class="col-sm-3 col-form-label required">Last Name </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="last_name" id="last_name">
                                            </div>
                                        </div>
                                         <div class="row mb-3">
                                            <label for="email" class="col-sm-3 col-form-label required">Email</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="email" id="email">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="phone" class="col-sm-3 col-form-label required">Phone</label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control" name="phone" id="phone">
                                            </div>
                                        </div>  
                                        <div class="row mb-3">
                                            <label for="role" class="col-sm-3 col-form-label required">Role</label>
                                            <div class="col-sm-9">
                                                <select name="role" class="form-select" id="role">
                                                <option value="">Select User Role</option>
                                                    @foreach ($roles as $data)
                                                    <option value="{{$data->id}}">
                                                        {{$data->name}}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                </select>
                                            </div>
                                        </div> 
                                        <div class="row mb-3">
                                            <label for="password" class="col-sm-3 col-form-label required">Password</label>
                                            <div class="col-sm-9">
                                                <input type="password" class="form-control" name="password" id="password">
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <label for="password_confirmation" class="col-sm-3 col-form-label required"> Confirm
                                                Password</label>
                                            <div class="col-sm-9">
                                                <input type="password" class="form-control mb-6" name="password_confirmation" id="password_confirmation">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-default">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end: Add User Modal -->



                 <!--start: Edit users Modal -->
                 <div class="modal fade" id="editUsers" tabindex="-1" aria-labelledby="role" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content" style="width:505px;">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="role">Edit User</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form id="editUsersForm">
                                    @csrf
                                    <div class=" modal-body">
                                        <div class="alert alert-danger" style="display:none"></div>
                                        <div class="row mb-3 mt-4">
                                            <label for="edit_first_name" class="col-sm-3 col-form-label required">First Name</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="first_name" id="edit_first_name">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="edit_last_name" class="col-sm-3 col-form-label required">Last Name </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="last_name" id="edit_last_name">
                                            </div>
                                        </div>
                                         <div class="row mb-3">
                                            <label for="edit_email" class="col-sm-3 col-form-label required">Email</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="email" id="edit_email">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="edit_phone" class="col-sm-3 col-form-label required">Phone</label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control" name="phone" id="edit_phone">
                                            </div>
                                        </div>   
                                        <div class="row mb-3">
                                            <label for="edit_role" class="col-sm-3 col-form-label required">Role</label>
                                            <div class="col-sm-9">
                                                <select name="role" class="form-select" id="edit_role">
                                                <option value="">Select User Role</option>
                                                    @foreach ($roles as $data)
                                                    <option value="{{$data->id}}">
                                                        {{$data->name}}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                </select>
                                            </div>
                                        </div> 
                                        <!-- <div class="row mb-3">
                                            <label for="edit_password" class="col-sm-3 col-form-label required">Password</label>
                                            <div class="col-sm-9">
                                                <input type="password" class="form-control" name="password" id="edit_password">
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <label for="edit_password_confirmation" class="col-sm-3 col-form-label required"> Confirm
                                                Password</label>
                                            <div class="col-sm-9">
                                                <input type="password" class="form-control mb-6" name="password_confirmation" id="edit_password_confirmation">
                                            </div>
                                        </div> -->
                                        <div class="row mb-3">
                                            <label for="edit_status" class="col-sm-3 col-form-label required">Status</label>
                                            <div class="col-sm-9">
                                                <select name="status" class="form-select" id="edit_status">
                                                    <option value="active">Active</option>
                                                    <option value="inactive">Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                        <input type="hidden" class="form-control" name="users_id" id="users_id" value="">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-default">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end: Edit User Modal -->

            </div>
        </div>
    </div>
    </div>
</section>

@endsection
@section('custom_js')
<script>
    $(document).ready(function() {

        $('#users_table').DataTable({
            "order": []

        });
        //hide error bag on modal close
        $(".modal").on("hidden.bs.modal", function() {
            $('.alert-danger').hide().html('');
        });
    });

    function openusersModal() {
            $('.alert-danger').html('');
            // $('.alert-danger').show();   
            $('#first_name').val('');
            $('#last_name').val('');
            $('#email').val('');
            $('#phone').val('');
            $('#password').val('');
            $('#password_confirmation').val('');
            $('#addUsers').modal('show');
    }

    $('#addUsersForm').submit(function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            url: "{{ url('/users/add')}}",
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            success: function(data) {
                // This function is called when the AJAX request is successful
                if (data.errors) {
                    displayErrors(data.errors); // Call a function to display errors
                } else {
                    // No errors, clear the error container and do whatever you want on success
                    $('.alert-danger').hide().html('');
                    $("#addUsers").modal('hide');
                    location.reload();
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                // This function is called when the AJAX request encounters an error
                // console.error(xhr.status); 
                // console.error(textStatus);
                // console.error(errorThrown); 
                
                // Check if the status code is 422 (Unprocessable Entity)
                if (xhr.status === 422) {
                    // Parse the error response if available
                    var errorResponse = xhr.responseJSON;
                    if (errorResponse && errorResponse.errors) {
                        displayErrors(errorResponse.errors);
                        return; 
                    }
                }

                // If the error response is not in the expected format or no errors are found, display a generic error message
                displayError('An error occurred while processing your request. Please try again later.');
            }
        });

    });

    function displayErrors(errors) {
            // Clear previous errors
            $('.alert-danger').html('');
            
            // Display each error
            $.each(errors, function(key, value) {
                $('.alert-danger').append('<li>' + value + '</li>');
            });

            // Show the error container
            $('.alert-danger').show();
        }

        function displayError(errorMessage) {
            // Display a single error message
            $('.alert-danger').html(errorMessage).show();
        }

    function editUsers(id) {
        $('.alert-danger').html('');
        $('#users_id').val(id);
        $.ajax({
            type: "GET",
            url: "{{ url('/users/edit') }}" + '/' + id, 
            dataType: 'json',
            success: (res) => {
                if (res.users != null) {
                    $('#editUsers').modal('show');
                    $('#edit_first_name').val(res.users.first_name);
                    $('#edit_last_name').val(res.users.last_name);
                    $('#edit_email').val(res.users.email);
                    $('#edit_phone').val(res.users.phone);  
                    $('#edit_status option[value="' + res.users.status + '"]').attr('selected',
                    'selected');
                    $('#edit_role option[value="' + res.users.role_id + '"]').attr('selected',
                    'selected');
                }
            }
        });
    }


    $('#editUsersForm').submit(function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        id =   $('#users_id').val();
        $('.alert-danger').hide().html('');
        $.ajax({
            type: "POST",
            url: `{{ route('users.update', ['user' => ':id']) }}`.replace(':id', id),
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(data) {
                // This function is called when the AJAX request is successful
                console.log(data);
                if (data.errors) {
                    displayErrors(data.errors); // Call a function to display errors
                } else {
                    // No errors, clear the error container and do whatever you want on success
                    $('.alert-danger').hide().html('');
                    $("#addUsers").modal('hide');
                    location.reload();
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                // This function is called when the AJAX request encounters an error
                // console.error(xhr.status); 
                // console.error(textStatus);
                // console.error(errorThrown); 
                
                // Check if the status code is 422 (Unprocessable Entity)
                if (xhr.status === 422) {
                    // Parse the error response if available
                    var errorResponse = xhr.responseJSON;
                    if (errorResponse && errorResponse.errors) {
                        displayErrors(errorResponse.errors);
                        return; 
                    }
                }

                // If the error response is not in the expected format or no errors are found, display a generic error message
                displayError('An error occurred while processing your request. Please try again later.');
            }
        });
    });

    function deleteUsers(id) {
        if (confirm("Are you sure ?")) {
            var token = $('meta[name="csrf-token"]').attr('content'); // Retrieve CSRF token from meta tag

            $.ajax({
                type: "DELETE",
                url: `{{ route('users.destroy', ['user' => ':id']) }}`.replace(':id', id),
                data: {
                    _token: token, // Include CSRF token in the request data
                    id: id
                },
                dataType: 'json',
                success: function(res) {
                    location.reload();
                },
                error: function(xhr, status, error) {
                    // Handle errors
                }
            });
        }
    }

       // Event listener for checkbox changes
       $("#all_users").change(function() {
            // Submit the form
            $("#filter-data-form").submit();
        });

        // Form submission
        // $("#filter-data-form").submit(function(event) {
        //     // Disable unchecked checkboxes
        //     if (!$("#all_catalogs").prop('checked')) {
        //     $("#all_catalogs").prop('disabled', true);
        //     }
        // });

         //Submit form on change the value of Project
         document.getElementById("roleFilterselectBox").addEventListener("change", function() {
                    document.getElementById("filter-data-form").submit();
                });

</script>
@endsection