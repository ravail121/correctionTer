<style type="text/css">
    .checked {
    background-color: #D9D9D9 !important; /* Selected background */
    color: black !important; /* Selected text color */
}
.checked:hover {
    background-color: #D9D9D9 !important; /* Selected background */
    color: black !important; /* Selected text color */
}
</style>
<style>
        #loadingicon {
            /* Initial styles for loading icon */
        }
        .hidden {
            display: none;
        }
    </style>
<div class="loadingicon" id="loadingicon">
    <img src="images/loading-animation.gif" style="max-width:50px;display:block;margin: auto;padding:50px 0px;">
</div>
<script>
        document.getElementById('datatext').style.display="none";
        setTimeout(function() {
            document.getElementById('loadingicon').classList.add('hidden');
            document.getElementById('stockTable').classList.remove('hidden');
            document.getElementById('datatext').style.display="block";
        }, 1000); // 4000 milliseconds = 4 seconds
    </script>
<?php
 include "db.php";
 $filterOption = $_POST['filterOption'];
 //$customnumber=$_POST['customnumber'];
 //$customnumber='-'.$customnumber;
if ($filterOption != 'favorite' && $filterOption != 'indexes' && $filterOption!='efts' && $filterOption!='crypto') {
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<style>
     .customheading{
            text-align: center;
                font-size: 28px;
    font-weight: 700;
        }
    .form-check-input[type=radio] {
            border-radius: 50%;
            margin-top: 4px !important;
        }
    .bg-warning {
        background-color: #FFF9C4 !important;
    }
    .bg-success {
        background-color: #ACE5DC !important;
    }
    .bg-danger{
        background-color: #F7D1DF !important;
    }
    .mynumber {
    font-size: 13px !important;
    height: 15px !important;
    width: 30px !important;
    border: 1px solid white !important;
    box-shadow: 1px 1px 1px 1px white !important;
    border-bottom: 1px solid black !important;
    margin-top: -1px !important;
    padding: 5px !important;
}
    .bg-danger, .bg-success, .bg-warning {
        color: #747474 !important;
        font-weight: 500;
    }
    thead {
        font-size: 13px;
    }
    table {
        text-align: center;
    }
    .cname1 {
        text-align: left !important;
        vertical-align: middle !important;
        padding-left: 10px !important;
    }
    .cname {
        text-align: left !important;
    position: relative; /* Establish a positioning context for absolute positioning */
    padding-right: 20px; /* Add padding to the right for the icon */
}

.heart-icon {
    position: absolute; /* Position the heart icon absolutely */
    right: 3px; /* Align it to the right of the cell */
    top: 50%; /* Center vertically */
    transform: translateY(-50%); /* Adjust to perfect vertical centering */
    color: red; /* Optional: Set the color of the heart icon */
}

    
    table.dataTable tbody tr {
        background-color: #ffffc500;
    }
    table.dataTable thead th, table.dataTable thead td {
            padding: 5px -5px;
            border-bottom: 1px solid #111;
        }
    table.dataTable thead th{
    padding: 5px 10px !important;
    border-bottom: 1px solid #111;
     }    
    
    table.dataTable thead .sorting {
        background-image:none;
    }    
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #ffffc500;
    }  
    .mycustomcol{
        margin-top:-40px;
    }  
        
    @media (max-width: 768px) {
        
    .mycustomcol{
        margin-top:-0px;
    }  
        .hidemobile {
            display: none;
        }
        .filterList{
            padding:0px 10px;
        }
        .text1{
            padding:0px 10px !important;
        }
        .heart-icon.favorited {
        font-size: 14px !important;
    }
    .heart-icon {
        cursor: pointer;
        margin-right: 0px !important;
        color: #000;
        font-size: 14px !important;
    }
    table.dataTable thead th {
    padding: 2px 10px !important;
    border-bottom: 1px solid #111;
}
        .customheading{
            padding-left:10px;
            font-size:23px;
            text-align: center;
        }
            .mynumber {
        font-size: 13px !important;
        height: 13px !important;
        width: 30px !important;
        border: 1px solid white !important;
        box-shadow: 1px 1px 1px 1px white !important;
        border-bottom: 1px solid black !important;
        margin-top: -3px !important;
        padding: 5px !important;
    }
        thead, table {
            font-size: 10px;
        }
        
        table.dataTable thead th {
            padding-right: 10px; /* Add padding to the right of header cells */
        }
        
        table.dataTable thead th.sorting:after, 
        table.dataTable thead th.sorting_asc:after, 
        table.dataTable thead th.sorting_desc:after {
            margin-left: 5px; /* Adjust the margin between text and arrow */
        }
        
    }
.table-striped>tbody>tr:nth-of-type(odd)>* {
--bs-table-accent-bg: rgb(0 0 0 / 0%)!important;
border-bottom: 1px solid #e7e7e7;
color: #636464;
text-align: center;
font-size:14px !important;
}
table.dataTable tbody th, table.dataTable tbody td {
border-bottom: 1px solid #e7e7e7;
color:#636464;
text-align: center;
font-size:14px !important;
}
a {
    color: #4289f1;
    text-decoration: none;
}

.text{
    color: #f1f1f1;
    text-decoration:none;
}
.text:hover{
    color: #ffffff;
    cursor: pointer;
    text-decoration:none;
}
.footer-ul{
    list-style: none;
}
.footer-ul>li{
    padding-top: 12px;
    border-bottom: 1px solid #ffffff3b;
    padding-bottom: 3px;
}
  

.bootstrap-select .dropdown-menu li a span.text {
    color: #0e0e0e !important;
}
.mt-6 {
   margin-top: 8.4rem !important;
    margin-bottom:2rem !important;
}
.hidedesktop{
    display: none;
}
.filter-option-inner-inner {
    color: black;
    font-weight:500;
}
.dropup .dropdown-toggle::after {
    font-size: 24px !important;
    color: black;
}
.dropdown-toggle::after {
     font-size: 24px !important;
    color: black;
}
.mycontain{
            padding:0px 5px !important;
            padding-bottom:50px !important;
        }
</style>

<table id="stockTable" class="hidden table table-bordered table-striped" style="border-radius: 10px;">
    <thead>
       <tr>
            <th class="cname1" data-column="name" width="25%" style="vertical-align: middle;">Company</th>
            <th class="hidemobile" data-column="marketCap" style="vertical-align: middle;">Market<br/>Cap($B)</th>
            <th data-column="lastClosePrice" style="vertical-align: middle;">Current<br/>Price($)</th>
            <th data-column="allTimeHighPrice" style="vertical-align: middle;">All Time<br/>High($)</th>
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
            <th data-column="correctionRange" style="width: 49.5781px;vertical-align: middle;padding:0px !important;"><a data-toggle="modal" data-target="#myModalFixed" style="cursor:pointer;"><img src="images/question-icon.png" style="max-width:30px" /></a></th>
        </tr>
    </thead>
    <tbody>
<?php
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
    
    if ($filterOption=='-10' || $filterOption=='-20' || $filterOption=='-30' || $filterOption=='-40') {
        $categories_query = mysqli_query($con, "SELECT * FROM companies where market_cap>0 and correction_range<$filterOption");
    }
    elseif ($filterOption=='magnificentSeven') {
        $categories_query = mysqli_query($con, "SELECT * FROM companies where market_cap>0 AND symbl='GOOGL' OR symbl='AMZN' OR symbl='AAPL' OR symbl='META' OR symbl='MSFT' OR symbl='NVDA' OR symbl='TSLA'");
    }
    elseif ($filterOption=='custom') {

        $categories_query = mysqli_query($con, "SELECT * FROM companies where market_cap>0 and correction_range<$customnumber");
    }
    elseif ($filterOption=='all') {
      $categories_query = mysqli_query($con, "SELECT * FROM companies where market_cap>0 ORDER BY name ASC");
    }
    elseif ($filterOption=='pouplar') {
      $categories_query = mysqli_query($con, "SELECT * 
FROM companies 
WHERE symbl IN ('AAPL', 'TSLA', 'NVDA', 'MSFT', 'GOOGL', 'AMZN', 'META', 'NFLX', 'BRK.B', 'AMD', 'KO', 'WMT', 'V', 'INTC', 'DIS', 'PFE', 'CRM', 'PYPL', 'ZM', 'QCOM')
ORDER BY 
  CASE symbl
    WHEN 'AAPL' THEN 1  -- Apple
    WHEN 'TSLA' THEN 2  -- Tesla
    WHEN 'NVDA' THEN 3  -- Nvidia
    WHEN 'MSFT' THEN 4  -- Microsoft
    WHEN 'GOOGL' THEN 5  -- Google
    WHEN 'AMZN' THEN 6  -- Amazon
    WHEN 'META' THEN 7  -- Meta
    WHEN 'NFLX' THEN 8  -- Netflix
    WHEN 'BRK.B' THEN 9  -- Berkshire Hathaway
    WHEN 'AMD' THEN 10  -- Advanced Micro Devices
    WHEN 'KO' THEN 11  -- The Coca-Cola Company
    WHEN 'WMT' THEN 12  -- Walmart Inc.
    WHEN 'V' THEN 13  -- Visa
    WHEN 'INTC' THEN 14  -- Intel Corporation
    WHEN 'DIS' THEN 15  -- The Walt Disney Company
    WHEN 'PFE' THEN 16  -- Pfizer
    WHEN 'CRM' THEN 17  -- Salesforce
    WHEN 'PYPL' THEN 18  -- PayPal Holdings
    WHEN 'ZM' THEN 19  -- Zoom Video Communications
    WHEN 'QCOM' THEN 20  -- Qualcomm
  END");
    }

    
    while ($fetch_categories = mysqli_fetch_array($categories_query)) {
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
                    <a href="https://www.google.com/finance/quote/<?php echo $fetch_categories["symbl"]; ?>:<?php echo $exchange_name; ?>?hl=en&window=1Y" target="_blank">
                        <?php echo htmlspecialchars($fetch_categories["name"]); ?>
                    </a>
                    <span class="heart-icon" data-symbol="<?php echo htmlspecialchars($fetch_categories["symbl"]); ?>">&#9829;</span>
                </td>
                <td  class="hidemobile"><?php echo is_numeric($converted_market_cap['billions']) ? number_format($converted_market_cap['billions'], 0) : ''; ?></td>
                <td><?php echo is_numeric($fetch_categories["last_close"]) ? number_format($fetch_categories["last_close"], 2) : ''; ?></td>
                <td><?php echo is_numeric($fetch_categories["all_time_high"]) ? number_format($fetch_categories["all_time_high"], 2) : ''; ?></td>
                <td class="hidemobile"><?php echo is_numeric($fetch_categories["all_time_high_count"]) ? number_format($fetch_categories["all_time_high_count"], 0) : ''; ?></td>
                <td class="hidemobile"><?php echo is_numeric($fetch_categories["ath_since"]) ? number_format($fetch_categories["ath_since"], 0) : ''; ?></td>
                
                <td class="toggle-column" data-pe="<?php echo $pe_ratio; ?>" data-change-today="<?php echo $fetch_categories["todaysChangePerc"]; ?>" data-avg-volume="<?php echo is_numeric($converted_average_volume['millions']) ? number_format($converted_average_volume['millions'], 0) : ''; ?>" data-market-cap="<?php echo is_numeric($converted_market_cap['billions']) ? number_format($converted_market_cap['billions'], 0) : ''; ?>" data-high-count="<?php echo is_numeric($fetch_categories["all_time_high_count"]) ? number_format($fetch_categories["all_time_high_count"], 0) : ''; ?>" data-last-high="<?php echo is_numeric($fetch_categories["ath_since"]) ? number_format($fetch_categories["ath_since"], 0) : ''; ?>">1.5</td>

                <td class="<?php if ($correctionRange >= -5) { echo 'bg-danger'; } else if ($correctionRange >= -15) { echo 'bg-warning'; } else { echo 'bg-success'; } ?>">
                    <?php echo is_numeric($fetch_categories["correction_range"]) ? number_format($fetch_categories["correction_range"], 2) : ''; ?>
                </td>
                <td> <a data-toggle="modal" data-target="#myModal<?php echo $fetch_categories["symbl"]; ?>" style="cursor:pointer;"><img src="images/bell-icon.png" style="max-width:15px" /></a></td>
            </tr>

<!-- Modal -->
  <div class="modal fade" id="myModal<?php echo $fetch_categories["symbl"]; ?>" role="dialog">
    <div class="modal-dialog modal-md">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-body">
            <img src="images/icon-x.png" style="max-width:25px;cursor:pointer;" data-dismiss="modal">
            <div class="row" style="padding: 5px 25px;">
                <div class="col-md-12">
                    <h5>Receive an alert sent to your email when:</h5>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="symbolName" value="<?php echo $fetch_categories['symbl']; ?>">
                            {<?php echo htmlspecialchars($fetch_categories["name"]); ?>} is down more than
                            <input type="text" name="downValue" placeholder="0-100">%
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="symbolnameAllTime" value="<?php echo $fetch_categories['symbl']; ?>">
                            {<?php echo htmlspecialchars($fetch_categories["name"]); ?>} hit new all-time high!
                        </label>
                    </div>
                    <div class="form-group">
                      <label for="usr" style="font-weight:bold;">Your Email:</label>
                      <input type="email" class="form-control" id="usr" required>
                    </div>
                    <button type="submit" class="btn btn-success">Confirm</button>
                </div>
            </div>
        </div>
      </div>
      
    </div>
  </div>    

<?php } $con->close(); ?>
    </tbody>
</table>

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
if ($filterOption=='magnificentSeven') { ?>
<script type="text/javascript">
    $(document).ready(function() {
           var table = $('#stockTable').DataTable({
        searching: false,
        paging: false,
        info: false,
        order: [], // Sort by column 5 (index 4) in descending order
        columnDefs: [
            { orderSequence: ["desc", "asc"], targets: "_all" } // Set default order sequence for all columns
        ]
    }); 
            }); 
</script>

<?php } else{ ?>
<script type="text/javascript">
    $(document).ready(function() {
           // Check if filterOption is 'all' to sort by company name
           var defaultOrder = <?php echo ($filterOption == 'all') ? '[[0, \'asc\']]' : '[]'; ?>;
           var table = $('#stockTable').DataTable({
        searching: false,
        paging: false,
        info: false,
        order: defaultOrder, // Sort by company name (column 0) for 'all' filter, no default for others
        columnDefs: [
            { orderSequence: ["desc", "asc"], targets: "_all" } // Set default order sequence for all columns
        ]
    }); 
            }); 
</script>

<?php } ?>

<script>
    $(document).ready(function() {

        $('input[name="filterOption"]').on('change', function() {
            filterTable(this.value);
        });

        $('#companySelect').on('change', function() {
            filterTableByCompany(this.value);
        });

        function filterTable(filterValue) {
            let rows = $('#stockTableBody tr');
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
            let rows = $('#stockTableBody tr');
            rows.each(function() {
                if ($(this).find('td:eq(0)').text() === companyName) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        // Handle heart icon click event
        $('.heart-icon').on('click', function() {
            let symbol = $(this).data('symbol');
            $(this).toggleClass('favorited');
            if ($(this).hasClass('favorited')) {
                $(this).html('&#9829;'); // Change to filled heart
                addFavorite(symbol);
            } else {
                $(this).html('&#9825;'); // Change to outline heart
                removeFavorite(symbol);
            }
        });

        function addFavorite(symbol) {
            // Example using localStorage
            let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
            if (!favorites.includes(symbol)) {
                favorites.push(symbol);
                localStorage.setItem('favorites', JSON.stringify(favorites));
            }
        }

        function removeFavorite(symbol) {
            // Example using localStorage
            let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
            favorites = favorites.filter(fav => fav !== symbol);
            localStorage.setItem('favorites', JSON.stringify(favorites));
        }

        // Initialize favorite icons on page load
        function initializeFavorites() {
            let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
            $('.heart-icon').each(function() {
                let symbol = $(this).data('symbol');
                if (favorites.includes(symbol)) {
                    $(this).addClass('favorited');
                    $(this).html('&#9829;'); // Set filled heart if favorited
                }
            });
        }

        initializeFavorites();
    });
</script>

<?php } elseif($filterOption=='indexes'){ ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<style>
     .customheading{
            text-align: center;
                font-size: 28px;
    font-weight: 700;
        }
    .form-check-input[type=radio] {
            border-radius: 50%;
            margin-top: 4px !important;
        }
    .bg-warning {
        background-color: #FFF9C4 !important;
    }
    .bg-success {
        background-color: #ACE5DC !important;
    }
    .bg-danger{
        background-color: #F7D1DF !important;
    }
    .mynumber {
    font-size: 13px !important;
    height: 15px !important;
    width: 30px !important;
    border: 1px solid white !important;
    box-shadow: 1px 1px 1px 1px white !important;
    border-bottom: 1px solid black !important;
    margin-top: -1px !important;
    padding: 5px !important;
}
    .bg-danger, .bg-success, .bg-warning {
        color: #747474 !important;
        font-weight: 500;
    }
    thead {
        font-size: 13px;
    }
    table {
        text-align: center;
    }
    .cname1 {
        text-align: left !important;
        vertical-align: middle !important;
        padding-left: 10px !important;
    }
    .cname {
        text-align: left !important;
    position: relative; /* Establish a positioning context for absolute positioning */
    padding-right: 20px; /* Add padding to the right for the icon */
}

.heart-icon {
    position: absolute; /* Position the heart icon absolutely */
    right: 3px; /* Align it to the right of the cell */
    top: 50%; /* Center vertically */
    transform: translateY(-50%); /* Adjust to perfect vertical centering */
    color: red; /* Optional: Set the color of the heart icon */
}

    
    table.dataTable tbody tr {
        background-color: #ffffc500;
    }
    table.dataTable thead th, table.dataTable thead td {
            padding: 5px -5px;
            border-bottom: 1px solid #111;
        }
    table.dataTable thead th{
    padding: 5px 10px !important;
    border-bottom: 1px solid #111;
     }    
    
    table.dataTable thead .sorting {
        background-image:none;
    }    
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #ffffc500;
    }  
    .mycustomcol{
        margin-top:-40px;
    }  
        
    @media (max-width: 768px) {
        
    .mycustomcol{
        margin-top:-0px;
    }  
        .hidemobile {
            display: none;
        }
        .filterList{
            padding:0px 10px;
        }
        .text1{
            padding:0px 10px !important;
        }
        .heart-icon.favorited {
        font-size: 14px !important;
    }
    .heart-icon {
        cursor: pointer;
        margin-right: 0px !important;
        color: #000;
        font-size: 14px !important;
    }
    table.dataTable thead th {
    padding: 2px 10px !important;
    border-bottom: 1px solid #111;
}
        .customheading{
            padding-left:10px;
            font-size:23px;
            text-align: center;
        }
            .mynumber {
        font-size: 13px !important;
        height: 13px !important;
        width: 30px !important;
        border: 1px solid white !important;
        box-shadow: 1px 1px 1px 1px white !important;
        border-bottom: 1px solid black !important;
        margin-top: -3px !important;
        padding: 5px !important;
    }
        thead, table {
            font-size: 10px;
        }
        
        table.dataTable thead th {
            padding-right: 10px; /* Add padding to the right of header cells */
        }
        
        table.dataTable thead th.sorting:after, 
        table.dataTable thead th.sorting_asc:after, 
        table.dataTable thead th.sorting_desc:after {
            margin-left: 5px; /* Adjust the margin between text and arrow */
        }
        
    }
.table-striped>tbody>tr:nth-of-type(odd)>* {
--bs-table-accent-bg: rgb(0 0 0 / 0%)!important;
border-bottom: 1px solid #e7e7e7;
color: #636464;
text-align: center;
font-size:14px !important;
}
table.dataTable tbody th, table.dataTable tbody td {
border-bottom: 1px solid #e7e7e7;
color:#636464;
text-align: center;
font-size:14px !important;
}
a {
    color: #4289f1;
    text-decoration: none;
}

.text{
    color: #f1f1f1;
    text-decoration:none;
}
.text:hover{
    color: #ffffff;
    cursor: pointer;
    text-decoration:none;
}
.footer-ul{
    list-style: none;
}
.footer-ul>li{
    padding-top: 12px;
    border-bottom: 1px solid #ffffff3b;
    padding-bottom: 3px;
}
  

.bootstrap-select .dropdown-menu li a span.text {
    color: #0e0e0e !important;
}
.mt-6 {
    margin-top: 8.4rem !important;
    margin-bottom:2rem !important;
}
.hidedesktop{
    display: none;
}
.filter-option-inner-inner {
    color: black;
    font-weight:500;
}
.dropup .dropdown-toggle::after {
    font-size: 24px !important;
    color: black;
}
.dropdown-toggle::after {
     font-size: 24px !important;
    color: black;
}
.mycontain{
            padding:0px 5px !important;
            padding-bottom:50px !important;
        }
</style>
<table id="stockTable" class="hidden table table-bordered table-striped" style="border-radius: 10px;">
    <thead>
        <tr>
            <th class="cname" data-column="name" width="25%">Company</th>
            <th data-column="lastClosePrice">Last Close Price ($)</th>
            <th data-column="allTimeHighPrice">All Time High ($)</th>
            <th data-column="correctionRange">Correction Range (%)</th>
        </tr>
    </thead>
    <tbody>
<?php
    function convertVolume($volume) {
        $billions = $volume / 1_000_000_000;
        $billions_rounded = round($billions, 2);
        $millions = $volume / 1_000_000;
        $millions_rounded = round($millions, 2);
        return [
            'billions' => $billions_rounded,
            'millions' => $millions_rounded
        ];
    }
    
    
    $categories_query = mysqli_query($con, "SELECT * FROM `indexes`");
    while ($fetch_categories = mysqli_fetch_array($categories_query)) {
     
        $lastClosePrice=$fetch_categories["last_close"];
        $allTimeHighPrice=$fetch_categories["all_time_high"];
        $correctionRange = ($allTimeHighPrice != 0 && is_numeric($allTimeHighPrice))
            ? number_format(($lastClosePrice - $allTimeHighPrice) / $allTimeHighPrice * 100, 2)
            : 0;

?>
        <tr>
            <td class="cname">
                <a href="<?php echo $fetch_categories["link"]; ?>" target="_blank">
                    <?php echo $fetch_categories["name"]; ?>
                </a>
            </td>
            <td><?php echo $fetch_categories["last_close"]; ?></td>
            <td><?php echo $fetch_categories["all_time_high"]; ?></td>
            <td class="<?php if ($correctionRange >= -5) { echo 'bg-danger'; } else if ($correctionRange >= -15) { echo 'bg-warning'; } else { echo 'bg-success'; } ?>"><?php echo $correctionRange; ?></td>
        </tr>
<?php } $con->close(); ?>
    </tbody>
</table>

<script>
    $(document).ready(function() {
          var table = $('#stockTable').DataTable({
        searching: false,
        paging: false,
        info: false,
        order: [], // Disable initial sorting to control via custom logic
        columnDefs: [
            { orderSequence: ["desc", "asc"], targets: "_all" } // Set default order sequence for all columns
        ]
    });



        $('input[name="filterOption"]').on('change', function() {
            filterTable(this.value);
        });

        $('#companySelect').on('change', function() {
            filterTableByCompany(this.value);
        });

        function filterTable(filterValue) {
            let rows = $('#stockTableBody tr');
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
            let rows = $('#stockTableBody tr');
            rows.each(function() {
                if ($(this).find('td:eq(0)').text() === companyName) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        // Handle heart icon click event
        $('.heart-icon').on('click', function() {
            let symbol = $(this).data('symbol');
            $(this).toggleClass('favorited');
            if ($(this).hasClass('favorited')) {
                $(this).html('&#9829;'); // Change to filled heart
                addFavorite(symbol);
            } else {
                $(this).html('&#9825;'); // Change to outline heart
                removeFavorite(symbol);
            }
        });

        function addFavorite(symbol) {
            // Example using localStorage
            let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
            if (!favorites.includes(symbol)) {
                favorites.push(symbol);
                localStorage.setItem('favorites', JSON.stringify(favorites));
            }
        }

        function removeFavorite(symbol) {
            // Example using localStorage
            let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
            favorites = favorites.filter(fav => fav !== symbol);
            localStorage.setItem('favorites', JSON.stringify(favorites));
        }

        // Initialize favorite icons on page load
        function initializeFavorites() {
            let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
            $('.heart-icon').each(function() {
                let symbol = $(this).data('symbol');
                if (favorites.includes(symbol)) {
                    $(this).addClass('favorited');
                    $(this).html('&#9829;'); // Set filled heart if favorited
                }
            });
        }

        initializeFavorites();
    });
</script>

<?php } elseif ($filterOption=='efts') {?>

<!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">-->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<style type="text/css">
    .checked {
    background-color: #D9D9D9; /* Selected background */
    color: black; /* Selected text color */
}
.checked:hover {
    background-color: #D9D9D9; /* Selected background */
    color: black; /* Selected text color */
}
</style>
<style>
     .customheading{
            text-align: center;
                font-size: 28px;
    font-weight: 700;
        }
    .form-check-input[type=radio] {
            border-radius: 50%;
            margin-top: 4px !important;
        }
    .bg-warning {
        background-color: #FFF9C4 !important;
    }
    .bg-success {
        background-color: #ACE5DC !important;
    }
    .bg-danger{
        background-color: #F7D1DF !important;
    }
    .mynumber {
    font-size: 13px !important;
    height: 15px !important;
    width: 30px !important;
    border: 1px solid white !important;
    box-shadow: 1px 1px 1px 1px white !important;
    border-bottom: 1px solid black !important;
    margin-top: -1px !important;
    padding: 5px !important;
}
    .bg-danger, .bg-success, .bg-warning {
        color: #747474 !important;
        font-weight: 500;
    }
    thead {
        font-size: 13px;
    }
    table {
        text-align: center;
    }
    .cname1 {
        text-align: left !important;
        vertical-align: middle !important;
        padding-left: 10px !important;
    }
    .cname {
        text-align: left !important;
    position: relative; /* Establish a positioning context for absolute positioning */
    padding-right: 20px; /* Add padding to the right for the icon */
}

.heart-icon {
    position: absolute; /* Position the heart icon absolutely */
    right: 3px; /* Align it to the right of the cell */
    top: 50%; /* Center vertically */
    transform: translateY(-50%); /* Adjust to perfect vertical centering */
    color: red; /* Optional: Set the color of the heart icon */
}

    
    table.dataTable tbody tr {
        background-color: #ffffc500;
    }
    table.dataTable thead th, table.dataTable thead td {
            padding: 5px -5px;
            border-bottom: 1px solid #111;
        }
    table.dataTable thead th{
    padding: 5px 18px !important;
    border-bottom: 1px solid #111;
     }    
    
    table.dataTable thead .sorting {
        background-image:none;
    }    
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #ffffc500;
    }  
    .mycustomcol{
        margin-top:-40px;
    }  
        
    @media (max-width: 768px) {
        
    .mycustomcol{
        margin-top:-0px;
    }  
        .hidemobile {
            display: none;
        }
        .filterList{
            padding:0px 10px;
        }
        .text1{
            padding:0px 10px !important;
        }
        .heart-icon.favorited {
        font-size: 14px !important;
    }
    .heart-icon {
        cursor: pointer;
        margin-right: 0px !important;
        color: #000;
        font-size: 14px !important;
    }
    table.dataTable thead th {
    padding: 2px 10px !important;
    border-bottom: 1px solid #111;
}
        .customheading{
            padding-left:10px;
            font-size:23px;
            text-align: center;
        }
            .mynumber {
        font-size: 13px !important;
        height: 13px !important;
        width: 30px !important;
        border: 1px solid white !important;
        box-shadow: 1px 1px 1px 1px white !important;
        border-bottom: 1px solid black !important;
        margin-top: -3px !important;
        padding: 5px !important;
    }
        thead, table {
            font-size: 10px;
        }
        
        table.dataTable thead th {
            padding-right: 10px; /* Add padding to the right of header cells */
        }
        
        table.dataTable thead th.sorting:after, 
        table.dataTable thead th.sorting_asc:after, 
        table.dataTable thead th.sorting_desc:after {
            margin-left: 5px; /* Adjust the margin between text and arrow */
        }
        
    }
.table-striped>tbody>tr:nth-of-type(odd)>* {
--bs-table-accent-bg: rgb(0 0 0 / 0%)!important;
border-bottom: 1px solid #e7e7e7;
color: #636464;
text-align: center;
font-size:14px !important;
}
table.dataTable tbody th, table.dataTable tbody td {
border-bottom: 1px solid #e7e7e7;
color:#636464;
text-align: center;
font-size:14px !important;
}
a {
    color: #4289f1;
    text-decoration: none;
}

.text{
    color: #f1f1f1;
    text-decoration:none;
}
.text:hover{
    color: #ffffff;
    cursor: pointer;
    text-decoration:none;
}
.footer-ul{
    list-style: none;
}
.footer-ul>li{
    padding-top: 12px;
    border-bottom: 1px solid #ffffff3b;
    padding-bottom: 3px;
}
  

.bootstrap-select .dropdown-menu li a span.text {
    color: #0e0e0e !important;
}
.mt-6 {
    margin-top: 8.4rem !important;
    margin-bottom:2rem !important;
}
.hidedesktop{
    display: none;
}
.filter-option-inner-inner {
    color: black;
    font-weight:500;
}
.dropup .dropdown-toggle::after {
    font-size: 24px !important;
    color: black;
}
.dropdown-toggle::after {
     font-size: 24px !important;
    color: black;
}
.mycontain{
            padding:0px 5px !important;
            padding-bottom:50px !important;
        }


</style>

<table id="stockTable" class="hidden table table-bordered table-striped" style="border-radius: 10px;">
    <thead>
        <tr>
            <th class="cname" data-column="name" width="25%" style="vertical-align: middle;">Company</th>
            <th data-column="lastClosePrice" style="vertical-align: middle;">Last Close<br/> Price ($)</th>
            <th data-column="allTimeHighPrice" style="vertical-align: middle;">All Time<br/> High ($)</th>
            <th data-column="correctionRange" style="vertical-align: middle;">Correction<br/> Range (%)</th>
        </tr>
    </thead>
    <tbody>
<?php
    function convertVolume($volume) {
        $billions = $volume / 1_000_000_000;
        $billions_rounded = round($billions, 2);
        $millions = $volume / 1_000_000;
        $millions_rounded = round($millions, 2);
        return [
            'billions' => $billions_rounded,
            'millions' => $millions_rounded
        ];
    }
    
    
    $categories_query = mysqli_query($con, "SELECT * FROM `efts` ORDER BY ID DESC");
    while ($fetch_categories = mysqli_fetch_array($categories_query)) {
     
        $lastClosePrice=$fetch_categories["last_close"];
        $allTimeHighPrice=$fetch_categories["all_time_high"];
        $correctionRange = ($allTimeHighPrice != 0 && is_numeric($allTimeHighPrice))
            ? number_format(($lastClosePrice - $allTimeHighPrice) / $allTimeHighPrice * 100, 2)
            : 0;
        $exchange_name=$fetch_categories["exchange_name"];
        $exchange_name = str_replace(' ', '', $exchange_name);
        if ($exchange_name=='XNAS') {
            $exchange_name='NASDAQ';
        }
        elseif ($exchange_name=='ARCX') {
            $exchange_name='NYSEARCA';
        }
        
?>
        <tr>
            <td class="cname">
                <a href="https://www.google.com/finance/quote/<?php echo $fetch_categories["symbl"]; ?>:<?php echo $exchange_name; ?>?window=1Y" target="_blank">
                    <?php echo $fetch_categories["name"]; ?>
                </a>
            </td>
            <td><?php echo $fetch_categories["last_close"]; ?></td>
            <td><?php echo $fetch_categories["all_time_high"]; ?></td>
            <td class="<?php if ($correctionRange >= -5) { echo 'bg-danger'; } else if ($correctionRange >= -15) { echo 'bg-warning'; } else { echo 'bg-success'; } ?>"><?php echo $correctionRange; ?></td>
        </tr>
<?php } $con->close(); ?>
    </tbody>
</table>

<script>
    $(document).ready(function() {
          var table = $('#stockTable').DataTable({
        searching: false,
        paging: false,
        info: false,
        order: [], // Disable initial sorting to control via custom logic
        columnDefs: [
            { orderSequence: ["desc", "asc"], targets: "_all" } // Set default order sequence for all columns
        ]
    });



        $('input[name="filterOption"]').on('change', function() {
            filterTable(this.value);
        });

        $('#companySelect').on('change', function() {
            filterTableByCompany(this.value);
        });

        function filterTable(filterValue) {
            let rows = $('#stockTableBody tr');
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
            let rows = $('#stockTableBody tr');
            rows.each(function() {
                if ($(this).find('td:eq(0)').text() === companyName) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        // Handle heart icon click event
        $('.heart-icon').on('click', function() {
            let symbol = $(this).data('symbol');
            $(this).toggleClass('favorited');
            if ($(this).hasClass('favorited')) {
                $(this).html('&#9829;'); // Change to filled heart
                addFavorite(symbol);
            } else {
                $(this).html('&#9825;'); // Change to outline heart
                removeFavorite(symbol);
            }
        });

        function addFavorite(symbol) {
            // Example using localStorage
            let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
            if (!favorites.includes(symbol)) {
                favorites.push(symbol);
                localStorage.setItem('favorites', JSON.stringify(favorites));
            }
        }

        function removeFavorite(symbol) {
            // Example using localStorage
            let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
            favorites = favorites.filter(fav => fav !== symbol);
            localStorage.setItem('favorites', JSON.stringify(favorites));
        }

        // Initialize favorite icons on page load
        function initializeFavorites() {
            let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
            $('.heart-icon').each(function() {
                let symbol = $(this).data('symbol');
                if (favorites.includes(symbol)) {
                    $(this).addClass('favorited');
                    $(this).html('&#9829;'); // Set filled heart if favorited
                }
            });
        }

        initializeFavorites();
    });
</script>

<?php } elseif ($filterOption=='crypto') {?>
<!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<style type="text/css">
    .checked {
    background-color: #D9D9D9; /* Selected background */
    color: black; /* Selected text color */
}
.checked:hover {
    background-color: #D9D9D9; /* Selected background */
    color: black; /* Selected text color */
}
</style>
<style>
     .customheading{
            text-align: center;
                font-size: 28px;
    font-weight: 700;
        }
    .form-check-input[type=radio] {
            border-radius: 50%;
            margin-top: 4px !important;
        }
    .bg-warning {
        background-color: #FFF9C4 !important;
    }
    .bg-success {
        background-color: #ACE5DC !important;
    }
    .bg-danger{
        background-color: #F7D1DF !important;
    }
    .mynumber {
    font-size: 13px !important;
    height: 15px !important;
    width: 30px !important;
    border: 1px solid white !important;
    box-shadow: 1px 1px 1px 1px white !important;
    border-bottom: 1px solid black !important;
    margin-top: -1px !important;
    padding: 5px !important;
}
    .bg-danger, .bg-success, .bg-warning {
        color: #747474 !important;
        font-weight: 500;
    }
    thead {
        font-size: 13px;
    }
    table {
        text-align: center;
    }
    .cname1 {
        text-align: left !important;
        vertical-align: middle !important;
        padding-left: 10px !important;
    }
    .cname {
        text-align: left !important;
    position: relative; /* Establish a positioning context for absolute positioning */
    padding-right: 20px; /* Add padding to the right for the icon */
}

.heart-icon {
    position: absolute; /* Position the heart icon absolutely */
    right: 3px; /* Align it to the right of the cell */
    top: 50%; /* Center vertically */
    transform: translateY(-50%); /* Adjust to perfect vertical centering */
    color: red; /* Optional: Set the color of the heart icon */
}

    
    table.dataTable tbody tr {
        background-color: #ffffc500;
    }
    table.dataTable thead th, table.dataTable thead td {
            padding: 5px -5px;
            border-bottom: 1px solid #111;
        }
    table.dataTable thead th{
    padding: 5px 10px !important;
    border-bottom: 1px solid #111;
     }    
    
    table.dataTable thead .sorting {
        background-image:none;
    }    
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #ffffc500;
    }  
    .mycustomcol{
        margin-top:-40px;
    }  
        
    @media (max-width: 768px) {
        
    .mycustomcol{
        margin-top:-0px;
    }  
        .hidemobile {
            display: none;
        }
        .filterList{
            padding:0px 10px;
        }
        .text1{
            padding:0px 10px !important;
        }
        .heart-icon.favorited {
        font-size: 14px !important;
    }
    .heart-icon {
        cursor: pointer;
        margin-right: 0px !important;
        color: #000;
        font-size: 14px !important;
    }
    table.dataTable thead th {
    padding: 2px 10px !important;
    border-bottom: 1px solid #111;
}
        .customheading{
            padding-left:10px;
            font-size:23px;
            text-align: center;
        }
            .mynumber {
        font-size: 13px !important;
        height: 13px !important;
        width: 30px !important;
        border: 1px solid white !important;
        box-shadow: 1px 1px 1px 1px white !important;
        border-bottom: 1px solid black !important;
        margin-top: -3px !important;
        padding: 5px !important;
    }
        thead, table {
            font-size: 10px;
        }
        
        table.dataTable thead th {
            padding-right: 10px; /* Add padding to the right of header cells */
        }
        
        table.dataTable thead th.sorting:after, 
        table.dataTable thead th.sorting_asc:after, 
        table.dataTable thead th.sorting_desc:after {
            margin-left: 5px; /* Adjust the margin between text and arrow */
        }
        
    }
.table-striped>tbody>tr:nth-of-type(odd)>* {
--bs-table-accent-bg: rgb(0 0 0 / 0%)!important;
border-bottom: 1px solid #e7e7e7;
color: #636464;
text-align: center;
font-size:14px !important;
}
table.dataTable tbody th, table.dataTable tbody td {
border-bottom: 1px solid #e7e7e7;
color:#636464;
text-align: center;
font-size:14px !important;
}
a {
    color: #4289f1;
    text-decoration: none;
}

.text{
    color: #f1f1f1;
    text-decoration:none;
}
.text:hover{
    color: #ffffff;
    cursor: pointer;
    text-decoration:none;
}
.footer-ul{
    list-style: none;
}
.footer-ul>li{
    padding-top: 12px;
    border-bottom: 1px solid #ffffff3b;
    padding-bottom: 3px;
}
  

.bootstrap-select .dropdown-menu li a span.text {
    color: #0e0e0e !important;
}
.mt-6 {
    margin-top: 8.4rem !important;
    margin-bottom:2rem !important;
}
.hidedesktop{
    display: none;
}
.filter-option-inner-inner {
    color: black;
    font-weight:500;
}
.dropup .dropdown-toggle::after {
    font-size: 24px !important;
    color: black;
}
.dropdown-toggle::after {
     font-size: 24px !important;
    color: black;
}
.mycontain{
            padding:0px 5px !important;
            padding-bottom:50px !important;
        }
</style>
<table id="stockTable" class="hidden table table-bordered table-striped" style="border-radius: 10px;">
    <thead>
        <tr>
            <th class="cname" data-column="name" width="25%"  style="vertical-align: middle;">Crypto</th>
            <th data-column="lastClosePrice"  style="vertical-align: middle;">Current<br/> Price ($)</th>
            <th data-column="allTimeHighPrice"  style="vertical-align: middle;">All Time<br/> High ($)</th>
            <th data-column="correctionRange"  style="vertical-align: middle;">Correction<br/> Range (%)</th>
        </tr>
    </thead>
    <tbody>
<?php
    function convertVolume($volume) {
        $billions = $volume / 1_000_000_000;
        $billions_rounded = round($billions, 2);
        $millions = $volume / 1_000_000;
        $millions_rounded = round($millions, 2);
        return [
            'billions' => $billions_rounded,
            'millions' => $millions_rounded
        ];
    }
    
    
    $categories_query = mysqli_query($con, "SELECT * FROM `crypto`");
    while ($fetch_categories = mysqli_fetch_array($categories_query)) {
     
        $lastClosePrice=$fetch_categories["last_close_price"];
        $allTimeHighPrice=$fetch_categories["all_time_high"];
        $correctionRange = ($allTimeHighPrice != 0 && is_numeric($allTimeHighPrice))
            ? number_format(($lastClosePrice - $allTimeHighPrice) / $allTimeHighPrice * 100, 2)
            : 0;

?>
        <tr>
            <td class="cname">
                <a href="https://www.google.com/finance/quote/<?php echo $fetch_categories["symbol"]; ?>-USD?window=1Y" target="_blank">
                    <?php echo $fetch_categories["name"]; ?>
                </a>
            </td>
              <td><?php echo is_numeric($fetch_categories["last_close_price"]) ? number_format($fetch_categories["last_close_price"], 2) : ''; ?></td>
                <td><?php echo is_numeric($fetch_categories["all_time_high"]) ? number_format($fetch_categories["all_time_high"], 2) : ''; ?></td>
            <td class="<?php if ($correctionRange >= -5) { echo 'bg-danger'; } else if ($correctionRange >= -15) { echo 'bg-warning'; } else { echo 'bg-success'; } ?>"><?php echo $correctionRange; ?></td>
        </tr>
<?php } $con->close(); ?>
    </tbody>
</table>

<script>
    $(document).ready(function() {
          var table = $('#stockTable').DataTable({
        searching: false,
        paging: false,
        info: false,
        order: [], // Disable initial sorting to control via custom logic
        columnDefs: [
            { orderSequence: ["desc", "asc"], targets: "_all" } // Set default order sequence for all columns
        ]
    });



        $('input[name="filterOption"]').on('change', function() {
            filterTable(this.value);
        });

        $('#companySelect').on('change', function() {
            filterTableByCompany(this.value);
        });

        function filterTable(filterValue) {
            let rows = $('#stockTableBody tr');
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
            let rows = $('#stockTableBody tr');
            rows.each(function() {
                if ($(this).find('td:eq(0)').text() === companyName) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        // Handle heart icon click event
        $('.heart-icon').on('click', function() {
            let symbol = $(this).data('symbol');
            $(this).toggleClass('favorited');
            if ($(this).hasClass('favorited')) {
                $(this).html('&#9829;'); // Change to filled heart
                addFavorite(symbol);
            } else {
                $(this).html('&#9825;'); // Change to outline heart
                removeFavorite(symbol);
            }
        });

        function addFavorite(symbol) {
            // Example using localStorage
            let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
            if (!favorites.includes(symbol)) {
                favorites.push(symbol);
                localStorage.setItem('favorites', JSON.stringify(favorites));
            }
        }

        function removeFavorite(symbol) {
            // Example using localStorage
            let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
            favorites = favorites.filter(fav => fav !== symbol);
            localStorage.setItem('favorites', JSON.stringify(favorites));
        }

        // Initialize favorite icons on page load
        function initializeFavorites() {
            let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
            $('.heart-icon').each(function() {
                let symbol = $(this).data('symbol');
                if (favorites.includes(symbol)) {
                    $(this).addClass('favorited');
                    $(this).html('&#9829;'); // Set filled heart if favorited
                }
            });
        }

        initializeFavorites();
    });
</script>






<?php } else { ?>




<!---------------------  Favourites Area ----------------------------->


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<style>
     .customheading{
            text-align: center;
                font-size: 28px;
    font-weight: 700;
        }
    .form-check-input[type=radio] {
            border-radius: 50%;
            margin-top: 4px !important;
        }
    .bg-warning {
        background-color: #FFF9C4 !important;
    }
    .bg-success {
        background-color: #ACE5DC !important;
    }
    .bg-danger{
        background-color: #F7D1DF !important;
    }
    .mynumber {
    font-size: 13px !important;
    height: 15px !important;
    width: 30px !important;
    border: 1px solid white !important;
    box-shadow: 1px 1px 1px 1px white !important;
    border-bottom: 1px solid black !important;
    margin-top: -1px !important;
    padding: 5px !important;
}
    .bg-danger, .bg-success, .bg-warning {
        color: #747474 !important;
        font-weight: 500;
    }
    thead {
        font-size: 13px;
    }
    table {
        text-align: center;
    }
    .cname1 {
        text-align: left !important;
        vertical-align: middle !important;
        padding-left: 10px !important;
    }
    .cname {
        text-align: left !important;
    position: relative; /* Establish a positioning context for absolute positioning */
    padding-right: 20px; /* Add padding to the right for the icon */
}

.heart-icon {
    position: absolute; /* Position the heart icon absolutely */
    right: 3px; /* Align it to the right of the cell */
    top: 50%; /* Center vertically */
    transform: translateY(-50%); /* Adjust to perfect vertical centering */
    color: red; /* Optional: Set the color of the heart icon */
}

    
    table.dataTable tbody tr {
        background-color: #ffffc500;
    }
    table.dataTable thead th, table.dataTable thead td {
            padding: 5px -5px;
            border-bottom: 1px solid #111;
        }
    table.dataTable thead th{
    padding: 5px 18px !important;
    border-bottom: 1px solid #111;
     }    
    
    table.dataTable thead .sorting {
        background-image:none;
    }    
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #ffffc500;
    }  
    .mycustomcol{
        margin-top:-40px;
    }  
        
    @media (max-width: 768px) {
        
    .mycustomcol{
        margin-top:-0px;
    }  
        .hidemobile {
            display: none;
        }
        .filterList{
            padding:0px 10px;
        }
        .text1{
            padding:0px 10px !important;
        }
        .heart-icon.favorited {
        font-size: 14px !important;
    }
    .heart-icon {
        cursor: pointer;
        margin-right: 0px !important;
        color: #000;
        font-size: 14px !important;
    }
    table.dataTable thead th {
    padding: 2px 10px !important;
    border-bottom: 1px solid #111;
}
        .customheading{
            padding-left:10px;
            font-size:23px;
            text-align: center;
        }
            .mynumber {
        font-size: 13px !important;
        height: 13px !important;
        width: 30px !important;
        border: 1px solid white !important;
        box-shadow: 1px 1px 1px 1px white !important;
        border-bottom: 1px solid black !important;
        margin-top: -3px !important;
        padding: 5px !important;
    }
        thead, table {
            font-size: 10px;
        }
        
        table.dataTable thead th {
            padding-right: 10px; /* Add padding to the right of header cells */
        }
        
        table.dataTable thead th.sorting:after, 
        table.dataTable thead th.sorting_asc:after, 
        table.dataTable thead th.sorting_desc:after {
            margin-left: 5px; /* Adjust the margin between text and arrow */
        }
        
    }
.table-striped>tbody>tr:nth-of-type(odd)>* {
--bs-table-accent-bg: rgb(0 0 0 / 0%)!important;
border-bottom: 1px solid #e7e7e7;
color: #636464;
text-align: center;
font-size:14px !important;
}
table.dataTable tbody th, table.dataTable tbody td {
border-bottom: 1px solid #e7e7e7;
color:#636464;
text-align: center;
font-size:14px !important;
}
a {
    color: #4289f1;
    text-decoration: none;
}

.text{
    color: #f1f1f1;
    text-decoration:none;
}
.text:hover{
    color: #ffffff;
    cursor: pointer;
    text-decoration:none;
}
.footer-ul{
    list-style: none;
}
.footer-ul>li{
    padding-top: 12px;
    border-bottom: 1px solid #ffffff3b;
    padding-bottom: 3px;
}
  

.bootstrap-select .dropdown-menu li a span.text {
    color: #0e0e0e !important;
}
.mt-6 {
    margin-top: 8.4rem !important;
    margin-bottom:2rem !important;
}
.hidedesktop{
    display: none;
}
.filter-option-inner-inner {
    color: black;
    font-weight:500;
}
.dropup .dropdown-toggle::after {
    font-size: 24px !important;
    color: black;
}
.dropdown-toggle::after {
     font-size: 24px !important;
    color: black;
}
.mycontain{
            padding:0px 5px !important;
            padding-bottom:50px !important;
        }
</style>



<?php

function convertVolume($volume) {
        $billions = $volume / 1_000_000_000;
        $billions_rounded = round($billions, 2);
        $millions = $volume / 1_000_000;
        $millions_rounded = round($millions, 2);
        return [
            'billions' => $billions_rounded,
            'millions' => $millions_rounded
        ];
    }
// Check if filterOption is set
if (isset($_POST['filterOption'])) {
    $filterOption = $_POST['filterOption'];

    // If the filter option is 'favorite', handle the JSON payload
    if ($filterOption == 'favorite' && isset($_POST['favoriteSymbols'])) {
        $symbols = json_decode($_POST['favoriteSymbols'], true);

        if (!empty($symbols)) {
            // Sanitize symbols for SQL query
            $escapedSymbols = array_map(function($symbol) use ($con) {
                return mysqli_real_escape_string($con, $symbol);
            }, $symbols);
            $symbolsList = "'" . implode("','", $escapedSymbols) . "'";

            // Query to fetch data for favorite companies
            $favorites_query = mysqli_query($con, "SELECT * FROM companies WHERE symbl IN ($symbolsList)");

            if ($favorites_query) {
                echo '<table id="stockTable" class="hidden table table-bordered table-striped" style="border-radius: 10px;">
                        <thead>
                            <tr>
                                <th class="cname1" data-column="name" width="25%">Company</th>
                                <th class="hidemobile" data-column="marketCap">Market Cap ($B)</th>
                                <th data-column="lastClosePrice">Current Price ($)</th>
                                <th data-column="allTimeHighPrice">All Time High ($)</th>
                                <th data-column="allTimeHighCount">All-Time High Count*</th>
                                <th  class="hidemobile" data-column="lastATH">Last ATH (Days)</th>
                                <th data-column="correctionRange">Correction Range (%)</th>
                                <th data-column="correctionRange"><a data-toggle="modal" data-target="#myModalFixed" style="cursor:pointer;"><img src="images/question-icon.png" style="max-width:30px" /></a></th>
                            </tr>
                        </thead>
                        <tbody>';

                while ($row = mysqli_fetch_assoc($favorites_query)) {
                            $market_cap = convertVolume($row["market_cap"]);
                            $average_volume = convertVolume($row["average_volume"]); // Convert average volume using the function
                            $exchange_value = $row["exchange_name"];
                            $exchange_name = ($exchange_value == 'XNAS') ? "NASDAQ" : (($exchange_value == 'XNYS') ? "NYSE" : $exchange_value);
                            $correction_range = $row["correction_range"];
                            $bg_class = '';
                            if ($correction_range >= -5) {
                                $bg_class = 'bg-danger';
                            } elseif ($correction_range >= -15) {
                                $bg_class = 'bg-warning';
                            } else {
                                $bg_class = 'bg-success';
                            }

                            echo '<tr>
                                    <td class="cname">
                                        <a href="https://www.google.com/finance/quote/' . $row["symbl"] . ':' . $exchange_name . '?hl=en&window=1Y" target="_blank">
                                            ' . $row["name"] . '
                                        </a>
                                        <span class="heart-icon favorited" data-symbol="' . $row["symbl"] . '">&#9829;</span>
                                    </td>
                                    <td class="hidemobile">' . (is_numeric($market_cap['billions']) ? number_format($market_cap['billions'], 2) : '') . '</td>
                                    <td>' . (is_numeric($row["last_close"]) ? number_format($row["last_close"], 2) : '') . '</td>
                                    <td>' . (is_numeric($row["all_time_high"]) ? number_format($row["all_time_high"], 2) : '') . '</td>
                                    <td>' . (is_numeric($row["all_time_high_count"]) ? number_format($row["all_time_high_count"]) : '') . '</td>
                                   <td class="hidemobile">' . $row["ath_since"] . '</td>
                                    <td class="' . $bg_class . '">' . $row["correction_range"] . '</td>
                                    <td> <a data-toggle="modal" data-target="#my1Modal'.$row["symbl"].'" style="cursor:pointer;"><img src="images/bell-icon.png" style="max-width:30px" /></a></td>
                                </tr>
<!-- Modal -->
  <div class="modal fade" id="my1Modal'.$row["symbl"].'" role="dialog">
    <div class="modal-dialog modal-md">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-body">
            <img src="images/icon-x.png" style="max-width:25px;cursor:pointer;" data-dismiss="modal">
            <div class="row" style="padding: 5px 25px;">
                <div class="col-md-12">
                    <h5>Receive an alert sent to your email when:</h5>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="symbolName" value="'.$row["symbl"].'">
                            {'.$row["name"].'} is down more than
                            <input type="text" name="downValue" placeholder="0-100">%
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="symbolnameAllTime" value="'.$row["symbl"].'">
                            {'.$row["name"].'} hit new all-time high!
                        </label>
                    </div>
                    <div class="form-group">
                      <label for="usr" style="font-weight:bold;">Your Email:</label>
                      <input type="email" class="form-control" id="usr" required>
                    </div>
                    <button type="submit" class="btn btn-success">Confirm</button>
                </div>
            </div>
        </div>
      </div>
      
    </div>
  </div>    


                                ';
      
                        }


                echo '</tbody></table>';
            } else {
                echo "<p>No favorite companies found.</p>";
            }
        } else {
            echo "<p>No favorite companies selected.</p>";
        }
    }
    $con->close();
}


?>


<script>
    $(document).ready(function() {
          var table = $('#stockTable').DataTable({
        searching: false,
        paging: false,
        info: false,
        order: [[6, 'asc']], // Sort by column 5 (index 4) in descending order
        columnDefs: [
            { orderSequence: ["desc", "asc"], targets: "_all" } // Set default order sequence for all columns
        ]
    });

        $('input[name="filterOption"]').on('change', function() {
            filterTable(this.value);
        });

        $('#companySelect').on('change', function() {
            filterTableByCompany(this.value);
        });

        function filterTable(filterValue) {
            let rows = $('#stockTableBody tr');
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
            let rows = $('#stockTableBody tr');
            rows.each(function() {
                if ($(this).find('td:eq(0)').text() === companyName) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        // Handle heart icon click event
        $('.heart-icon').on('click', function() {
            let symbol = $(this).data('symbol');
            $(this).toggleClass('favorited');
            if ($(this).hasClass('favorited')) {
                $(this).html('&#9829;'); // Change to filled heart
                addFavorite(symbol);
            } else {
                $(this).html('&#9825;'); // Change to outline heart
                removeFavorite(symbol);
            }
        });

        function addFavorite(symbol) {
            // Example using localStorage
            let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
            if (!favorites.includes(symbol)) {
                favorites.push(symbol);
                localStorage.setItem('favorites', JSON.stringify(favorites));
            }
        }

        function removeFavorite(symbol) {
            // Example using localStorage
            let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
            favorites = favorites.filter(fav => fav !== symbol);
            localStorage.setItem('favorites', JSON.stringify(favorites));
        }

        // Initialize favorite icons on page load
        function initializeFavorites() {
            let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
            $('.heart-icon').each(function() {
                let symbol = $(this).data('symbol');
                if (favorites.includes(symbol)) {
                    $(this).addClass('favorited');
                    $(this).html('&#9829;'); // Set filled heart if favorited
                }
            });
        }

        initializeFavorites();
    });
</script>


<?php } ?>
