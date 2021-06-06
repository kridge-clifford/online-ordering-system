<?php
include "includes/header.php";

$today_sales = orderTodaySales();
$total_sales = 0;

while ($rows = $today_sales->fetch_assoc()) {
  $total_sales = $rows['total_price'];
}
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>

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

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
          <div class="dropdown no-arrow mb-4 ml-auto mr-3">
            <button class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-download fa-sm text-white-50"></i> Orders Report
            </button>

            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 38px, 0px);">
              <a class="dropdown-item" href="./includes/generate_pdf.php?status=completed" target="_blank">Completed</a>
              <a class="dropdown-item" href="./includes/generate_pdf.php?status=cancelled" target="_blank">Cancelled</a>
              <a class="dropdown-item" href="./includes/generate_pdf.php?status=shipping" target="_blank">Shipping</a>
              <a class="dropdown-item" href="./includes/generate_pdf.php?status=waiting" target="_blank">Waiting</a>
              <a class="dropdown-item" href="./includes/generate_pdf.php?status=processing" target="_blank">Processing</a>
              <a class="dropdown-item" href="./includes/generate_pdf.php?status=all" target="_blank">All</a>
            </div>
          </div>
          <div class="dropdown no-arrow mb-4">
            <button class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm dropdown-toggle" type="button" id="dropdownMenuButtonProducts" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-download fa-sm text-white-50"></i> Products Report
            </button>

            <div class="dropdown-menu" aria-labelledby="dropdownMenuButtonProducts" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 38px, 0px);">
              <a class="dropdown-item" href="./includes/generate_product_pdf.php?status=completed" target="_blank">Completed</a>
              <a class="dropdown-item" href="./includes/generate_product_pdf.php?status=cancelled" target="_blank">Cancelled</a>
              <a class="dropdown-item" href="./includes/generate_product_pdf.php?status=refunded" target="_blank">Refunded</a>
              <a class="dropdown-item" href="./includes/generate_product_pdf.php?status=processing" target="_blank">Processing</a>
              <a class="dropdown-item" href="./includes/generate_product_pdf.php?status=all" target="_blank">All</a>
            </div>
          </div>
        </div>
        <div class="row">

          <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Today Sales</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800" id="total_sales"><?= $total_sales ?></div>
                  </div>
                  <div class="col-auto">
                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
              <!-- Card Header - Dropdown -->
              <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Orders Report</h6>
              </div>
              <!-- Card Body -->
              <?php
              $query = "SELECT orders.order_id, CONCAT(customer.customer_firstname, ' ', customer.customer_lastname) as customer_name, orders.order_date, payment.payment_amount as total_price, order_status from orders INNER JOIN customer ON customer.customer_id = orders.customer_id INNER JOIN order_item ON order_item.order_id = orders.order_id INNER JOIN payment ON payment.order_id = orders.order_id WHERE orders.order_status != 'Pending' GROUP BY orders.order_id ORDER BY order_status ASC, order_item.order_id DESC ";
              $stmt = $connection->prepare($query);
              $stmt->execute();
              $result = $stmt->get_result();
              ?>
              <div class="card-body">
                <p id="date_filter">
                  <span id="date-label-from" class="date-label">From: </span><input class="date_range_filter date" type="text" id="datepicker_from" value="0" />
                  <span id="date-label-to" class="date-label">To:<input class="date_range_filter date" type="text" id="datepicker_to" value="0" />
                </p>
                <div class="table-responsive">
                  <table class="table table-bordered" id="datatable" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>Reference #</th>
                        <th>Customer Name</th>
                        <th>Total Price</th>
                        <th>Date</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tfoot>
                      <tr>
                        <th>Reference #</th>
                        <th>Customer Name</th>
                        <th>Total Price</th>
                        <th>Date</th>
                        <th>Status</th>
                      </tr>
                    </tfoot>
                    <tbody>
                      <?php

                      while ($row = $result->fetch_assoc()) {
                        $order_id = $row["order_id"] + 2019000;
                        $name = $row["customer_name"];
                        $real_total_price = $row["total_price"];
                        $order_status_show = $row["order_status"];
                        $order_date = date_format(date_create($row['order_date']), 'Y/m/d');
                        $total_price = number_format($real_total_price, 2, '.', '.');
                      ?>
                        <tr>
                          <td><?= $order_id ?></td>
                          <td><?= $name ?></td>
                          <td><?= $total_price ?></td>
                          <td><?= $order_date ?></td>
                          <td><span class="<?= colorOrderStatus($order_status_show) ?>"><?= $order_status_show ?></span></td>
                        </tr>

                      <?php } ?>
                    </tbody>
                  </table>
                </div>
                <hr>
                <div class="col-xl-12 col-lg-12 mt-4">
                  <div class="row">
                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-3 col-md-6 mb-4">
                      <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                          <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Waiting Orders</div>
                              <div class="h5 mb-0 font-weight-bold text-gray-800" id="pending_count">0</div>
                            </div>
                            <div class="col-auto">
                              <i class="fas fa-hourglass fa-2x text-gray-300"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-3 col-md-6 mb-4">
                      <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                          <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Completed Orders</div>
                              <div class="h5 mb-0 font-weight-bold text-gray-800" id="completed_count">0</div>
                            </div>
                            <div class="col-auto">
                              <i class="fas fa-check fa-2x text-gray-300"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-3 col-md-6 mb-4">
                      <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                          <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Processing Orders</div>
                              <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                  <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800" id="processing_count">0</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-auto">
                              <i class="fas fa-retweet fa-2x text-gray-300"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Pending Requests Card Example -->
                    <div class="col-xl-3 col-md-6 mb-4">
                      <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                          <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                              <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Refunded Items</div>
                              <div class="h5 mb-0 font-weight-bold text-gray-800" id="refunded_count">0</div>
                            </div>
                            <div class="col-auto">
                              <i class="fas fa-redo fa-2x text-gray-300"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row" id="dvContainer">
          <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
              <!-- Card Header - Dropdown -->
              <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Earnings Overview</h6>
              </div>
              <!-- Card Body -->
              <div class="card-body">
                <div class="chart-area">
                  <canvas id="myAreaChart"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->
    <!-- Page level custom scripts -->
    <script src="vendor/chart.js/Chart.min.js"></script>
    <script src="js/demo/chart-area-demo.js"></script>
    <?php include "includes/footer.php"; ?>

    <script>
      function onClick() {
        html2canvas(document.querySelector("#dvContainer"), {

          onrendered: function(canvas) {

            var img = canvas.toDataURL("image/png");
            var doc = new jsPDF({
              orientation: 'landscape',
            });
            doc.setFontSize(22);
            doc.text("Goldiloops Vapeshop Sales and Report", 15, 15);

            doc.setFontSize(17);
            doc.text("Order Status", 25, 30);

            doc.setFont("courier");
            doc.setFontStyle("normal")
            doc.setFontSize(14);
            doc.text("Pending: " + $("#pending_count").text(), 25, 37);

            doc.setFont("courier");
            doc.setFontStyle("normal")
            doc.setFontSize(14);
            doc.text("Completed: " + $("#completed_count").text(), 90, 37);

            doc.setFont("courier");
            doc.setFontStyle("normal")
            doc.setFontSize(14);
            doc.text("Processing: " + $("#processing_count").text(), 155, 37);

            doc.setFont("courier");
            doc.setFontStyle("normal")
            doc.setFontSize(14);
            doc.text("Refunded: " + $("#refunded_count").text(), 220, 37);

            doc.setFontSize(17);
            doc.setFont("arial");
            doc.setFontStyle("normal")
            doc.text("Sales", 25, 65);

            doc.addImage(img, 'JPEG', 15, 70, 400, 120);
            // doc.save('SalesAndReportGoldiloops.pdf');
          }

        });
      };


      function getOrderStatus(date_from = "", date_to = "") {
        $.ajax({
          url: 'includes/order_status.php',
          method: 'post',
          data: {
            date_from: date_from,
            date_to: date_to,
          },
          success: function(data) {
            var obj = JSON.parse(data);

            var refunded_count = obj.refunded_count;
            var pending_count = obj.pending_count;
            var completed_count = obj.completed_count;
            var processing_count = obj.processing_count;

            $("#refunded_count").text(refunded_count);
            $("#pending_count").text(pending_count);
            $("#completed_count").text(completed_count);
            $("#processing_count").text(processing_count);
          }
        });
      }


      var element = document.getElementById("dropdownMenuButton");
      element.addEventListener("click", onClick);

      $(function() {
        var oTable = $('#datatable').DataTable({
          "deferLoading": 0,
          "oLanguage": {
            "sSearch": "Filter Data"
          },
          "iDisplayLength": -1,
          "sPaginationType": "full_numbers",

        });


        $("#datepicker_from").datepicker({
          showOn: "button",
          class: "calendar-alt",
          buttonText: "Choose Date",
          dateFormat: 'yy-mm-dd',
          "onSelect": function(date) {
            minDateFilter = new Date(date).getDate();
            oTable.draw();
          }
        }).keyup(function() {
          minDateFilter = new Date(this.value).getDate();
          oTable.draw();
        });

        $("#datepicker_to").datepicker({
          showOn: "button",
          buttonText: "Choose Date",
          dateFormat: 'yy-mm-dd',
          "onSelect": function(date) {
            maxDateFilter = new Date(date).getDate();
            oTable.draw();
          }
        }).keyup(function() {
          maxDateFilter = new Date(this.value).getDate();
          oTable.draw();
        });
      });

      // Date range filter
      minDateFilter = "";
      maxDateFilter = "";

      $.fn.dataTableExt.afnFiltering.push(
        function(oSettings, aData, iDataIndex) {
          var date_to = $("#datepicker_to").val();
          var date_from = $("#datepicker_from").val();
          getOrderStatus(date_from, date_to);

          if (typeof aData._date == 'undefined') {
            aData._date = new Date(aData[3]).getDate();
          }
          if (minDateFilter && !isNaN(minDateFilter)) {
            if (aData._date < minDateFilter) {
              return false;
            }
          }

          if (maxDateFilter && !isNaN(maxDateFilter)) {
            if (aData._date > maxDateFilter) {
              return false;
            }
          }

          return true;
        }
      );


      $(document).ready(function() {
        getOrderStatus();
        $("#datepicker_to").val("0");
        $("#datepicker_from").val("0");
      });
    </script>