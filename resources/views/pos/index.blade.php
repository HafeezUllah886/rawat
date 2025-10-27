<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
   {{--  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}"> --}}
   <link href="{{ asset('assets/plugins/bootstrap5.0/bootstrap.min.css') }}" rel="stylesheet" >
    <link rel="stylesheet" href="{{ asset('assets/plugins/selectize/selectize.min.css') }}">
    <title>POS</title>
    <style>
        .right{
            right: 0 !important;
            top: 0 !important;
            position: absolute !important;
            margin-top: 0 !important;
        }

        /* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
.productsList tbody tr td{
    margin: 0;
    padding: 0 !important;
    border:none;
}
.productsList tbody tr{
    border: none;
}
.pos-input
{
    background: transparent;
    outline: none;
    border: none;
    text-align: center;
}
    </style>
</head>
<body>
    <div class="row " style="height: 100vh; margin:0; overflow:hidden; background:rgb(194, 194, 194);">
        <div class="col-6 h-100 d-flex flex-column" style="background:#fff;">

            <div class="row flex-grow-1" style=" overflow-y:auto;">
                <div class="col-12">
                    <form method="post" id="productsForm">
                        <table class="table productsList" >
                            <thead>
                                <th width="50%">Product</th>
                            {{--  <th width="10%">Size</th>
                                <th width="15%">Color</th> --}}
                                <th width="10%">Price</th>
                                <th width="10%">Dis</th>
                                <th width="20%" class="text-center">Qty</th>
                                <th >Amount</th>
                                <th ></th>
                            </thead>
                            <tbody id="productsList">

                            </tbody>
                        </table>
                </div>
            </div>

            <div class="row" style="height:80px;border-top:1px solid gray;">
                <div class="col-12">
                    <table style="width: 100%">
                        <tbody><tr>
                            <td>Items</td>
                            <td width="20%" class="text-center text-dark"><span id="rowQty">0</span>(<span id="numQty">0</span>)</td>
                            <td>Discount</td>
                            <td width="20%"><input type="number" class="pos-input" step="any" oninput="updateAmounts()" value="0" name="discount" id="discount"></td>
                            <td>Total</td>
                            <td width="20%"><input type="number" class="pos-input" style="background:#97ffbf;" readonly="" step="any" value="0.00" name="total" id="total"></td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <div class="d-grid">
                                    <button class="btn btn-info btn-lg btn-flat btn-block" type="submit" id="continueBtn">Continue</button>
                                </div>
                            </td>
                        </tr>
                    </tbody></table>
                </form>
                </div>
            </div>
        </div>
        <div class="col-6 h-100 d-flex flex-column" style="background:#fff;">
            <div class="row pt-2" style="height: 50px;">
                <div class="col-12">
                    <select name="product" class="selectize" placeholder="Search Product or Scan Barcode" id="selectize">
                        <option value=""></option>
                        @foreach ($products as $product)
                            @if($product->stock > 0)
                                <option value="{{ $product->id }}">{{ $product->code }} | {{ $product->name }} </option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            {{-- <div class="row pt-2 g-1" style="height: 50px;">
                <div class="col-4">
                    <button class="btn btn-outline-success w-100" onclick="allProducts()">All Products</button>
                </div>
                <div class="col-4">
                    <button class="btn btn-outline-info w-100" data-bs-toggle="modal" data-bs-target="#categoryModal">Categories</button>
                </div>
                <div class="col-4">
                <button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#brandModal">Brands</button>
                </div>
            </div> --}}
            <div class="row flex-grow-1" style="overflow-y:auto;" >
                <div class="col-12">
                    <div class="row" id="sideBar">
                    </div>
                </div>
            </div>
            <div class="row" style="height: 30px;">
                <div class="col-12">
                    <table style="width: 100%">
                        <tr>
                            <td colspan="2">
                                <div class="d-grid">
                                    <button class="btn btn-info btn-sm btn-flat btn-block" id="fullScreen">Full Screen</button>
                                </div>
                            </td>
                            <td colspan="2">
                                <div class="d-grid">
                                    <a class="btn btn-primary btn-sm btn-flat btn-block" href="{{ url('/sale/printlast') }}" >Print Last Sale</a>
                                </div>
                            </td>
                            <td colspan="2">
                                <div class="d-grid">
                                    <a class="btn btn-dark btn-sm btn-flat btn-block" href="{{ url('/sale/history') }}">Exit POS</a>
                                </div>
                            </td>
                        </tr>
                    </tbody></table>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="modal fade"  id="categoryModal" tabindex="-1" aria-labelledby="categoryModal" aria-hidden="true">
        <div class="modal-dialog modal-lg  right" style="width:100%;">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="categoryModalLabel">Select Category</h5>
            </div>
            <div class="modal-body">
                @foreach ($categories as $cats)
                    <button type="button" class="btn btn-info btn-lg" onclick="findByCategory({{ $cats->id }})">
                        {{ $cats->cat }} <span class="badge bg-secondary">{{ $cats->products->count() }}</span>
                    </button>
                @endforeach
            </div>
          </div>
        </div>
    </div>
    <div class="modal fade"  id="brandModal" tabindex="-1" aria-labelledby="brandModal" aria-hidden="true">
        <div class="modal-dialog modal-lg right" style="width:100%;">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="categoryModalLabel">Select Brand</h5>
            </div>
            <div class="modal-body">
                @foreach ($brands as $brand)
                    <button type="button" class="btn btn-primary btn-lg" onclick="findByBrand({{ $cats->id }})">
                        {{ $brand->name }} <span class="badge bg-secondary">{{ $brand->products->count() }}</span>
                    </button>
                @endforeach
            </div>
          </div>
        </div>
    </div>
 --}}
    <div class="modal fade"  id="detailsModal" tabindex="-1" aria-labelledby="detailsModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="width:100%;">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="categoryModalLabel">Sale Details</h5>
            </div>
            <div class="modal-body">
                <form method="post" id="detailsForm">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="billAmount">Total Bill</label>
                                <input type="number" readonly class="form-control" id="billAmount" name="billAmount">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="row g-1">
                                <div class="col-3"><button type="button" onclick="addToReceived(5)" class="btn btn-success btn-sm w-100">5</button></div>
                                <div class="col-3"><button type="button" onclick="addToReceived(10)" class="btn btn-success btn-sm w-100">10</button></div>
                                <div class="col-3"><button type="button" onclick="addToReceived(20)" class="btn btn-success btn-sm w-100">20</button></div>
                                <div class="col-3"><button type="button" onclick="addToReceived(50)" class="btn btn-success btn-sm w-100">50</button></div>
                                <div class="col-3"><button type="button" onclick="addToReceived(100)" class="btn btn-success btn-sm w-100">100</button></div>
                                <div class="col-3"><button type="button" onclick="addToReceived(500)" class="btn btn-success btn-sm w-100">500</button></div>
                                <div class="col-3"><button type="button" onclick="addToReceived(1000)" class="btn btn-success btn-sm w-100">1000</button></div>
                                <div class="col-3"><button type="button" onclick="addToReceived(5000)" class="btn btn-success btn-sm w-100">5000</button></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="received">Received Amount</label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control" autofocus id="received" oninput="changeReceived()" placeholder="Enter Received Amount" aria-label="Enter Received Amount" aria-describedby="basic-addon2">
                                    <span class="input-group-text btn-warning" id="basic-addon2">0.00</span>
                                </div>
                            </div>

                    </div>
                    <div class="col-3 g-1">
                        <div class="form-group">
                            <label for="account">Account</label>
                            <select name="account" id="account" class="form-control">
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="datetime-local" value="{{ now() }}" name="date" id="date" class="form-control">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="customer">Customer</label>
                                <select name="customer" id="customer" class="form-control">
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="notes">Notes</label>
                                <textarea name="notes" id="notes" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>

            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-success w-100 saveBtn">Save</button>
                    </div>
                </div>
            </form>
            </div>
          </div>
        </div>
    </div>

  {{--   <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script> --}}
  <script src="{{ asset('assets/plugins/bootstrap5.0/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>

    <script src="{{ asset('assets/plugins/selectize/selectize.min.js') }}"></script>
    <script>
        $(function () {
            $('.selectize').selectize({
            onInitialize: function() {
                this.focus();
            },
            onType: function(str) {
                if (str.slice(-1) === ' ' || str.slice(-1) === '\n') {
                if (this.currentResults.items.length === 1) {
                    var value = this.currentResults.items[0].id;
                    this.addItem(value);
                }
            }
            },
            onChange: function(value) {
                if (!value.length) return;
                getSingleProduct(value); // call the function with the selected value
                this.clear(); // clear the selected value
                this.focus(); // refocus on the selectize input
            },
           /* onDropdownOpen: function() {
                if (!this.lastQuery.length) {
                this.close();
            }
            } */
        });
        });
        $(document).ready(function() {
  $('#fullScreen').click(function() {
    var element = document.documentElement;
    if (element.requestFullscreen) {
      if (document.fullscreenElement) {
        document.exitFullscreen();
        $(this).text("Full Screen");
      } else {
        element.requestFullscreen();
        $(this).text("Exit Full Screen");
      }
    } else if (element.mozRequestFullScreen) {
      if (document.mozFullScreenElement) {
        document.mozCancelFullScreen();
        $(this).text("Full Screen");
      } else {
        element.mozRequestFullScreen();
        $(this).text("Exit Full Screen");
      }
    } else if (element.webkitRequestFullscreen) {
      if (document.webkitFullscreenElement) {
        document.webkitExitFullscreen();
        $(this).text("Full Screen");
      } else {
        element.webkitRequestFullscreen();
        $(this).text("Exit Full Screen");
      }
    } else if (element.msRequestFullscreen) {
      if (document.msFullscreenElement) {
        document.msExitFullscreen();
        $(this).text("Full Screen");
      } else {
        element.msRequestFullscreen();
        $(this).text("Exit Full Screen");
      }
    }
  });
});
        /* allProducts(); */
        function allProducts()
        {
            spinner();
            $.ajax({
                url : "{{ url('/pos/allProducts') }}",
                method : "GET",
                success : function (data){
                    sidebar(data);
                }
            });
        }
        function findByCategory(id)
        {
            spinner();
            $.ajax({
                url : "{{ url('/pos/byCategory/') }}/"+id,
                method : "GET",
                success : function (data){
                    sidebar(data);
                }
            });

            $("#categoryModal").modal('hide');
        }
        function findByBrand(id)
        {
            spinner();
            $.ajax({
                url : "{{ url('/pos/byBrand/') }}/"+id,
                method : "GET",
                success : function (data){
                    sidebar(data);
                }
            });
            $("#brandModal").modal('hide');
        }
           function sidebar(data)
           {

            var sidebarHTML = "";
            var image = "";
        data.forEach(function (s) {
            image = "{{asset('')}}" + s.pic;
           if(s.pic == null)
           {
            image =  "{{asset('assets/images/no_image.jpg')}}";
           }
            sidebarHTML += '<div class="col-12 mt-1 g-1">';
            sidebarHTML += '<div class="card border-success" onclick="getSingleProduct('+s.id+')">';
           /*  sidebarHTML += '<img src="'+image+'" class="card-img-top" style="width:100%;height:130px;">'; */
            sidebarHTML += '<div class="card-body">';
           /*  sidebarHTML += '<h6 class="card-title">'+s.name+'</h5>'; */
            /* sidebarHTML += '<h6 class="card-title">Rs. '+s.price+' | Q: '+ s.stock +'</h5>';
            sidebarHTML += '<p class="card-subtitle text-muted" style="font-size:15px;">Bike: '+s.bike +'</p>';
            sidebarHTML += '<p class="card-subtitle text-muted" style="font-size:15px;">Model: '+s.model +'</p>';
            sidebarHTML += '<p class="card-subtitle text-muted" style="font-size:15px;">Brand: '+s.brand +'</p>'; */
            sidebarHTML += '<h1 class="card-card-title" style="font-size:50px;">'+s.name +'</h1>';
            sidebarHTML += '<h1 class="card-card-title" style="font-size:50px;">Code: '+s.code +'</h1>';
            sidebarHTML += '<h1 class="card-card-title" style="font-size:50px;">Rak No: '+s.model +'</h1>';
            sidebarHTML += '<h1 class="card-card-title" style="font-size:50px;">Bike: '+s.bike +'</h1>';
            sidebarHTML += '<h1 class="card-card-title" style="font-size:50px;">Brand: '+s.brand +'</h1>';
            sidebarHTML += '<h1 class="card-card-title text-center" style="font-size:90px;">'+s.price +'</h1>';
            sidebarHTML += '</div>';
            sidebarHTML += '</div>';
            sidebarHTML += '</div>';

        });


    $("#sideBar").html(sidebarHTML);
            }

            function spinner()
            {
                var sideBarHTML = "";
                sideBarHTML += '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';
                $("#sideBar").html(sideBarHTML);
            }
            var existingProducts = [];
           function getSingleProduct(id)
            {
                var productsListHTML = '';
                $.ajax({
                url : "{{ url('/pos/getSingleProduct/') }}/"+id,
                method : "GET",
                success : function (data)
                {
                    if (!existingProducts.includes(data.product.id))
                    {
                        if(data.stock !== 0)
                        {
                            productsListHTML += '<tr id="row_'+data.product.id+'">';
                                productsListHTML += '<td><p>'+data.product.name + " | " + data.product.bike + " | " + data.product.model + " | " + data.product.brand +'<br><span style="font-size:10px;">'+data.product.code+'</span></p></td>';
                               /*  productsListHTML += '<td>'+data.product.size+'</td>';
                                productsListHTML += '<td>'+data.product.color+'</td>'; */
                                productsListHTML += '<td><input type="number" name="price[]" step="any" id="price_'+data.product.id+'" class="form-control form-control-sm bg-white text-dark" style="background: transparent;outline: none;border: none;text-align: center;padding:0;" readonly value="'+data.product.price+'"></td>';
                                productsListHTML += '<td><input type="number" name="discount_[]" step="any" id="discount_'+data.product.id+'" oninput="updateDiscount('+data.product.id+')" class="form-control form-control-sm bg-white text-dark" style="background: transparent;outline: none;border: none;text-align: center;padding:0;" value="0"></td>';
                                productsListHTML += '<td class="text-center">';
                                    productsListHTML += '<div class="input-group">';
                                        productsListHTML += '<span class="input-group-text btn btn-danger btn-sm" onclick="decreaseQty('+data.product.id+')">-</span>';
                                        productsListHTML += '<input type="number" name="qty[]" max="'+data.stock+'" required oninput="updateQty('+data.product.id+')" id="qty_'+data.product.id+'" class="form-control form-control-sm text-center" value="1">';
                                        productsListHTML += '<span class="input-group-text btn btn-success btn-sm" onclick="increaseQty('+data.product.id+')">+</span>';
                                    productsListHTML += '</div>';
                                    productsListHTML += '<span>'+data.stock+'</span>';
                                productsListHTML += '</td>';
                                productsListHTML += '<td><input type="number" name="amount[]" step="any" id="amount_'+data.product.id+'" class="form-control form-control-sm bg-white text-dark" style="background: transparent;outline: none;border: none;text-align: center;padding:0;" readonly value="'+data.product.price+'"></td>';
                                productsListHTML += '<td><span class="btn btn-danger btn-sm" onclick="deleteRow('+data.product.id+')">X</span></td>';
                                productsListHTML += '<input type="hidden" value="'+data.product.id+'" name="id[]">';
                            productsListHTML += '</tr>';
                            $("#productsList").prepend(productsListHTML);
                            existingProducts.push(data.product.id);
                            singleSideBar(data);
                            updateAmounts();
                        }
                        else
                        {
                            alert("Stock Not Available");
                        }
                    }
                    else
                    {
                        var existingQty = $("#qty_"+id).val();
                        existingQty++;
                        $("#qty_"+id).val(existingQty);
                        updateQty(id);

                    }
                }
            });
            }

            function singleSideBar(data)
            {
                var sidebarHTML = '';
            sidebarHTML += '<div class="col-12 mt-1 g-1">';
            sidebarHTML += '<div class="card border-success">';
            sidebarHTML += '<div class="card-body">';
            sidebarHTML += '<h1 class="card-card-title" style="font-size:50px;">'+data.product.name +'</h1>';
            sidebarHTML += '<h1 class="card-card-title" style="font-size:50px;">Code: '+data.product.code +'</h1>';
            sidebarHTML += '<h1 class="card-card-title" style="font-size:50px;">Rak No.: '+data.product.model +'</h1>';
            sidebarHTML += '<h1 class="card-card-title" style="font-size:50px;">Bike: '+data.product.bike +'</h1>';
            sidebarHTML += '<h1 class="card-card-title" style="font-size:50px;">Brand: '+data.product.brand +'</h1>';
            sidebarHTML += '<h1 class="card-card-title text-center" style="font-size:90px;">'+data.product.price +'</h1>';
            sidebarHTML += '</div>';
            sidebarHTML += '</div>';
            sidebarHTML += '</div>';
            $("#sideBar").html(sidebarHTML);
            }

            function updateQty(id){
        $("input[id^='qty_']").each(function() {
                var $input = $(this);
                var currentValue = parseInt($input.val());
                var maxAttributeValue = parseInt($input.attr("max"));

                if (currentValue > maxAttributeValue) {
                    alert(maxAttributeValue+ " Available in stock");
                    $input.val(maxAttributeValue);
                }
                if (currentValue < 1) {
                    $input.val(1);
                }
            });
        var existingQty = $("#qty_"+id).val();
        var amount = existingQty * ($("#price_"+id).val() - $("#discount_"+id).val());
        $("#amount_"+id).val(amount.toFixed(2));
        updateAmounts();
    }

    function updateDiscount(id){
        var discount = $("#discount_"+id).val();
        var amount = ($("#price_"+id).val() - discount) * $("#qty_"+id).val();
        $("#amount_"+id).val(amount.toFixed(2));
        updateAmounts();
    }

    function increaseQty(id)
    {
        var existingQty = $("#qty_"+id).val();
        existingQty++;
        $("#qty_"+id).val(existingQty);
        updateQty(id);
    }
    function decreaseQty(id)
    {
        var existingQty = $("#qty_"+id).val();
        existingQty--;
        $("#qty_"+id).val(existingQty);
        updateQty(id);
    }

    function deleteRow(id){
        $("#row_"+id).remove();
        existingProducts = $.grep(existingProducts, function(value) {
                return value !== id;
            });
            updateAmounts();
    }

    function updateAmounts(){
        var subTotal = 0;
        $("input[id^='amount_']").each(function() {
                    var inputId = $(this).attr('id');
                    var inputValue = $(this).val();
                    subTotal += parseFloat(inputValue);
                });

            var discount = $("#discount").val();
            var gTotal = (parseFloat(subTotal) - parseFloat((discount == '') ? 0 : discount));
            $("#total").val(gTotal.toFixed(2));

            var count = $("[id^='row_']").length;
            $("#rowQty").html(count);

            var numQty = 0;
                // Select input fields whose id starts with "qty_"
                $("input[id^='qty_']").each(function() {
                    var value = parseFloat($(this).val());
                    if (!isNaN(value)) {
                        numQty += value;
                    }
                });

                $("#numQty").html(numQty);

    }
    /////////////////////////// Submit Products Form /////////////////////////
    $("#productsForm").submit(function (e){
        e.preventDefault();
        if(existingProducts.length > 0)
        {
            $("#billAmount").val($("#total").val());
            $("#detailsModal").modal("show");
            $('#detailsModal').on('shown.bs.modal', function () {
            $(this).find('input:visible:enabled:not([readonly]):first').focus();
            });

        }
        else
        {
            alert("Please add at least one product");
        }

    });

    function changeReceived()
    {
        var received = $("#received").val();
        var billAmount = $("#billAmount").val();
        $("#basic-addon2").text(received - billAmount);
    }

    function addToReceived(amount)
    {
        var existingAmount = $("#received").val();
        var newAmount = Number(amount) + Number(existingAmount);

        $("#received").val(newAmount);
        changeReceived();
    }

    ////////////////// Save ////////////////////////
    $("#detailsForm").submit(function (e) {
        e.preventDefault();
        $(".saveBtn").prop("disabled", true);
        $(".saveBtn").css('display', 'none');
     

        var data1 = $("#productsForm").serialize();
        var data2 = $("#detailsForm").serialize();

        var combinedData = data1 + '&' + data2;

        $.ajax({
            url: "{{ url('/pos/save') }}",
            method: "GET",
            data: combinedData,
            success: function (response)
            {
                window.open("{{ url('/sale/printlast') }}", "_self")
            }
        });
    });
    </script>
</body>
</html>
