@extends('layouts.app')
@section('title', 'Pending Catalogs')
@section('sub-title', 'Pending Catalogs')
@section('content')
<div id="loader">

    <div class="spinner-border text-warning loader-spinner" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
<section class="section catalog">
    <div class="row">
        <!-- Left side columns -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <!-- <button class="btn btn-default my-3" onClick="openPendingCatalogModal()" href="javascript:void(0)">Add Pending Catalog</button> -->
                    <!-- <h5 class="card-title">Table with stripped rows</h5> -->
                    <form id="filter-data" method="GET" action="{{ route('pending-catalogs.index') }}">
                        <div class="row mt-3 mx-auto">
                            <div class="col-md-4 filtersContainer d-flex p-0">
                                <div style="margin-right:20px;">
                                    <input type="checkbox" class="form-check-input" name="all_catalogs" id="all_catalogs"
                                    {{ $allCatalogsFilter == 'on' ? 'checked' : '' }}  > 
                                        <label for="all_catalogs">All</label>
                                </div>
                            </div>
                        </div>
                    </form>
                    <br>

                    <!-- Table with stripped rows -->
                    <div class="box-header with-border" id="filter-box">
                        <div class="box-body table-responsive" style="margin-bottom: 5%">
                            <table class="datatable table table-striped my-2" id="pending_catalogs_table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Catalog Id</th>
                                        <th scope="col">Base Price</th>
                                        <th scope="col">Publish Date</th>
                                        <th scope="col">Image</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($catalogs as $index => $data)
                                    <tr>
                                        <th scope="row">{{ $index + 1 }}</th>
                                        <td>{{ucfirst($data->title) ?? ''}}</td>
                                        <td>{{$data->catalog_id ?? 'Null'}}</td>
                                        <td>{{ $data->base_price !== null ? '$' . $data->base_price : '' }}</td>
                                        <td>{{$data->publish_date ?? 'NA'}}</td>
                                        <td>
                                        @if ($data->image)
                                            @if (Str::startsWith($data->image, ['http://', 'https://']))
                                                <a href="{{ $data->image }}" target="_blank">
                                                    <img src="{{ $data->image }}" height="40" width="70" alt="Catalog Image">
                                                </a>
                                            @else
                                                <img src="{{ asset('storage/' . $data->image) }}" height="40" width="70" alt="Catalog Image">
                                            @endif
                                        @else
                                            NA
                                        @endif
                                        </td>
                                        <td>
                                            @if($data->status == 'draft')
                                            <span class="badge rounded-pill bg-warning">{{ucfirst($data->status)}}</span>
                                            @else
                                            <span class="badge rounded-pill  bg-success">{{ucfirst($data->status)}}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <!-- <i onClick="editCatalogs('{{ $data->id }}')" href="javascript:void(0)" class="fa fa-edit fa-fw pointer btn-fa-catalog"></i> -->

                                            <!-- <i onClick="deleteModal('{{ $data->id }}')" href="javascript:void(0)" class="fa fa-trash fa-fw pointer btn-fa-catalog"></i> -->

                                            <a onClick="editCatalogs('{{ $data->id }}')" href="javascript:void(0)" class="fa fa-eye btn-fa-catalog" data-toggle="tooltip" data-placement="left" title="View"></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- End Table with stripped rows -->


                    <!--start: Add Pending Catalog Modal -->
                    <div class="modal fade" id="addCatalog" tabindex="-1" aria-labelledby="role" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="role">Add Pending Catalog</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form id="addPendingCatalogsForm">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="alert alert-danger" style="display:none"></div>
                                        <div class="row mb-3 mt-4">
                                            <label for="title" class="col-sm-3 col-form-label required">Title</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="title" id="title">
                                            </div>
                                        </div>
                                        <div class="row mb-3">

                                            <label for="content" class="col-sm-3 col-form-label required">Content</label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" name="content" style="height: 100px" id="content"></textarea>
                                            </div>
                                        </div>
                                        <!-- <div class="row mb-3 mt-4">
                                            <label for="name" class="col-sm-3 col-form-label required">Name</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="name" id="name">
                                            </div>
                                        </div> -->
                                        <div class="row mb-3 mt-4">
                                            <label for="category" class="col-sm-3 col-form-label">Wp Category</label>
                                            <div class="col-sm-9">

                                                <select name="category" class="form-select" id="category">
                                                    <option value="">Select Category</option>
                                                </select>
                                                <!-- <input type="text" class="form-control" name="category" id="category"> -->
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-4">
                                            <label for="sku" class="col-sm-3 col-form-label">SKU</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="sku" id="sku">
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-4">
                                            <label for="base_price" class="col-sm-3 col-form-label required">Base Price</label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control" name="base_price" id="base_price" step=".01">
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-4">
                                            <label for="image" class="col-sm-3 col-form-label">Image</label>
                                            <div class="col-sm-9">
                                                <input type="file" class="form-control" name="image" id="image">
                                            </div>
                                        </div>
                                        <!-- <div class="row mb-3">
                                            <label for="date" class="col-sm-3 col-form-label required">Date</label>
                                            <div class="col-sm-9">
                                                <input type="date" class="form-control" name="date" id="date">
                                            </div>
                                        </div> -->
                                        <div class="row mb-3">
                                            <label for="status" class="col-sm-3 col-form-label required">Status</label>
                                            <div class="col-sm-9">
                                                <select name="status" class="form-select" id="status">
                                                    <option value="draft">Draft</option>
                                                    <!-- <option value="publish">Publish</option> -->
                                                </select>
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
                <!--end: Add Pending Catalog Modal -->



                <!--start: Edit users Modal -->
                <div class="modal fade" id="editCatalogs" aria-labelledby="role" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="role">Edit Pending Catalog</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form id="editPendingCatalogsForm">
                                @csrf
                                <div class=" modal-body">
                                    <div class="alert alert-danger" style="display:none"></div>
                                    <div class="row mb-3 mt-4">
                                        <label for="edit_title" class="col-sm-3 col-form-label required">Title</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="title" id="edit_title">
                                        </div>
                                    </div>
                                    <div class="row mb-3">

                                        <label for="edit_content" class="col-sm-3 col-form-label required">Content</label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" name="content" style="height: 100px" id="edit_content"></textarea>
                                        </div>
                                    </div>
                                    <!-- <div class="row mb-3 mt-4">
                                            <label for="edit_name" class="col-sm-3 col-form-label required">Name</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="name" id="edit_name">
                                            </div>
                                        </div> -->
                                    <div class="row mb-3 mt-4">
                                        <label for="edit_category" class="col-sm-3 col-form-label">Wp Category</label>
                                        <div class="col-sm-9">

                                            <select name="category" class="form-select" id="edit_category">
                                                <option value="">Select Category</option>
                                            </select>
                                            <!-- <input type="text" class="form-control" name="category" id="category"> -->
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-4">
                                        <label for="edit_sku" class="col-sm-3 col-form-label">SKU</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="sku" id="edit_sku">
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-4">
                                        <label for="edit_base_price" class="col-sm-3 col-form-label required">Base Price</label>
                                        <div class="col-sm-9">
                                            <input type="number" class="form-control" name="base_price" id="edit_base_price" step=".01">
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-4">
                                        <label for="edit_image" class="col-sm-3 col-form-label">Image</label>
                                        <div class="col-sm-9">
                                            <input type="file" class="form-control" name="image" id="edit_image">
                                        </div>
                                    </div>
                                    <!-- <div class="row mb-3">
                                            <label for="date" class="col-sm-3 col-form-label required">Date</label>
                                            <div class="col-sm-9">
                                                <input type="date" class="form-control" name="date" id="date">
                                            </div>
                                        </div> -->
                                    <!-- <div class="row mb-3">
                                        <label for="edit_status" class="col-sm-3 col-form-label required">Status</label>
                                        <div class="col-sm-9">
                                            <select name="status" class="form-select" id="edit_status">
                                                <option value="draft">Draft</option>
                                                <option value="publish">Publish</option>
                                            </select>
                                        </div>
                                    </div> -->
                                    <input type="hidden" class="form-control" name="pending_catalog_id" id="catalog_id" value="">
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="decline" class="btn btn-secondary">Decline</button>
                                    <button type="submit" class="btn btn-default">Publish</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!--end: Edit User Modal -->


            <!--start: Delete Modal -->
            <div class="modal fade" id="deletePendingCatalog" tabindex="-1" aria-labelledby="role" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="role">Delete Pending Catalog</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="deletePendingCatalogsForm">
                                <input type="hidden" name="id" id="id">
                                <p>Are You Sure You Want To Delete Catalog From Pending List?</p>
                                <button type="submit" class="btn btn-default">Yes</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end: Delete Modal -->

        <!--start: Publish Pending Catalog Modal -->
        <div class="modal fade" id="publishPendingCatalog" tabindex="-1" aria-labelledby="role" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="role">Publish Catalog</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="publishPendingCatalogForm">
                            @csrf
                            <input type="hidden" name="id" id="id">
                            <p>Do You Want To Publish This Catelog.</p>
                            <button type="submit" class="btn btn-default">Approve</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end: Publish Pending Catalog Modal -->

    </div>
    </div>
    </div>
    </div>
</section>

@endsection
@section('custom_js')
<script>
    $(document).ready(function() {
        $(function () {
             $('[data-toggle="tooltip"]').tooltip()
        });
        $('#pending_catalogs_table').DataTable({
            "order": []

        });
        //hide error bag on modal close
        $(".modal").on("hidden.bs.modal", function() {
            $('.alert-danger').hide().html('');
        });

        $('#category').select2({
            dropdownParent: $('#addCatalog')
        });
        $('#edit_category').select2({
            dropdownParent: $('#editCatalogs')
        });
    });


    function openPendingCatalogModal() {
        //fetch category on modal open
        fetchCategories();
        $('.alert-danger').html('');
        $('#title').val('');
        $('#content').val('');
        $('#category').val('');
        $('#sku').val('');
        $('#base_price').val('');
        // $('#status').val('');
        $('#addCatalog').modal('show');
    }

    $('#addPendingCatalogsForm').submit(function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        if ($('#image')[0].files.length > 0) {
            var imageFile = $('#image')[0].files[0];
            formData.append('image', imageFile);
        }
        $.ajax({
            type: 'POST',
            url: "{{ url('/pending-catalogs/add/')}}",
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
                    $("#addCatalog").modal('hide');
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
    
    function editCatalogs(id) {
        $('#loader').show(); 
        //fetch category on modal open
        fetchCategoriesForEdit();
        $('.alert-danger').html('');
        $('#catalog_id').val(id);
        $.ajax({
            type: "GET",
            url: "{{ url('/pending-catalogs/edit') }}" + '/' + id,
            dataType: 'json',
            success: (res) => {
                if (res.catalogs != null) {
                    $('#editCatalogs').modal('show');
                    $('#edit_name').val(res.catalogs.name);
                    $('#edit_title').val(res.catalogs.title);
                    $('#edit_content').val(res.catalogs.content);
                    $('#edit_sku').val(res.catalogs.sku);
                    $('#edit_base_price').val(res.catalogs.base_price);
                    $('#edit_category').val(res.catalogs.wp_category_id).trigger('change');
                    // $('#edit_status option[value="' + res.catalogs.status + '"]').attr('selected',
                    //     'selected');
                }
            }
        });
    }


    $('#editPendingCatalogsForm').submit(function(event) {
        id = $('#catalog_id').val();
        event.preventDefault();

        // Determine the action based on the button clicked
        var status;
        if ($(event.originalEvent.submitter).attr('name') === 'decline') {
            status = 'decline';
        } else {
            status = 'publish';
        }
        if (confirm("Are you sure You Want To Continue to "+status+" Catalog ?")) {

            // var imageFile = $('#edit_image')[0].files[0];
            var formData = new FormData(this);
            formData.append('status', status);
            // Check if an image file is selected
            if ($('#edit_image')[0].files.length > 0) {
                var imageFile = $('#edit_image')[0].files[0];
                formData.append('image', imageFile);
            }
            // formData.append('image',imageFile);
            $.ajax({
                type: "POST",
                url: `{{ route('pending-catalogs.update', ['catalog' => ':id']) }}`.replace(':id', id),
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.errors) {
                        console.log()
                        $('.alert-danger').html('');
                        $.each(res.errors, function(key, value) {
                            $('.alert-danger').show();
                            $('.alert-danger').append('<li>' + value + '</li>');
                        })
                    } else {
                        $('.alert-danger').html('');
                        $("#editCatalogs").modal('hide');
                        location.reload();
                    }
                },
                 error: function(xhr, textStatus, errorThrown) {
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
        }
    });


    $('#deletePendingCatalogsForm').submit(function(event) {
        id = $('#id').val();
        event.preventDefault();
        var token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            type: "DELETE",
            url: `{{ route('pending-catalogs.destroy', ['catalog' => ':id']) }}`.replace(':id', id),
            data: {
                _token: token,
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
    });


    function deleteModal(id) {
        $('#deletePendingCatalog').modal('show');
        $('#id').val(id);
    }


    $('#publishPendingCatalogForm').submit(function(event) {
        id = $('#id').val();
        // console.log(id);
        event.preventDefault();
        var formData = new FormData(this);
        formData.set('id', id);
        var token = $('meta[name="csrf-token"]').attr('content');
        // Display the key/value pairs
        // for (var pair of formData.entries()) {
        //     console.log(pair[0]+ ', ' + pair[1]); ßß
        // }
        $.ajax({
            type: "POST",
            url: "{{ route('pending-catalogs.publish') }}",
            // url: `{{ route('catalogs.update', ['catalog' => ':id']) }}`.replace(':id', id),

            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': token
            },
            success: function(res) {
                console.log(res);
                if (res.errors) {
                    $('.alert-danger').html('');
                    $.each(res.errors, function(key, value) {
                        $('.alert-danger').show();
                        $('.alert-danger').append('<li>' + value + '</li>');
                    });
                } else {
                    $('.alert-danger').html('');
                    $("#editCatalogs").modal('hide');
                    // Optionally, you can reload the page or perform any other action
                    // location.reload();
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                // This function is called when the AJAX request encounters an error
                console.error(xhr.status);
                console.error(textStatus);
                console.error(errorThrown);

                // Optionally, you can display a generic error message to the user
                $('.alert-danger').html('An error occurred. Please try again.');
                $('.alert-danger').show();
            }
        });
    });

    function publishCatalog(id) {
        console.log(id);
        $('#publishPendingCatalog').modal('show');
        $('#id').val(id);

        // var token = $('meta[name="csrf-token"]').attr('content'); // Retrieve CSRF token from meta tag

        // $.ajax({
        //     type: "POST",
        //     url: "{{ route('pending-catalogs.publish') }}",
        //     data: {
        //         _token: token, // Include CSRF token in the request data
        //         id: id
        //     },
        //     dataType: 'json',
        //     success: function(res) {
        //         location.reload();
        //     },
        //     error: function(xhr, status, error) {
        //         // Handle errors
        //     }
        // });
    }

    // Function to fetch categories using Axios
    function fetchCategories() {
        // console.log("here");
        axios.get('/fetch-catalog-categories')
            .then(function(response) {
                // Handle the response data
                var categories = response.data;
                // console.log(categories) 
                // Populate the select element with the received categories
                $('#category').empty(); // Clear existing options
                $('#category').append($('<option>').text('Select Category').val('')); // Add default option
                categories.forEach(function(category) {
                    $('#category').append($('<option>').text(category.name).val(category.id));
                });
            })
            .catch(function(error) {
                console.error(error);
            });
    }

    // Function to fetch categories using Axios
    function fetchCategoriesForEdit() {
        axios.get('/fetch-catalog-categories')
            .then(function(response) {
                // Handle the response data
                var categories = response.data;

                $('#edit_category').empty(); // Clear existing options
                $('#edit_category').append($('<option>').text('Select Category').val('')); // Add default option
                categories.forEach(function(category) {
                    $('#edit_category').append($('<option>').text(category.name).val(category.id));
                });
                $('#loader').hide();
            })
            .catch(function(error) {
                $('#loader').hide();
                console.error(error);
            });
    }

        // Event listener for checkbox changes
        $("#filter-data input:checkbox").change(function() {
            // Submit the form
            $("#filter-data").submit();
        });

        // Form submission
        $("#filter-data").submit(function(event) {
            // Disable unchecked checkboxes
            if (!$("#all_catalogs").prop('checked')) {
            $("#all_catalogs").prop('disabled', true);
            }
        });

</script>
@endsection