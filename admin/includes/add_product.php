<?php
if (isset($_POST['add_product'])) {
    addProduct();
}
?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Add Product</h6>
    </div>
    <div class="card-body">
        <form class="row needs-validation" action="" method="post" enctype="multipart/form-data" novalidate>
            <div class="col-md-6">
                <div class="form-group productname">
                    <label for="title">Product Name</label>
                    <input class="form-control" type="text" name="product_name" pattern="\S+.*" required>
                    <div class="invalid-feedback">
                        Please enter a product name
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="category">Category</label>
                        <select class="custom-select form-control" name="product_category" id="category">
                            <?php
                            $result = selectAllCategories();

                            while ($row = $result->fetch_assoc()) {
                                $category_id = $row['category_id'];
                                $category_title = $row['category_title'];

                                echo "<option value='{$category_id}'>{$category_title}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="brand">Brand</label>
                        <select class="custom-select form-control" name="product_brand" id="brand">
                            <?php
                            $result = selectAllBrands();

                            while ($row = $result->fetch_assoc()) {
                                $brand_id = $row['brand_id'];
                                $brand_title = $row['brand_title'];

                                echo "<option value='{$brand_id}'>{$brand_title}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="product_quantity">Quantity</label>
                        <input class="form-control product_quantity" type="text" name="product_quantity" pattern="[0-9]+([,\.][0-9]+)?" required>
                        <div class="invalid-feedback">
                            Please enter a quantity
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="product_price">Price</label>
                        <input class="form-control product_price" type="text" name="product_price" pattern="[0-9]+([,\.][0-9]+)?" required>
                        <div class="invalid-feedback">
                            Please enter a price
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="product_description">Description</label>
                    <textarea class="form-control" name="product_description" id="" cols="30" rows="2"></textarea>
                </div>
                <div class="form-group">
                    <label>Upload Thumbnail</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="imgInp" name="image" accept='image/*' required>
                            <label class="custom-file-label" for="imgInp">Choose file...</label>
                        </div>
                    </div>
                    <img id='img-upload' class="w-50 h-50" />
                </div>
                <div class="form-group">
                    <label>Upload Images</label>
                    <div class="input-group mb-3">
                        <input type="file" class="custom-file-input" id="uploadFile" name="uploadFile[]" multiple accept='image/*' required>
                        <label class="custom-file-label" for="imgInp">Choose file...</label>
                    </div>
                    <div id="image_preview"></div>
                </div>
                <div class="form-group">
                    <button type="submit" name="add_product" class="btn btn-primary add-product">
                        <i class="fa fa-plus fa-lg"></i> Add Product
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <label class="d-block"><strong>Product Variants</strong><em class="ml-2">A single variant will be created automatically unless you enable the multiple variant option.</em></label>
                <div class="border px-3 pt-3">
                    <div class="form-group">
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input variant-chk" type="checkbox" name="product_variants">This product has multiple variants
                            </label>
                        </div>
                    </div>
                    <div class="variant_field" style="display: none;">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="variant_name">Variant Option</label>
                                <input class="form-control variant_name" type="text" name="variant_name" pattern="\S+.*">
                                <div class="invalid-feedback">
                                    Please enter a variant name
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="variant_option_values">Option Values</label>
                            <input type="text" data-role="tagsinput" name="variant_option_values" value="jQuery,Script,Net">
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.19.0/jquery.validate.min.js"></script>
        <script>
            $(document).ready(function() {
                $('.variant-chk').change(function() {
                    if (this.checked) {
                        $(".variant_field").show();
                        $(".variant_name").prop('required', true);
                        return;
                    }
                    $(".variant_field").hide();
                    $(".variant_name").prop('required', false);
                });
                $(".product_quantity").keypress(function(e) {
                    //if the letter is not digit then display error and don't type anything
                    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                        //display error message
                        return false;
                    }
                });

                $('input[name ="product_name"]').keyup(function() {
                    $(".productname .invalid-feedback").hide();
                    var product_name = $('input[name ="product_name"]').val();
                    $.ajax({
                        url: 'includes/product_check.php',
                        method: 'post',
                        data: {
                            product_name: product_name,
                        },
                        success: function(data) {
                            var obj = JSON.parse(data);

                            if (obj.success) {
                                if (obj.product == 'exists') {
                                    event.preventDefault();
                                    event.stopPropagation();
                                    var invalid_product = $('input[name ="product_name"]').get(0);
                                    invalid_product.setCustomValidity("Product name already exists.");
                                    $(".productname .invalid-feedback").text("Product name already exists.");
                                    $(".productname .invalid-feedback").show();
                                    return;
                                } else {
                                    var invalid_product = $('input[name ="product_name"]').get(0);
                                    invalid_product.setCustomValidity("");
                                }
                            }
                        },
                    });
                });
                $(".product_price").keydown(function(event) {


                    if (event.shiftKey == true) {
                        event.preventDefault();
                    }

                    if ((event.keyCode >= 48 && event.keyCode <= 57) ||
                        (event.keyCode >= 96 && event.keyCode <= 105) ||
                        event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 ||
                        event.keyCode == 39 || event.keyCode == 46 || event.keyCode == 190) {

                    } else {
                        event.preventDefault();
                    }

                    if ($(this).val().indexOf('.') !== -1 && event.keyCode == 190)
                        event.preventDefault();
                    //if a decimal has been added, disable the "."-button

                });

                $("#uploadFile").change(function() {
                    var fileName = $(this).val();
                    var idxDot = fileName.lastIndexOf(".") + 1;
                    var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
                    if (extFile == "jpg" || extFile == "jpeg" || extFile == "png") {
                        //TO DO
                    } else {
                        alert("Only jpg/jpeg and png files are allowed!");
                        return;
                    }


                    $('#image_preview').html("");
                    var total_file = document.getElementById("uploadFile").files.length;


                    for (var i = 0; i < total_file; i++) {
                        $('#image_preview').append("<img src='" + URL.createObjectURL(event.target.files[i]) + "'>");
                    }


                });

                $(document).on('change', '.btn-file :file', function() {


                    var input = $(this),
                        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
                    input.trigger('fileselect', [label]);
                });

                $('.btn-file :file').on('fileselect', function(event, label) {

                    var input = $(this).parents('.input-group').find(':text'),
                        log = label;

                    if (input.length) {
                        input.val(log);
                    } else {
                        if (log) alert(log);
                    }

                });

                function readURL(input) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();

                        reader.onload = function(e) {
                            $('#img-upload').attr('src', e.target.result);
                        }

                        reader.readAsDataURL(input.files[0]);
                    }
                }

                $("#imgInp").change(function() {
                    var fileName = $(this).val();
                    var idxDot = fileName.lastIndexOf(".") + 1;
                    var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
                    if (extFile == "jpg" || extFile == "jpeg" || extFile == "png") {
                        //TO DO
                    } else {
                        alert("Only jpg/jpeg and png files are allowed!");
                        return;
                    }

                    readURL(this);
                });
            });
        </script>