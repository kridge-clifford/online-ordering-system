<!-- Hero Section-->
<section class="hero hero-page gray-bg padding-small">
    <div class="container">
        <div class="row d-flex">
            <div class="col-lg-9 order-2 order-lg-1">
                <h1>Checkout</h1>
                <p class="lead total-items-cart">You currently have 3 item(s) in your basket</p>
            </div>
            <div class="col-lg-3 text-right order-1 order-lg-2">
                <ul class="breadcrumb justify-content-lg-end">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Checkout</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- Checkout Forms-->
<section class="checkout">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <form class="checkout-frm" target="payments.php" method="post">
                    <ul class="nav nav-pills" id="checkout-nav">
                        <li class="nav-item"><a class="nav-link active show " id="address-tab" href="#address" role="tab" aria-controls="address" aria-selected="true">Address</a></li>
                        <li class="nav-item"><a class="nav-link disabled" id="delivery-method-tab" href="#delivery-method" role="tab" aria-controls="delivery-method" aria-selected="false">Delivery Method</a></li>
                        <li class="nav-item"><a class="nav-link disabled" id="payment-method-tab" href="#payment-method" role="tab" aria-controls="payment-method" aria-selected="false">Payment Method</a></li>
                        <li class="nav-item"><a class="nav-link disabled" id="order-review-tab" href="#order-review" role="tab" aria-controls="order-review" aria-selected="false">Order Review</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="address" class="tab-pane fade active show" role="tabpanel" aria-labelledby="address-tab">
                            <div class="active tab-block">
                                <div class="block-header mb-5">
                                    <h6>Invoice address </h6>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6 first_name">
                                        <label for="firstname" class="form-label">First Name</label>
                                        <input id="firstname" type="text" name="first-name" maxlength="32" placeholder="Enter you first name" class="form-control">
                                        <label class="invalid-feedback ml-4">
                                            Please enter first name.
                                        </label>
                                    </div>
                                    <div class="form-group col-md-6 last_name">
                                        <label for="lastname" class="form-label">Last Name</label>
                                        <input id="lastname" type="text" name="last-name" maxlength="32" placeholder="Enter your last name" class="form-control">
                                        <label class="invalid-feedback ml-4">
                                            Please enter first name.
                                        </label>
                                    </div>
                                    <div class="form-group col-md-6 email">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input id="email" type="email" name="email" placeholder="Enter email" class="form-control">
                                        <label class="invalid-feedback ml-4">
                                            Please enter first name.
                                        </label>
                                    </div>
                                    <div class="form-group col-md-6 phone_number">
                                        <label for="phone_number" class="form-label">Phone Number</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend rounded-circle">
                                                <span class="input-group-text" id="basic-addon1">+63</span>
                                            </div>
                                            <input id="phone_number" type="number" name="phone_number" placeholder="Enter your phone number." aria-describedby="basic-addon1" onKeyPress="if(this.value.length==10) return false;" class="form-control">
                                            <label class="invalid-feedback ml-4">
                                                Please enter first name.
                                            </label>
                                        </div>
                                    </div>

                                    <!-- New address-->
                                    <div class="form-group col-md-12 house_number">
                                        <label for="house_number" class="form-label">House number, building, street name and barangay</label>
                                        <input id="house_number" type="text" name="house_number" maxlength="54" placeholder="house no., building, st. name and barangay" class="form-control">
                                        <label class="invalid-feedback ml-4">
                                            Please enter first name.
                                        </label>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="province_option" class="form-label">Province</label>
                                        <select id="province_option" name="province_option" class="form-control" style="padding: 0px 15px;" onmousedown="if(this.options.length>8){this.size=8;}" onchange='this.size=0;' onblur="this.size=0;">
                                            <?php

                                            $provinces_list = selectAllProvince();

                                            while ($province_row = $provinces_list->fetch_assoc()) {
                                                echo '<option value="' . $province_row['province_code'] . '">' . $province_row['province_name'] . '</option>';
                                            }

                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="municipality_option" class="form-label">City</label>
                                        <select id="municipality_option" name="municipality_option" class="form-control" style="padding: 0px 15px;" onmousedown="if(this.options.length>8){this.size=8;}" onchange='this.size=0;' onblur="this.size=0;">

                                        </select>
                                    </div>
                                    <!-- New address-->
                                    <!--<div class="form-group col-md-12 address">
                                        <label for="addressText" class="form-label">Address</label>
                                        <textarea id="addressText" type="text" name="address" placeholder="your address" rows="2" class="form-control"></textarea>
                                        <label class="invalid-feedback ml-4">
                                            Please enter first name.
                                        </label>
                                    </div>-->
                                </div>
                                <div class="CTAs d-flex justify-content-between flex-column flex-lg-row">
                                    <button type="button" id="back-to-cart" name="back-to-cart" class="btn btn-template-outlined wide btn-prev mb-2"> <i class="fa fa-angle-left"></i>Back to basket</button>
                                    <button type="submit" id="personal-info" name="personal-info" class="btn btn-template wide mb-2">
                                        Choose delivery method<i class="fa fa-angle-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div id="delivery-method" class="tab-pane fade" role="tabpanel" aria-labelledby="delivery-method-tab">
                            <div class="tab-block">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <input type="radio" name="shippping" value="SHIPPING" id="shipping" class="radio-template shipping-input" checked>
                                        <label for="shipping"><strong>Shipping</strong><br><span class="label-description">Door-to-door delivery, includes shipping fee. Shipping fee info will be sent to your email</span></label>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input type="radio" name="shippping" value="PICKUP" id="pickup" class="radio-template shipping-input">
                                        <label for="pickup"><strong>Pickup</strong><br><span class="label-description">Pick it up right to our store.</span></label>
                                    </div>
                                </div>
                                <div class="CTAs d-flex justify-content-between flex-column flex-lg-row">
                                    <button href="#address" data-toggle="pill" class="btn btn-template-outlined wide prev btn-prev mb-2"><i class="fa fa-angle-left"></i>Back to Address</button>
                                    <button type="submit" id="payment_method" name="payment_method" class="btn btn-template wide mb-2">
                                        Choose payment method<i class="fa fa-angle-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div id="payment-method" class="tab-pane fade" role="tabpanel" aria-labelledby="delivery-method-tab">
                            <div id="accordion" role="tablist" aria-multiselectable="true">
                                <div class="card">
                                    <div id="headingTwo" role="tab" class="card-header">
                                        <h6>Paypal</h6>
                                    </div>
                                    <div class="card-body">
                                        <input type="radio" name="payment" value="PAYPAL" id="payment-method-1" class="radio-template delivery-input">
                                        <label for="payment-method-1"><strong>Continue with Paypal</strong><br></label>
                                    </div>
                                </div>
                                <div class="card shipping-method">
                                    <div id="headingThree" role="tab" class="card-header">
                                        <h6>Pay on delivery</h6>
                                    </div>
                                    <div class="card-body">
                                        <input type="radio" name="payment" value="COD" id="payment-method-2" class="radio-template delivery-input">
                                        <label for="payment-method-2"><strong>Pay on Delivery</strong><br></label>
                                    </div>
                                </div>
                                <div class="card pickup-method">
                                    <div id="headingThree" role="tab" class="card-header">
                                        <h6>Over-the-Counter</h6>
                                    </div>
                                    <div class="card-body">
                                        <input type="radio" name="payment" value="PICKUP" id="payment-method-3" class="radio-template delivery-input">
                                        <label for="payment-method-3"><strong>Over-the-Counter payment</strong><br></label>
                                    </div>
                                </div>
                            </div>
                            <div class="CTAs d-flex justify-content-between flex-column flex-lg-row">
                                <button href="#delivery-method" data-toggle="pill" class="btn btn-template-outlined wide prev btn-prev mb-2"><i class="fa fa-angle-left"></i>Back to delivery method</button>
                                <button type="submit" id="order_review" name="order_review" class="btn btn-template wide mb-2">
                                    Order Review<i class="fa fa-angle-right"></i>
                                </button>
                            </div>
                        </div>
                        <div id="order-review" class="tab-pane fade" role="tabpanel" aria-labelledby="order-review-tab">
                            <div class="tab-block">
                                <div class="cart">
                                    <div class="cart-holder">
                                        <div class="basket-header">
                                            <div class="row">
                                                <div class="col-6">Product</div>
                                                <div class="col-2">Price</div>
                                                <div class="col-2">Quantity</div>
                                                <div class="col-2">Unit Price</div>
                                            </div>
                                        </div>
                                        <div class="basket-body">
                                            <?php
                                            $total_price = 0;
                                            foreach ($_SESSION['cart'] as $key => $value) {
                                                $total_price += $value['product_price'];
                                            ?>
                                                <div class="item row d-flex align-items-center item-<?= $key ?>">
                                                    <div class="col-6">
                                                        <div class="d-flex align-items-center item-image-<?= $key ?>"><img src="images/<?= $value['product_image']; ?>" alt="..." class="img-fluid">
                                                            <div class="title"><a href="detail.html">
                                                                    <h6><?= $value['product_name']; ?></h6><span class="text-muted">Size: Large</span>
                                                                </a></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-2"><span>₱<?= number_format($value['product_price_fix'], 2, '.', ','); ?></span></div>
                                                    <div class="col-2"><span><?= $value['quantity']; ?></span></div>
                                                    <div class="col-2"><span>₱<?= number_format($value['product_price'], 2, '.', ','); ?></span></div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="total row">
                                        <span class="col-md-10 col-2">Total</span><span class="col-md-2 col-10 text-primary price-total">₱<?= number_format($total_price, 2, '.', ','); ?></span>
                                    </div>
                                </div>
                                <div class="overflow-auto p-3 border mt-3" style="height:100px;">
                                    <h4>Terms and Conditions</h4>
                                    <strong>Acceptance of the terms</strong><br>
                                    <p><small>By accepting this TOS or by accessing or using the Service or Site, You acknowledge that you have read, understood, and agree to be bound by this TOS. If you are entering into this TOS on behalf of a Goldiloops Vape Shop or other legal entity, you represent that You have the authority to bind such entity and its affiliates to these TOS, in which case the terms “you” or “your” shall refer to such entity and its affiliates.</small></p>
                                    <p><small>If you do not have such authority, or if you do not agree with these TOS, You must not accept these TOS and may not use the Service. Ordering may change this TOS from time to time without prior notice. The revised terms and conditions will become effective upon posting, and if you use the Service after that date, we will treat your use as acceptance of the revised terms and conditions. If any change to this TOS is not acceptable to you, your only remedy is to stop accessing and using the Service.</small></p>
                                    <br><strong>General Conditions / Access and Use of the Service</strong><br>
                                    <p><small>You shall not modify, adapt or hack the Service to falsely imply any sponsorship or association with Ordering, or otherwise attempt to gain unauthorized access to the Service or its related systems or networks. You are responsible for all information, data, text, messages or other materials that you post or otherwise transmit via the Service. You are responsible for maintaining the confidentiality of your information and are fully responsible for any and all activities.</small></p>
                                    <p><small>Ordering reserves the right to access any or all your accounts to respond to your requests for technical support. You acknowledge that this TOS is a contract between You and Ordering, even though it is electronic and is not physically signed by you and Ordering, and it governs your use of the Service and takes the place of any prior agreements between You and Ordering. It is the sole responsibility of the Customer to ensure that the Customer Content is complete, accurate, current, legal and decent reflecting the true state and features of the displayed information. The Customer acknowledges that he/she owns the displayed information or has the legal right to display the information.</small></p>
                                    <p><small>The Customer is responsible for ensuring that his/her complies with local law. The Customer is responsible for drafting the terms of use and privacy policy for his/her, privacy policy must contain terms that are at least as protective of a user’s privacy as those contained in this Agreement.</small></p>

                                    <br><strong>Return of Items and Refund Policy</strong><br>
                                    <p><small>Customers have the right to return or ask for a refund on items that they bought from any branch of Goldiloops Vapeshop that they deemed faulty or not in proper order, within the period of ten (10) working days, starting from the date of its original purchase.</small></p>
                                    <p><small>Customers must follow these procedures:</small></p>
                                    <p><small>1. They must secure an original receipt of purchase signed by any authorized personnel of Goldiloops Vapeshop.</small></p>
                                    <p><small>2. They may mail a letter of concern stating the incident of the said receipt to the mailing address of the Goldiloops Vapeshop where they made the purchase, and wait for 5 to 7 working days for response from the branch managers. They may also follow up through telephone numbers: 88-483-270.</small></p>
                                    <p><small>3. If the customers planned to call the attention of the branch manager, through online, they may send a scanned copy of their original receipt to the email address: goldiloops.vapeshop@gmail.com. They may also follow up through telephone numbers: 88-483-270.</small></p>
                                    <p><small>4. Customers who also have concerns about their returns, and refunds, who are not willing to wait, may also come personally to the Goldiloops Branch where they made the purchase. Just bring the faulty items, and the original receipt for a very fast, and smooth action.</small></p>
                                    <p><small>We value the views and opinions of our customers. For more information on how we can serve you better, pls. don’t hesitate to reach us through: goldiloops.vapeshop@gmail.com or at telephone numbers: 88-483-270.</small></p>

                                    <br><strong>Governing Law and Severability</strong><br>
                                    <p><small>These Terms of Use shall be governed by and construed in accordance with the laws of Philippines, without regard to its conflict of laws rules. You expressly agree that the exclusive jurisdiction for any claim or dispute under the Terms of Use and or your use of the Websites resides in the courts of Philippines, and you further expressly agree to submit to the personal jurisdiction of such courts for the purpose of litigating any such claim or action.</small></p>
                                    <p><small>If any provision of these Terms of Use is found to be invalid by any court having competent jurisdiction, the invalidity of such provision shall not affect the validity of the remaining provisions of these Terms of Use, which shall remain in full force and effect. No waiver of any provision in these Terms of Use shall be deemed a further or continuing waiver of such provision or any other provision.</small></p>
                                    <br><strong>Agreement to terms</strong><br>
                                    <p><small>By using the Websites, you agree to these Terms of Use and the Goldliloops Vape Shop. Each time you use the Websites, you reaffirm your acceptance of the then-current Terms of Use. If you do not wish to be bound by these Terms of Use, your only remedy is to discontinue using the Websites.</small></p>

                                </div>
                                <div class="overflow-auto p-3 border mt-3" style="height:100px;">
                                    <h4>Policy Privacy</h4>
                                    <p><small>What personally identifiable information is collected from you through the website, how it is used and with whom it may be shared.</small></p>
                                    <br><br>

                                    <p><small>What choices are available to you regarding the use of your data. The security procedures in place to protect the misuse of your information. How you can correct any inaccuracies in the information. Information Collection, Use, and Sharing</small></p>
                                    <br><br>

                                    <p><small>We are the sole owners of the information collected on this site. We only have access to/collect information that you voluntarily give us via email or other direct contact from you. We will not sell or rent this information to anyone.</small></p>
                                    <br><br>

                                    <p><small>We will use your information to respond to you, regarding the reason you contacted us.</small></p>
                                    <p><small>We will not share your information with any third party outside of our organization, other than as necessary to fulfill your request, e.g. to ship an order.</small></p>
                                    <br><br>

                                    <p><small>Unless you ask us not to, we may contact you via email in the future to tell you about specials, new products or services, or changes to this privacy policy.</small></p>
                                    <br><br>

                                    <p><small>Your Access to and Control Over Information You may opt out of any future contacts from us at any time. You can do the following at any time by contacting us via the email address or phone number given on our website:</small></p>
                                    <br><br>

                                    <p><small>See what data we have about you, if any. Change/correct any data we have about you. Have us delete any data we have about you. Express any concern you have about our use of your data.</small></p>
                                    <br><br>
                                    <strong>Return of Items and Refund Policy</strong><br>
                                    <p><small>Customers have the right to return or ask for a refund on items that they bought from any branch of Goldiloops Vapeshop that they deemed faulty or not in proper order, within the period of ten (10) working days, starting from the date of its original purchase.</small></p>
                                    <p><small>Customers must follow these procedures:</small></p>
                                    <p><small>1. They must secure an original receipt of purchase signed by any authorized personnel of Goldiloops Vapeshop.</small></p>
                                    <p><small>2. They may mail a letter of concern stating the incident of the said receipt to the mailing address of the Goldiloops Vapeshop where they made the purchase, and wait for 5 to 7 working days for response from the branch managers. They may also follow up through telephone numbers: 88-483-270.</small></p>
                                    <p><small>3. If the customers planned to call the attention of the branch manager, through online, they may send a scanned copy of their original receipt to the email address: goldiloops.vapeshop@gmail.com. They may also follow up through telephone numbers: 88-483-270.</small></p>
                                    <p><small>4. Customers who also have concerns about their returns, and refunds, who are not willing to wait, may also come personally to the Goldiloops Branch where they made the purchase. Just bring the faulty items, and the original receipt for a very fast, and smooth action.</small></p>
                                    <p><small>We value the views and opinions of our customers. For more information on how we can serve you better, pls. don’t hesitate to reach us through: goldiloops.vapeshop@gmail.com or at telephone numbers: 88-483-270.</small></p>

                                    <br><strong>Security</strong><br>
                                    <p><small>We take precautions to protect your information. When you submit sensitive information via the website, your information is protected both online and offline.</small></p>
                                    <br><br>

                                    <p><small>Wherever we collect sensitive information such as online payment, that information is encrypted and transmitted to us in a secure way. You can verify this by looking for a lock icon in the address bar and looking for "https" at the beginning of the address of the Web page.</small></p>
                                    <br><br>
                                    <p><small>While we use encryption to protect sensitive information transmitted online, we also protect your information offline. Only employees who need the information to perform a specific job (for example, billing or customer service) are granted access to personally identifiable information. The computers/servers in which we store personally identifiable information are kept in a secure environment.</small></p>
                                    <br><br>
                                    <p><small>If you feel that we are not abiding by this privacy policy, you should contact us immediately via phone at 88-483-270 or via email.</small></p>

                                </div>
                                <div class="form-group mb-1 mt-2">
                                    <input id="terms" type="checkbox" name="brand[]" value="1" class="checkbox-template checkbox-brands">
                                    <label for="terms">I Agree with the <a href="./index.php?option=terms" target="_blank"><strong>Terms & conditions</strong></a></label>
                                </div>
                                <div class="form-group mb-1 mt-2">
                                    <input id="policy" type="checkbox" value="1" class="checkbox-template checkbox-brands">
                                    <label for="policy">I Agree with the <a href="./index.php?option=privacy_policy" target="_blank"><strong>Privacy Policy</strong></a></label>
                                </div>
                                <div class="CTAs d-flex justify-content-between flex-column flex-lg-row">
                                    <button href="#payment-method" data-toggle="pill" class="btn btn-template-outlined wide prev mb-2"><i class="fa fa-angle-left mb-2"></i>Back to payment method</button>
                                    <button type="submit" id="place_order" name="submit" value="submit" class="btn btn-template wide mb-2 place_order">Place an order<i class="fa fa-angle-right"></i></button>
                                    <!--<button type="submit" id="place_order_paypal" class="btn btn-template wide mb-2 place_order_paypal">Place an order<i class="fa fa-angle-right"></i></button>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-4">
                <div class="block-body order-summary">
                    <h6 class="text-uppercase">Order Summary</h6>
                    <p>Shipping and additional costs are calculated based on values you have entered</p>
                    <ul class="order-menu list-unstyled">
                        <li class="d-flex justify-content-between"><span>Order Subtotal </span><strong class="price-sub-total">$390.00</strong></li>
                        <li class="d-flex justify-content-between shippingfeeinclude" style="display:none!important;"><span>Shipping and handling</span><strong>₱200.00</strong></li>
                        <li class="d-flex justify-content-between"><span>Total</span><strong class="text-primary price-total">$400.00</strong></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
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
    var shipping_fee = 0;
    $(function() {

        function show_cart_count(count, total, real_total, shipping_fee) {
            $('.total-items-cart').html('You currently have ' + count + ' items in your shopping cart');
            $('.price-sub-total').text('₱' + total);

            var new_total = parseFloat(real_total) + parseFloat(shipping_fee);
            console.log(new_total);
            new_total = new_total.toLocaleString('en-US', {
                minimumFractionDigits: 2
            });
            $('.price-total').text('₱' + new_total);
        }

        function refreshCart() {
            $.ajax({
                url: 'includes/add_to_cart.php',
                method: 'post',
                data: {
                    type: 'show',
                    shipping_fee: shipping_fee,
                },
                success: function(data) {
                    var obj = JSON.parse(data);

                    if (obj.success) {
                        if (obj.type === 'new') {
                            $('#cart-items').html(obj.content);
                            $('.cart-no').text(obj.count);
                            show_cart_count(obj.count, obj.total_price, obj.real_total, obj.shipping_fee);
                        }
                    }
                }
            }).done(function(data) {});
        }

        function validate() {
            var firstname = $("#firstname").val();
            var lastname = $("#lastname").val();
            var email = $("#email").val();
            var phone_number = $("#phone_number").val();
            var address = $("#addressText").val();
            var house_number = $("#house_number").val();
            var regex_email = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i
            var isValid = true;
            $(".checkout-frm .invalid-feedback").hide();
            if (firstname.trim() == "") {
                $(".first_name .invalid-feedback").text("Please enter first name.");
                $(".first_name .invalid-feedback").show();
                isValid = false;
            }
            if (lastname.trim() == "") {
                $(".last_name .invalid-feedback").text("Please enter last name.");
                $(".last_name .invalid-feedback").show();
                isValid = false;
            }
            if (email.trim() == "" || !regex_email.test(email.trim())) {
                $(".email .invalid-feedback").text("Please enter valid email.");
                $(".email .invalid-feedback").show();
                isValid = false;
            }
            if (phone_number.trim() == "") {
                $(".phone_number .invalid-feedback").text("Please enter your phone number.");
                $(".phone_number .invalid-feedback").show();
                isValid = false;
            }
            if (house_number.trim() == "") {
                $(".house_number .invalid-feedback").text("Please enter your address info.");
                $(".house_number .invalid-feedback").show();
                isValid = false;
            }
            // } else if (address.trim() == "") {
            //     $(".address .invalid-feedback").text("Please enter address.");
            //     $(".address .invalid-feedback").show();
            //    return false;



            return isValid;
        }

        $('#personal-info, #order_review, #place_order, #payment_method').on("click", ".btn-prev", function(e) {
            e.preventDefault();
            var href_loc = $(this).attr("href");
            $('#checkout-nav a[href="' + href_loc + '"]').removeClass("disabled").tab('show');

        });

        $('#personal-info, #order_review, #place_order, #payment_method, #place_order_paypal, #back-to-cart').on("click", function(e) {
            e.preventDefault();
            if ($(this).attr("name") == "back-to-cart") {


                location.href = "./index.php?option=cart";
                return;
            } else if (!validate()) {
                return;
            }

            if ($(this).attr("name") == "personal-info") {
                $('#checkout-nav a[href="#delivery-method"]').removeClass("disabled").tab('show');
                return;
            } else if ($(this).attr("name") == "order_review") {


                $('#checkout-nav a[href="#order-review"]').removeClass("disabled").tab('show');
                return;

            } else if ($(this).attr("name") == "payment_method") {
                $('#checkout-nav a[href="#payment-method"]').removeClass("disabled").tab('show');
                $('input[name=payment]').removeAttr('checked');
                $(".pickup-method").show();
                $(".shipping-method").show();
                if ($(".shipping-input:checked").val() == "PICKUP") {
                    $(".shippingfeeinclude").attr("style", "display: none !important");
                    shipping_fee = 0;
                    refreshCart();
                    $(".shipping-method").hide();
                    $("input[name=payment][value=PICKUP]").prop('checked', true);
                } else {
                    shipping_fee = 200;
                    refreshCart();
                    $(".shippingfeeinclude").show();
                    $(".pickup-method").hide();
                    $("input[name=payment][value=PAYPAL]").prop('checked', true);
                };
                return;
            }

            if (!$("#terms").prop("checked")) {
                alert("You must agree to terms and condition to continue.");
                return;
            }

            if (!$("#policy").prop("checked")) {
                alert("You must agree to policy privacy to continue.");
                return;
            }
            // if($(".delivery-input:checked").val() == "PAYPAL"){
            // $('form.checkout-frm').submit();
            // return;
            // }
            $("#waitModal").modal({
                backdrop: 'static',
                keyboard: false
            });



            $("#waitModal").modal('toggle');
            $.ajax({
                url: 'includes/confirm_order.php',
                method: "post",
                data: $('.checkout-frm').serialize(),
                success: function(data) {
                    var obj = JSON.parse(data);
                    console.log(obj);
                    if (obj.success) {
                        console.log(obj.url);
                        if (obj.url != "none") {
                            location.href = obj.url;
                            return;
                        }

                        $("#waitModal").modal('toggle');
                        location.href = "./index.php?option=confirm_order";
                    }
                },
            });
        });
    });

    $(document).ready(function() {



        function selectMunicipalities() {
            var selected_province = $('#province_option').find(":selected").val();

            $("#waitModal").modal({
                backdrop: 'static',
                keyboard: false
            });

            $("#waitModal").modal('toggle');

            $.ajax({
                url: 'includes/municipalities.php',
                method: 'post',
                data: {
                    province_code: selected_province,
                },
                success: function(data) {
                    var obj = JSON.parse(data);
                    if (obj.success) {
                        $('#municipality_option').html(obj.content);
                        $("#waitModal").modal('toggle');
                    }
                    $("#waitModal").modal('toggle');
                }
            });
        }

        selectMunicipalities();

        $('#province_option').change(function() {

            selectMunicipalities();
        });

        $('.next, .prev').on('click', function(e) {
            e.preventDefault()
            var url_href = $(this).attr("href");

            $('#checkout-nav a[href="' + url_href + '"]').removeClass("disabled").tab('show');
        });

        $("#cart-items").on("click", ".delete-item", function(e) {
            e.preventDefault();
            var product_id = $(this).attr("id");

            $.ajax({
                url: 'includes/add_to_cart.php',
                method: 'post',
                data: {
                    product_id: product_id,
                    type: 'delete',
                },
                success: function(data) {
                    var obj = JSON.parse(data);
                    $("#cart-items").html(obj.content);
                    $(".cart-no").text(obj.count);
                    show_cart_count(obj.count, obj.total_price);
                    $("#cart-body").addClass("show");
                    $(".dropdown-menu").addClass("show");
                    product_id = product_id.replace('cart-item-', '');
                    $(".item-image-" + product_id).parents('.item-' + product_id).fadeOut().css("cssText", "display: none !important;");
                    $(".price-total").text("₱" + obj.total_price + shipping_fee);
                }
            });
        });

    });
</script>