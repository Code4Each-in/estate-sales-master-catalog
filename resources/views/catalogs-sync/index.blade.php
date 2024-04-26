@extends('layouts.app')
@section('title', 'Catalogs Sync')
@section('sub-title', 'Catalogs Sync')
@section('content')



<body>
    <button class="btn btn-default my-3" onClick="assign_model()">Assign</button>
    <button class="btn btn-default my-3" onClick="openCatalogModal()" href="javascript:void(0)">Add Catalog</button>
    <button class="btn btn-default my-3" id="fetchBtn"  href="javascript:void(0)">Fetch</button>
</body>


<!-- <button class="btn btn-default my-3" onClick="assign_model()" href="javascript:void(0)">ASSIGN</button> -->

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
                                        <th scope="col">ID</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Base Price</th>
                                        <th scope="col">SKU</th>
                                        <th scope="col">Publish Date</th>
                                        <th scope="col">User Count</th>
                                        <th scope="col">Image</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>

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
                                   
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form id="addCatalogsForm" action= "{{ url('assignCatalog') }}" method="post">
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
                                        <button type="submit" class="btn btn-default">Save</button>
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
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form id="addCatalogsForm">
                                    @csrf
                                    <div id="check_box">
                                    <!-- <label for="vehicle2"> I have a car</label><br>
                                    <input type="checkbox" name="vehicle3" value="Boat" checked> -->
                                    </div>
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
                                        <!-- <div class="row mb-3 mt-4">
                                            <label for="image" class="col-sm-3 col-form-label">Image</label>
                                            <div class="col-sm-9">
                                                <input type="file" class="form-control" name="image" id="image">
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
                },
                {
                        data: 'Action',
                        width: "20%",
                        render: function(data, type, row) {
                        // Assuming there's some logic here to determine the value of isChecked
                        var isChecked = row.isChecked ? 'checked' : ''; // Example: Checking the checkbox based on some condition
                        return '<input type="checkbox" name="pro_id[]" value="'+row.id+'">';
                    }
                }
            ]
        });
    });
</script>

<script>
    var checkedValues = [];
   function assign_model() {
   
   
    $("#append_pro").empty();
    $('input[name="pro_id[]"]:checked').each(function() {
        var checkboxValue = $(this).val();
        checkedValues.push($(this).val());
        console.log(checkedValues);
        var productName = $(this).closest('tr').find('td:eq(1)').text(); 
        var price = $(this).closest('tr').find('td:eq(2)').text(); 
       // alert(price);
        // Append checkbox with label and name
        $("#append_pro").append('<label><input type="checkbox" checked name="products[]" value="' + checkboxValue + '">' + productName + '</label>');
    });

        var vdata = { pro_id: checkedValues };
        console.log(vdata);
      
       $.ajax({
         url: "{{ url('getCatlogs')}}",
         type:"get",
         dataType: "json",
         success: function(data){
            $.each(data, function(key, val) {
            // console.log(val.id);
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
        $("#append_pro").empty();
        var firstCheckboxPrice = null; 
        $('input[name="pro_id[]"]:checked').each(function(index) {
            var checkboxValue = $(this).val();
            var productName = $(this).closest('tr').find('td:eq(1)').text();
           // alert(checkboxValue);
            pro_data  = {
                "id": checkboxValue,
                "name" : productName
            }
            allcheckboxdata.push(pro_data);
        if (index === 0) {
            var checkboxValue = $(this).val();
            var productName = $(this).closest('tr').find('td:eq(1)').text(); 
            var price = $(this).closest('tr').find('td:eq(2)').text(); 
            var sku = $(this).closest('tr').find('td:eq(3)').text(); 
            var publiish_date = $(this).closest('tr').find('td:eq(4)').text(); 
            var image = $(this).closest('tr').find('td:eq(6) img').attr('src');
            var status = $(this).closest('tr').find('td:eq(7)').text(); 
            firstCheckboxPrice = price; 
            var productData = {
                id: checkboxValue,
                price:price,
                productName: productName,
                sku: sku,
                publiish_date: publiish_date,
                image: image,
                status: status
            };
            onecheckboxdata.push(productData);
          
             allpreFieldData(onecheckboxdata, allcheckboxdata);
           // return false;
        }
    });
    
    function allpreFieldData(onecheckboxdata,allcheckboxdata){
    console.log(allcheckboxdata);
       id  = onecheckboxdata[0].id;
       vdata = {id:id};
        $.ajax({
         url: "{{ url('catalogData')}}",
         type:"get",
         data:vdata,
         dataType: "json",
         success: function(data){
            
            id = data.data.id;
            name = data.data.name;
            status = data.data.status;
            description = data.data.short_description;
            sku = data.data.sku;
            price = data.data.price;
            weight = data.data.weight;
            sale_price = data.data.sale_price;
            length = data.data.dimensions.length;
            width = data.data.dimensions.width;
            height = data.data.dimensions.height;

            document.getElementById("title").value = name;
            document.getElementById("content").value = description;
            document.getElementById("sku").value = sku;
            document.getElementById("base_price").value = price;
            document.getElementById("weight").value = weight;
            document.getElementById("sale_price").value = sale_price;
            document.getElementById("length").value = length;
            document.getElementById("width").value = width;
            document.getElementById("height").value = height;
            document.getElementById("status").value = status;
            allcheckboxdata.forEach(function(item) {
    // Create a checkbox element
    
    var container = document.getElementById("check_box");
    var checkbox = document.createElement('input');
    checkbox.type = 'checkbox';
    checkbox.id = item.id; // Set the id attribute
    // Set the label text
    var label = document.createElement('label');
    label.htmlFor = item.id;
    label.appendChild(document.createTextNode(item.name));

    // Append the checkbox and label to the container element
    container.appendChild(checkbox);
    container.appendChild(label);
    container.appendChild(document.createElement('br')); // Add line break for separation
});



              $('#addCatalog').modal('show');
             
         }
       });
    
    
    }
     
}

        </script>

<script>
    document.getElementById('fetchBtn').addEventListener('click', function() {
        window.location.reload();
    });
</script>




@endsection

@section('custom_js')