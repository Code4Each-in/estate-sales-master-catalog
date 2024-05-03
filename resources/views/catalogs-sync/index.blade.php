@extends('layouts.app')
@section('title', 'Catalogs Sync')
@section('sub-title', 'Catalogs Sync')
@section('content')


<div id="hide_sess">@if(Session::has('cata_added'))
<p class="alert alert-info">{{ Session::get('cata_added') }}</p>
   @endif
</div>
<div  id="catalog_success">

</div>

<div  id="check_error">

</div>
<body>
    <button class="btn btn-default my-3" onClick="assign_model()">Assign</button>
    <button class="btn btn-default my-3" onClick="openCatalogModal()" href="javascript:void(0)">Add Catalog</button>
    <button class="btn btn-default my-3" id="fetchBtn"  href="javascript:void(0)">Fetch</button>
</body>

<section class="section catalog">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <!-- Table with stripped rows -->
                    <div class="box-header with-border" id="filter-box">
                        <div class="box-body table-responsive" style="margin-bottom: 5%">
                            <table class="datatable table table-striped my-2" id="catalogs_table">
                                <thead>
                                    <tr>
                                       <th scope="col">Action</th>
                                        <th scope="col">ID</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Base Price</th>
                                        <th scope="col">SKU</th>
                                        <th scope="col">Publish Date</th>
                                        <th scope="col">User Count</th>
                                        <th scope="col">Image</th>
                                        <th scope="col">Status</th>
                                   

                                    </tr>
                                </thead>
                          
                            </table>
                            
                        </div>
                    </div>
                    <!-- End Table with stripped rows -->

                </div>
            </div>
        </div>  
    </div>
           <!--start: Add users Modal -->
           <div class="modal fade" id="assign_model" tabindex="-1" aria-labelledby="role" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="role">Assign Catalog</h5>
                                    <p class="alert alert-info">Because of Domain Issue we cannot assign Products</p>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form  action= "{{ url('assignCatalog') }}" method="post">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="alert alert-danger" style="display:none"></div>
                                        <div class="row mb-3 mt-4">
                                            <label for="title" class="col-sm-3 col-form-label required">Title</label>
                                            <div class="col-sm-9">
                                                <div id="append_pro">

                                                 </div>
                                          
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="status" class="col-sm-3 col-form-label required">Catalogs</label>
                                            <div class="col-sm-9">
                                                <select name="catalog" class="form-select" id="append_Catalogs">
                                                  
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-default"  >Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end: Add User Modal -->

                
                    <!--start: Add users Modal -->
                    <div class="modal fade" id="addCatalog" tabindex="-1" aria-labelledby="role" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="role">Add Catalog</h5>
                                    <p class="alert alert-info">Because of Domain Issue we cannot Add Products</p>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form id="addCatalogsForm"  >
                                    @csrf
                                    <div class="modal-body">
                                        <div class="alert alert-danger" style="display:none"></div>
                                        <div class="row mb-3 mt-4">
                                            <label for="title" class="col-sm-3 col-form-label required">Products</label>
                                            <div class="col-sm-9" id="check_box">
                                              
                                            </div>
                                        </div>
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
                                             
                                            </div>
                                        </div>
                                  
                                        <div class="row mb-3 mt-4">
                                            <label for="category" class="col-sm-3 col-form-label">Wp Category</label>
                                            <div class="col-sm-9">

                                                <select name="category" class="form-select" id="category">
                                                    <option value="">Select Category</option>
                                                </select>
                                               
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
                                                <input type="text" class="form-control" name="length" id="length" placeholder="Length">
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" name="width" id="width" placeholder="Width">
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" name="height" id="height" placeholder="Height">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-default" >Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end: Add User Modal -->

</section>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>


<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).ready(function() {
        $('#catalogs_table').DataTable({
            processing: true,


            serverSide: true,
            searchDelay: 3000 ,
            
            ajax: {
                url: "{{ route('catalogs-sync.index') }}",
                type: "get",
                data: function (data) {
                data.draw = data.draw || 1;
                data.start = data.start || 0;
                data.length = data.length || 10;
                data.search = $('input[type="search"]').val();
    }
            },
            order: ['1', 'DESC'],
            pageLength: 10,
            searching: true,
            aoColumns: [
                {
                        data: 'Action',
                        width: "20%",
                        render: function(data, type, row) {
                        // Assuming there's some logic here to determine the value of isChecked
                        var isChecked = row.isChecked ? 'checked' : ''; // Example: Checking the checkbox based on some condition
                        return '<input type="checkbox" name="pro_id[]" value="'+row.id+'">';
                    }
                },
                {
                        data: 'id',
                        width: "20%",
                        render: function(data, type, row) {
                            return row.id; 
                        }
                },
                {
                        data: 'name',
                        width: "20%",
                        render: function(data, type, row) {
                            return row.name; 
                        }
                },
                {
                        data: 'regular_price',
                        width: "20%",
                        render: function(data, type, row) {
                            return row.regular_price; 
                        }
                },
                {
                        data: 'sku',
                        width: "20%",
                        render: function(data, type, row) {
                            return row.sku; 
                        }
                },
                {
                        data: 'publish_date',
                        width: "20%",
                        render: function(data, type, row) {
                            return row.publish_date; 
                        }
                },
                {
                        data: 'id',
                        width: "20%",
                        render: function(data, type, row) {
                            return row.id; 
                        }
                },
                {
                    data: 'image',
                    width: "20%",
                    render: function(data, type, row) {
                        var image = '<img src="' + row.images.thumbnail + '" height="40" width="70" alt="Catalog Image">';
                        return image;
                     }

                },
              
                {
                        data: 'status',
                        width: "20%",
                        render: function(data, type, row) {
                        if (row.status === 'draft') {
                            return '<span class="badge rounded-pill bg-warning">Draft</span>';
                        } else if (row.status === 'publish') {
                            return '<span class="badge rounded-pill bg-success">Publish</span>';
                        }   
                    }
                }
                
            ]
        });
    });
</script>

<script>
    var checkedValues = [];

    function assign_model() {
        // Check if any checkbox is checked
        if ($('input[name="pro_id[]"]:checked').length === 0) {
            $('#check_error').html("Please select at least one Checkbox.").addClass('alert alert-danger');
            return; 
        }
        $('#check_error').html("").removeClass('alert alert-danger');
        $("#append_pro").empty();
        $('input[name="pro_id[]"]:checked').each(function() {
            var checkboxValue = $(this).val();
            checkedValues.push($(this).val());
            var productName = $(this).closest('tr').find('td:eq(2)').text();
        
           var checkbox = $('<input>', {
                type: 'checkbox',
                checked: 'checked',
                name: 'products[]',
                value: checkboxValue
            }).css('display', 'none');
            var label = $('<label>').text(productName);
            if ($("#append_pro input[type='checkbox']").length > 0) {
        $("#append_pro").append(', ');
    }

           $("#append_pro").append(checkbox, label);
        });

        $.ajax({
            url: "{{ url('getCatlogs')}}",
            type:"get",
            dataType: "json",
            success: function(data){
                $.each(data, function(key, val) {
                    $("#append_Catalogs").append('<option value=' + val.id + '>' + val.title + '</option>');
                });
                $('#assign_model').modal('show');
            }
        });
    }
</script>


    <script>
function openCatalogModal() {
    var onecheckboxdata = [];
    var allcheckboxdata = [];
    var atLeastOneChecked = false; // Flag to check if at least one checkbox is checked

    $("#append_pro").empty();
    
    $('input[name="pro_id[]"]:checked').each(function(index) {
        atLeastOneChecked = true; // Set the flag to true if at least one checkbox is checked
        
        var checkboxValue = $(this).val();
        var productName = $(this).closest('tr').find('td:eq(2)').text();
        var pro_data = {
            "id": checkboxValue,
            "name": productName
        };
        allcheckboxdata.push(pro_data);

        if (index === 0) {
            var checkboxValue = $(this).val();
            var productName = $(this).closest('tr').find('td:eq(1)').text();
            var price = $(this).closest('tr').find('td:eq(2)').text();
            var sku = $(this).closest('tr').find('td:eq(3)').text();
            var publiish_date = $(this).closest('tr').find('td:eq(4)').text();
            var image = $(this).closest('tr').find('td:eq(6) img').attr('src');
            var status = $(this).closest('tr').find('td:eq(7)').text();
            var productData = {
                id: checkboxValue,
                price: price,
                productName: productName,
                sku: sku,
                publiish_date: publiish_date,
                image: image,
                status: status
            };
            onecheckboxdata.push(productData);
        }
    });

    if (!atLeastOneChecked) {
        // If no checkbox is checked, display an alert
        $('#check_error').html("Please select at least one Checkbox.").addClass('alert alert-danger');
        return; // Exit the function without opening the modal
    }else{
        $('#check_error').html("").removeClass('alert alert-danger');
        allpreFieldData(onecheckboxdata, allcheckboxdata);
    }


   
}

function allpreFieldData(onecheckboxdata, allcheckboxdata) {
    var id = onecheckboxdata[0].id;
    var vdata = { id: id };
    
    $.ajax({
        url: "{{ url('catalogData')}}",
        type: "get",
        data: vdata,
        dataType: "json",
        success: function(data) {
            console.log(data.data.dimensions.length);
            // Populate modal fields with data received from AJAX response
            document.getElementById("title").value = data.data.name;
            document.getElementById("content").value = data.data.short_description;
            document.getElementById("sku").value = data.data.sku;
            document.getElementById("base_price").value = data.data.price;
            document.getElementById("weight").value = data.data.weight;
            document.getElementById("sale_price").value = data.data.sale_price;
            if (data.data.dimensions.length == '') 
            {
                document.getElementById("length").value = '';
            }else{
                document.getElementById("length").value = parseFloat(data.data.dimensions.length);
            }
            if (data.data.dimensions.width == '') {
                document.getElementById("width").value = '';
            }else{
                document.getElementById("width").value = parseFloat(data.data.dimensions.width);
            }
            if (data.data.dimensions.height == '') {
                document.getElementById("height").value = '';
            }else{
                document.getElementById("height").value = parseFloat(data.data.dimensions.height);
            }
           
           
            document.getElementById("status").value = data.data.status;

            // Populate checkboxes in the modal with data from allcheckboxdata
            var container = document.getElementById("check_box");
            container.innerHTML = ''; // Clear existing checkboxes

            allcheckboxdata.forEach(function(item, index) {
            var checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.id = item.id;
            checkbox.value = item.id;
            checkbox.name = 'products[]';
            checkbox.checked = true;
            checkbox.style.display = 'none'; 

    var label = document.createElement('label');
    label.htmlFor = item.id;
    label.appendChild(document.createTextNode(item.name));

    container.appendChild(checkbox);
    container.appendChild(label);

    if (index < allcheckboxdata.length - 1) { // Append comma if not the last item
        container.appendChild(document.createTextNode(', '));
    }

    container.appendChild(document.createElement('br'));
});



            // Show the modal
            $('#addCatalog').modal('show');
        }
    });
}

        </script>

<script>
    document.getElementById('fetchBtn').addEventListener('click', function() {
        window.location.reload();
    });
</script>

<script>
    $('#addCatalogsForm').submit(function(event) {
        event.preventDefault();
        // var content = tinymce.get('content').getContent();
        // $('#content').val(content);

        var formData = new FormData(this);
        // if ($('#image')[0].files.length > 0) {
        //     var imageFile = $('#image')[0].files[0];
        //     formData.append('image', imageFile);
        // }
        $.ajax({
            type: 'POST',
            url: "{{ url('/addCatalog')}}",
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            success: function(data) {
              // console.log(data.errors);
                if (data.errors) {
                    displayErrors(data.errors); 
                } else {
                    $('.alert-danger').hide().html('');
                    $("#addCatalog").modal('hide');
                   var data = JSON.parse(data);
                 
                   
                   $('#hide_sess').hide();
                   $('#catalog_success').html(data.message).addClass('alert alert-info');
                   setTimeout(function(){
                    location.reload(); 
                }, 3000); 
                   // location.reload();
                }
            },
            error: function(xhr, textStatus, errorThrown) { 
                if (xhr.status === 422) {
                    var errorResponse = xhr.responseJSON;
                    if (errorResponse && errorResponse.errors) {
                        displayErrors(errorResponse.errors);
                        return;
                    }
                }
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
    </script>




@endsection

@section('custom_js')