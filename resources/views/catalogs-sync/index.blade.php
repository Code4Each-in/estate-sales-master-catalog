@extends('layouts.app')
@section('title', 'Catalogs Sync')
@section('sub-title', 'Catalogs Sync')
@section('content')




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
                    console.log(data);
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
                            return '<span class="badge rounded-pill bg-warning">Assign</span>';
                         
                    }
                }
            ]
        });
    });
</script>




@endsection

@section('custom_js')