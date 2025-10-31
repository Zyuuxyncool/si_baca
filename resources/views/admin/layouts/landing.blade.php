<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $web_title ?? '' }}</title>
    <link rel="shortcut icon" href="favicon.ico">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('css/owl.carousel.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('rs-plugin/css/settings.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Oswald:200,300,400,500,600,700" rel="stylesheet">
    <style>
        .navbar-default .navbar-nav>li>a {
            padding: 20px 24px;
        }
        .quote-wrap {
            background-color: #00BEB4;
        }
        .top-bar {
            background-color: #3E5460;
        }
        .quote-wrap .quote-btn a {
            background-color: #E4022B;
            color: #fff;
        }
        .sticky {
            height: 60px;
        }
        .img-logo {
            height: 100px;
            width: auto;;
            position: absolute;
            border-radius: 4px;
            z-index: 9;
        }
        .navbar-default .navbar-nav>li>a.active {
            background-color: unset;
            color: unset;
            border-bottom: 5px solid #fdc236;
        }
        .navbar-default .navbar-nav>li:hover>a, .navbar-nav li:hover .dropdown-menu {
            background-color: unset;
            color: unset;
            border-bottom: 5px solid #fdc236;
        }
        .navbar-default .navbar-nav>li>a {
            padding: 8px 20px;
            margin-bottom: 16px;
            margin-top: 16px;
        }
        .topbar-links {
            text-align: left;
        }
        .top-text {
            float: right;
        }
        .topbar-links > li, .topbar-links > li > i {
            color: #fff;
            /*line-height: 20pt;*/
        }
        .top-text {
            display: flex; gap: 10px;padding: 0;
        }
        .top-text > i, .top-text > a > i {
            color: #fff;
            font-size: 12pt;
        }
        .top-text > span {
            font-size: 10pt;
        }
        .top-bar > .container {
            padding-top: 5px;
            padding-bottom: 5px;
        }
    </style>
</head>
<body>

<div class="top-bar">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <ul class="topbar-links">
                    <li><i class="fa fa-map-marker"></i> Address</li>
                    <li><i class="fa fa-clock-o"></i> Operation Hour : 00:00 - 00:00</li>
                </ul>
            </div>
            <div class="col-md-6">
                <div class="top-text">
                    <span>Follow Us : </span>
                    <a href=""><i class="fa fa-facebook"></i></a>
                    <a href=""><i class="fa fa-instagram"></i></a>
                    <a href=""><i class="fa fa-twitter"></i></a>
                    <a href=""><i class="fa fa-youtube"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="header">
    <div class="container">
        <div class="row">
            <div class="col-xs-3">
                <div class="logo"><img src="{{ asset('images/logo.png') }}" class="img-logo"></div>
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
                </div>
            </div>
            <div class="col-xs-9">
                <div class="navigationwrape">
                    <div class="navbar navbar-default" role="navigation">
                        <div class="navbar-collapse collapse">
                            <ul class="nav navbar-nav">
                                <li> <a href="#" class="active">Home</a></li>
                                <li> <a href="#">Projects</a></li>
                                <li> <a href="#">Services</a></li>
                                <li> <a href="#">Blog</a></li>
                                <li> <a href="#">About Us</a></li>
                                <li> <a href="#">Contact</a></li>
                            </ul>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="tp-banner-container sliderWraper">
    <div class="tp-banner" >
        <ul>
            <li data-slotamount="7" data-transition="incude" data-masterspeed="1000" data-saveperformance="on"> <img alt="" src="images/slide1.jpg" data-lazyload="images/slide1.jpg">
                <div class="caption lft large-title tp-resizeme slidertext1" data-x="left" data-y="210" data-speed="600" data-start="2200"><span>Your Dream House</span></div>
                <div class="caption lfb large-title tp-resizeme slidertext2" data-x="left" data-y="310" data-speed="600" data-start="2800">Lorem Ipsum is simply dummy text of the printing and typesetting<br>
                    industry industry's standard dummy text.</div>
                <div class="caption lfl large-title tp-resizeme slidertext3" data-x="left" data-y="350" data-speed="600" data-start="3500"><a href="#">Contact Us</a></div>
            </li>
            <li data-slotamount="7" data-transition="3dcurtain-vertical" data-masterspeed="1000" data-saveperformance="on"> <img alt="" src="images/slide1.jpg" data-lazyload="images/slide1.jpg">
                <div class="caption lfl large-title tp-resizeme slidertext1" data-x="center" data-y="210" data-speed="600" data-start="2200"><span>Construction</span></div>
                <div class="caption lfb large-title tp-resizeme slidertext4" data-x="center" data-y="310" data-speed="600" data-start="2800">Lorem Ipsum is simply dummy text of the printing and typesetting<br>
                    industry industry's standard dummy text.</div>
                <div class="caption lfl large-title tp-resizeme slidertext3" data-x="center" data-y="350" data-speed="600" data-start="3500"><a href="#">Contact Us</a></div>
            </li>
        </ul>
    </div>
</div>

<div class="quote-wrap">
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h2>Looking for a quality constructor for your next project?</h2>
            </div>
            <div class="col-md-3">
                <div class="quote-btn"><a href="#">Contact Us</a></div>
            </div>
        </div>
    </div>
</div>

<!--About start-->
<div class="about-wrap">
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <h1>About <span>Our Company</span></h1>
                <div class="aboutTxt">Curabitur congue egestas dapibus.</div>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non libero consectetur, blandit mauris eget, imperdiet nisl. Etiam commodo ex nec erat tempor varius. Nunc rutrum pretium nunc in malesuada. Curabitur mollis urna ac sapien vulputate, ut congue sapien vehicula. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam et suscipit dui. Maecenas velit quam, mollis id erat eget, scelerisque elementum odio. Maecenas ac sagittis erat. Quisque hendrerit dapibus diam, a venenatis dui efficitur nec. Aenean tincidunt ullamcorper fermentum.</p>
                <ul class="about-service">
                    <li>Lorem ipsum dolor sit amet consectetur</li>
                    <li>Lorem ipsum dolor sit amet consectetur</li>
                    <li>Ut sit amet nisl sed ante vulputate</li>
                    <li>Ut sit amet nisl sed ante vulputate</li>
                    <div class="clearfix"></div>
                </ul>
                <div class="readmore"><a href="#">Read More</a></div>
            </div>
            <div class="col-md-5">
                <div class="about-image"><img src="{{ asset('images/about-jhoss.jpg') }}"></div>
            </div>
        </div>
    </div>
</div>

<!--About start-->

<!--service start-->
<div class="service-wrap">
    <div class="container">
        <h1>Our <span>Service</span></h1>
        <ul class="row serviceList">
            <li class="col-md-4 col-sm-6">
                <div class="service-image"><img src="images/feature-image-1.jpg">
                    <div class="hoverlink">
                        <div class="icon"><a href="#"><i class="fa fa-link" aria-hidden="true"></i></a></div>
                    </div>
                </div>
                <div class="service-details">
                    <h3><a href="#">Donec justo sapien</a></h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam eu faucibus magna. Fusce et odio nec tellus egestas volutpat.</p>
                    <div class="readmore"><a href="#">Read More</a></div>
                </div>
            </li>
            <li class="col-md-4 col-sm-6">
                <div class="service-image"><img src="images/feature-image-2.jpg">
                    <div class="hoverlink">
                        <div class="icon"><a href="#"><i class="fa fa-link" aria-hidden="true"></i></a></div>
                    </div>
                </div>
                <div class="service-details">
                    <h3><a href="#">Donec justo sapien</a></h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam eu faucibus magna. Fusce et odio nec tellus egestas volutpat.</p>
                    <div class="readmore"><a href="#">Read More</a></div>
                </div>
            </li>
            <li class="col-md-4 col-sm-6">
                <div class="service-image"><img src="images/feature-image-3.jpg">
                    <div class="hoverlink">
                        <div class="icon"><a href="#"><i class="fa fa-link" aria-hidden="true"></i></a></div>
                    </div>
                </div>
                <div class="service-details">
                    <h3><a href="#">Donec justo sapien</a></h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam eu faucibus magna. Fusce et odio nec tellus egestas volutpat.</p>
                    <div class="readmore"><a href="#">Read More</a></div>
                </div>
            </li>
        </ul>
    </div>
</div>
<!--service end-->

<!--porfolio start-->
<div class="porfolio-wrap">
    <div class="container">
        <h1>Latest <span>Project</span></h1>
        <ul class="row portfolio-service">
            <li class="col-md-3 col-sm-6">
                <div class="project-image"><img src="images/portfolio.jpg">
                    <div class="portfolio-overley">
                        <div class="content">
                            <h3><a href="#">Interior Design</a></h3>
                            <div class="portfolio-tags"> <span>Builder</span>, <span>Repairman</span> </div>
                        </div>
                    </div>
                </div>
            </li>
            <li class="col-md-3 col-sm-6">
                <div class="project-image"><img src="images/portfolio2.jpg">
                    <div class="portfolio-overley">
                        <div class="content">
                            <h3><a href="#">Home</a></h3>
                            <div class="portfolio-tags"> <span>Builder</span>, <span>Repairman</span> </div>
                        </div>
                    </div>
                </div>
            </li>
            <li class="col-md-3 col-sm-6">
                <div class="project-image"><img src="images/portfolio3.jpg">
                    <div class="portfolio-overley">
                        <div class="content">
                            <h3><a href="#">Green House</a></h3>
                            <div class="portfolio-tags"> <span>Builder</span>, <span>Repairman</span> </div>
                        </div>
                    </div>
                </div>
            </li>
            <li class="col-md-3 col-sm-6">
                <div class="project-image"><img src="images/portfolio4.jpg">
                    <div class="portfolio-overley">
                        <div class="content">
                            <h3><a href="#">Home</a></h3>
                            <div class="portfolio-tags"> <span>Builder</span>, <span>Repairman</span> </div>
                        </div>
                    </div>
                </div>
            </li>
            <li class="col-md-3 col-sm-6">
                <div class="project-image"><img src="images/portfolio5.jpg">
                    <div class="portfolio-overley">
                        <div class="content">
                            <h3><a href="#">Interior Design</a></h3>
                            <div class="portfolio-tags"> <span>Builder</span>, <span>Repairman</span> </div>
                        </div>
                    </div>
                </div>
            </li>
            <li class="col-md-3 col-sm-6">
                <div class="project-image"><img src="images/portfolio6.jpg">
                    <div class="portfolio-overley">
                        <div class="content">
                            <h3><a href="#">Home</a></h3>
                            <div class="portfolio-tags"> <span>Builder</span>, <span>Repairman</span> </div>
                        </div>
                    </div>
                </div>
            </li>
            <li class="col-md-3 col-sm-6">
                <div class="project-image"><img src="images/portfolio7.jpg">
                    <div class="portfolio-overley">
                        <div class="content">
                            <h3><a href="#">Home</a></h3>
                            <div class="portfolio-tags"> <span>Builder</span>, <span>Repairman</span> </div>
                        </div>
                    </div>
                </div>
            </li>
            <li class="col-md-3 col-sm-6">
                <div class="project-image"><img src="images/portfolio8.jpg">
                    <div class="portfolio-overley">
                        <div class="content">
                            <h3><a href="#">Home</a></h3>
                            <div class="portfolio-tags"> <span>Builder</span>, <span>Repairman</span> </div>
                        </div>
                    </div>
                </div>
            </li>
            <li class="col-md-3 col-sm-6">
                <div class="project-image"><img src="images/portfolio9.jpg">
                    <div class="portfolio-overley">
                        <div class="content">
                            <h3><a href="#">Home</a></h3>
                            <div class="portfolio-tags"> <span>Builder</span>, <span>Repairman</span> </div>
                        </div>
                    </div>
                </div>
            </li>
            <li class="col-md-3 col-sm-6">
                <div class="project-image"><img src="images/portfolio10.jpg">
                    <div class="portfolio-overley">
                        <div class="content">
                            <h3><a href="#">Interior Design</a></h3>
                            <div class="portfolio-tags"> <span>Builder</span>, <span>Repairman</span> </div>
                        </div>
                    </div>
                </div>
            </li>
            <li class="col-md-3 col-sm-6">
                <div class="project-image"><img src="images/portfolio11.jpg">
                    <div class="portfolio-overley">
                        <div class="content">
                            <h3><a href="#">Home</a></h3>
                            <div class="portfolio-tags"> <span>Builder</span>, <span>Repairman</span> </div>
                        </div>
                    </div>
                </div>
            </li>
            <li class="col-md-3 col-sm-6">
                <div class="project-image"><img src="images/portfolio12.jpg">
                    <div class="portfolio-overley">
                        <div class="content">
                            <h3><a href="#">Home</a></h3>
                            <div class="portfolio-tags"> <span>Builder</span>, <span>Repairman</span> </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>
<!--porfolio end-->

<!--Testimonials start-->
<div class="testimonials-wrap">
    <div class="container">
        <div class="heading-wrap">
            <h1>Testimonials</h1>
        </div>
        <ul class="owl-carousel testimonials">
            <li class="item">
                <div class="testi-info">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis quis augue ultricies, molestie nisl mollis, efficitur velit. Nunc urna ligula, malesuada nec condimentum eu, tincidunt sit amet purus.</p>
                    <div class="name">John Doe <span>Lorem Ispum</span></div>
                    <div class="client-image"><img src="images/client.jpg"></div>
                </div>
            </li>
            <li class="item">
                <div class="testi-info">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis quis augue ultricies, molestie nisl mollis, efficitur velit. Nunc urna ligula, malesuada nec condimentum eu, tincidunt sit amet purus.</p>
                    <div class="name">John Doe <span>Lorem Ispum</span></div>
                    <div class="client-image"><img src="images/client2.jpg"></div>
                </div>
            </li>
            <li class="item">
                <div class="testi-info">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis quis augue ultricies, molestie nisl mollis, efficitur velit. Nunc urna ligula, malesuada nec condimentum eu, tincidunt sit amet purus.</p>
                    <div class="name">John Doe <span>Lorem Ispum</span></div>
                    <div class="client-image"><img src="images/client.jpg"></div>
                </div>
            </li>
            <li class="item">
                <div class="testi-info">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis quis augue ultricies, molestie nisl mollis, efficitur velit. Nunc urna ligula, malesuada nec condimentum eu, tincidunt sit amet purus.</p>
                    <div class="name">John Doe <span>Lorem Ispum</span></div>
                    <div class="client-image"><img src="images/client2.jpg"></div>
                </div>
            </li>
            <li class="item">
                <div class="testi-info">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis quis augue ultricies, molestie nisl mollis, efficitur velit. Nunc urna ligula, malesuada nec condimentum eu, tincidunt sit amet purus.</p>
                    <div class="name">John Doe <span>Lorem Ispum</span></div>
                    <div class="client-image"><img src="images/client.jpg"></div>
                </div>
            </li>
            <li class="item">
                <div class="testi-info">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis quis augue ultricies, molestie nisl mollis, efficitur velit. Nunc urna ligula, malesuada nec condimentum eu, tincidunt sit amet purus.</p>
                    <div class="name">John Doe <span>Lorem Ispum</span></div>
                    <div class="client-image"><img src="images/client2.jpg"></div>
                </div>
            </li>
        </ul>
    </div>
</div>
<!--Testimonials end-->

<!--Footer start-->
<div class="footer-wrap">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-12 col-xs-12">
                <div class="footer-logo"><a href="index.html"><img src="images/footer-logo.png"></a></div>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris aliquam neque eget ipsum porta, sed convallis odio convallis... <a href="#">Read More</a> </p>
                <div class="social"> <a href="#." target="_blank"> <i class="fa fa-facebook-square" aria-hidden="true"></i></a> <a href="#." target="_blank"><i class="fa fa-twitter-square" aria-hidden="true"></i></a> <a href="#." target="_blank"><i class="fa fa-google-plus-square" aria-hidden="true"></i></a> <a href="#." target="_blank"><i class="fa fa-linkedin-square" aria-hidden="true"></i></a> <a href="#." target="_blank"><i class="fa fa-youtube-square" aria-hidden="true"></i></a> </div>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-12">
                <h3>Quick Links</h3>
                <ul class="footer-links">
                    <li> <a href="#"> Home </a></li>
                    <li> <a href="#"> About us</a></li>
                    <li> <a href="#"> Service</a></li>
                    <li> <a href="#"> Projects </a></li>
                    <li> <a href="#"> Blog </a></li>
                    <li> <a href="#"> Contact us </a></li>
                </ul>
            </div>
            <div class="col-md-3 col-sm-4 col-xs-12">
                <h3>Services</h3>
                <ul class="footer-links">
                    <li><a href="#">Project Management</a></li>
                    <li><a href="#">Material Sourcing</a></li>
                    <li><a href="#">Space Optimization</a></li>
                    <li><a href="#">Construction &amp; installation </a></li>
                </ul>
            </div>
            <div class="col-md-3 col-sm-4 col-xs-12">
                <h3>Contact Info</h3>
                <div class="footer-address">1234 Lorem Road, ISpum A Kennesaw, GA 1234</div>
                <div class="call-us"> <a href="#">(777) 123-4567</a></div>
                <div class="fax"> <a href="#">(777) 132-4567</a></div>
            </div>
        </div>
        <div class="footerWrp">
            <div class="row">
                <div class="col-md-6 col-sm-7">
                    <div class="copyright">© 2017 company name here | All Rights Reserved</div>
                </div>
                <div class="col-md-6 col-sm-5">
                    <ul class="foot footer-links">
                        <li><a href="#">Sitemap</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Support</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Footer end-->

<!--page scroll start-->
<div class="page-scroll scrollToTop"><a href="#"><i class="fa fa-arrow-up" aria-hidden="true"></i></a></div>
<!--page scroll start-->

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="js/jquery-2.1.4.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>

<!-- Load JS siles -->
<!-- SLIDER REVOLUTION SCRIPTS  -->
<script type="text/javascript" src="rs-plugin/js/jquery.themepunch.tools.min.js"></script>
<script type="text/javascript" src="rs-plugin/js/jquery.themepunch.revolution.min.js"></script>
<!-- general script file -->
<script src="js/owl.carousel.js"></script>
<script type="text/javascript" src="js/script.js"></script>
</body>
</html>
