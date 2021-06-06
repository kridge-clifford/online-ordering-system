<!-- Footer-->

<div class="toast bg-success text-white" id="myToast" style="position: fixed; top: 105px; right: 5px;">
    <div class="toast-body">
        <div>Item added to cart <i class="fa fa-check"></i></div>
    </div>
</div>

<div class="modal" id="waitModal" tabindex="-1" role="dialog" aria-labelledby="waitModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header btn-secondary" style="border-bottom:0;">
                <h4 class="modal-title font-weight-bold" id="waitModalLabel"></h4>
            </div>
            <div class="modal-body text-center" id="changePasswordContent">
                <h3>Please wait.</h3>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        
        $("#myToast").toast({
            delay: 2000,
        });
    });
</script>
<footer class="main-footer">
    <!-- Main Block -->
    <div class="main-block">
        <div class="container">
            <div class="row">
                <div class="info col-lg-4">
                    <div class="logo mb-4">
                        <img src="./images/goldiwhite-logo.png" alt="..." style="height:55px;">
                    </div>
                    <ul class="social-menu list-inline">
                        <li class="list-inline-item"><a href="https://www.facebook.com/pages/Goldiloops-Vapeshop/128614097712893" target="_blank" title="facebook"><i class="fa fa-facebook"></i></a></li>
                        <li class="list-inline-item"><a href="https://www.instagram.com/gvs_fam" target="_blank" title="instagram"><i class="fa fa-instagram"></i></a></li>
                    </ul>
                </div>
                <div class="site-links col-lg-2 col-md-6">
                    <h5 class="text-uppercase">Company</h5>
                    <ul class="list-unstyled">
                        <li> <a href="./index.php?option=terms">Terms & Conditions</a></li>
                        <li> <a href="./index.php?option=privacy_policy">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="copyrights">
        <div class="container">
            <div class="row d-flex align-items-center">
                <div class="text col-md-6">
                    <p>&copy; 2019 Goldiloops-Vapeshop. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- JavaScript files-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery.cookie/jquery.cookie.js"> </script>
<script src="vendor/owl.carousel/owl.carousel.min.js"></script>
<script src="vendor/owl.carousel2.thumbs/owl.carousel2.thumbs.min.js"></script>
<script src="vendor/bootstrap-select/js/bootstrap-select.min.js"></script>
<script src="vendor/nouislider/nouislider.min.js"></script>
<script src="vendor/jquery-countdown/jquery.countdown.min.js"></script>
<script src="vendor/masonry-layout/masonry.pkgd.min.js"></script>
<script src="vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
<script src="js/tagsinput.js"></script>
<!-- masonry -->
<script>
    $(function() {
        var $grid = $('.masonry-wrapper').masonry({
            itemSelector: '.item',
            columnWidth: '.item',
            percentPosition: true,
            transitionDuration: 0,
        });

        $grid.imagesLoaded().progress(function() {
            $grid.masonry();
        });
    })
</script>
<!-- Main Template File-->
<script src="js/front.js"></script>
</body>


</html>