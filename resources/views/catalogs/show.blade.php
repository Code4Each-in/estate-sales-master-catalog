@extends('layouts.app')
@section('title', 'Catalog')
@section('sub-title', 'Show Catalog')
@section('content')
<div id="loader">
    <div class="spinner-border text-warning loader-spinner"  role="status">
                <span class="visually-hidden">Loading...</span>
    </div>
</div>
<section class="section catalog">
    <div class="row">
        <!-- Left side columns -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">

                    <div class="row mb-1 mt-4">
                        <label for="" class="col-sm-3">Catalog Title:</label>
                        <div class="col-sm-9">
                            {{$catalog->title ?? 'NA'}}
                        </div>
                    </div>

                    <div class="row mb-1 mt-4">
                        <label for="" class="col-sm-3">SKU:</label>
                        <div class="col-sm-9">
                            {{$catalog->sku ?? 'NA' }}
                        </div>
                    </div>

                    <div class="row mb-1 mt-4">
                        <label for="" class="col-sm-3">Content:</label>
                        <div class="col-sm-9">
                        {{ str_replace('&nbsp;', '', strip_tags($catalog->content)) ?? 'NA' }}
                        </div>
                    </div>

                    <div class="row mb-1 mt-4">
                        <label for="" class="col-sm-3">Base Price:</label>
                        <div class="col-sm-9">
                            {{$catalog->base_price }}
                        </div>
                    </div>
                    @if($catalog->status == 'publish')
                    <div class="row mb-1 mt-4">
                        <label for="" class="col-sm-3">Publish Date:</label>
                        <div class="col-sm-9">
                            {{$catalog->publish_date ?? 'NA'}}
                        </div>
                    </div>
                    @endif

                    <div class="row mb-1 mt-4">
                        <label for="" class="col-sm-3">Status:</label>
                        <div class="col-sm-9">
                            @if($catalog->status == 'draft')
                            <span class="badge rounded-pill bg-warning">Draft</span>
                            @else
                            <span class="badge rounded-pill  bg-success">Publish</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-1 mt-4">
                        <label for="" class="col-sm-3">Weigth:</label>
                        <div class="col-sm-9">
                            {{$catalog->weight ?? 'NA' }}
                        </div>
                    </div>
                    <div class="row mb-1 mt-4">
                        <label for="" class="col-sm-3">Color:</label>
                        <div class="col-sm-9">
                            {{$catalog->color ?? 'NA' }}
                        </div>
                    </div>
                    <div class="row mb-1 mt-4">
                        <label for="" class="col-sm-3">Sale Price:</label>
                        <div class="col-sm-9">
                            {{$catalog->sale_price ?? 'NA' }}
                        </div>
                    </div>
                    <div class="row mb-1 mt-4">
                        <label for="" class="col-sm-3">Brand:</label>
                        <div class="col-sm-9">
                            {{$catalog->brand ?? 'NA' }}
                        </div>
                    </div>
                    <div class="row mb-1 mt-4">
                        <label for="" class="col-sm-3">Length:</label>
                        <div class="col-sm-9">
                            {{$catalog->length ?? 'NA' }}
                        </div>
                    </div>

                    <div class="row mb-1 mt-4">
                        <label for="" class="col-sm-3">Width:</label>
                        <div class="col-sm-9">
                            {{$catalog->width ?? 'NA' }}
                        </div>
                    </div>

                    <div class="row mb-1 mt-4">
                        <label for="" class="col-sm-3">Height:</label>
                        <div class="col-sm-9">
                            {{$catalog->height ?? 'NA' }}
                        </div>
                    </div>

                    <div class="row mb-1 mt-4">
                        <label for="" class="col-sm-3">Image:</label>
                        <div class="col-sm-9">
                            @if ($catalog->image == null)
                            No Uploaded Image Found
                            @else
                            <img src="{{ asset('storage').'/'.$catalog->image }}" height="50" width="70">
                            @endif
                        </div>
                    </div>

                </div>
            </div>
            <div class="card-title">
                <h4>Products</h4>
            </div>
            <div class="card">
                <div class="card-body">
                    <!-- Table with stripped rows -->
                    <div class="box-header with-border my-4" id="filter-box">
                        <div class="box-body table-responsive" style="margin-bottom: 5%">
                            <table class="datatable table table-striped my-2" id="product_table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <!-- <th scope="col">Name</th> -->
                                        <th scope="col">Name</th>
                                        <th scope="col">Slug</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Regular Price</th>
                                        <th scope="col">Sale Price</th>
                                        <th scope="col">Publish Date</th>
                                        <th scope="col">Stock</th>
                                        <th scope="col">Image</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach($products as $index => $product) 
                                    <tr>
                                        <th scope="row">{{ $product['id'] }}</th>

                                        <td>{{ucfirst($product['name']) ?? 'NA'}}</td>
                                        <td>{{ isset($product['sku']) && $product['sku'] ? $product['sku'] : 'NA' }}</td>
                                        <td>{{$product['price'] ?? 'NA'}}</td>
                                        <td>{{$product['regular_price'] ?? 'NA'}}</td>
                                        <td>{{ isset($product['sale_price']) && $product['sale_price'] ? $product['sale_price'] : 'NA' }}</td>
                                        <td>{{isset($product['publish_date']) && $product['publish_date'] ? $product['publish_date'] : 'NA'}}</td>
                                        <td>{{isset($product['stock']) && $product['stock'] ? $product['stock'] : 'NA'}}</td>
                                        <td>


                                            @if (isset($product['images']) && isset($product['images']['thumbnail']))
                                            @if (Str::startsWith($product['images']['thumbnail'], ['http://', 'https://']))
                                            <!-- External link -->
                                            <a href="{{ $product['images']['thumbnail'] }}" target="_blank">
                                                <img src="{{ $product['images']['thumbnail'] }}" height="40" width="70" alt="Catalog Image">
                                            </a>
                                            @else
                                            <i class="bi bi-file-earmark-image mr-1"></i>

                                            @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if($product['status'] == 'publish')
                                            <span class="badge rounded-pill bg-success">{{ucfirst($product['status'])}}</span>
                                            @else
                                            <span class="badge rounded-pill  bg-warning">{{ucfirst($product['status'])}}</span>
                                            @endif
                                        </td>
                                        <td>
                                        
                                            <a href="javascript:void(0)" onClick="openGalleryModal('{{ isset($product['gallery_images']) ? (json_encode($product['gallery_images'])) : '' }}')" class="btn btn-default-border">Gallery</a>
                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>

                            </table>
                        </div>
                    </div>
                    <!-- End Table with stripped rows -->
                </div>
            </div>

            <!-- // Product sale history -->
            <div class="card-title">
                <h4>Products Sale History</h4>
            </div>
            <div class="card">
                <div class="card-body">
                    <!-- Table with stripped rows -->
                    <div class="box-header with-border my-4" id="filter-box">
                        <div class="box-body table-responsive" style="margin-bottom: 5%">
                            <table class="datatable table table-striped my-2" id="sale_history_table">
                                <thead>
                                    <tr>
                                        <th scope="col">Id</th>
                                        <!-- <th scope="col">Name</th> -->
                                        <th scope="col">Name</th>
                                        <th scope="col">Sku</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Regular Price</th>
                                        <th scope="col">Sale Price</th>
                                        <th scope="col">Publish Date</th>
                                        <th scope="col">Stock</th>
                                        <th scope="col">Image</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                 <tbody   id="sale_history">

                                 </tbody>

                            </table>
                        </div>
                    </div>
                    <!-- End Table with stripped rows -->
                </div>
            </div>
             <!-- // End Product sale history -->






            <!-- <div class="card-title">
                <h4>Reports</h4>
            </div> -->
            <div class="card">

                <div class="card-body">
                    <div class="row">
                        <!-- Reports -->
                        <div class="col-12">

                            <div class="card-body">
                                <h5 class="card-title">Reports</h5>
                                <div>
                                   <p class="alert alert-info" style="color: red;">This graph originates from statistical data and will be made dynamic later.</p>
                                
                                  
                                </div>
                             
                                <!-- Line Chart -->
                               <div id="reportsChart"></div>
                            
                                <!-- End Line Chart -->
                            </div>
                        </div>
                        <!-- End Reports -->
                    </div>
                </div>
            </div>

            <!--start: Add users Modal -->
            <div class="modal fade" id="showGallery" tabindex="-1" aria-labelledby="role" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="role">Show Gallary</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div id="galleryContainer"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!--end: Add User Modal -->
    </div>
    </div>
</section>
@endsection

@section('custom_js')
<script>
    $(document).ready(function() {

        $('#product_table').DataTable({
            "order": []

        });
    });

    function openGalleryModal(galleryData) {
        var images = JSON.parse(galleryData);
        // Get the gallery container
        var galleryContainer = document.getElementById("galleryContainer");

        // Clear previous content
        galleryContainer.innerHTML = "";

        // Check if images array is null or empty
        if (images === null || images.length === 0) {
            var noImageText = document.createElement("p");
            noImageText.textContent = "No Image";
            galleryContainer.appendChild(noImageText);
        } else {
            // Display "Loading..." text while images are being loaded
            var loadingText = document.createElement("p");
            loadingText.textContent = "Loading...";
            galleryContainer.appendChild(loadingText);

            // Loop through each image URL and create img elements
            images.forEach(function(imageUrl) {
                // Create img element
                var img = document.createElement("img");

                // Set src attribute
                img.src = imageUrl;

                // Set alt attribute
                img.alt = "Gallery Image";

                // Add styling if needed
                img.style.maxWidth = "100px"; // Example styling

                // Append img element to the gallery container
                galleryContainer.appendChild(img);
            });

            // Hide the "Loading..." text after 2 seconds
            setTimeout(function() {
                galleryContainer.removeChild(loadingText);
            }, 2000);

        }
        // $('.alert-danger').html('');
        // $('#first_name').val('');
        $('#showGallery').modal('show');
    }
  
</script>


<script>

function historyGalary(galleryData) {
    var images = galleryData.split(','); // Split the string into an array of image URLs
    var galleryContainer = document.getElementById("galleryContainer");
    galleryContainer.innerHTML = ""; // Clear previous content

    if (images === null || images.length === 0) {
        var noImageText = document.createElement("p");
        noImageText.textContent = "No Image";
        galleryContainer.appendChild(noImageText);
    } else {
        var loadingText = document.createElement("p");
        loadingText.textContent = "Loading...";
        galleryContainer.appendChild(loadingText);

        images.forEach(function(imageUrl) { // Loop through each image URL
            var img = document.createElement("img");
            img.src = imageUrl.trim(); // Trim any whitespace around the URL
            img.alt = "Gallery Image";
            img.style.maxWidth = "100px";
            galleryContainer.appendChild(img);
        });

        setTimeout(function() {
            galleryContainer.removeChild(loadingText);
        }, 2000);
    }
       $('#showGallery').modal('show');
}
</script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
                         
<script>
  $(document).ready(function(){
    var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    var url = $(location).attr('href').split("/").splice(0, 5).join("/");
    var segments = url.split( '/' );
    var cata_id = segments[4];
    $('#loader').show(); 
    var vdata = {cata_id : cata_id, "_token": "{{ csrf_token() }}"};
    $.ajax({
      url: "{{url('show_pro_his')}}",
      type: "post",
      data: vdata,
      success: function(data){
        $('#loader').hide(); 
        var salePricesArray = [];
       // var years = [];
         var dates = [];
         var salePricesByYear =  [];
         
           $.each(data, function(key, val) {
        var orderDateParts = val.order_created_at.split(' ')[0].split('-');
        var year = orderDateParts[0];
        var month = orderDateParts[1];
        var date = val.order_created_at.split(' ')[0];

        salePricesByYear.push({year55: year, price: val.last_selling_price});
       // dates.push(months[parseInt(month) - 1] + ' ' + month);
        dates.push(months[parseInt(month) - 1] + ' ' + month + ',' + year);
            
            var statusBadge;
            if(val.status=="publish"){
              statusBadge = '<span class="badge rounded-pill bg-success">' + val.status + '</span>';
            } else {
              statusBadge = '<span class="badge rounded-pill bg-warning">' + val.status + '</span>';
            }
            var skuValue = val.sku == '' ? "NA" : val.sku;
            stock = val.stock == null ? "NA" : val.stock;


          var result = '<tr>' +
                         '<td>'+ val.id+ '</td>' +
                         '<td>'+ val.name+ '</td>' +
                         '<td>'+ skuValue + '</td>' +
                         '<td>'+ val.price+ '</td>' +
                         '<td>'+ val.regular_price+ '</td>' +
                         '<td>'+ val.sale_price+ '</td>' +
                         '<td>'+ val.publish_date+ '</td>' +
                         '<td>'+ stock + '</td>' +
                         '<td>'+ '<img src="' + val.images.thumbnail + '" alt="img" width="40" height="70">' + '</td>' +
                         '<td>'+ statusBadge + '</td>' +
                         '<td><a href="javascript:void(0)" onClick="historyGalary(\'' + val.gallery_images + '\')" class="btn btn-default-border">Gallery</a></td>' +
                       '</tr>';
                       $('#sale_history').append(result); 
        });
      
          var options = {
               series: [
                {
                    name: "Price(<b><span style='color: black'>$</span></b>)",
                    data: salePricesByYear.map(item =>  item.price)
                  // data : [700, 550, 600, 800, 200, 153, 190, 700]
                    
                }

    ],
          chart: {
          height: 350,
          type: 'line',
          zoom: {
            enabled: false
          }
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'straight'
        },
        title: {
          text: 'Product Trends by Month',
          align: 'left'
        },
        grid: {
          row: {
            colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
            opacity: 0.5
          },
        },
        xaxis: {
          categories: dates,
        // categories: ["Feb 01","Feb 02", "Mar 10", "April 15", "May 25", "June 30", "July 06", "Sep 11"]
        },
        yaxis: {
            
        title: {
            text: 'Price ($)'
        }
    }
        };
               // Populate series data from AJAX response

            const chart = new ApexCharts(document.querySelector("#reportsChart"), options);
           chart.render();
             if ($.fn.DataTable.isDataTable('#sale_history_table')) {
                    $('#sale_history_table').DataTable().destroy();
                }
                
                $('#sale_history_table').dataTable();
      }
    });
  });
</script>


   
@endsection