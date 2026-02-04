<?php
include "db.php";
 if (isset($_POST["filterCompany"])) {
        $companyName=$_POST["serachCompany"];
        $categories_query = mysqli_query($con, "SELECT * FROM companies where name='$companyName'");
    }
    else{

        if (isset($_POST['favoriteSymbols'])) {
           $symbols = json_decode($_POST['favoriteSymbols'], true);
           
           // Check if favorites array is not empty
           if (!empty($symbols) && is_array($symbols)) {
                $escapedSymbols = array_map(function($symbol) use ($con) {
                        return mysqli_real_escape_string($con, $symbol);
                    }, $symbols);
                    $symbolsList = "'" . implode("','", $escapedSymbols) . "'";

                    // Query to fetch data for favorite companies
                    $favorites_query = mysqli_query($con, "SELECT * FROM companies WHERE symbl IN ($symbolsList)");
           } else {
                // No favorites selected - set query to null so table shows with message
                $favorites_query = null;
           }
        }
       else{
        // If no POST data, set query to null so table shows with message
        $favorites_query = null;
       } 
        
    }


if(isset($_POST['setnotification'])){
    $useremail=$_POST['useremail'];
    $msg='';
    if (isset($_POST["symbolName"])) {
        $symbolName=$_POST["symbolName"];
        $downValue=$_POST["downValue"];
        $query = "INSERT INTO `down_value`(`email`, `symbol`, `down_more`,`status`) VALUES ('$useremail','$symbolName','$downValue','not sent')";
        if ($con->query($query) === TRUE) {
            $msg.='<div class="alert alert-success alert-dismissible">
                   <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  <strong>Success!</strong> Record Has Been Saved Successfully.</div>';
        } else {
             $msg ="Error: " . $query . "<br>" . $con->error;
        }  
    }
    
    if (isset($_POST["symbolnameAllTime"])) {
        $msg='';
        $symbolnameAllTime=$_POST["symbolnameAllTime"];
        $query = "INSERT INTO `new_ath`(`email`, `symbol`,`status`) VALUES ('$useremail','$symbolnameAllTime','not sent')";
        if ($con->query($query) === TRUE) {
            $msg.='<div class="alert alert-success alert-dismissible">
                   <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  <strong>Success!</strong> Record Has Been Saved Successfully.</div>';
        } else {
             $msg ="Error: " . $query . "<br>" . $con->error;
        }      
    }
}

 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Correction Territory</title>

    <link rel="icon" href="images/icon.png" type="image/x-icon">
        <!-- All in One SEO 4.6.7.1 - aioseo.com -->
        <meta name="robots" content="max-image-preview:large" />
        <meta name="generator" content="All in One SEO (AIOSEO) 4.6.7.1" />
        <meta property="og:locale" content="en_US" />
        <meta property="og:site_name" content="Correction Territory - Invest Simply" />
        <meta property="og:type" content="article" />
        <meta property="og:title" content="Tracking Stocks' Gap from All-Time Highs" />
        <meta property="og:url" content="https://correctionterritory.com/blogs/home/" />
        <meta property="article:published_time" content="2024-05-20T20:33:00+00:00" />
        <meta property="article:modified_time" content="2024-07-14T21:13:56+00:00" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="Tracking Stocks' Gap from All-Time Highs" />

        <meta name="viewport" content="width=device-width, initial-scale=1" />
  
        <link rel="icon" href="images/icon.jpg" type="image/x-icon">
        <link rel="icon" href="images/icon.jpg" type="image/jpg">
        <link rel="apple-touch-icon" href="images/icon.jpg">


        <link rel="canonical" href="https://correctionterritory.com/" class="yoast-seo-meta-tag" />
        <meta property="og:locale" content="en_US" class="yoast-seo-meta-tag" />
        <meta property="og:type" content="article" class="yoast-seo-meta-tag" />
        <meta property="og:title" content="Tracking Stocks' Gap from All-Time Highs" class="yoast-seo-meta-tag" />
        <meta property="og:url" content="https://correctionterritory.com" class="yoast-seo-meta-tag" />
        <meta property="og:site_name" content="Correction Territory" class="yoast-seo-meta-tag" />
        <meta property="article:modified_time" content="2024-07-14T21:13:56+00:00" class="yoast-seo-meta-tag" />
        <meta name="twitter:card" content="summary_large_image" class="yoast-seo-meta-tag" />
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8754771874266985"
     crossorigin="anonymous"></script>
     
     <!-- X (Twitter) Pixel -->
     <script>
     !function(e,t,n,s,u,a){e.twq||(s=e.twq=function(){s.exe?s.exe.apply(s,arguments):s.queue.push(arguments);
     },s.version='1.1',s.queue=[],u=t.createElement(n),u.async=!0,u.src='https://static.ads-twitter.com/uwt.js',
     a=t.getElementsByTagName(n)[0],a.parentNode.insertBefore(u,a))}(window,document,'script');
     twq('config','r0zgi');
     </script>
     
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
     <script src="https://www.google.com/recaptcha/api.js?render=6Lf9TEcqAAAAAHau8MDGhNq4BmRG2sjiaXhaX3P9"></script>
</head>
<div class="overlay" id="tvOverlay" onclick="closeTradingView()" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:9999;">
  <div style="position:absolute; left:0; right:0; bottom:0; height:75%; background:#fff; border-radius:16px 16px 0 0;"
       onclick="event.stopPropagation()">
    <iframe id="tvFrame" style="width:100%; height:100%; border:0;"></iframe>
  </div>
</div>
<body>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<link href="custom.css" rel="stylesheet">
<?php include "header.php" ?>
 <div class="table-responsive" id="tablesList">
    <script type="text/javascript">
function openTradingView(symbol) {
    const url =
        "https://www.tradingview.com/widgetembed/?" +
        "symbol=" + encodeURIComponent(symbol) +
        "&interval=W" +
        "&hide_side_toolbar=true" +
        "&theme=light";

    document.getElementById("tvFrame").src = url;
    document.getElementById("tvOverlay").style.display = "block";
}

function closeTradingView() {
    document.getElementById("tvOverlay").style.display = "none";
    document.getElementById("tvFrame").src = "";
}
</script>
    <style>
        #loadingicon {
            /* Initial styles for loading icon */
        }
        .hidden {
            display: none;
        }
        #company-input::-webkit-input-placeholder {
                color: #7a7a7a;
              }

              #company-input::-moz-placeholder {
                color: #7a7a7a;
              }

              #company-input:-ms-input-placeholder {
                color: #7a7a7a;
              }

              #company-input::placeholder {
                color: #7a7a7a;
              }
        
        /* Prevent All-Time from breaking into multiple lines */
        th[data-column="allTimeHighPrice"] {
            word-break: keep-all;
            white-space: normal;
        }
        th[data-column="allTimeHighPrice"] br {
            display: block;
        }
        
    </style>
<div class="loadingicon" id="loadingicon">
    <img src="images/loading-animation.gif" style="max-width:50px;display:block;margin: auto;padding:50px 0px;">
</div>

   <table id="stockTable" class="hidden table table-bordered table-striped" style="border-radius: 10px;width:100% !important;">
    <thead>
       <tr>
            <th class="cname1" data-column="name" width="25%" style="vertical-align: middle;">Company</th>
            <th class="hidemobile" data-column="marketCap" style="vertical-align: middle;">Market<br/>Cap($B)</th>
            <th data-column="lastClosePrice" style="vertical-align: middle;">Current<br/>Price($)</th>
            <th data-column="allTimeHighPrice" style="vertical-align: middle;"><span style="white-space: nowrap;">All-Time</span><br/>High($)</th>
            <th data-column="allTimeHighCount" class="hidemobile" style="vertical-align: middle;">Highs<br/>Count</th>
            <th  class="hidemobile" data-column="lastATH" style="vertical-align: middle;">Last High<br/>(Days)</th>
            <th  scope="col" id="toggleColumnHeader" style="padding:0px !important;vertical-align: middle;">
                        <!-- Dropdown embedded in table header -->
                        <div class="dropdown d-inline" style="display: block !important;">
                            <button class="btn btn-sm dropdown-toggle" type="button" id="modifyColumns" data-bs-toggle="dropdown" aria-expanded="false" style="background: #7e7e7e;color: white;padding: 0px 4px;font-size: 11px;width:100%;">
                                Modify
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="modifyColumns">
                                <li><a class="dropdown-item column-toggle" href="#" data-column="peRatio">P/E Ratio</a></li>
                                <li><a class="dropdown-item column-toggle" href="#" data-column="changeToday">Change (%)</a></li>
                                <li><a class="dropdown-item column-toggle" href="#" data-column="avgVolume">Average Volume</a></li>
                                <li class="showmobile"><a class="dropdown-item column-toggle" href="#" data-column="marketCap">Market Cap</a></li>
                                <li class="showmobile"><a class="dropdown-item column-toggle" href="#" data-column="highCount">Highs Count</a></li>
                                <li class="showmobile"><a class="dropdown-item column-toggle" href="#" data-column="lastHigh">Last High (Days)</a></li>
                            </ul>
                        </div>
                        <span>Change(%)</span> <!-- Default column text -->
                    </th>
            <th data-column="correctionRange">Correction<br/>Range(%)</th>
            <th data-column="correctionRange" style="width: 49.5781px;vertical-align: middle;padding:0px !important;"><a data-toggle="modal" data-target="#myModalFixed" style="cursor:pointer;"><img src="images/question-icon.png" style="max-width:27px" /></a></th>
        </tr>
    </thead>
    <tbody id="favoritesTableBody">
        <?php

        $hasRows = false;
        if (isset($favorites_query) && $favorites_query) {
            while ($fetch_categories = mysqli_fetch_array($favorites_query)) {
            $hasRows = true;
            $correctionRange = $fetch_categories["correction_range"];
            $market_cap = $fetch_categories["market_cap"];
            $average_volume = $fetch_categories["average_volume"];
            $converted_market_cap = convertVolume($market_cap);
            $converted_average_volume = convertVolume($average_volume);
            $exchange_value = $fetch_categories["exchange_name"];

            if ($fetch_categories["eps_value"]!='') {
               $pe_ratio = floatval($fetch_categories["last_close"])/floatval($fetch_categories["eps_value"]);
                $pe_ratio = number_format($pe_ratio, 2);

            }
            else{
                $pe_ratio='-'; 
            }
            

            if ($exchange_value == 'XNAS') {
                $exchange_name = "NASDAQ";
            } elseif ($exchange_value == 'XNYS') {
                $exchange_name = "NYSE";
            }
        ?>
            <tr>
                <td class="cname">
                    <a href="javascript:void(0)"
                       onclick="openTradingView('<?php echo $exchange_name; ?>:<?php echo $fetch_categories["symbl"]; ?>')">
                        <?php echo htmlspecialchars($fetch_categories["name"]); ?>
                    </a>
                    <span class="heart-icon" data-symbol="<?php echo htmlspecialchars($fetch_categories["symbl"]); ?>"><i class="fa fa-heart"></i></span>
                </td>
                <td  class="hidemobile"><?php echo is_numeric($converted_market_cap['billions']) ? number_format($converted_market_cap['billions'], 0) : ''; ?></td>
                <td><?php echo is_numeric($fetch_categories["last_close"]) ? number_format($fetch_categories["last_close"], 2) : ''; ?></td>
                <td><?php echo is_numeric($fetch_categories["all_time_high"]) ? number_format($fetch_categories["all_time_high"], 2) : ''; ?></td>
                <td class="hidemobile"><?php echo is_numeric($fetch_categories["all_time_high_count"]) ? number_format($fetch_categories["all_time_high_count"], 0) : ''; ?></td>
                <td class="hidemobile"><?php echo is_numeric($fetch_categories["ath_since"]) ? number_format($fetch_categories["ath_since"], 0) : ''; ?></td>
                
                <td class="toggle-column" data-pe="<?php echo $pe_ratio; ?>" data-change-today="<?php echo $fetch_categories["todaysChangePerc"]; ?>" data-avg-volume="<?php echo is_numeric($converted_average_volume['millions']) ? number_format($converted_average_volume['millions'], 0) : ''; ?>" data-market-cap="<?php echo is_numeric($converted_market_cap['billions']) ? number_format($converted_market_cap['billions'], 0) : ''; ?>" data-high-count="<?php echo is_numeric($fetch_categories["all_time_high_count"]) ? number_format($fetch_categories["all_time_high_count"], 0) : ''; ?>" data-last-high="<?php echo is_numeric($fetch_categories["ath_since"]) ? number_format($fetch_categories["ath_since"], 0) : ''; ?>"></td>

                <td class="<?php if ($correctionRange >= -5) { echo 'bg-danger'; } else if ($correctionRange >= -15) { echo 'bg-warning'; } else { echo 'bg-success'; } ?>">
                    <?php echo is_numeric($fetch_categories["correction_range"]) ? number_format($fetch_categories["correction_range"], 2) : ''; ?>
                </td>
                <td> <a data-toggle="modal" data-target="#myModal<?php echo $fetch_categories["symbl"]; ?>" style="cursor:pointer;"><img src="images/bell-icon.png" style="max-width:18px" /></a></td>
            </tr>
<!-- Modal -->
  <div class="modal fade" id="myModal<?php echo $fetch_categories["symbl"]; ?>" role="dialog">
    <div class="modal-dialog modal-md">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-body">
            <img src="images/icon-x.png" style="max-width:25px;cursor:pointer;" data-dismiss="modal">
            <div class="row" style="padding: 5px 25px;">
              <form method="POST">
                <div class="col-md-12">
                    <h5 style="text-decoration: underline;">Receive an alert sent to your email when:</h5>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="symbolName" value="<?php echo $fetch_categories['symbl']; ?>">
                            <?php echo htmlspecialchars($fetch_categories["name"]); ?> is down more than
                            <input type="text" name="downValue" placeholder="0-100">%
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="symbolnameAllTime" value="<?php echo $fetch_categories['symbl']; ?>">
                            <?php echo htmlspecialchars($fetch_categories["name"]); ?> hit a new all-time high!
                        </label>
                    </div>
                    <div class="form-group" style="display: inline-block; width: 100%;">
    <label for="usr" style="font-weight: bold; display: inline-block;">Your Email:</label>
    <input type="email" name="useremail" class="form-control" id="usr" style="display: inline-block; width: calc(100% - 100px);height:30px;" required>
</div>

                    <button type="submit" class="btn btn-sm btn-primary" style="display: block;margin: auto;" name="setnotification">Confirm</button>
                </div>
                </form>
            </div>
        </div>
      </div>
      
    </div>
  </div>    


        <?php 
            } // end while loop
            if (!$hasRows) {
                // Query executed but returned no rows
                echo '<tr><td colspan="9" style="text-align:center;padding:20px;">You haven\'t added any favorites yet. Click the heart icon next to a stock\'s name to add it to your favorites.</td></tr>';
            }
        } else {
            // No favorites selected or query failed
            echo '<tr><td colspan="9" style="text-align:center;padding:20px;">You haven\'t added any favorites yet. Click the heart icon next to a stock\'s name to add it to your favorites.</td></tr>';
        }
        if (isset($con)) {
            $con->close();
        }
        ?>
        <script>
        document.getElementById('datatext').style.display="none";
        setTimeout(function() {
            document.getElementById('loadingicon').classList.add('hidden');
            document.getElementById('stockTable').classList.remove('hidden');
            document.getElementById('datatext').style.display="block";
        }, 1); // 4000 milliseconds = 4 seconds
    </script>
    </tbody>
</table>


    </div>
    
    <br/>
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8754771874266985"
     crossorigin="anonymous"></script>
<!-- Unit1 -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-8754771874266985"
     data-ad-slot="7225743774"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>
    

</div>
<!-- Include JavaScript for handling favorite functionality -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const heartIcons = document.querySelectorAll('.heart-icon');

        heartIcons.forEach(icon => {
            icon.addEventListener('click', function() {
                const symbol = this.getAttribute('data-symbol');
                this.classList.toggle('favorited');
                const favorites = JSON.parse(localStorage.getItem('favorites')) || [];
                
                if (this.classList.contains('favorited')) {
                    this.innerHTML = '<i class="fa fa-heart"></i>'; // Filled heart
                    favorites.push(symbol);
                } else {
                    this.innerHTML = '<i class="fa fa-heart"></i>'; // Empty heart
                    const index = favorites.indexOf(symbol);
                    if (index > -1) {
                        favorites.splice(index, 1);
                    }
                }
                
                localStorage.setItem('favorites', JSON.stringify(favorites));
            });
        });

        const savedFavorites = JSON.parse(localStorage.getItem('favorites')) || [];
        heartIcons.forEach(icon => {
            if (savedFavorites.includes(icon.getAttribute('data-symbol'))) {
                icon.classList.add('favorited');
                icon.innerHTML = '<i class="fa fa-heart"></i>'; // Filled heart
            }
        });
    });
</script>

<!-- Include some CSS for the heart icon -->
<style>
    .heart-icon {
        cursor: pointer;
        margin-right: 0px;
        color: #E4E4E4;
        font-size: 14px;
    }

    .heart-icon.favorited {
        font-size: 14px;
        color: #F7D1DF;
    }
</style>
<script>
    $(document).ready(function() {
         var table = $('#stockTable').DataTable({
    searching: false,
    paging: false,
    info: false,
    order: [], // Keep this empty if you don't want any default sorting at initialization
    columnDefs: [
        { orderSequence: ["desc", "asc"], targets: "_all" }, // Default order sequence for all columns
        { orderable: false, targets: 8 } // Disable sorting for the "Correction Range" column (index 7)
    ]
});


    


        $('input[name="filterOption"]').on('change', function() {
            filterTable(this.value);
        });

        $('#companySelect').on('change', function() {
            filterTableByCompany(this.value);
        });

        function filterTable(filterValue) {
            let rows = $('#favoritesTableBody tr');
            rows.each(function() {
                let correctionRange = parseFloat($(this).find('td:eq(5)').text());
                if (filterValue === 'magnificentSeven') {
                    let magnificentSeven = ['Apple Inc.', 'Alphabet Inc.', 'Microsoft Corporation', 'Amazon.com Inc.', 'Meta Platforms', 'Tesla, Inc.', 'NVIDIA Corporation'];
                    if (magnificentSeven.includes($(this).find('td:eq(0)').text())) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                } else {
                    if (correctionRange <= parseFloat(filterValue)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                }
            });
        }

        function filterTableByCompany(companyName) {
            let rows = $('#favoritesTableBody tr');
            rows.each(function() {
                if ($(this).find('td:eq(0)').text() === companyName) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }
    });
</script>


    <script>
    $(document).ready(function() {
        // Trigger the AJAX call when a radio button is selected
        $("input[name='filterOptions']").on("change", function() {
            var selectedValue = $('input[name="filterOptions"]:checked').val(); // Get the selected radio button value
            var customnumber=$('#customnumber').val();
            // Check if the selected value is 'favorite'
            if (selectedValue === 'favorite') {
                var favorites = JSON.parse(localStorage.getItem('favorites')) || [];

        // If favorites exist, submit them to fav.php via POST
        if (favorites.length > 0) {
            // Create a form
            var form = document.createElement("form");
            form.method = "POST";
            form.action = "fav.php";

            // Create a hidden input field for favorites
            var input = document.createElement("input");
            input.type = "hidden";
            input.name = "favoriteSymbols";
            input.value = JSON.stringify(favorites); // Pass the favorites as a JSON string

            // Append the input to the form
            form.appendChild(input);

            // Append the form to the body and submit it
            document.body.appendChild(form);
            form.submit();
        }

            } else {

                if (selectedValue === 'all'){
                    console.log("test");
                    $.ajax({
                        url: "index.php",
                        type: "POST",
                        data: {
                            filterOption: selectedValue,
                            customnumber:customnumber
                        },
                        cache: false,
                        success: function(result) {
                            $("#tablesList").html(result); // Insert result into the tables list div
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error: ", status, error);
                            $("#tablesList").html("<p>An error occurred while fetching data.</p>");
                        }
                         });

                }
                else{
                  // Handle other filter options
                $.ajax({
                    url: "myData.php",
                    type: "POST",
                    data: {
                        filterOption: selectedValue,
                        customnumber:customnumber
                    },
                    cache: false,
                    success: function(result) {
                        $("#tablesList").html(result); // Insert result into the tables list div
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: ", status, error);
                        $("#tablesList").html("<p>An error occurred while fetching data.</p>");
                    }
                });  
                }
                
            }
        });
    });
</script>

<?php include "footer.php"; ?>
<!-- Cookies Consent Popup -->
<div id="cookieConsent" class="cookie-consent">
  <p>This website uses cookies to ensure you get the best experience on our website. 
    <a href="../../terms-and-conditions.php">Learn more</a>
  </p>
  <button id="acceptCookies" class="btn btn-primary btn-sm mt-2">Accept</button>
</div>
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function () {
    // Check if cookie consent is already given
    if (!localStorage.getItem("cookiesAccepted")) {
        document.getElementById("cookieConsent").style.display = "block";
    }

    // Set cookie consent on button click
    document.getElementById("acceptCookies").addEventListener("click", function () {
        localStorage.setItem("cookiesAccepted", "true");
        document.getElementById("cookieConsent").style.display = "none";
    });
});

</script>
<script>
    // Call reCAPTCHA v3 on page load
    grecaptcha.ready(function() {
      grecaptcha.execute('6Lf9TEcqAAAAAHau8MDGhNq4BmRG2sjiaXhaX3P9', {action: 'homepage'}).then(function(token) {
        // Send the token to the backend using AJAX or a form submission
        fetch('/verify-recaptcha', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            recaptcha_token: token
          })
        }).then(function(response) {
          return response.json();
        }).then(function(data) {
          // Handle the response from the server
          if (data.success) {
            console.log('Verified!');
          } else {
            console.log('Failed verification.');
          }
        });
      });
    });
  </script>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data (reCAPTCHA token)
    $recaptcha_token = file_get_contents("php://input");
    $recaptcha_data = json_decode($recaptcha_token, true);
    $recaptcha_token = $recaptcha_data['recaptcha_token'];

    // Verify the token with Google
    $recaptcha_secret = '6Lf9TEcqAAAAAI5oeZjOf08LhtOrAY4ueADnqDfI';
    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';

    $response = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_token);
    $response_data = json_decode($response);

    // Check if verification was successful and the score is high enough
    if ($response_data->success && $response_data->score >= 0.5) {
         json_encode(['success' => true]);
    } else {
         json_encode(['success' => false]);
    }
}
?>

<script>
        // Function to set a cookie
        function setCookie(name, value, days) {
            let expires = "";
            if (days) {
                const date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (value || "") + expires + "; path=/";
        }

        // Function to get a cookie
        function getCookie(name) {
            const nameEQ = name + "=";
            const ca = document.cookie.split(';');
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) === ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        }

        // Function to display the default column based on the cookie
        function setDefaultColumn() {
            const defaultColumn = getCookie("defaultColumn") || "changeToday"; // Default to Change Today if no cookie is set
            const toggleColumnHeader = document.getElementById('toggleColumnHeader');
            const toggleColumnCells = document.querySelectorAll('.toggle-column');

            const columnData = {
                peRatio: { header: 'P/E Ratio', dataAttribute: 'data-pe' },
                changeToday: { header: 'Change(%)', dataAttribute: 'data-change-today' },
                avgVolume: { header: 'Volume(M)', dataAttribute: 'data-avg-volume' },
                marketCap: { header: 'MKT Cap', dataAttribute: 'data-market-cap' },
                highCount: { header: 'High Count', dataAttribute: 'data-high-count' },
                lastHigh: { header: 'Last High', dataAttribute: 'data-last-high' }
            };

            // Set the header and cells based on the cookie
            const column = columnData[defaultColumn];
            toggleColumnHeader.querySelector('span').textContent = column.header;
            toggleColumnCells.forEach(function(cell) {
                const newData = cell.getAttribute(column.dataAttribute);
                cell.textContent = newData;
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            setDefaultColumn(); // Set the default column on page load

            const columnToggles = document.querySelectorAll('.column-toggle');
            const toggleColumnHeader = document.getElementById('toggleColumnHeader'); // Select the header for the 7th column
            const toggleColumnCells = document.querySelectorAll('.toggle-column'); // Select all cells in the 7th column

            const columnData = {
                peRatio: { header: 'P/E Ratio', dataAttribute: 'data-pe' },
                changeToday: { header: 'Change(%)', dataAttribute: 'data-change-today' },
                avgVolume: { header: 'Volume(M)', dataAttribute: 'data-avg-volume' },
                marketCap: { header: 'MKT Cap', dataAttribute: 'data-market-cap' },
                highCount: { header: 'High Count', dataAttribute: 'data-high-count' },
                lastHigh: { header: 'Last High', dataAttribute: 'data-last-high' }
            };

            columnToggles.forEach(function(toggle) {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();

                    const columnKey = this.getAttribute('data-column'); // Get which column was clicked
                    const column = columnData[columnKey]; // Find corresponding data from the object

                    // Update the header for the 7th column
                    toggleColumnHeader.querySelector('span').textContent = column.header;

                    // Update each cell in the 7th column using the respective data attribute
                    toggleColumnCells.forEach(function(cell) {
                        const newData = cell.getAttribute(column.dataAttribute);
                        cell.textContent = newData;
                    });

                    // Set the cookie for the selected column
                    setCookie("defaultColumn", columnKey, 7); // Cookie expires in 7 days
                });
            });
        });
    </script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php 
include "suggestions.php";
function convertVolume($volume) {
                    // Ensure $volume is numeric
                    if (!is_numeric($volume) || $volume == '') {
                        return [
                            'billions' => 0,
                            'millions' => 0
                        ];
                    }

                    $billions = $volume / 1_000_000_000;
                    $billions_rounded = round($billions, 2);
                    
                    $millions = $volume / 1_000_000;
                    $millions_rounded = round($millions, 2);

                    return [
                        'billions' => $billions_rounded,
                        'millions' => $millions_rounded
                    ];
                }
 ?>

</body>
</html>
