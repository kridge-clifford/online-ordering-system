
<nav class="navbar navbar-expand-lg pt-1">
    <div class="container-fluid">
        <a href="index.php" class="navbar-brand">
		<img src="./images/goldilogo.png" alt="..." style="height:55px;">
	</a>
	
        <button type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler navbar-toggler-right"><i class="fa fa-bars"></i></button>
        <div id="navbarCollapse" class="collapse navbar-collapse">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a href="index.php" class="nav-link">Home</a>
                </li>
                <li class="nav-item">
                    <a href="index.php?option=shop" class="nav-link">Shop</a>
                </li>
                <li class="nav-item">
                    <a href="index.php?option=contact" class="nav-link">Contact Us</a>
                </li>
                <li class="nav-item">
                    <a href="index.php?option=about" class="nav-link">About Us</a>
                </li>
                <li class="nav-item">
                    <a href="index.php?option=faq" class="nav-link">FAQ</a>
                </li>
                <li class="nav-item">
                    <a href="index.php?option=track_order" class="nav-link">Track Order</a>
                </li>
            </ul>
            <div class="right-col d-flex align-items-lg-center flex-column flex-lg-row">
                <div class="cart dropdown show" id="cart-body">
                    <a id="cartdetails" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle">
                        <i class="icon-cart"></i>
                        <div class="cart-no">0</div>
                    </a>
                    <a href="index.php?option=cart" class="text-primary view-cart">View Cart</a>
                    <div aria-labelledby="cartdetails" class="dropdown-menu" id="cart-items">
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
<div class="input-group w-25 pl-2 m-2" id="datetime">
    </div>
<script>
    $(document).ready(function() {


        setInterval(() => {
            var dt = new Date();
            $("#datetime").html(dt.toLocaleString());
        }, 1000);
    });
</script>