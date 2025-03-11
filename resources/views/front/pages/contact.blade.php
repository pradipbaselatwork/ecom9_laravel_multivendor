@extends('front.layout.layout')
@section('content')
<div id="app">
    <!-- Page Introduction Wrapper -->
    <div class="page-style-a">
        <div class="container">
            <div class="page-intro">
                <h2>Contact</h2>
                <ul class="bread-crumb">
                    <li class="has-separator">
                        <i class="ion ion-md-home"></i>
                        <a href="index.html">Home</a>
                    </li>
                    <li class="is-marked">
                        <a href="contact.html">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Page Introduction Wrapper /- -->
    <!-- Contact-Page -->
    <div class="page-contact u-s-p-t-80">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="touch-wrapper">
                        <h1 class="contact-h1">Get In Touch With Us</h1>
                
                        {{-- Display Success Message --}}
                        @if (Session::has('success_message'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success: </strong>{{ Session::get('success_message') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                
                        <form action="{{ route('contact') }}" method="post">@csrf
                            <div class="group-inline u-s-m-b-30">
                                {{-- Name Input --}}
                                <div class="group-1 u-s-p-r-16">
                                    <label for="contact-name">Your Name <span class="astk">*</span></label>
                                    <input type="text" id="contact-name" name="name" class="text-field" placeholder="Name" value="{{ old('name') }}">
                                    @if ($errors->has('name'))
                                        <div class="text-danger">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>
                
                                {{-- Email Input --}}
                                <div class="group-2">
                                    <label for="contact-email">Your Email <span class="astk">*</span></label>
                                    <input type="email" id="contact-email" name="email" class="text-field" placeholder="Email" value="{{ old('email') }}">
                                    @if ($errors->has('email'))
                                        <div class="text-danger">{{ $errors->first('email') }}</div>
                                    @endif
                                </div>
                            </div>
                
                            {{-- Subject Input --}}
                            <div class="u-s-m-b-30">
                                <label for="contact-subject">Subject <span class="astk">*</span></label>
                                <input type="text" id="contact-subject" name="subject" class="text-field" placeholder="Subject" value="{{ old('subject') }}">
                                @if ($errors->has('subject'))
                                    <div class="text-danger">{{ $errors->first('subject') }}</div>
                                @endif
                            </div>
                
                            {{-- Message Textarea --}}
                            <div class="u-s-m-b-30">
                                <label for="contact-message">Message <span class="astk">*</span></label>
                                <textarea class="text-area" name="message" id="contact-message">{{ old('message') }}</textarea>
                                @if ($errors->has('message'))
                                    <div class="text-danger">{{ $errors->first('message') }}</div>
                                @endif
                            </div>
                
                            {{-- Submit Button --}}
                            <div class="u-s-m-b-30">
                                <button type="submit" class="button button-outline-secondary">Send Message</button>
                            </div>
                        </form>
                    </div>
                </div>
                
                
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="information-about-wrapper">
                        <h1 class="contact-h1">Information About Us</h1>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Similique, tempora, voluptate. Architecto aspernatur, culpa cupiditate deserunt dolore eos facere in, incidunt omnis quae quam quos, similique sunt tempore vel vero.
                        </p>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Similique, tempora, voluptate. Architecto aspernatur, culpa cupiditate deserunt dolore eos facere in, incidunt omnis quae quam quos, similique sunt tempore vel vero.
                        </p>
                    </div>
                    <div class="contact-us-wrapper">
                        <h1 class="contact-h1">Contact Us</h1>
                        <div class="contact-material u-s-m-b-16">
                            <h6>Location</h6>
                            <span>4441 Jett Lane</span>
                            <span>Bellflower, CA 90706</span>
                        </div>
                        <div class="contact-material u-s-m-b-16">
                            <h6>Email</h6>
                            <span>info@sitemakers.in</span>
                        </div>
                        <div class="contact-material u-s-m-b-16">
                            <h6>Telephone</h6>
                            <span>+111-222-333</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="u-s-p-t-80">
            <div id="map"></div>
        </div>
    </div>
    <!-- Contact-Page /- -->
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <!-- Outer-Footer -->
            <div class="outer-footer-wrapper u-s-p-y-80">
                <h6>
                    For special offers and other discount information
                </h6>
                <h1>
                    Subscribe to our Newsletter
                </h1>
                <p>
                    Subscribe to the mailing list to receive updates on promotions, new arrivals, discount and coupons.
                </p>
                <form class="newsletter-form">
                    <label class="sr-only" for="newsletter-field">Enter your Email</label>
                    <input type="text" id="newsletter-field" placeholder="Your Email Address">
                    <button type="submit" class="button">SUBMIT</button>
                </form>
            </div>
            <!-- Outer-Footer /- -->
            <!-- Mid-Footer -->
            <div class="mid-footer-wrapper u-s-p-b-80">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="footer-list">
                            <h6>COMPANY</h6>
                            <ul>
                                <li>
                                    <a href="about.html">About Us</a>
                                </li>
                                <li>
                                    <a href="contact.html">Contact Us</a>
                                </li>
                                <li>
                                    <a href="faq.html">FAQ</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="footer-list">
                            <h6>COLLECTION</h6>
                            <ul>
                                <li>
                                    <a href="cart.html">Men Clothing</a>
                                </li>
                                <li>
                                    <a href="checkout.html">Women Clothing</a>
                                </li>
                                <li>
                                    <a href="account.html">Kids Clothing</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="footer-list">
                            <h6>ACCOUNT</h6>
                            <ul>
                                <li>
                                    <a href="shop-v1-root-category.html">My Account</a>
                                </li>
                                <li>
                                    <a href="shop-v1-root-category.html">My Profile</a>
                                </li>
                                <li>
                                    <a href="listing.html">My Orders</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="footer-list">
                            <h6>Contact</h6>
                            <ul>
                                <li>
                                    <i class="fas fa-location-arrow u-s-m-r-9"></i>
                                    <span>Stack Developers Youtube Channel</span>
                                </li>
                                <li>
                                    <a href="tel:+111-222-333">
                                        <i class="fas fa-phone u-s-m-r-9"></i>
                                        <span>+111-222-333</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="mailto:info@sitemakers.in">
                                        <i class="fas fa-envelope u-s-m-r-9"></i>
                                        <span>
                                            info@sitemakers.in</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Mid-Footer /- -->
            <!-- Bottom-Footer -->
            <div class="bottom-footer-wrapper">
                <div class="social-media-wrapper">
                    <ul class="social-media-list">
                        <li>
                            <a href="#">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fab fa-twitter"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fab fa-google-plus-g"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fas fa-rss"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fab fa-pinterest"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <p class="copyright-text">Copyright &copy; 2022
                    <a href="https://youtube.com/stackdevelopers">Stack Developers</a> | All Right Reserved</p>
            </div>
        </div>
        <!-- Bottom-Footer /- -->
    </footer>
    <!-- Footer /- -->
    <!-- Dummy Selectbox -->
    <div class="select-dummy-wrapper">
        <select id="compute-select">
            <option id="compute-option">All</option>
        </select>
    </div>
    <!-- Dummy Selectbox /- -->
    <!-- Responsive-Search -->
    <div class="responsive-search-wrapper">
        <button type="button" class="button ion ion-md-close" id="responsive-search-close-button"></button>
        <div class="responsive-search-container">
            <div class="container">
                <p>Start typing and press Enter to search</p>
                <form class="responsive-search-form">
                    <label class="sr-only" for="search-text">Search</label>
                    <input id="search-text" type="text" class="responsive-search-field" placeholder="PLEASE SEARCH">
                    <i class="fas fa-search"></i>
                </form>
            </div>
        </div>
    </div>
    <!-- Responsive-Search /- -->
</div>
@endsection
