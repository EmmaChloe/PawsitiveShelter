<?php
session_start();

// Include configuration file 
include_once 'config.php'; 

// Include database connection file 
include_once 'dbConn.php';

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">

  <!-- font awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

  <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />

  <title>Pawsitive Shelter</title>

  <!-- Favicons -->
  <link href="images/icon.png" rel="icon">
  <link href="images/icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="vendor/aos/aos.css" rel="stylesheet">
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/bootstrap-icons.css" rel="stylesheet">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

  <!-- Template Main CSS File -->
  <link href="css/style.css" rel="stylesheet">

  <!-- PayPal JavaScript SDK -->
  <script src="https://www.paypal.com/sdk/js?client-id=AY7dJTIxY_6EWCObkkQeuHiX-Puy-FCI8F9E20hlGK2CqTe4TAjjmHAoGf3gPqxnG5mEFxhzo07-lfJ6"></script>

  <!-- =======================================================
  * Template Name: Squadfree
  * Updated: May 30 2023 with Bootstrap v5.3.0
  * Template URL: https://bootstrapmade.com/squadfree-free-bootstrap-template-creative/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top header-transparent">
    <div class="container d-flex align-items-center justify-content-between position-relative">

      <div class="logo">
        <h1 class="text-light"><a href="index.php"><img src="images/logo.png" alt=""></a></h1>
        <!-- Uncomment below if you prefer to use an image logo -->
        <!-- <a href="index.php"><img src="img/logo.png" alt="" class="img-fluid"></a>-->
      </div>

      <nav id="navbar" class="navbar">
        <ul>
          <li><a class="nav-link " href="index.php">Home</a></li>
          <li><a class="nav-link " href="adopt.php">Adoption</a></li>
          <li><a class="nav-link active" href="donate_detail.php">Donation</a></li>
          <li><a class="nav-link " href="volunteer.php">Volunteer</a></li>
          <li><a class="nav-link " href="lost.php">Lost & Found</a></li>
          <li><a class="nav-link " href="news.php">News</a></li>
           <li class="dropdown">
            <?php
            if (!isset($_SESSION["username"])) {
                echo '<a href="login.php">Log in</a>';
            } else {
                echo '<a href="#"><span>' . $_SESSION['username'] . '</span> <i class="bi bi-chevron-down"></i></a>';
                echo '<ul>
                        <li><a href="user.php">Profile</a></li>
                        <li><a href="logout.php">Log Out</a></li>
                      </ul>';
            }
            ?>
        </li>
          
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->

    </div>
  </header><!-- End Header -->

  <!-- banner -->

<section class="banner">

    <div class="row">
        
        <div class="content">
            <h3>Donation</h3>
        <p>Your donation can change the life of a furry friend in need<br>
            and bring joy to their journey towards a loving forever home.</p> 
        </div>

        <div class="image">
            <img src="images/donate-bg.png" alt="" >
        </div>
        
    </div>

</section>

<!-- end -->

  <main>
    <section class="donate-section">
      <div class="section-overlay"></div>
      <div class="container">
        <div class="row">
          <div class="col-lg-6 col-12 mx-auto">
            <?php
            // Check for success query parameter and display success message
            if (isset($_GET['donation']) && $_GET['donation'] == 'success') {
              echo '<div class="alert alert-success" role="alert">Your donation has been submitted. Thank you!</div>';
            }

            // Check for error query parameter and display error message
            if (isset($_GET['error']) && $_GET['error'] == 'donationinsertfailed') {
              $error_message = isset($_GET['message']) ? urldecode($_GET['message']) : "Unknown error occurred";
              echo '<div class="alert alert-danger" role="alert">' . $error_message . '</div>';
            }
            ?>
            <form class="custom-form donate-form" action="insertData.php" method="post" id="paypal_form" onSubmit="return validateForm();">
              <h3 class="mb-4">Make a donation</h3>
              <div class="row">

                            <div class="col-lg-12 col-12">
                                <h5 class="mt-2 mb-3">Donation Amount</h5>
                            </div>

                            <div class="col-lg-12 col-12 form-check-group">
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1">$</span>
                                    <input type="text" class="form-control" placeholder="Custom amount" name="amount" id="amount" required>
                                </div>
                            </div>

                            <div class="col-lg-12 col-12">
                                <h5 class="mt-1">Personal Info</h5>
                            </div>

                            <div class="col-lg-6 col-12 mt-2">
                                <input type="text" name="name" id="name" class="form-control" placeholder="Name" required>
                            </div>

                            <div class="col-lg-6 col-12 mt-2">
                                <input type="email" name="email" id="email" pattern="[^ @]*@[^ @]*" class="form-control" placeholder="Email" required>
                            </div>

                            <div class="col-lg-6 col-12 mt-2">
                                <input type="phone" name="phone" id="phone" class="form-control" placeholder="Phone Number" required>
                            </div>

                            <div class="col-lg-6 col-12 mt-2">
                                <input type="date" name="donation-date" id="donation-date" class="form-control" placeholder="Date" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>

                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            
                            <!-- PayPal form fields -->
                              <input type="hidden" name="cmd" value="_donations">
                              <input type="hidden" name="business" value="YOUR_PAYPAL_EMAIL">
                              <input type="hidden" name="currency_code" value="USD">
                              <input type="hidden" name="return" value="success.php">
                              <input type="hidden" name="cancel_return" value="cancel.php">
                        
                              <!-- Donation amount input for PayPal -->
                            <div style="padding-bottom: 18px;">
                                <div id="paypal-button-container"></div>
                            </div>
                            
                            <!-- Specify a Buy Now button. -->
                            <input type="hidden" name="cmd" value="_xclick">
                            <!-- Specify URLs -->
                            <input type="hidden" name="return" value="<?php echo PAYPAL_RETURN_URL; ?>">
                            <input type="hidden" name="cancel_return" value="<?php echo PAYPAL_CANCEL_URL; ?>">

                        </div>
                    </form>

                    <!-- PayPal JavaScript SDK -->
                    <script src="https://www.paypal.com/sdk/js?client-id=YOUR_PAYPAL_CLIENT_ID"></script>

                    <!-- JavaScript code for PayPal integration -->
                    <script>
                        $(document).ready(function () {
                            // Additional validation function
                            function validateForm(event) {
                            if (!validateEmail($('#email').val().trim())) {
                                alert('Email must be a valid email address!');
                                return false;
                            }
                        
                            // Adjust the condition to check if the amount is valid
                            if (!$('#amount').val().trim()) {
                                alert('Donation Amount is required!');
                                return false;
                            }
                        
                            // Set the PayPal amount field before form submission
                            $('#paypal-amount').val($('#amount').val().trim());
                        
                            // If validation is successful, prevent the default form submission
                            event.preventDefault();
                        
                            // Trigger the PayPal form submission
                            $('#paypal_form').submit();
                            return true;
                        }


                            function validateEmail(email) {
                                var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,15}(?:\.[a-z]{2})?)$/i;
                                return isEmpty(email) || re.test(email);
                            }

                            function submitData() {
                                // Serialize form data
                                var formData = $('#paypal_form').serialize();

                                $.ajax({
                                    url: "insertData.php",
                                    type: "POST",
                                    data: formData
                                });

                                // Redirect to PayPal with form data
                                // Use the correct PayPal SDK client ID
                                window.location.href = 'https://www.sandbox.paypal.com/cgi-bin/webscr?' + formData;
                            }

                            // Initialize the PayPal button
                            paypal.Buttons({
                                style: {
                                    layout: 'horizontal'
                                },
                                createOrder: function (data, actions) {
                                    return actions.order.create({
                                        purchase_units: [{
                                            amount: {
                                                value: $('#amount').val().trim()
                                            }
                                        }]
                                    });
                                },
                                onApprove: function (data, actions) {
                                    return actions.order.capture().then(function (details) {
                                        // Handle the transaction success
                                        // You may want to perform additional actions here
                    
                                        // Send AJAX request to insert data into the Donation database
                                        var formData = $('#paypal_form').serialize();
                                        $.ajax({
                                            url: 'insertData.php',
                                            type: 'POST',
                                            data: formData,
                                            success: function(response) {
                                                // Handle the success response if needed
                                                console.log(response);
                                            },
                                            error: function(error) {
                                                // Handle the error if needed
                                                console.error(error);
                                            }
                                        });
                    
                                        window.location.href = 'success.php';
                                    });
                                }
                            }).render('#paypal-button-container');
                        });
                    </script>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- ======= Contact Section ======= -->
<section id="contact" class="contact section-bg">

</section><!-- End Contact Section -->

    <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="footer-top">
      <div class="container">
        <div class="row">

          <div class="col-lg-6 col-md-6">
            <div class="footer-info">
              <img src="images/logo.png" alt="" width="300" height="60">
              <br>
              <br>
              <strong>PAWSITIVE SHELTER MANGEMENT SYSTEM</strong>
              <br>
              <br>
              <p>
                UMSKAL Labuan <br>
                87000 Malaysia<br><br>
              <p class="pb-3"><em>Enhancing Efficiency, Adoption, and Care.</em></p>
              </p>
            </div>
          </div>

          <div class="col-lg-2 col-md-6 footer-links"></div>

          <div class="col-lg-2 col-md-6 footer-links">
            <h4>Useful Links</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="index.php">Home</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="adopt.php">Adoption</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="donate_detail.php">Donation</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="volunteer.php">Volunteer</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="lost.php">Lost & Found</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="news.php">News</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="terms.php">Terms and Condition</a></li>
            </ul>
          </div>



          </div>

        </div>
      </div>
    </div>

    <div class="container">
      <div class="copyright">
        &copy; 2023 Copyright <strong><span>Pawsitive</span></strong>. All Rights Reserved.
      </div>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/squadfree-free-bootstrap-template-creative/ -->
        For Educational Purpose Only.
      </div>
    </div>
  </footer><!-- End Footer -->


  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="vendor/aos/aos.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="vendor/glightbox/js/glightbox.min.js"></script>
  <script src="vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="vendor/swiper/swiper-bundle.min.js"></script>
  <script src="vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="js/main.js"></script>

  <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>

    <script src="js/script.js"></script>

</body>
</html>