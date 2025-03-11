<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use App\Models\ProductsFilter;
use App\Models\ProductAttribute;
use App\Models\Vendor;
use App\Models\Order;
use App\Models\Cart;
use App\Models\User;
use App\Models\OrdersProducts;
use App\Models\Coupon;
use App\Models\Country;
use App\Models\DeliveryAddress;
use App\Models\ShippingCharges;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Validator;

class ProductsController extends Controller
{
    public function listing(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            $url = $data['url'];
            $_GET['sort'] = $data['sort'];
            $categoryCount = Category::where(['url' => $url, 'status' => 1])->count();
            if ($categoryCount > 0) {
                //get Categroy Details
                $categoryDetails = Category::categoryDetails($url);
                $categoryProducts = Product::with('brand')->whereIn('category_id', $categoryDetails['catIds'])->where('status', 1);

                //checking for Dynamic filters
                $productFilters = ProductsFilter::productFilters();
                foreach ($productFilters as $key => $filter) {
                    if (isset($filter['filter_column']) && isset($data[$filter['filter_column']]) && !empty($filter['filter_column']) && !empty($data[$filter['filter_column']])) {
                        $categoryProducts->whereIn($filter['filter_column'], $data[$filter['filter_column']]);
                    }
                }

                // if(isset($data['fabric']) && !empty($data['fabric'])){
                //     $categoryProducts->whereIn('products.fabric',$data['fabric']);
                // }

                //Checking for Sort
                if (isset($_GET['sort']) && !empty($_GET['sort'])) {
                    if ($_GET['sort'] == "product_latest") {
                        $categoryProducts->orderby('products.id', 'Desc');
                    } else if ($_GET['sort'] == "price_lowest") {
                        $categoryProducts->orderby('products.product_price', 'Asc');
                    } else if ($_GET['sort'] == "price_highest") {
                        $categoryProducts->orderby('products.product_price', 'Desc');
                    } else if ($_GET['sort'] == "name_a_z") {
                        $categoryProducts->orderby('products.product_name', 'Asc');
                    } else if ($_GET['sort'] == "name_z_a") {
                        $categoryProducts->orderby('products.product_name', 'Desc');
                    }
                }

                //checking for Size
                if (isset($data['size']) && !empty($data['size'])) {
                    $productIds = ProductAttribute::select('product_id')->whereIn('size', $data['size'])->pluck('product_id')->toArray();
                    $categoryProducts->whereIn('products.id', $productIds);
                }

                //checking for color
                if (isset($data['color']) && !empty($data['color'])) {
                    $productIds = Product::select('id')->whereIn('product_color', $data['color'])->pluck('id')->toArray();
                    $categoryProducts->whereIn('products.id', $productIds);
                }

                //Checking for Price
                $productIds = [];
                if (isset($data['price']) && !empty($data['price'])) {
                    foreach ($data['price'] as $key => $price) {
                        $priceArr = explode("-", $price);
                        if (isset($priceArr[0]) && isset($priceArr[1])) {
                            $productIds[] = Product::select('id')->whereBetween('product_price', [$priceArr[0], $priceArr[1]])->pluck('id')->toArray();
                        }
                    }
                    $productIds = array_unique(array_flatten($productIds));
                    $categoryProducts->whereIn('products.id', $productIds);
                }

                //Checking for Brand
                if (isset($data['brand']) && !empty($data['brand'])) {
                    $productIds = Product::select('id')->whereIn('brand_id', $data['brand'])->pluck('id')->toArray();
                    $categoryProducts->whereIn('products.id', $productIds);
                }
                $categoryProducts = $categoryProducts->paginate(7);
                return view('front.products.ajax_products_listing')->with(compact('categoryDetails', 'categoryProducts', 'url'));
            } else {
                abort(404);
            }
        }else{
            if(isset($_REQUEST['search']) && !empty($_REQUEST['search'])) {
                $search_product = $_REQUEST['search'];
                $categoryDetails['breadcrumbs'] = $search_product;
                $categoryDetails['categoryDetails']['category_name'] = $search_product;
                $categoryDetails['categoryDetails']['description'] = "Search Product for ".$search_product;
                $categoryProducts = Product::select('products.id','products.section_id','products.category_id','products.brand_id','products.vendor_id',
                'products.product_name','products.product_code','products.product_color','products.product_price','products.product_discount',
                'products.product_image','products.description')->with('brand')->join('categories', 'categories.id', '=', 'products.category_id')
                ->where(function($query) use ($search_product) {
                    $query->where('products.product_name', 'like', '%' . $search_product . '%')
                        ->orWhere('products.product_code', 'like', '%' . $search_product . '%')
                        ->orWhere('products.product_color', 'like', '%' . $search_product . '%')
                        ->orWhere('products.description', 'like', '%' . $search_product . '%')
                        ->orWhere('categories.category_name', 'like', '%' . $search_product . '%');
                })->where('products.status', 1);
               
                if(isset($_REQUEST['section_id']) && !empty($_REQUEST['section_id'])) {
                    $categoryProducts = $categoryProducts->where('products.section_id',$_REQUEST['section_id']);
                }   
                $categoryProducts = $categoryProducts->get();
                // $categoryDetails = []; 
                // dd($categoryProducts);
                return view('front.products.listing')->with(compact('categoryDetails', 'categoryProducts'));
                // dd($categoryProducts);
            }else{
                $url = Route::getFacadeRoot()->current()->uri();
                $categoryCount = Category::where(['url' => $url, 'status' => 1])->count();
                if ($categoryCount > 0) {
                    //get Categroy Details
                    $categoryDetails = Category::categoryDetails($url);
                    $categoryProducts = Product::with('brand')->whereIn('category_id', $categoryDetails['catIds'])->where('status', 1);
    
                    //Checking for Sort
                    if (isset($_GET['sort']) && !empty($_GET['sort'])) {
                        if ($_GET['sort'] == "product_latest") {
                            $categoryProducts->orderby('products.id', 'Desc');
                        } else if ($_GET['sort'] == "price_lowest") {
                            $categoryProducts->orderby('products.product_price', 'Asc');
                        } else if ($_GET['sort'] == "price_highest") {
                            $categoryProducts->orderby('products.product_price', 'Desc');
                        } else if ($_GET['sort'] == "name_a_z") {
                            $categoryProducts->orderby('products.product_name', 'Asc');
                        } else if ($_GET['sort'] == "name_z_a") {
                            $categoryProducts->orderby('products.product_name', 'Desc');
                        }
                    }
    
                    $categoryProducts = $categoryProducts->paginate(7);
                    return view('front.products.listing')->with(compact('categoryDetails', 'categoryProducts', 'url'));
                } else {
                    abort(404);
                }
            }
        }
        // return view();
    }

    public function vendorListing($vendorid)
    {
        //Get Vendor Shop Name
        $getVendorShop = Vendor::getVendorShop($vendorid);

        //Get Vendor Products
        $vendorProducts = Product::with('brand')->where('vendor_id', $vendorid)->where('status', 1);
        $vendorProducts = $vendorProducts->paginate(30);

        return view('front.products.vendor_listing')->with(compact('getVendorShop', 'vendorProducts'));
    }

    public function detail($id)
    {
        $productDetails = Product::with(['section', 'category', 'brand', 'attributes' => function ($query) {
            $query->where('stock', '>', 0)->where('status', 1);
        }, 'images', 'vendor'])->find($id)->toArray();

        $categoryDetails = Category::categoryDetails($productDetails['category']['url']);

        //Get Similar Products
        $similarProducts = Product::with('brand')->where('category_id', $productDetails['category']['id'])->where('id', '!=', $id)->limit(4)->inRandomOrder()->get()->toArray();

        //Get Session for Recently Viewed Products
        if (empty(Session::get('session_id'))) {
            $session_id = md5(uniqid(rand(), true));
        } else {
            $session_id = Session::get('session_id');
        }
        Session::put('session_id', $session_id);

        //Insert Recently Views Products in table in not exists
        $countRecentlyViewedProducts = DB::table('recently_viewed_products')->where(['product_id' => $id, 'session_id' => $session_id])->count();
        if ($countRecentlyViewedProducts == 0) {
            DB::table('recently_viewed_products')->insert(['product_id' => $id, 'session_id' => $session_id]);
        }

        //Get Recently Viewed Products Ids
        $recentProductsIds = DB::table('recently_viewed_products')->select('product_id')->where('product_id', '!=', $id)->where('session_id', $session_id)->inRandomOrder()->get()->take(4)->pluck('product_id');

        //Get Recently Viewed Products
        $recentlyViewedProducts = Product::with('brand')->whereIn('id', $recentProductsIds)->get();

        //Get Group Products (Product Color)
        $groupProducts = array();
        if (!empty($productDetails['group_code'])) {
            $groupProducts = Product::select('id', 'product_image')->where('id', '!=', $id)->where(['group_code' => $productDetails['group_code'], 'status' => 1])->get()->toArray();
        }

        $totalStock = ProductAttribute::where('product_id', $id)->sum('stock');
        return view('front.products.detail')->with(compact('productDetails', 'categoryDetails', 'totalStock', 'similarProducts', 'recentlyViewedProducts', 'groupProducts'));
    }

    public function getProductPrice(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            $getDiscountAttributePrice = Product::getDiscountAttributePrice($data['product_id'], $data['size']);
            return $getDiscountAttributePrice;
        }
    }

    public function addCart(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            //Forget the coupon sessions
            Session::forget('couponAmount');
            Session::forget('couponCode');

            if($data['quantity']<=0){
                $data['quantity']=1;
            }

            //Check Product Stock is Availabe or Not
            $getProductStock = ProductAttribute::getProductStock($data['product_id'], $data['size']);
            if ($getProductStock < $data['quantity']) {
                return redirect()->back()->with('error_message', 'Required quantity is not Availabe!');
            }

            //Generate Session Id if not exists
            $session_id = Session::get('session_id');
            if (empty($session_id)) {
                $session_id = Session::getId();
                Session::put('session_id', $session_id);
            }

            // if($data['user_id']==""){
            //    $data['user_id']="";
            // }

            //Check Product if already exists in the user Cart
            if (Auth::check()) {
                //User is logged in
                $user_id = Auth::user()->id;
                $countProducts = Cart::where(['product_id' => $data['product_id'], 'size' => $data['size'], 'user_id' => $user_id])->count();
            } else {
                // User is not logged in
                $user_id = 0;
                $countProducts = Cart::where(['product_id' => $data['product_id'], 'size' => $data['size'], 'session_id' => $session_id])->count();
            }
            if ($countProducts > 0) {
                return redirect()->back()->with('error_message', 'Product already Exists in Cart!');
            }

            //Save Product in carts table
            $item = new Cart;
            $item->session_id = $session_id;
            $item->user_id = $user_id;
            $item->product_id = $data['product_id'];
            $item->size = $data['size'];
            $item->quantity = $data['quantity'];
            $item->save();
            return redirect()->back()->with('success_message', 'Product has been added to Cart! <a style="text-decoration: underline;!important" href="/cart">View Cart</a>');
        }
    }

    public function cart()
    {
        $getCartItems = Cart::getCartItems();
        return view('front.products.cart')->with(compact('getCartItems'));
    }

    public function cartUpdate(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            // dd($data);

            //Forget the cupon sessions
            Session::forget('couponAmount');
            Session::forget('couponCode');

            //Get Cart Details
            $cartDetils = Cart::find($data['cartid']);

            //Get Availabe Product Stock
            $availableStock = ProductAttribute::select('stock')->where(['product_id' => $cartDetils['product_id'], 'size' => $cartDetils['size']])->first()->toArray();

            //Check if desired Stock form user is available
            if ($data['qty'] > $availableStock['stock']) {
                $getCartItems = Cart::getCartItems();
                return response()->json([
                    'status' => false,
                    'message' => 'Product Stock is not available',
                    'view' => (string) View::make('front.products.cart_items')->with(compact('getCartItems'))
                ]);
            }

            //Check if product size is available
            $availableSize = ProductAttribute::where(['product_id' => $cartDetils['product_id'], 'size' => $cartDetils['size'], 'status' => 1])->count();
            if ($availableSize == 0) {
                $getCartItems = Cart::getCartItems();
                return response()->json([
                    'status' => false,
                    'message' => 'Product Size is not available. Please remove this Product and choose another one!',
                    'view' => (string) View::make('front.products.cart_items')->with(compact('getCartItems'))
                ]);
            }

            //Update the QTY
            Cart::where('id', $data['cartid'])->update(['quantity' => $data['qty']]);
            $getCartItems = Cart::getCartItems();
            $totalCartItems = totalCartItems();
            Session::forget('couponAmount');
            Session::forget('couponCode');
            return response()->json([
                'status' => true,
                'totalCartItems' => $totalCartItems,
                'view' => (string) View::make('front.products.cart_items')->with(compact('getCartItems')),
                'headerview' => (string) View::make('front.layout.header_cart_items')->with(compact('getCartItems')),
            ]);
        }
    }

    public function cartDelete(Request $request)
    {
        if ($request->ajax()) {
            Session::forget('couponAmount');
            Session::forget('couponCode');

            $data = $request->all();
            
            //Forgot the cupon sessions
            Session::forget('couponAmount');
            Session::forget('couponCode');

            Cart::where('id', $data['cartid'])->delete();
            $getCartItems = Cart::getCartItems();
            $totalCartItems = totalCartItems();
            return response()->json([
                'totalCartItems' => $totalCartItems,
                'view' => (string) View::make('front.products.cart_items')->with(compact('getCartItems')),
                'headerview' => (string) View::make('front.layout.header_cart_items')->with(compact('getCartItems')),
            ]);
        }
    }

    public function applyCoupon(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            Session::forget('couponAmount');
            Session::forget('couponCode');
            // echo "<pre>"; print_r($data); die;
            $getCartItems = Cart::getCartItems();
            $totalCartItems = totalCartItems();
            $couponCount = Coupon::where('coupon_code', $data['code'])->count();
            // echo "<pre>"; print_r($couponCount); die;
            if ($couponCount == 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Empty or invalid coupon code!',
                    'totalCartItems' => $totalCartItems,
                    'view' => (string) View::make('front.products.cart_items')->with(compact('getCartItems')),
                    'headerview' => (string) View::make('front.layout.header_cart_items')->with(compact('getCartItems')),
                ]);
            } else {
                // Check for other coupon condition

                //Get Coupon Details
                $couponDetails = Coupon::where('coupon_code', $data['code'])->first();

                //Check if coupon is active
                if ($couponDetails->status == 0) {
                    $message = "The coupon is not active!";
                }

                //Check if coupon is expired
                $expiry_date = $couponDetails->expiry_date;
                $current_date = date('Y-m-d');
                if ($expiry_date < $current_date) {
                    $message = "The coupon is expired";
                }

                //Check if coupon is for single time
                if($couponDetails->coupon_type=="Single Time"){
                    $couponCount = Order::where(['coupon_code'=>$data['code'],'user_id'=>Auth::user()->id])->count();
                    if($couponCount>=1){
                        $message = "This coupon code is already availed by you!";
                    }
                }

                // Check if coupon is from selected categories
                // get all selected categories form coupon
                $catArr = explode(",", $couponDetails->categories);
                // echo "<pre>"; print_r($getCartItems); die;

                //check if any cart item not belog to coupon category
                $total_amount = 0;
                foreach ($getCartItems as $key => $item) {
                    if (!in_array($item['product']['category_id'], $catArr)) {
                        $message = "This coupon code is not for one of the selected products.";
                    }
                    $attrPrice = Product::getDiscountAttributePrice($item['product_id'], $item['size']);
                    $total_amount = $total_amount + ($attrPrice['final_price'] * $item['quantity']);
                }

                // Check if coupon is from selected users
                // get all selected users form coupon
                if (isset($couponDetails->users) && !empty($couponDetails->users)) {
                    $usersArr = explode(",", $couponDetails->users);
                    // echo "<pre>"; print_r($usersArr); die;
                    $usersId = [];
                    if (count($usersArr)) {
                        foreach ($usersArr as $key => $user) {
                            $getUserId = User::select('id')->where('id', $user)->first();
                            if ($getUserId) {
                                $usersId[] = $getUserId['id'];
                            }
                        }
                    }

                    // Check if any cart item not belog to coupon user
                    foreach ($getCartItems as $item) {
                        if (!in_array($item['user_id'], $usersId)) {
                            $message = "This coupon code is not for you. Try with valid coupon code ";
                        }
                    }

                    if ($couponDetails->vendor_id > 0) {
                        $productIds = Product::select('id')->where('vendor_id', $couponDetails->vendor_id)->pluck('id')->toArray();
                        // echo "<pre>"; print_r($productIds); die;
                        foreach ($getCartItems as $item) {
                            if (!in_array($item['product']['id'], $productIds)) {
                                $message = "This coupon code is not for you. Try with valid coupon code";
                            }
                        }
                    }
                }
                //if error message is there
                if (isset($message)) {
                    return response()->json([
                        'status' => false,
                        'totalCartItems' => $totalCartItems,
                        'message' => $message,
                        'view' => (string) View::make('front.products.cart_items')->with(compact('getCartItems')),
                        'headerview' => (string) View::make('front.layout.header_cart_items')->with(compact('getCartItems')),
                    ]);
                } else {
                    //Coupon code is correct
                    //Check if Coupon Amount type is Fixed or Percentage
                    if ($couponDetails->amount_type == "Fixed") {
                        $couponAmount = $couponDetails->amount;
                    } else {
                        $couponAmount = $total_amount * ($couponDetails->amount / 100);
                    }
                    $grand_total = $total_amount - $couponAmount;
                    // Add coupon code & amount is Session Variables
                    Session::put('couponAmount', $couponAmount);
                    Session::put('couponCode', $data['code']);
                    $message = "Coupon Code successfully applied. You are availing discount!";

                    return response()->json([
                        'status' => true,
                        'totalCartItems' => $totalCartItems,
                        'couponAmount' => $couponAmount,
                        'grand_total' => $grand_total,
                        'message' => $message,
                        'view' => (string) View::make('front.products.cart_items')->with(compact('getCartItems')),
                        'headerview' => (string) View::make('front.layout.header_cart_items')->with(compact('getCartItems')),
                    ]);
                }
            }
        }
    }

    public function checkout(Request $request)
    {
        $countries = Country::where('status', 1)->get()->toArray();
        $getCartItems = Cart::getCartItems();
        if (count($getCartItems) == 0) {
            $message = "Shopping cart is empty! Please add products to checkout!";
            return redirect()->route('cart')->with('error_message', $message);
        }

        $total_price = 0;
        $total_weight = 0;
        foreach ($getCartItems as $item){
            // dd($getCartItems);
            $attrPrice = Product::getDiscountAttributePrice($item['product_id'], $item['size']);
            $total_price = $total_price + ($attrPrice['final_price'] * $item['quantity']);
            $product_weight = $item['product']['product_weight'];
            $total_weight = $total_weight+$product_weight;
        }
        // dd($total_price);

        $deliveryAddresses = DeliveryAddress::deliveryAddresses();
        foreach($deliveryAddresses as $key => $value){
            $shippingCharges = ShippingCharges::getShippingCharges($total_weight,$value['country']);
            $deliveryAddresses[$key]['shipping_charges'] = $shippingCharges;
        }
        // dd($deliveryAddresses);


        if ($request->isMethod('post')) {
            $data = $request->all();
            //  echo "<pre>"; print_r($data); die;

              //Website Security
             foreach($getCartItems as $item) {
                // Prevent Disabled Products to Order
                $product_status = Product::getProductStatus($item['product_id']);
                if($product_status==0){
                    Product::deleteCartProduct($item['product_id']);
                    $message = "One of the product is disabled! Please try again.";
                    return redirect('/cart')->with('error_message',$message);
                }

                //Prevent Sold out Products to Order
                $getProductStock = ProductAttribute::getProductStock($item['product_id'], $item['size']);
                // dd($getProductStock);
                if($getProductStock==0){
                    // Product::deleteCartProduct($item['product_id']);
                    // $message = "One of the product is sold out! Please try again.";
                    $message =$item['product']['product_name']." with ".$item['size']."Size is not availabe. Please remove fromcart and choose some other product.";
                    return redirect('/cart')->with('error_message',$message);
                }
                
                //Prevent Disabled Attribute to Order
                $getAttributeStatus = ProductAttribute::getAttributeStatus($item['product_id'], $item['size']);
                if($getAttributeStatus==0){
                    // Product::deleteCartProduct($item['product_id']);
                    // $message = "One of the product attribute is disabled! Please try again.";
                    $message =$item['product']['product_name']." with ".$item['size']."Size is not availabe. Please remove fromcart and choose some other product.";
                    return redirect('/cart')->with('error_message',$message);
                }

                //Prevent Disabled Categories Products to Order
                $getCategoryStatus = Category::getCategoryStatus(($item['product']['category_id']));
                if($getCategoryStatus==0){
                    // Product::deleteCartProduct($item['product_id']);
                    // $message = "One of the product is disabled! Please try again.";
                    $message =$item['product']['product_name']." with ".$item['size']."Size is not availabe. Please remove fromcart and choose some other product.";
                    return redirect('/cart')->with('error_message',$message);
                }
             }

            //Delivery Address Validaiton
            if (empty($data['address_id'])) {
                $message = "Please select delivery Address!";
                return redirect()->back()->with('error_message', $message);
            }

            //Payment Method Validaiton
            if (empty($data['payment_gateway'])) {
                $message = "Please select payment method!";
                return redirect()->back()->with('error_message', $message);
            }

            //Terms and condtion Validaiton
            if (empty($data['accept'])) {
                $message = "Please accept terms & condition!";
                return redirect()->back()->with('error_message', $message);
            }

            $deliveryAddresses = DeliveryAddress::where('id', $data['address_id'])->first()->toArray();

            //Select payment_gateway method COD if COD selected else set and Prepaid
            if ($data['payment_gateway'] == "COD") {
                $payment_method = "COD";
                $order_status = "New";
            } else {
                $payment_method = "Prepaid";
                $order_status = "New";
            }

            DB::beginTransaction();

            //Fetch order Total Price
            $total_price = 0;
            foreach ($getCartItems as $item){
                $getDiscountAttributePrice = Product::getDiscountAttributePrice($item['product_id'], $item['size']);
                $total_price = $total_price + ($getDiscountAttributePrice['final_price'] * $item['quantity']);
            }

            //Calculate Shipping Charges
            $shipping_charges = 0;

            $shipping_charges = ShippingCharges::getShippingCharges($total_weight, $deliveryAddresses['country']);
            //  dd($shipping_charges);
            //Calculate Grand Total
            $couponAmount = Session::get('couponAmount', 0); // Default to 0 if no coupon amount is set
            $grand_total = $total_price + $shipping_charges - $couponAmount;

            //Insert Grand Total in Session Variable
            Session::put('grand_total', $grand_total);

            //Insert Order Details
            $order = new Order;
            $order->user_id = Auth::user()->id;
            $order->name = $deliveryAddresses['name'];
            $order->address = $deliveryAddresses['address'];
            $order->city = $deliveryAddresses['city'];
            $order->state = $deliveryAddresses['state'];
            $order->country = $deliveryAddresses['country'];
            $order->pincode = $deliveryAddresses['pincode'];
            $order->mobile = $deliveryAddresses['mobile'];
            $order->email = Auth::user()->email;
            $order->shipping_charges = $shipping_charges;
            $order->coupon_code = Session::get('couponCode');
            $order->coupon_amount = $couponAmount;
            $order->order_status = $order_status;
            $order->payment_method = $payment_method;
            $order->payment_gateway = $data['payment_gateway'];
            $order->grand_total = $grand_total;
            $order->save();
            $order_id = DB::getPdo()->lastInsertId();

            foreach ($getCartItems as $item) {
                $cartItem = new OrdersProducts;
                $cartItem->order_id = $order_id;
                $cartItem->user_id = Auth::user()->id;
                $getProductDetails = Product::select('product_code', 'product_name', 'product_color', 'admin_id', 'vendor_id')->where('id', $item['product_id'])->first()->toArray();
                $cartItem->admin_id = $getProductDetails['admin_id'];
                $cartItem->vendor_id = $getProductDetails['vendor_id'];
                $cartItem->product_id = $item['product_id'];
                $cartItem->product_code = $getProductDetails['product_code'];
                $cartItem->product_name = $getProductDetails['product_name'];
                $cartItem->product_color = $getProductDetails['product_color'];
                $cartItem->product_size = $item['size'];
                $getDiscountAttributePrice = Product::getDiscountAttributePrice($item['product_id'], $item['size']);
                $cartItem->product_price = $getDiscountAttributePrice['final_price'];
                $cartItem->product_qty = $item['quantity'];
                $cartItem->save();

                //Reduce Stock Script Starts
                $getProductStock = ProductAttribute::getProductStock($item['product_id'],$item['size']);
                $newStock = $getProductStock- $item['quantity'];
                ProductAttribute::where(['product_id'=>$item['product_id'],'size'=>$item['size']])->update(['stock'=>$newStock]);
               //Reduce Stock Script Ends Here
            }
            //Insert Order Id in Session Variable
            Session::put('order_id', $order_id);
            DB::commit();

            $orderDetails = Order::with('orders_products')->where('id',$order_id)->first()->toArray();
            // dd($orderDetails);
            if ($data['payment_gateway'] == "COD") {
                //Send Order email
                $email = Auth::user()->email;
                $messageData = [
                    'email' => $email,
                    'name' => Auth::user()->name,
                    'order_id' => $order_id,
                    'orderDetails' => $orderDetails,
                ];
                Mail::send('emails.order',$messageData,function($message)use($email){
                    $message->to($email)->subject('Order Placed - WebHatDeveloper.com');
                });
                //Send Order sms
                // $message = "Dear Customer, your order ".$order_id." has been successfully placed with WebHatDevelopers.com. We will intimate you once your order is Shipped.";
                // $mobile = Auth::user()->mobile;
                // Sms = sendSms::($message,$mobile);
                }if($data['payment_gateway']=="Paypal"){
                    return redirect('/paypal');
                } else if($data['payment_gateway'] == "eSewa") {
                    return redirect('/esewa');
               }else {
                 echo "Other Prepaid payment method comming soon...";
            }
            return redirect('thanks');
        }

        return view('front.products.checkout')->with(compact('deliveryAddresses', 'countries', 'getCartItems','total_price'));
    }

    public function thanks()
    {
        if(Session::has('order_id')){
            Cart::where('user_id',Auth::user()->id)->delete();
            return view('front.products.thanks');
        }else{
            return redirect('cart');
        }
    }
}
