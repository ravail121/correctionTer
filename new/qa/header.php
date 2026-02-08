<style>
        /* Full-width navbar with transparent background and fixed height */
        .navbar {
            width: 100%;
            height: 100px;
            padding-left: 0px;
            padding-right: 0px;
            box-sizing: border-box;
            background-color: #333333;
            position: fixed;
            top: 0;
            z-index: 1080;
        }

        /* Centered logo */
        .navbar-brand {
            position: absolute;
            left: 50%;
            top: 0px;
            transform: translateX(-50%);
            height: 100%;
            display: flex;
            align-items: center;
        }

        /* Toggle button on the right */
        .navbar-toggler {
            float: left;
            color: #fff;
            padding: .15rem .35rem;
            font-size: 20px;
            border: 2px solid rgb(139 139 139) !important;
        }

        /* Side menu styling */
        .side-menu {
            position: fixed;
            top: 0;
            right: -40%;
            width: 17%;
            height: 100%;
            background-color: #fff;
            transition: right 0.3s ease-in-out;
            z-index: 1090;
            padding: 1rem;
            box-shadow: -2px 0 5px rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* When the menu is open */
        .side-menu.open {
            animation: slideIn 0.3s forwards;
            left: 0;
        }

        @keyframes slideIn {
            from {
                right: -40%;
            }
            to {
                right: 0;
            }
        }

        /* Overlay background */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1030;
        }

        .overlay.show {
            display: block;
        }

        /* Close button styling */
        .close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 30px;
            color: #333;
            cursor: pointer;
        }

        /* Navigation item styling */
        .side-menu .nav-link {
            font-size: 18px;
            color: #333;
            margin-bottom: 0;
            text-align: left;
            padding-left: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
        }

        .bold {
            font-weight: bold;
        }

        .main_cat_li li a {
            font-size: 16px;
            line-height: 16px;
        }

        .main_cat_li li a:hover {
            font-weight: bold;
        }

        .full-width-container {
            width: 100%;
            padding-left: 20px;
            padding-right: 20px;
            box-sizing: border-box;
        }

         @media (max-width: 768px) {
            .navbar-brand {
                left: 60%;
            }
            .navbar-toggler {
              margin-left:8px;
            }
            .side-menu {
            width: 40%;
                
            }
            .modal {
                top: 50px !important;
                
            }
            .alertText{
                font-size:15px !important;
            }
            .checkbox label {
                    display: flex;
                    align-items: center;
                    font-size: 13px;
                }
        }        
    </style>

    <!-- Navbar -->
    <nav class="navbar navbar-dark">
        <div class="container"  style="padding:0px !important;">
            <!-- Centered Logo -->
            <a class="navbar-brand" href="index.php">
                 <span style="line-height: 22px;font-size: 20px;font-weight: 600;font-weight: 900;font-family: arial;">CORRECTION <br/>TERRITORY <br/><i style="font-size: 14px;font-weight: 200;">invest simply</i></span>
                <img src="images/logo-bear.png"
                    alt="Logo" width="150">
            </a>
            <!-- Toggle button on the right -->
            <button class="navbar-toggler" type="button" id="menuToggle">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <!-- White side menu -->
    <div class="side-menu" id="sideMenu">
        <!-- Close button -->
        <span class="close-btn" id="closeMenu">&times;</span>

        <!-- Menu items -->
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link bold" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link bold" href="../../about.php">About Us</a>
            </li>
            <li class="nav-item">
                <a class="nav-link bold" href="../../contact-us.php">Contact Us</a>
            </li>
        </ul>
    </div>

    <!-- Overlay behind the menu -->
    <div class="overlay" id="overlay"></div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle side menu and overlay
        document.getElementById('menuToggle').addEventListener('click', function () {
            var sideMenu = document.getElementById('sideMenu');
            var overlay = document.getElementById('overlay');

            // Toggle menu visibility
            sideMenu.classList.toggle('open');

            // Show/hide overlay
            overlay.classList.toggle('show');
        });

        // Hide the side menu and overlay when clicking on the overlay
        document.getElementById('overlay').addEventListener('click', function () {
            document.getElementById('sideMenu').classList.remove('open');
            this.classList.remove('show');
        });

        // Close the menu when the close button is clicked
        document.getElementById('closeMenu').addEventListener('click', function () {
            document.getElementById('sideMenu').classList.remove('open');
            document.getElementById('overlay').classList.remove('show');
        });
    </script>
<div class="container mycontain">
   <h3 class="customheading mt-6">S&P 500 Stocks and Their Current<br/> Percentage Gap to All-Time High</h3>
   <div class="row">
       <div class="offset-md-4 col-md-4 offset-sm-2 col-sm-8 offset-2 col-8">
        <form method="POST" action="index.php">
         <div class="input-group">
              <input type="text" class="form-control" placeholder="Search Company" id="company-input" name="serachCompany" style="border-radius: 6px;height:28px;" onkeyup="showSuggestions()">
              <button class="input-group-append" name="filterCompany" style="border:0px;padding:0px;margin: 0px;height: max-content;margin-left: 5px;background: none;">
                <span class="input-group-text" style="border-bottom-right-radius: 14px;border-top-right-radius: 14px;"><img src="images/search.png" style="width: 14px;"></span>
              </button>
            </div> 
        </form>     
            <div id="suggestions" class="suggestion-box"></div>
       </div>
   </div>
   <div class="container mt-4">
    <div class="row mb-1 filterList">
        <div class="col-12 col1-12 d-flex flex-wrap justify-content-center" id="scrollableDiv">
<!-- Big 7 (default / first) -->
<div class="form-check form-check-inline">
    <a href="index.php?filter=big-7">
        <label class="form-check-label custom-btn <?php if (!isset($_GET['filter']) || $_GET['filter']=='big-7'){echo 'checked';} ?>">
            Big 7
        </label>
    </a>
</div>

<!-- Crypto -->
<div class="form-check form-check-inline">
    <a href="crypto.php?filter=crypto">
        <label class="form-check-label custom-btn <?php if (isset($_GET['filter']) && $_GET['filter']=='crypto'){echo 'checked';} ?>">
            Crypto
        </label>
    </a>
</div>

<!-- Indexes -->
<div class="form-check form-check-inline">
    <a href="efts.php?filter=eft">
        <label class="form-check-label custom-btn <?php if (isset($_GET['filter']) && $_GET['filter']=='eft'){echo 'checked';} ?>">
            Indexes
        </label>
    </a>
</div>

<!-- Favorites -->
<div class="form-check form-check-inline">
    <input class="form-check-input btn-check" type="radio" name="filterOptions" id="filterOptionsFav" value="favorite" <?php if (isset($_GET['filter']) && $_GET['filter']=='favorites'){echo 'checked';} ?>>
    <label class="form-check-label custom-btn <?php if (isset($_GET['filter']) && $_GET['filter']=='favorites'){echo 'checked';} ?>" for="filterOptionsFav">Favorites</label>
</div>

<!-- Popular -->
<div class="form-check form-check-inline">
    <a href="index.php?filter=pouplar">
        <label class="form-check-label custom-btn <?php if (isset($_GET['filter']) && $_GET['filter']=='pouplar'){echo 'checked';} ?>">
            Popular
        </label>
    </a>
</div>

<!-- All -->
<div class="form-check form-check-inline">
    <a href="index.php?filter=all">
        <label class="form-check-label custom-btn <?php if (isset($_GET['filter']) && $_GET['filter']=='all'){echo 'checked';} ?>">
            All
        </label>
    </a>
</div>

        </div>
    </div>
</div>
<?php
if (isset($_GET['filter'])) {
    $filters=$_GET['filter'];
    if ($filters=="down-10" || $filters=="big-7") {?>
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
    var scrollableDiv = document.getElementById('scrollableDiv');
    // Scroll to the rightmost position
    scrollableDiv.scrollLeft = scrollableDiv.scrollWidth;

    // Optionally, if the above does not work immediately, you can use setTimeout
    setTimeout(() => {
        scrollableDiv.scrollLeft = scrollableDiv.scrollWidth;
    }, 0); // The delay can be adjusted if necessary
});

</script>
   <?php } } ?>

<!-- Google Analytics - Filter Click Tracking -->
<script type="text/javascript">
document.addEventListener("DOMContentLoaded", function() {
    // Track clicks on filter link buttons (Big 7, Crypto, Indexes, Popular, All)
    var filterLinks = document.querySelectorAll('#scrollableDiv a');
    filterLinks.forEach(function(link) {
        link.addEventListener('click', function() {
            // Get button name from the label text
            var buttonName = this.querySelector('label') ? this.querySelector('label').textContent.trim() : 'Unknown';
            
            // Google Analytics gtag tracking
            if (typeof gtag !== 'undefined') {
                gtag('event', 'filter_event_google', {
                    'button_name': buttonName
                });
            }
        });
    });
    
    // Track clicks on Favorites radio button
    var favoritesRadio = document.getElementById('filterOptionsFav');
    if (favoritesRadio) {
        favoritesRadio.addEventListener('change', function() {
            if (this.checked) {
                var buttonName = 'Favorites';
                
                // Google Analytics gtag tracking
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'filter_event_google', {
                        'button_name': buttonName
                    });
                }
            }
        });
    }
});
</script>
 

    <?php if (isset($msg)) { echo $msg;} ?>
        <p class="hidden text-muted text1" id="datatext">Data Last Updated:
                    <?php
                        date_default_timezone_set("Asia/Jerusalem");
                        echo date('d/m/Y, g:')."00 ".date('A');
                        ?>
                    </p>                 
  <!-- Modal -->
  <div class="modal fade" id="myModalFixed" role="dialog">
    <div class="modal-dialog modal-md">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-body">
            <img src="images/icon-x.png" style="max-width:25px;cursor:pointer;" data-dismiss="modal">
             <div class="container" style="width: 85%; margin: auto;">
                    <div class="row bg-danger mb-1" style="border: 2px solid #F2A0A4; margin-bottom: 1px;border-radius: 10px;text-align: center;">
                        <div class="col-6 pt-2 pb-2" style="border-right: 2px solid #F2A0A4;">
                            <b>Expensive</b>
                        </div>
                        <div class="col-6 pt-2 pb-2 text-center">
                            0% to -5%
                        </div>
                    </div>
                    <div class="row bg-warning mb-1" style="border: 2px solid #FDDF80;border-radius: 10px;text-align: center;">
                        <div class="col-6 pt-2 pb-2"  style="border-right: 2px solid #FDDF80;">
                            <b>Mid-Range</b>
                        </div>
                        <div class="col-6 pt-2 pb-2 text-center">
                            -5% to -15%
                        </div>
                    </div>
                    <div class="row bg-success mb-1" style="border: 2px solid #73C2B5; border-radius: 10px;text-align: center;">
                        <div class="col-6 pt-2 pb-2"  style="border-right: 2px solid #73C2B5;">
                            <b>Cheap</b>
                        </div>
                        <div class="col-6 pt-2 pb-2 text-center">
                            -15% or less
                        </div>
                    </div>
                </div>

                    <p style="text-align:center;margin:0px;" class="mt-3">All website information is from the last 5 years.</p>
                    <p style="text-align:center;">The data is updated every hour.</p>
        </div>
      </div>
      
    </div>
  </div>    


<!------ Popups ---->
<?php 
$categories_query = mysqli_query($con, "SELECT * FROM companies");
 while ($fetch_popup = mysqli_fetch_array($categories_query)) {
            $correctionRange = $fetch_popup["correction_range"];
            $market_cap = $fetch_popup["market_cap"];
            $average_volume = $fetch_popup["average_volume"];
            $converted_market_cap = convertVolume($market_cap);
            $converted_average_volume = convertVolume($average_volume);
            $exchange_value = $fetch_popup["exchange_name"];

            $eps = floatval($fetch_popup["eps_value"]);

            if ($eps != 0.0) {
                $pe_ratio = floatval($fetch_popup["last_close"]) / $eps;
                $pe_ratio = number_format($pe_ratio, 2);
            } else {
                $pe_ratio = '-';
            }

            if ($exchange_value == 'XNAS') {
                $exchange_name = "NASDAQ";
            } elseif ($exchange_value == 'XNYS') {
                $exchange_name = "NYSE";
            }

?>
<div class="modal fade" id="myModalPoup<?php echo $fetch_popup["id"]; ?>" role="dialog">
    <div class="modal-dialog modal-md">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-body">
            <img src="images/icon-x.png" style="max-width:25px;cursor:pointer;" data-dismiss="modal">
            <div class="row" style="padding: 5px 0px;">
              <form method="POST" onsubmit="return validateForm(<?php echo $fetch_popup['id']; ?>)">
                <div class="col-md-12">
                    <h5 class="alertText" style="text-decoration: underline;">Receive an alert sent to your email when:</h5>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="symbolName" value="<?php echo $fetch_popup['symbl']; ?>">
                            <?php echo htmlspecialchars($fetch_popup["name"]); ?> is down more than
                            <input type="text" name="downValue" placeholder="0-100">%
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="symbolnameAllTime" value="<?php echo $fetch_popup['symbl']; ?>">
                            <?php echo htmlspecialchars($fetch_popup["name"]); ?> hit a new all-time high!
                        </label>
                    </div>
                    <div class="form-group" style="display: inline-block; width: 100%;">
                        <label for="usr" style="font-weight: bold; display: inline-block;">Your Email:</label>
                        <input type="email" name="useremail" class="form-control" id="usr" style="display: inline-block; width: calc(100% - 100px);height:30px;" required>
                    </div>
                    <!-- Error message placeholder -->
                    <p id="error-message-<?php echo $fetch_popup['id']; ?>" style="color: red; display: none;">Please select at least one option before submitting.</p>

                    <button type="submit" class="btn btn-sm btn-primary" style="display: block;margin: auto;" name="setnotification">Confirm</button>
                </div>
                </form>
            </div>
        </div>
      </div>
      
    </div>
  </div>   
<?php } ?>

<script>
function validateForm(modalId) {
    // Get the modal by ID
    var modal = document.getElementById('myModalPoup' + modalId);
    // Find all checkboxes inside this modal
    var checkboxes = modal.querySelectorAll('input[type="checkbox"]');
    var isChecked = false;

    // Loop through all checkboxes to see if any are checked
    checkboxes.forEach(function(checkbox) {
        if (checkbox.checked) {
            isChecked = true;
        }
    });

    // Get the error message element
    var errorMessage = document.getElementById('error-message-' + modalId);

    if (!isChecked) {
        // Display the error message if no checkbox is selected
        errorMessage.style.display = 'block';
        return false; // Prevent form submission
    }

    // Hide the error message if at least one checkbox is selected
    errorMessage.style.display = 'none';
    return true; // Allow form submission
}
</script>


