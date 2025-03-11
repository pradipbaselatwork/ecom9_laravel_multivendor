//Jquery for Front Product detail price change
$(document).ready(function () {
    $("#getPrice").change(function () {
        let size = $(this).val();
        let product_id = $(this).attr("product-id");
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/get-product-price',
            data: { size: size, product_id: product_id },
            type: 'post',
            success: function (resp) {
                if (resp['discount'] > 0) {
                    $(".getAttributePrice").html("<div class='price'><h4>Rs." + resp['final_price'] + "</h4></div><div class='original-price'><span>Original Price:</span><span>Rs." + resp['product_price'] + "</span></div>");
                } else {
                    $(".getAttributePrice").html("<div class='price'><h4>Rs." + resp['final_price'] + ".</h4></div>");
                }
            }, error: function () {
                alert("Error");
            }
        });
    });
});

// Update Cart Items Qty
$(document).on('click', '.updateCartItem', function () {
    if ($(this).hasClass('plus-a')) {
        //Get qty
        var quantity = $(this).data('qty');
        // increase the qty by 1
        new_qty = parseInt(quantity) + 1;
        //  alert(new_qty);
    }
    if ($(this).hasClass('minus-a')) {
        //Get qty
        var quantity = $(this).data('qty');
        if (quantity <= 1) {
            alert('Item quantity must be 1 or greater!');
            return false;
        }
        // increase the qty by 1
        new_qty = parseInt(quantity) - 1;
        // alert(new_qty);
    }

    var cartid = $(this).data('cartid');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/cart-update',
        data: { cartid: cartid, qty: new_qty },
        type: 'post',
        success: function (resp) {
            if (resp.status == false) {
                alert(resp.message);
            }
            $(".totalCartItems").html(resp.totalCartItems);
            $("#appendCartItems").html(resp.view);
            $("#appendHeaderCartItems").html(resp.headerview);
        }, error: function () {
            alert("Error");
        }
    });
});

//Delete Cart Items
$(document).on('click', '.deleteCartItem', function () {
    var cartid = $(this).data(cartid);
    var result = confirm('Are you sure to delete cart item?');
    if (result) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/cart-delete',
            data: { cartid: cartid },
            type: 'post',
            success: function (resp) {
                $(".totalCartItems").html(resp.totalCartItems);
                $("#appendCartItems").html(resp.view);
                $("#appendHeaderCartItems").html(resp.headerview);
            }, error: function () {
                alert("Error");
            }
        });
    }
});

//Show Loader At the Time of Order Placement
$(document).on('click','#placeOrder', function (e) {
        $('.loader').show();
});

//Customer Register form
$(document).ready(function () {
    $('#registerForm').on('submit', function (e) {
        $('.loader').show();
        e.preventDefault(); // prevent the default form submission
        var formdata = $(this).serialize();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/user/register',
            data: formdata,
            type: 'post',
            success: function (resp) {
                if (resp.type == "error") {
                    $.each(resp.errors, function (i, error) {
                        $('.loader').hide();
                        $("#register-" + i).attr('style', 'color:red');
                        $("#register-" + i).html(error);
                        setTimeout(function () {
                            $("#register-" + i).css({
                                'display': 'none'
                            });
                        }, 3000);
                    });
                } else if (resp.type == "success") {
                    // alert(resp.message);
                    $('.loader').hide();
                    $("#register-success").attr('style', 'color:green');
                    $("#register-success").html(resp.message);
                    // window.location.href = resp.url;
                }
            },
            error: function () {
                alert("Error");
            }
        });
    });
});

//Customer Account form
$(document).ready(function () {
    $('#accountForm').on('submit', function (e) {
        $('.loader').show();
        e.preventDefault(); // prevent the default form submission
        var formdata = $(this).serialize();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/user/account',
            data: formdata,
            type: 'post',
            success: function (resp) {
                if (resp.type == "error") {
                    $.each(resp.errors, function (i, error) {
                        $('.loader').hide();
                        $("#account-" + i).attr('style', 'color:red');
                        $("#account-" + i).html(error);
                        setTimeout(function () {
                            $("#account-" + i).css({
                                'display': 'none'
                            });
                        }, 3000);
                    });
                } else if (resp.type == "success") {
                    // alert(resp.message);
                    $('.loader').hide();
                    $("#account-success").attr('style', 'color:green');
                    $("#account-success").html(resp.message);
                    // window.location.href = resp.url;
                }
            },
            error: function () {
                alert("Error");
            }
        });
    });
});

//Customer Password form
$(document).ready(function () {
    $('#passwordForm').on('submit', function (e) {
        $('.loader').show();
        e.preventDefault(); // prevent the default form submission
        var formdata = $(this).serialize();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/user/update-password',
            data: formdata,
            type: 'post',
            success: function (resp) {
                if (resp.type == "error") {
                    $.each(resp.errors, function (i, error) {
                        $('.loader').hide();
                        $("#password-" + i).attr('style', 'color:red');
                        $("#password-" + i).html(resp.message);
                        setTimeout(function () {
                            $("#password-" + i).css({
                                'display': 'none'
                            });
                        }, 3000);
                    });
                } else if (resp.type == "incorrect") {
                    // alert(resp.message);
                    $('.loader').hide();
                    $("#password-error").attr('style', 'color:red');
                    $("#password-error").html(resp.message);
                } else if (resp.type == "success") {
                    // alert(resp.message);
                    $('.loader').hide();
                    $("#password-success").attr('style', 'color:green');
                    $("#password-success").html(resp.message);
                }
            },
            error: function () {
                alert("Error");
            }
        });
    });
});

//Customer login form
$(document).ready(function () {
    $('#loginForm').on('submit', function (e) {
        e.preventDefault(); // prevent the default form submission
        var formdata = $(this).serialize();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/user/login',
            data: formdata,
            type: 'post',
            success: function (resp) {
                if (resp.type == "error") {
                    $.each(resp.errors, function (i, error) {
                        $("#login-" + i).attr('style', 'color:red');
                        $("#login-" + i).html(error);
                        setTimeout(function () {
                            $("#login-" + i).css({
                                'display': 'none'
                            });
                        }, 3000);
                    });
                } else if (resp.type == "incorrect") {
                    $("#login-error").attr('style', 'color:red');
                    $("#login-error").html(resp.message);
                } else if (resp.type == "inactive") {
                    $("#login-error").attr('style', 'color:red');
                    $("#login-error").html(resp.message);
                }
                else if (resp.type == "success") {
                    window.location.href = resp.url;
                }
            },
            error: function () {
                alert("Error");
            }
        });
    });
});

// Customer Forgot Password
$(document).ready(function () {
    $('#forgotForm').on('submit', function (e) {
        $('.loader').show();
        e.preventDefault(); // prevent the default form submission
        var formdata = $(this).serialize();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/user/forgot-password',
            data: formdata,
            type: 'post',
            success: function (resp) {
                if (resp.type == "error") {
                    $.each(resp.errors, function (i, error) {
                        $('.loader').hide();
                        $("#forgot-" + i).attr('style', 'color:red');
                        $("#forgot-" + i).html(error);
                        setTimeout(function () {
                            $("#forgot-" + i).css({
                                'display': 'none'
                            });
                        }, 3000);
                    });
                } else if (resp.type == "success") {
                    // alert(resp.message);
                    $('.loader').hide();
                    $("#forgot-success").attr('style', 'color:green');
                    $("#forgot-success").html(resp.message);
                    // window.location.href = resp.url;
                }
            },
            error: function () {
                alert("Error");
            }
        });
    });
});

// Apply Coupon Ajax Jquery
$(document).ready(function () {
    $('#ApplyCoupon').on('submit', function (e) {
        e.preventDefault();
        var user = $(this).attr("user");
        if (user == 1) {
            // Do nothing
        } else {
            alert("Please login to apply Coupon!");
            return; // Exit the function if user is not logged in
        }
        var code = $("#code").val();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            data: { code: code },
            url: '/apply-coupon',
            success: function (resp) {
                if (resp.message != "") {
                    alert(resp.message);
                }
                $(".totalCartItems").html(resp.totalCartItems);
                $("#appendCartItems").html(resp.view);
                $("#appendHeaderCartItems").html(resp.headerview);
                if (resp.couponAmount > 0) {
                    $(".couponAmount").text("Rs." + resp.couponAmount);
                } else {
                    $(".couponCode").text("Rs.0");
                }
                if (resp.grand_total > 0) {
                    $(".grand_total").text("Rs." + resp.grand_total);
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
});

//Edit Delivery Adressess
$(document).on('click', '.editAddress', function () {
    let addressid = $(this).data("addressid");
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'post',
        data: { addressid: addressid },
        url: '/get-delivery-address',
        success: function (resp) {
            $('#showdifferent').removeClass('collapse');
            $('.newAddress').hide();
            $('.deliveryText').text('Edit Delivery Address');
            $('[name=delivery_id').val(resp.address['id']);
            $('[name=delivery_name').val(resp.address['name']);
            $('[name=delivery_address').val(resp.address['address']);
            $('[name=delivery_city').val(resp.address['city']);
            $('[name=delivery_state').val(resp.address['state']);
            $('[name=delivery_country').val(resp.address['country']);
            $('[name=delivery_pincode').val(resp.address['pincode']);
            $('[name=delivery_mobile').val(resp.address['mobile']);
        },
        error: function (xhr, status, error) {
            alert("Error");
        }
    });
});

//Remove Delivery Addresses
$(document).on('click', '.removeAddress', function () {
    if(confirm("Are you sure to remove this?")){
        $('.loader').show();
       let addressid = $(this).data("addressid");
       $.ajax({
           headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           },
           type:'post',
           data: {addressid:addressid},
           url: '/remove-delivery-address',
           success: function (resp) {
               $('.loader').hide();
               $('#deliveryAddresses').html(resp.view);
               window.location.href = "checkout";
           },
           error: function (xhr, status, error) {
               alert("Error");
           }
       });
    }
});

//Save Delivery Adressess
$(document).on('submit', '#addressAddEditForm', function (e) {
    $('.loader').show();
    e.preventDefault();
       let formdata = $('#addressAddEditForm').serialize();
       $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'post',
        data: formdata,
        url: '/save-delivery-address',
        success: function (resp) {
            if (resp.type == "error") {
                $.each(resp.errors, function (i, error) {
                    $('.loader').hide();
                    $("#delivery-" + i).attr('style', 'color:red');
                    $("#delivery-" + i).html(error);
                    setTimeout(function () {
                        $("#delivery-" + i).css({
                            'display': 'none'
                        });
                    }, 3000);
                });
            }else{
                $('.loader').hide();
                $('#deliveryAddresses').html(resp.view);
                window.location.href = "checkout";
            }
        },
        error: function (xhr, status, error) {
            alert("Error");
        }
    });
});

// $('input[name="address_id"]').on('click', function () {
//     var shipping_charges = $(this).attr("shipping_charges");
//     var total_price = $(this).attr("total_price");
//     var coupon_amount = $(this).attr("coupon_amount");
//     $(".shipping_charges").html("Rs." + shipping_charges);
//     if (coupon_amount === "") {
//         coupon_amount = 0;
//     }
//     $(".couponAmount").html("Rs." + coupon_amount);
//     var grand_total = parseInt(total_price) + parseInt(shipping_charges) - parseInt(coupon_amount);
//     $(".grand_total").html("Rs." +grand_total);
// });

// $(document).ready(function () {
//     $('input[name="address_id"]').on('click', function () {
//         var shipping_charges = $(this).attr("shipping_charges");
//         var total_price = $(this).attr("total_price");
//         var coupon_amount = $(this).attr("coupon_amount");
//         $(".shipping_charges").html("Rs." + shipping_charges);
//         if (coupon_amount === "") {
//             coupon_amount = 0;
//         }
//         $(".couponAmount").html("Rs." + coupon_amount);
//         var grand_total = parseInt(total_price) + parseInt(shipping_charges) - parseInt(coupon_amount);
//         $(".grand_total").html("Rs." +grand_total);
//     });
// });

//Get Dynamic filter
function get_filter(class_name) {
    var filter = [];
    $('.' + class_name + ':checked').each(function () {
        filter.push($(this).val());
    });
    return filter;
}
