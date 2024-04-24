@extends('layouts.app')
@section('title', 'Catalogs')
@section('sub-title', 'Catalogs')
@section('content')


<div id="loader">
    <div class="spinner-border text-warning loader-spinner"  role="status">
                <span class="visually-hidden">Loading...</span>
    </div>
</div>
<div id="url_msg">@if(Session::has('static_url'))
<p class="alert alert-info">{{ Session::get('static_url') }}</p>
 @endif
 </div>
<section class="section catalog">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="export_outer_cont">
                    <div  class="export_btn_cont">
                    <div >@if(Session::has('message'))
                        <p class="alert alert-info">{{ Session::get('message') }}</p>
                        @endif
                      </div>
                    <div id="success_msg">@if(Session::has('success'))
                        <p class="alert alert-info">{{ Session::get('success') }}</p>
                        @endif
                      </div>

                    <button class="btn btn-default my-3" onClick="openCatalogModal()" href="javascript:void(0)">Add Catalog</button>
                  <a href="{{url('download_csv')}}"><button class="btn btn-secondary my-3"  href="javascript:void(0)">Download CSV Format</button></a>
                  <button id="export_csv" class="btn btn-secondary my-3" onClick="" href="javascript:void(0)">Export CSV</button>
</div>
<div  class="import_btn_cont">
                   <input id="status" type  ="hidden">

                <form action="{{ url('importCSV') }}" method="POST" enctype="multipart/form-data" >
                        @csrf
                        <div class="choose-form">
                        <div class="import_div my-3">
                            <div class="input-group mb-3">
                                <input type="file" class="form-control" id="import_csv" name="import_csv" accept=".csv" required>
                            </div>
                        </div>
                        <button  type="submit" class="btn btn-success">Import CSV</button>
                  </div>
                </form>
</div>
</div>
                    <!-- <h5 class="card-title">Table with stripped rows</h5> -->
                    <form id="filter-data" method="GET" action="{{ route('catalogs.index') }}">
                        <div class="row mt-3 mx-auto">
                                <div class="col-md-4 form-group">
                                        <div class="main-input">
                                        <label for="statusFilterselectBox">Status:</label>    
                                            <select class="form-control mx-2" id="statusFilterselectBox" name="status_filter">
                                                <option value="" selected >Select Status</option>
                                                <option value="all" {{ request()->input('status_filter') == 'all' ? 'selected' : '' }} >All</option>
                                                <option value="draft" {{ request()->input('status_filter') == 'draft' ? 'selected' : '' }} >Draft</option>
                                                <option value="publish" {{ request()->input('status_filter') == 'publish' ? 'selected' : '' }} >Publish</option>
                                                <!-- <option value="decline" {{ request()->input('status_filter') == 'decline' ? 'selected' : '' }} >Decline</option> -->               
                                            </select>
                                        </div>
                                        @if ($errors->has('status_filter'))
                                            <span style="font-size: 10px;" class="text-danger">{{ $errors->first('status_filter') }}</span>
                                        @endif
                                </div>
                        </div>
                       
                    </form>
                    <br>
                    

                    <!-- Table with stripped rows -->
                    <div class="box-header with-border" id="filter-box">
                        <div class="box-body table-responsive" style="margin-bottom: 5%">
                            <table class="datatable table table-striped my-2" id="catalogs_table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Base Price</th>
                                        <th scope="col">SKU</th>
                                        <th scope="col">Publish Date</th>
                                        <th scope="col">User Count</th>
                                        <!-- <th scope="col">Total  Catalogs</th> -->
                                        <th scope="col">Image</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                        
                                    </tr>
                                </thead>
                                <!-- <tbody >
                                  
                                </tbody> -->
                            </table>
                        </div>
                    </div>
                    <!-- End Table with stripped rows -->


                    <!--start: Add users Modal -->
                    <div class="modal fade" id="addCatalog" tabindex="-1" aria-labelledby="role" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="role">Add Catalog</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form id="addCatalogsForm">
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

                                            <label for="content" class="col-sm-3 col-form-label required">Description</label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" name="content" style="height: 100px" id="content"></textarea>
                                                <!-- <textarea id="myTextarea" name="content"></textarea> -->
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
                                                    <option value="publish">Publish</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-4">
                                            <label for="weight" class="col-sm-3 col-form-label ">Weight (lbs)</label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control" name="weight" id="weight" >
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-4">
                                            <label for="color" class="col-sm-3 col-form-label ">Color</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="color" id="color" >
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-4">
                                            <label for="sale_price" class="col-sm-3 col-form-label ">Sale Price </label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control" name="sale_price" id="sale_price" >
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="Brand" class="col-sm-3 col-form-label ">Brand</label>
                                            <div class="col-sm-9">
                                                <select name="Brand" class="form-select" id="Brand">
                                                     <option value="0">Select the Brand</option>
                                                    <option value="Puma">Puma</option>
                                                    <option value="Addidas">Addidas</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-4">
                                            <label for="dimensions" class="col-sm-3 col-form-label ">Dimensions (in)</label>
                                            <div class="col-sm-3">
                                                <input type="number" class="form-control" name="length" id="length" placeholder="Length">
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="number" class="form-control" name="width" id="width" placeholder="Width">
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="number" class="form-control" name="height" id="height" placeholder="Height">
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
                <div class="modal fade" id="editCatalogs" aria-labelledby="role" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="role">Edit Catalog</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form id="editCatalogsForm">
                                @csrf
                                <div class="modal-body">
                                    <div class="alert alert-danger" style="display:none"></div>
                                    <div class="row mb-3 mt-4">
                                        <label for="edit_title" class="col-sm-3 col-form-label required">Title</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="title" id="edit_title">
                                        </div>
                                    </div>
                                    <div class="row mb-3">

                                        <label for="edit_content" class="col-sm-3 col-form-label required">Description</label>
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
                                            <div id="image_tag">
                                            <img src="" height="80" width="40" alt="Catalog Image" id="edit_image5" class="mt-3">
                                            </div>
                                            
                                        </div>
                                       
                                    </div>
                                    <!-- <div class="row mb-3">
                                            <label for="date" class="col-sm-3 col-form-label required">Date</label>
                                            <div class="col-sm-9">
                                                <input type="date" class="form-control" name="date" id="date">
                                            </div>
                                        </div> -->
                                    <div class="row mb-3">
                                        <label for="edit_status" class="col-sm-3 col-form-label required">Status</label>
                                        <div class="col-sm-9">
                                            <select name="status" class="form-select" id="edit_status">
                                                <option value="draft">Draft</option>
                                                <option value="publish">Publish</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-4">
                                            <label for="weight" class="col-sm-3 col-form-label ">Weight (lbs)</label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control" name="weight" id="edit_weight" >
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-4">
                                            <label for="color" class="col-sm-3 col-form-label ">Color</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="color" id="edit_color" >
                                            </div>
                                        </div>  
                                        <div class="row mb-3 mt-4">
                                            <label for="sale_price" class="col-sm-3 col-form-label ">Sale Price </label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control" name="sale_price" id="edit_sale_price" >
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="Brand" class="col-sm-3 col-form-label ">Brand</label>
                                            <div class="col-sm-9">
                                                <select name="editBrand" class="form-select" id="edit_Brand">
                                                <option value="0">Select the Brand</option>
                                                    <option value="Puma">Puma</option>
                                                    <option value="Addidas">Addidas</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-4">
                                            <label for="dimensions" class="col-sm-3 col-form-label ">Dimensions (in)</label>
                                            <div class="col-sm-3">
                                                <input type="number" class="form-control" name="length" id="edit_length" placeholder="Length">
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="number" class="form-control" name="width" id="edit_width" placeholder="Width">
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="number" class="form-control" name="height" id="edit_height" placeholder="Height">
                                            </div>
                                        </div>
                                    <input type="hidden" class="form-control" name="users_id" id="catalog_id" value="">
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


                         <!--start:  users count Modal -->
                         <div class="modal fade" id="user_count" aria-labelledby="role" aria-hidden="true">
                         <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="role">User Count</h5><br>
                            
                             
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                <!-- Table with stripped rows -->
                    <div class="box-header with-border" id="filter-box">
                    <h5 id="append_title"></h5>
                        <div class="box-body table-responsive" style="margin-bottom: 5%">
                            <table class="datatable table table-striped my-2" id="myDataTable">
                                <thead>
                                    <tr>
                                        <th scope="col">Id</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">E-MAIL</th>
                                   
                                    </tr>
                                </thead>
                                <tbody id="append_user">
                                    
                                  
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- End Table with stripped rows -->
                        </div>
                    </div>
                </div>
            </div>
            <!--end:  User  count Modal -->


            <!--start: Delete Modal -->
            <div class="modal fade" id="deleteCatalog" tabindex="-1" aria-labelledby="role" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="role">Delete Catalog</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="deleteCatalogsForm">
                                    <input type="hidden" name="id" id="id">                        
                                        <p>Are You Sure You Want To Delete Catalog?</p>
                                        <button type="submit" class="btn btn-default">Yes</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>         
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end: Delete Modal -->

    </div>
    </div>
    </div>
    </div>
</section>

@endsection
@section('custom_js')
<script>
     var catalogsTable;
    $(document).ready(function() {
        catalogsTable = $('#catalogs_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('catalogs.index') }}",
            paging: true, // Enable server-side pagination
            pageLength: 10, // Initial number of entries per page
            columns: [
                { name: 'Id', 
                    render: function (data, type, row) {
                      return row.id;    
                    }
                },
                { name: 'Title', 
                    render: function (data, type, row) {
                      return row.title ?? 'N/A';    
                    }
                },
                { name: 'Base Price', 
                    render: function (data, type, row) {
                      return '$'+row.base_price ?? 'N/A';    
                    }
                },{ name: 'SKU', 
                    render: function (data, type, row) {
                      return row.sku ?? 'N/A';    
                    }
                },{ name: 'Publish Date', 
                    render: function (data, type, row) {
                      return row.publish_date ?? 'N/A';    
                    }
                },
                { name: 'User Count', 
                    render: function (data, type, row) {
                        // console.log(row);
                        return 'N/A';
                // if(row.user_count !== undefined && row.user_count !== null) {
              
                //     return '<a class="btn-fa-catalog" onclick="user_count(' + row.id + ',\'' + row.title + '\')" href="javascript:void(0)">' + row.user_count + '</a>';
                //     }
                //     if (row.user_count == undefined){
                //         return 'N/A';
                //     }

                    }
                },
                // { name: 'Total  Catalogs', 
                //     render: function (data, type, row) {
                //       return row.total_catalog ?? 'N/A';    
                //     }
                // },
                {
                    name: 'Image',
                    render: function(data, type, row) {
                        var image = "N/A";
                        if (row.image) {
                            if(row.image  == "")
                            {
                                image = "N/A";
                            }else{
                                image = '<img src="{{ asset('storage') }}/' + row.image + '" height="40" width="70" alt="Catalog Image">';
                            }
                           
                        }
                        return image;
                    }
                },
                { name: 'Status', 
                    render: function (data, type, row) {
                        if (row.status === 'draft') {
                            return '<span class="badge rounded-pill bg-warning">Draft</span>';
                        } else if (row.status === 'publish') {
                            return '<span class="badge rounded-pill bg-success">Publish</span>';
                        } else {
                            return '----';
                        }   
                    }
                },
                {
                    name: 'Action',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        var action = '';
                        var baseUrl = window.location.origin + '/catalog/';
                        action += '<a href="' + baseUrl + row.id + '" data-toggle="tooltip" data-placement="left" title="Show Products">';
                        action += '<i class="fa fa-eye fa-fw pointer btn-fa-catalog"></i></a>';
                        action += '<i onClick="editCatalogs(\'' + row.id + '\')" href="javascript:void(0)" class="fa fa-edit fa-fw pointer btn-fa-catalog"></i>';
                        action += '<i onClick="deleteModal(\'' + row.id + '\')" href="javascript:void(0)" class="fa fa-trash fa-fw pointer btn-fa-catalog"></i>';
                        return action;
                    }
                }


            ],
            rowCallback: function (row, data) {
                $(row).addClass('row_status_'+data.id); // Add a CSS class to the row
            }
    });
        $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            })
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


    function openCatalogModal() {
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

    $('#addCatalogsForm').submit(function(event) {
        event.preventDefault();
        var content = tinymce.get('content').getContent();
        $('#content').val(content);

        var formData = new FormData(this);
        if ($('#image')[0].files.length > 0) {
            var imageFile = $('#image')[0].files[0];
            formData.append('image', imageFile);
        }
        $.ajax({
            type: 'POST',
            url: "{{ url('/catalogs/add')}}",
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
            url: "{{ url('/catalogs/edit') }}" + '/' + id,
            dataType: 'json',
            success: (res) => {
              
                if (res.catalogs != null) {
                    $('#editCatalogs').modal('show');
                    $('#edit_name').val(res.catalogs.name);
                    $('#edit_title').val(res.catalogs.title);

                    tinymce.get('edit_content').setContent(res.catalogs.content);
                    //$('#edit_content').val(res.catalogs.content);

                    $('#edit_sku').val(res.catalogs.sku);
                    $('#edit_base_price').val(res.catalogs.base_price);
                    $('#edit_status option[value="' + res.catalogs.status + '"]').attr('selected','selected');
                    $('#edit_category').val(res.catalogs.wp_category_id).trigger('change');
                    if(res.catalogs.image  == ''){
                      $('#image_tag').hide();
                    }else{
                       
                        var url =   {!! json_encode(url('/storage/')) !!} + '/'+ res.catalogs.image;
                         $('#edit_image5').attr({src: url});
                         $('#image_tag').show();
                    }
                  

                    $('#edit_weight').val(res.catalogs.weight);
                    $('#edit_color').val(res.catalogs.color);
                    $('#edit_sale_price').val(res.catalogs.sale_price);
                    $('#edit_Brand').val(res.catalogs.brand);
                    $('#edit_length').val(res.catalogs.length);
                    $('#edit_width').val(res.catalogs.width);
                    $('#edit_height').val(res.catalogs.height);
                    if(res.catalogs.brand ==  null){

                        $('#edit_Brand option[value="0"]').attr('selected','selected');
                    }else{
                        $('#edit_Brand option[value="' + res.catalogs.brand + '"]').attr('selected','selected');
                    }
                    
                    // $("#edit_category").select2('val',res.catalogs.wp_category_id);
                    // $('#edit_category').select2().val(225).trigger('change');
                }
            }
        });
    }


    $('#editCatalogsForm').submit(function(event) {
        id = $('#catalog_id').val();
        event.preventDefault();
        // var imageFile = $('#edit_image')[0].files[0];
        var content = tinymce.get('edit_content').getContent();
        $('#edit_content').val(content);
        var formData = new FormData(this);
        // Check if an image file is selected
        if ($('#edit_image')[0].files.length > 0) {
            var imageFile = $('#edit_image')[0].files[0];
            formData.append('image', imageFile);
        }
        // formData.append('image',imageFile);
        $.ajax({
    type: "POST",
    url: `{{ route('catalogs.update', ['catalog' => ':id']) }}`.replace(':id', id),
    data: formData,
    dataType: 'json',
    processData: false,
    contentType: false,
    success: function(res) {
   
        if (res.errors) {
            $('.alert-danger').html('');
            // $.each(res.errors, function(key, value) {
            //     console.log(value);
            //     $('.alert-danger').show();
            //     $('.alert-danger').append('<li>' + value + '</li>');
            // })
        } else {
            $('.alert-danger').html('');
            $("#editCatalogs").modal('hide');
            location.reload();
        }
    },
    error: function(xhr, status, error) {
    console.error('Error:', error);

    try {
        var errors = JSON.parse(xhr.responseText);
        if (errors && errors.errors) {
            $('.alert-danger').html('');
            $.each(errors.errors, function(key, value) {
               
                $('.alert-danger').show();
                $('.alert-danger').append('<li>' + value + '</li>');
            });
        } else {
            // If there are no specific error messages returned in the response
            $('.alert-danger').html('An error occurred while processing your request. Please try again later.');
            $('.alert-danger').show();
        }
    } catch (e) {
        // If the response is not in JSON format or cannot be parsed
        console.error('Error parsing response:', e);
        $('.alert-danger').html('An unexpected error occurred. Please try again later.');
        $('.alert-danger').show();
    }
}

});

    });


    $('#deleteCatalogsForm').submit(function(event) {
        id = $('#id').val();
        event.preventDefault();
        var token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
                type: "DELETE",
                url: `{{ route('catalogs.destroy', ['catalog' => ':id']) }}`.replace(':id', id),
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
        $('#deleteCatalog').modal('show');
        $('#id').val(id);
    }

    // function deleteCatalogs(id) {
    //     console.log(id);
    //     if (confirm("Are you sure You Want To Delete Catalog ?")) {
    //         var token = $('meta[name="csrf-token"]').attr('content'); // Retrieve CSRF token from meta tag

    //         $.ajax({
    //             type: "DELETE",
    //             url: `{{ route('catalogs.destroy', ['catalog' => ':id']) }}`.replace(':id', id),
    //             data: {
    //                 _token: token, // Include CSRF token in the request data
    //                 id: id
    //             },
    //             dataType: 'json',
    //             success: function(res) {
    //                 location.reload();
    //             },
    //             error: function(xhr, status, error) {
    //                 // Handle errors
    //             }
    //         });
    //     }
    // }

    // Function to fetch categories using Axios
    function fetchCategories() {
        axios.get('/fetch-catalog-categories')
            .then(function(response) {
                // Handle the response data
                var categories = response.data;
                // Populate the select element with the received categories
                $('#category').empty(); // Clear existing options
                $('#category').append($('<option>').text('Select Category').val('')); // Add default option
                categories.forEach(function(category) {
                    $('#category').append($('<option>').text(category.name).val(category.id));
                });
            })
            .catch(function(error) {
                console.error(error);
                $('#loader').hide(); 
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
                console.error(error);
                $('#loader').hide(); 
            });
    }
    //Get Records Using Ajax For Status Filter 
    $("#statusFilterselectBox").change(function() {
        var selectedValue = $(this).val();
        // Remove selected attribute from all options
        $(this).find("option").removeAttr("selected");

        // Set selected attribute for the option with the selected value
        $(this).find("option[value='" + selectedValue + "']").attr("selected", "selected");
        catalogsTable.ajax.url("{{ route('catalogs.index') }}?status_filter=" + selectedValue).load();
        $('#status').val(selectedValue);

       

  
    });

</script>

<script>
$(document).on("click", '#export_csv', function(){
    var status = $('#status').val();
    window.location.href = "{{ URL::to('export') }}?status_filter=" + status;
});
</script>

<script>
 setTimeout(function() {
    $('#success_msg').fadeOut('fast');
}, 5000);
</script>

<script>
    function user_count(id,title) {
       // console.log(id)
        $('#loader').show(); 
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
            var tboby = $('#append_user');
            tboby.empty();
         vdata =  {id:id, title: title};
         $.ajax({
    type: "post",
    url: "{{ url('get_user_data') }}" + '/' + id,
    headers: {'X-CSRF-Token': csrfToken},
    data: vdata,
    dataType: 'json',
    success: function(data)  {
        $('#loader').hide(); 
        $('#user_count').modal('show');
        $("#append_user").empty();
        var tbody = $("#append_user"); // Select the table body
        $.each(data, function(key, val) {
            var tr = $('<tr></tr>'); // Create a new table row for each user
            tr.append('<td>' + val.user_data.user_id + '</td>');
            tr.append('<td>' + val.user_data.user_name + '</td>');
            tr.append('<td>' + val.user_data.user_email + '</td>');
            tbody.append(tr); // Append the new table row to the table body
            $('#append_title').html(val.title);
        });
        table = $('#myDataTable').DataTable(); // Initialize DataTable
    }
});

    }
    </script>

 <script src="https://cdn.tiny.cloud/1/0dav8na2f2kt1ffcyc1zmxhyohcbkjxfji99bcvl4xevhk0n/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

 <script>
 tinymce.init({
    selector: 'textarea',
    setup: function (editor) {
        editor.on('init', function () {
            // Editor is initialized, now bind the form submission
            document.getElementById('addCatalogsForm').addEventListener('submit', function() {
                var content = tinymce.get('content').getContent();
                document.getElementById('content').value = content;
            });
        });
    }
});
</script>
<script>

setTimeout(function() {
    $('#url_msg').fadeOut('fast');
}, 5000); 

</script>

@endsection