<?php
$result = selectProductById();

while ($row = $result->fetch_assoc()) {
    $product_id = $row['product_id'];
    $category_title = $row['category_title'];
    $brand_title = $row['brand_title'];
    $product_name = $row['product_name'];
    $product_description = $row['product_description'];
    $product_quantity = $row['product_quantity'];
    $product_price = $row['product_price'];
    $product_image = $row['product_image'];
    $product_variant = $row['product_variant'] != "NONE" ? $row['product_variant'] : "";
}

$bool_show_variant = $product_variant != "" ? "" : "style='display:none;'";
$bool_show_variant_chk = $product_variant != "" ? "checked" : "";
if (isset($_POST['update_product'])) {
    updateProduct();
}
$variants = selectAllProductVariants($_GET['product_id']);
$selected_variants = "";
while ($row = $variants->fetch_assoc()) {
    if ($row['variant_name'] == "NONE") {
        $selected_variants = "";
        break;
    }
    $selected_variants .= $row['variant_name'] . ",";
}
?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Product</h6>
    </div>
    <div class="card-body">
        <form class="row needs-validation" action="" method="post" enctype="multipart/form-data" novalidate>
            <div class="col-md-6">
                <input type="hidden" name="product_id" value="<?= $product_id ?>">
                <div class="form-group productname">
                    <label for="title">Product Name</label>
                    <input class="form-control" type="text" name="product_name" value="<?= $product_name ?>" pattern="\S+.*" required>
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
                                                                                            $category_title_temp = $row['category_title'];
                            ?>
                                <option value="<?= $category_id ?>" <?php if ($category_title_temp == $category_title) echo "selected"; ?>><?= $category_title_temp ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="brand">Brand</label>
                        <select class="custom-select form-control" name="product_brand" id="brand">
                            <?php
                                                                                                                                        $result = selectAllBrands();

                                                                                                                                        while ($row = $result->fetch_assoc()) {
                                                                                                                                            $brand_id = $row['brand_id'];
                                                                                                                                            $brand_title_temp = $row['brand_title'];
                            ?>
                                <option value="<?= $brand_id ?>" <?php if ($brand_title_temp == $brand_title) echo "selected"; ?>><?= $brand_title_temp ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="product_quantity">Quantity</label>
                        <input class="form-control product_quantity" type="text" name="product_quantity" value="<?= $product_quantity ?>" pattern="[0-9]+([,\.][0-9]+)?" required>
                        <div class="invalid-feedback">
                            Please enter a quantity
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="product_price">Price</label>
                        <input class="form-control product_price" type="text" name="product_price" value="<?= $product_price ?>" pattern="[0-9]+([,\.][0-9]+)?" required>
                        <div class="invalid-feedback">
                            Please enter a price
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="product_description">Description</label>
                    <textarea class="form-control" name="product_description" id="" cols="30" rows="2"><?= $product_description ?></textarea>
                </div>
                <div class="form-group">
                    <label>Upload Thumbnail</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="imgInp" accept='image/*' name="image">
                            <label class="custom-file-label" for="imgInp">Choose file...</label>
                            <div class="invalid-feedback">Example invalid custom file feedback</div>
                        </div>
                    </div>
                    <img id='img-upload' class="w-50 h-50" src="../images/<?= $product_image ?>" />
                </div>
                <div class="form-group">
                    <label>Upload Images</label>
                    <div class="input-group mb-3">
                        <input type="file" class="custom-file-input" id="uploadFile" name="uploadFile[]" multiple accept='image/*'>
                        <label class="custom-file-label" for="imgInp">Choose file...</label>
                    </div>
                    <div id="image_preview">
                        <?php
                                                                                                                                        $product_images = retrieveProductImages();

                                                                                                                                        while ($images = $product_images->fetch_assoc()) {

                        ?>
                            <img src="../images/<?= $images['products_images_name'] ?>">

                        <?php } ?>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" name="update_product" class="btn btn-primary">
                        <i class="fa fa-save fa-lg"></i> Update Product
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <label class="d-block"><strong>Product Variants</strong><em class="ml-2">A single variant will be created automatically unless you enable the multiple variant option.</em></label>
                <div class="border px-3 pt-3">
                    <div class="form-group">
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input variant-chk" type="checkbox" name="product_variants" <?= $bool_show_variant_chk ?>>This product has multiple variants
                            </label>
                        </div>
                    </div>
                    <div class="variant_field" <?= $bool_show_variant ?>>
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="variant_name">Variant Option</label>
                                <input class="form-control variant_name" type="text" name="variant_name" pattern="\S+.*" value="<?= $product_variant ?>">
                                <div class="invalid-feedback">
                                    Please enter a variant name
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="variant_option_values">Option Values</label>
                            <input type="text" data-role="tagsinput" name="variant_option_values" value="<?= $selected_variants ?>">
                        </div>
                    </div>
                </div>
            </div>
        </form>

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
                    var product_id = $('input[name ="product_id"]').val();

                    $.ajax({
                        url: 'includes/product_check.php',
                        method: 'post',
                        data: {
                            product_name: product_name,
                            product_id: product_id,
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