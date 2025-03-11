$(document).ready(function () {
    //Call Datatable Class
    $("#bootstrap-4-datatables").DataTable();
    // $("#categories").DataTable();
    // $("#brands").DataTable();

    $(".nav-item").removeClass("active");
    $(".nav-link").removeClass("active");

    //Check Admin Password is corrent or not
    $("#current_password").keyup(function () {
        var current_password = $("#current_password").val();
        //    alert(current_password);
        $.ajax({
            type: 'post',
            url: 'check-admin-password',
            data: { current_password: current_password },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (resp) {
                // alert(resp);
                if (resp == "false") {
                    $("#check_password").html("<font color='red'>Current Password is Incorrect !</fornt>");
                } else if (resp == "true") {
                    $("#check_password").html("<font color='green'>Current Password is Correct !</fornt>");
                }
            }, error: function () {
                // alert('Error');
            }
        });
    });

    //UPDATE ADMIN STATUS
    $(".updateAdminStatus").on("click", function () {
        var status = $(this).children("i").attr("status");
        var admin_id = $(this).attr("admin_id");
        $.ajax({
            type: 'post',
            url: '/admin/update-admin-status',
            data: { status: status, admin_id: admin_id },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (resp) {
                // alert(resp);
                if (resp['status'] == 0) {
                    $("#admin-" + admin_id).html("<i style='font-size:25px' class='mdi mdi-bookmark-outline' status='Inactive'></i>")
                } else if (resp['status'] == 1) {
                    $("#admin-" + admin_id).html("<i style='font-size:25px' class='mdi mdi-bookmark-check' status='Active'></i>")
                }
            }, error: function () {
                alert('Error');
            }
        });
    });

    //UPDATE SECTION STATUS
    $(".updateSectionStatus").on("click", function () {
        var status = $(this).children("i").attr("status");
        var section_id = $(this).attr("section_id");
        $.ajax({
            type: 'post',
            url: '/admin/update-section-status',
            data: { status: status, section_id: section_id },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (resp) {
                // alert(resp);
                if (resp['status'] == 0) {
                    $("#section-" + section_id).html("<i style='font-size:25px' class='mdi mdi-bookmark-outline' status='Inactive'></i>")
                } else if (resp['status'] == 1) {
                    $("#section-" + section_id).html("<i style='font-size:25px' class='mdi mdi-bookmark-check' status='Active'></i>")
                }
            }, error: function () {
                alert('Error');
            }
        });
    });


    //UPDATE filter STATUS
    $(".updateFilterStatus").on("click", function () {
        var status = $(this).children("i").attr("status");
        var filter_id = $(this).attr("filter_id");
        $.ajax({
            type: 'post',
            url: '/admin/update-filter-status',
            data: { status: status, filter_id: filter_id },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (resp) {
                // alert(resp);
                if (resp['status'] == 0) {
                    $("#filter-" + filter_id).html("<i style='font-size:25px' class='mdi mdi-bookmark-outline' status='Inactive'></i>")
                } else if (resp['status'] == 1) {
                    $("#filter-" + filter_id).html("<i style='font-size:25px' class='mdi mdi-bookmark-check' status='Active'></i>")
                }
            }, error: function () {
                alert('Error');
            }
        });
    });

        //UPDATE FILTER VALUES STATUS
        $(".updateFilterValueStatus").on("click", function () {
            var status = $(this).children("i").attr("status");
            var filter_id = $(this).attr("filter_id");
            $.ajax({
                type: 'post',
                url: '/admin/update-filter-values-status',
                data: { status: status, filter_id: filter_id },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (resp) {
                    // alert(resp);
                    if (resp['status'] == 0) {
                        $("#filter-" + filter_id).html("<i style='font-size:25px' class='mdi mdi-bookmark-outline' status='Inactive'></i>")
                    } else if (resp['status'] == 1) {
                        $("#filter-" + filter_id).html("<i style='font-size:25px' class='mdi mdi-bookmark-check' status='Active'></i>")
                    }
                }, error: function () {
                    alert('Error');
                }
            });
        });


    //UPDATE CATEGORY STATUS
    $(document).on("click", '.updateCategoryStatus', function () {
        var status = $(this).children("i").attr("status");
        var category_id = $(this).attr("category_id");
        $.ajax({
            type: 'post',
            url: '/admin/update-category-status',
            data: { status: status, category_id: category_id },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (resp) {
                // alert(resp);
                if (resp['status'] == 0) {
                    $("#category-" + category_id).html("<i style='font-size:25px' class='mdi mdi-bookmark-outline' status='Inactive'></i>")
                } else if (resp['status'] == 1) {
                    $("#category-" + category_id).html("<i style='font-size:25px' class='mdi mdi-bookmark-check' status='Active'></i>")
                }
            }, error: function () {
                alert('Error');
            }
        });
    });

    //UPDATE BRAND STATUS
    $(".updateBrandStatus").on("click", function () {
        var status = $(this).children("i").attr("status");
        var brand_id = $(this).attr("brand_id");
        $.ajax({
            type: 'post',
            url: 'update-brand-status',
            data: { status: status, brand_id: brand_id },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (resp) {
                // alert(resp);
                if (resp['status'] == 0) {
                    $("#brand-" + brand_id).html("<i style='font-size:25px' class='mdi mdi-bookmark-outline' status='Inactive'></i>")
                } else if (resp['status'] == 1) {
                    $("#brand-" + brand_id).html("<i style='font-size:25px' class='mdi mdi-bookmark-check' status='Active'></i>")
                }
            }, error: function () {
                alert('Error');
            }
        });
    });

    //UPDATE PRODUCT STATUS
    $(document).on("click", '.updateProductStatus', function () {
    // $(".updateProductStatus").on("click", function () {
        var status = $(this).children("i").attr("status");
        var product_id = $(this).attr("product_id");
        $.ajax({
            type: 'post',
            url: 'update-product-status',
            data: { status: status, product_id: product_id },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (resp) {
                // alert(resp);
                if (resp['status'] == 0) {
                    $("#product-" + product_id).html("<i style='font-size:25px' class='mdi mdi-bookmark-outline' status='Inactive'></i>")
                } else if (resp['status'] == 1) {
                    $("#product-" + product_id).html("<i style='font-size:25px' class='mdi mdi-bookmark-check' status='Active'></i>")
                }
            }, error: function () {
                alert('Error');
            }
        });
    });

    //UPDATE SHIPPING CHARGES STATUS
    $(document).on("click", '.updateShippingStatus', function () {
        var status = $(this).children("i").attr("status");
        var shipping_id = $(this).attr("shipping_id");
        $.ajax({
            type: 'post',
            url: 'update-shipping-charges-status',
            data: { status: status, shipping_id: shipping_id },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (resp) {
                // alert(resp);
                if (resp['status'] == 0) {
                    $("#shipping-" + shipping_id).html("<i style='font-size:25px' class='mdi mdi-bookmark-outline' status='Inactive'></i>")
                } else if (resp['status'] == 1) {
                    $("#shipping-" + shipping_id).html("<i style='font-size:25px' class='mdi mdi-bookmark-check' status='Active'></i>")
                }
            }, error: function () {
                alert('Error');
            }
        });
    });

    //CONFIRM DELETE
    $(document).on("click", '.confirmDelete', function () {
        // $(".confirmDelete").on("click", function () {
        var module = $(this).attr('module');
        var moduleid = $(this).attr('moduleid');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                )
                window.location = "/admin/delete-" + module + "/" + moduleid;
            }
        })
    });


    //APPEND CATEGORIES LEVEL
    $("#section_id").change(function () {
        var section_id = $(this).val();
        // console.log(section_id)
        $.ajax({
            type: 'get',
            url: '/admin/append-categories-level',
            data: { section_id: section_id },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (resp) {
                $("#appendCategoriesLevel").html(resp);
            }, error: function () {
                // alert("Error");
            }
        });
    });

    // SHOW FILTERS OF SECTION OF CATEGORY
    $("#category_id").change(function () {
        var category_id = $(this).val();
        //  alert(category_id);
        $.ajax({
            type:'post',
            url:'category-filters',
            data:{category_id:category_id},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(resp){
                $(".loadFilters").html(resp.view);
            }, error: function () {
                // alert("Error");
            }
        });
    });

        //UPDATE COUPONS STATUS
    $(".updateCouponStatus").on("click", function () {
        var status = $(this).children("i").attr("status");
        var coupon_id = $(this).attr("coupon_id");
        $.ajax({
            type: 'post',
            url: 'update-coupon-status',
            data: { status: status, coupon_id: coupon_id },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (resp) {
                // alert(resp);
                if (resp['status'] == 0) {
                    $("#coupon-" + coupon_id).html("<i style='font-size:25px' class='mdi mdi-bookmark-outline' status='Inactive'></i>")
                } else if (resp['status'] == 1) {
                    $("#coupon-" + coupon_id).html("<i style='font-size:25px' class='mdi mdi-bookmark-check' status='Active'></i>")
                }
            }, error: function () {
                alert('Error');
            }
        });
    });

    //UPDATE PRODUCT STATUS
    $(document).on("click", '.updateUsersStatus', function () {
        var status = $(this).children("i").attr("status");
        var user_id = $(this).attr("user_id");
        $.ajax({
            type: 'post',
            url: 'update-users-status',
            data: { status: status, user_id: user_id },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (resp) {
                // alert(resp);
                if (resp['status'] == 0) {
                    $("#user-" + user_id).html("<i style='font-size:25px' class='mdi mdi-bookmark-outline' status='Inactive'></i>")
                } else if (resp['status'] == 1) {
                    $("#user-" + user_id).html("<i style='font-size:25px' class='mdi mdi-bookmark-check' status='Active'></i>")
                }
            }, error: function () {
                alert('Error');
            }
        });
    });

    //PRODUCT ATTRIBUTES ADD/REMOVE SCRIPTS

    var maxField = 10; //Input fields increment limitation
    var addButton = $('.add_button'); //Add button selector
    var wrapper = $('.field_wrapper'); //Input field wrapper
    var fieldHTML = '<div><div style="height:10px;"></div><input type="text" name="size[]" placeholder="Size" style="width: 110px;" required=""/>&nbsp;<input type="text" name="sku[]" placeholder="Sku" style="width: 120px;" required=""/>&nbsp;<input type="text" name="price[]" placeholder="Price" style="width: 110px;" required="" />&nbsp;<input type="text" name="stock[]" placeholder="Stock" style="width: 110px;" required=""/>&nbsp;<a href="javascript:void(0);" class="remove_button">Remove</a></div>'; //New input field html
    var x = 1; //Initial field counter is 1

    //Once add button is clicked
    $(addButton).click(function () {
        //Check maximum number of input fields
        if (x < maxField) {
            x++; //Increment field counter
            $(wrapper).append(fieldHTML); //Add field html
        }
    });

    //Once remove button is clicked
    $(wrapper).on('click', '.remove_button', function (e) {
        e.preventDefault();
        $(this).parent('div').remove(); //Remove field html
        x--; //Decrement field counter
    });

    //UPDATE ATTRIBUTE STATUS
    $(document).on("click", '.updateAttributeStatus', function () {
        var status = $(this).children("i").attr("status");
        var attribute_id = $(this).attr("attribute_id");
        $.ajax({
            type: 'post',
            url: '/admin/update-attribute-status',
            data: { status: status, attribute_id: attribute_id },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (resp) {
                // alert(resp);
                if (resp['status'] == 0) {
                    $("#attribute-" + attribute_id).html("<i style='font-size:25px' class='mdi mdi-bookmark-outline' status='Inactive'></i>")
                } else if (resp['status'] == 1) {
                    $("#attribute-" + attribute_id).html("<i style='font-size:25px' class='mdi mdi-bookmark-check' status='Active'></i>")
                }
            }, error: function () {
                alert('Error');
            }
        });
    });

    //UPDATE IMAGE STATUS
    $(document).on("click", '.updateImageStatus', function () {
        var status = $(this).children("i").attr("status");
        var image_id = $(this).attr("image_id");
        $.ajax({
            type: 'post',
            url: '/admin/update-image-status',
            data: { status: status, image_id: image_id },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (resp) {
                // alert(resp);
                if (resp['status'] == 0) {
                    $("#image-" + image_id).html("<i style='font-size:25px' class='mdi mdi-bookmark-outline' status='Inactive'></i>")
                } else if (resp['status'] == 1) {
                    $("#image-" + image_id).html("<i style='font-size:25px' class='mdi mdi-bookmark-check' status='Active'></i>")
                }
            }, error: function () {
                alert('Error');
            }
        });
    });

    //UPDATE BANNER STATUS
    $(document).on("click", '.updateBannerStatus', function () {
        var status = $(this).children("i").attr("status");
        var banner_id = $(this).attr("banner_id");
        $.ajax({
            type: 'post',
            url: '/admin/update-banner-status',
            data: { status: status, banner_id: banner_id },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (resp) {
                // alert(resp);
                if (resp['status'] == 0) {
                    $("#banner-" + banner_id).html("<i style='font-size:25px' class='mdi mdi-bookmark-outline' status='Inactive'></i>")
                } else if (resp['status'] == 1) {
                    $("#banner-" + banner_id).html("<i style='font-size:25px' class='mdi mdi-bookmark-check' status='Active'></i>")
                }
            }, error: function () {
                alert('Error');
            }
        });
    });

    //Show/Hide Coupon filed for Manual/Automatic
    $("#ManualCoupon").click(function(){
        $("#couponField").show();
    });
    $("#AutomaticCoupon").click(function(){
        $("#couponField").hide();
    });

    //Show Courier Name & Tracking Name in case of Shipped Order Status
    $("#courier_name").hide();
    $("#tracking_number").hide();
    $("#order_status").on("change",function(){
        if(this.value=="Shipped"){
            $("#courier_name").show();
            $("#tracking_number").show();
        }else{
            $("#courier_name").hide();
            $("#tracking_number").hide();
        }
    });

    //For adding width in Field of Update Order Status
    $("#order_status").on("change",function(){
        if (this.value === "Partially Shipped" || this.value === "Partially Delivered") {
            this.style.width = '170px';
        }else{
            this.style.width = '120px';
        }
    });

    // Optionally, set the width on page load if an option is already selected
    if ($("#order_status").val() === "Partially Shipped" || $("#order_status").val() === "Partially Delivered") {
        $("#order_status").css('width', '170px');
    }
});
