@extends('layouts.app')
@section('title', 'Catalogs Not Assigned')
@section('sub-title', 'Catalogs Not Assigned')
@section('content')


<div class="category-output">
    <ul>
    </ul>
</div>
<ul class="main-category-check">
    <li>
        <p>
            <label>
                <input type="checkbox" class="filled-in categoryCheck" name="category1" value="category1"/>
                <span>Master Catalog</span>
                <button class="btn btn-primary btn-sm float-right" id="assign_btn"  data-id="category1">Assign</button>
            </label>
        </p>
        <ul class="sub-category-check">
            <li>
                <p>
                    <label>
                        <input type="checkbox" class="filled-in" name="category1[]" value="sub_cate1_part1" />
                        <span>Product1</span>
                    </label>
                </p>
            </li>
            <li>
                <p>
                    <label>
                        <input type="checkbox" class="filled-in" name="category1[]"  value="sub_cate1_part2"/>
                        <span>Product2</span>
                    </label>
                </p>
            </li>
            <li>
                <p>
                    <label>
                        <input type="checkbox" class="filled-in" name="category1[]" value="sub_cate1_part3" />
                        <span>Product3</span>
                    </label>
                </p>
            </li>
            <li>
                <p>
                    <label>
                        <input type="checkbox" class="filled-in" name="category1[]" value="sub_cate1_part4"/>
                        <span>Product4</span>
                    </label>
                </p>
            </li>
            <li>
                <p>
                    <label>
                        <input type="checkbox" class="filled-in" name="category1[]" value="sub_cate1_part5"/>
                        <span>Product5</span>
                    </label>
                </p>
            </li>
        </ul>
    </li>
    <li>
        <p>
            <label>
                <input type="checkbox" class="filled-in categoryCheck" name="season2" />
                <span>Master Catalog  2</span>
                <button class="btn btn-primary btn-sm float-right" data-id="category2">Assign</button>
            </label>
        </p>
        <ul class="sub-category-check">
            <li>
                <p>
                    <label>
                        <input type="checkbox" class="filled-in" name="season2" />
                        <span>Product1</span>

                    </label>
                </p>
            </li>
            <li>
                <p>
                    <label>
                        <input type="checkbox" class="filled-in" name="season2" />
                        <span> Product2</span>
                    </label>
                </p>
            </li>
        </ul>
    </li>
    <li>
        <p>
            <label>
                <input type="checkbox" class="filled-in categoryCheck" name="season2" />
                <span> Master Category 3</span>
                <button class="btn btn-primary btn-sm float-right" data-id="category3">Assign</button>
            </label>
        </p>
        <ul class="sub-category-check">
            <li>
                <p>
                    <label>
                        <input type="checkbox" class="filled-in" name="season2" />
                        <span>Product1</span>
                    </label>
                </p>
            </li>
            <li>
                <p>
                    <label>
                        <input type="checkbox" class="filled-in" name="season2" />
                        <span>Product2</span>
                    </label>
                </p>
            </li>
        </ul>
    </li>
</ul>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all the assign buttons
        var assignButtons = document.querySelectorAll('.btn-primary');
        assignButtons.forEach(function(button) {
            // Add click event listener
            button.addEventListener('click', function() {
                var categoryElement = this.closest('li');
                var checkboxes = categoryElement.querySelectorAll('.sub-category-check input[type="checkbox"]');
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = true;
                });
            });
        });
    });
</script>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script>
$("#assign_btn").on('click', function() {
  //  ret = DetailsView.GetProject($(this).attr("#data-id"), OnComplete, OnTimeOut, OnError);
  id = $(this).data("id");
  var checkedSubCategories = [];
    
    $(this).closest('li').find('.sub-category-check input[type="checkbox"]').each(function() {
        checkedSubCategories.push($(this).val());
    });
  vdata =  { "_token": "{{ csrf_token() }}","id": id , "subCategories": checkedSubCategories}
  $.ajax({
    url: "{{ url('sbtnotAssigned') }}",
    type: "post",
    data: vdata,
    success:function(data){
        console.log(data);
    }
  });
});
</script>



@endsection

@section('custom_js')