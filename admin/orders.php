<?php include "includes/header.php"; ?>

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    <?php include "includes/navigation.php"; ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <?php include "includes/topbar.php"; ?>
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <?php
                if (isset($_GET['source'])) {
                    $source = $_GET['source'];
                } else {
                    $source = '';
                }
                switch ($source) {
                    case 'add_product':
                        include "includes/add_product.php";
                        break;
                    case 'edit_product':
                        include "includes/edit_product.php";
                        break;
                    default:
                        include "includes/view_all_orders.php";
                        break;
                }
                ?>
            </div>
        </div>



    </div>
    <!-- /.container-fluid -->

</div>

<!-- End of Main Content -->
<div class="modal fade" id="orderSummaryModal" tabindex="-1" role="dialog" aria-labelledby="orderSummaryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h4 class="modal-title font-weight-bold" id="orderSummaryModalLabel">Order Details</h4>
                <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="orderSummaryContent"></div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(".view_data").click(function() {
            var order_id_post = $(this).attr("id");

            $.ajax({
                url: 'includes/order_summary.php',
                method: "post",
                data: {
                    order_id_post: order_id_post
                },
                success: function(response) {
                    $("#orderSummaryContent").html(response);
                    $("#orderSummaryModal").modal("show");
                }
            });
        });
    });
</script>
<?php addValidation(); ?>
<?php include "includes/footer.php"; ?>