<?php
/* Template Name: Custom Stock Table */

get_header(); // Include the header
?>

<?php
 include "db.php";
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
    .bg-warning {
        background-color: #f5be19 !important;
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
        color: white !important;
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

    .table-striped tbody tr:nth-of-type(odd) {
            background-color: #E6E6B1;
        }
    table.dataTable tbody tr {
        background-color: #FFFFC5;
    }
    table.dataTable thead th, table.dataTable thead td {
            padding: 9px 2px;
            border-bottom: 1px solid #111;
        }
    
    table.dataTable thead .sorting {
        background-image:none;
    }    
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #FFFFC5;
    }  
      
        
    @media (max-width: 768px) {
        .hidemobile {
            display: none;
        }
        .customheading{
            padding-left:10px;
            font-size:19px;
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
            padding-right: 15px; /* Add padding to the right of header cells */
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
color: var(--bs-table-striped-color);
text-align: center;
}
table.dataTable tbody th, table.dataTable tbody td {
border-bottom: 1px solid #e7e7e7;

color: var(--bs-table-striped-color);
text-align: center;
}

</style>
<div class="container" style="padding:50px 5px;">
   <h3 class="mb-4 customheading">S&P 500 Stocks and Their Current Correction Status</h3>
    <div class="row">
        <div class="col-md-6">
                    <p class="text-muted">Data Last Updated:
                        <?php
                        date_default_timezone_set('America/New_York'); // Set the timezone to New York

                        $current_time = strtotime(date('H:i')); // Get the current time in hours and minutes
                        $threshold_time = strtotime('16:30'); // 4:30 PM in 24-hour format

                        // Check if the current time is before 4:30 PM
                        if ($current_time < $threshold_time) {
                            $last_updated = date('Y-m-d', strtotime('-1 day')); // Subtract 1 day from current date
                        } else {
                            $last_updated = date('d/m/Y'); // Use current date
                        }
                        echo $last_updated . ', 4:30PM';
                        ?>
                    </p>

           <div class="mb-3">
                <select id="companySearch" class="selectpicker" data-show-subtext="true" data-live-search="true">
                    <option selected disabled>Find a company</option>
                    <?php
                    $categories_query = mysqli_query($con, "SELECT * FROM companies");
                    while ($fetchc = mysqli_fetch_array($categories_query)) { ?>
                        <option value="<?php echo $fetchc["symbl"]; ?>"><?php echo $fetchc["name"]; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
<script>
$(document).ready(function() {
$('.selectpicker').selectpicker();
 $("#companySearch").on("change",function(){
      var main_id =$('#companySearch').val();

      $.ajax({
      url: "companySearch.php",
      type: "POST",
      data: {
      main_id: main_id
      },
      cache: false,
      success: function(result){
      $("#tablesList").html(result);
      }
      });
    });



});
</script>        
        <div class="col-md-4">
            <table class="table table-bordered">
                <tr>
                    <td><b>Minor</b></td>
                    <td class="bg-danger" style="text-align:center;">0% to -5%</td>
                </tr>
                <tr>
                    <td><b>Moderate</b></td>
                    <td class="bg-warning" style="text-align:center;">-5% to -15%</td>
                </tr>
                <tr>
                    <td><b>Major</b></td>
                    <td class="bg-success" style="text-align:center;">-15% or less</td>
                </tr>
            </table>
        </div>
        <div class="col-md-12 mt-2">
            <div class="mb-3">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="filterOptions" id="filterOptions1" value="-10">
                    <label class="form-check-label" for="filterOptions1">Stocks down more than 10%</label>
                </div>
            <!--    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="filterOptions" id="filterOptions2" value="-20">
                    <label class="form-check-label" for="filterOptions2">More than 20%</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="filterOptions" id="filterOptions3" value="-30">
                    <label class="form-check-label" for="filterOptions3">More than 30%</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="filterOptions" id="filterOptions4" value="-40">
                    <label class="form-check-label" for="filterOptions4">More than 40%</label>
                </div>-->
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="filterOptions" id="filterOptions8" value="custom">
                    <label class="form-check-label" for="filterOptions8">Stocks down more than <input type="text" id="customnumber" class="mynumber" min="0" value="50"/> percent</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="filterOptions" id="filterOptions5" value="magnificentSeven">
                    <label class="form-check-label" for="filterOptions5">Magnificent Seven</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="filterOptions" id="filterOptions7" value="indexes">
                    <label class="form-check-label" for="filterOptions7">Indexes</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="filterOptions" id="filterOptions6" value="favorite">
                    <label class="form-check-label" for="filterOptions6">Favorites</label>
                </div>
            </div>
        </div>
    </div>
 <div class="table-responsive" id="tablesList">
   <table id="stockTable" class="table table-bordered table-striped">
    <thead>
       <tr>
            <th class="cname1" data-column="name" width="25%">Company</th>
            <th data-column="lastClosePrice">Last Close Price ($)</th>
            <th data-column="allTimeHighPrice">All Time High ($)</th>
            <th data-column="marketCap">Market Cap ($B)</th>
            <th class="hidemobile" data-column="averageVolume">Average Volume ($M)</th>
            <th data-column="correctionRange">Correction Range (%)</th>
        </tr>
    </thead>
    <tbody id="favoritesTableBody">
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

        $categories_query = mysqli_query($con, "SELECT * FROM companies where market_cap>0");
        while ($fetch_categories = mysqli_fetch_array($categories_query)) {
            $correctionRange = $fetch_categories["correction_range"];
            $market_cap = $fetch_categories["market_cap"];
            $average_volume = $fetch_categories["average_volume"];
            $converted_market_cap = convertVolume($market_cap);
            $converted_average_volume = convertVolume($average_volume);
            $exchange_value = $fetch_categories["exchange_name"];
            if ($exchange_value == 'XNAS') {
                $exchange_name = "NASDAQ";
            } elseif ($exchange_value == 'XNYS') {
                $exchange_name = "NYSE";
            }
        ?>
            <tr>
                <td class="cname">
                    <a href="https://www.google.com/finance/quote/<?php echo $fetch_categories["symbl"]; ?>:<?php echo $exchange_name; ?>?hl=en&window=1Y" target="_blank">
                        <?php echo $fetch_categories["name"]; ?>
                    </a>
                    <span class="heart-icon" data-symbol="<?php echo $fetch_categories["symbl"]; ?>">&#9825;</span>
                </td>
                <td><?php echo $fetch_categories["last_close"]; ?></td>
                <td><?php echo $fetch_categories["all_time_high"]; ?></td>
                <td><?php echo $converted_market_cap['billions']; ?></td>
                <td class="hidemobile"><?php echo $converted_average_volume['millions']; ?></td>
                <td class="<?php if ($correctionRange >= -5) { echo 'bg-danger'; } else if ($correctionRange >= -15) { echo 'bg-warning'; } else { echo 'bg-success'; } ?>"><?php echo $fetch_categories["correction_range"]; ?></td>
            </tr>
        <?php } $con->close(); ?>
    </tbody>
</table>


    </div>
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
                    this.innerHTML = '&#9829;'; // Filled heart
                    favorites.push(symbol);
                } else {
                    this.innerHTML = '&#9825;'; // Empty heart
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
                icon.innerHTML = '&#9829;'; // Filled heart
            }
        });
    });
</script>

<!-- Include some CSS for the heart icon -->
<style>
    .heart-icon {
        cursor: pointer;
        margin-left: 10px;
        color: #ccc;
    }

    .heart-icon.favorited {
        color: red;
    }
</style>
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

<style type="text/css">
    .footer-wrapper {
    padding: 0px;
    background-color: #333333;
    color: #fff;
}

.footer-widgets {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}

.footer-widgets .widget {
    flex: 1 1 200px; /* Flex-grow, flex-shrink, flex-basis */
    margin: 10px;
}

.widget-title {
    font-size: 1.2em;
    margin-bottom: 10px;
}

.is-divider {
    margin: 10px 0;
}

.footer .col-inner {
    margin: 0;
    padding: 0;
}

.footer-primary {
    text-align: center;
    margin-top: 20px;
}

@media (max-width: 767px) {
    .footer-widgets {
        flex-direction: column;
        align-items: center;
    }

    .footer-widgets .widget {
        width: 100%;
        max-width: 400px;
    }

    .widget-title {
        text-align: center;
    }
}

@media (max-width: 480px) {
    .footer-widgets .widget {
        padding: 10px;
    }

    .widget-title {
        font-size: 1em;
    }

    .is-divider {
        margin: 5px 0;
    }
}

.absolute-footer {
    text-align: center;
    padding: 7px 0;
    background-color: #111;
}

.back-to-top {
    display: none; /* Initially hidden */
}

@media (min-width: 768px) {
    .back-to-top {
        display: block;
    }
}

</style>

    <script>
    $(document).ready(function() {
        // Trigger the AJAX call when a radio button is selected
        $("input[name='filterOptions']").on("change", function() {
            var selectedValue = $('input[name="filterOptions"]:checked').val(); // Get the selected radio button value
            var customnumber=$('#customnumber').val();
            // Check if the selected value is 'favorite'
            if (selectedValue === 'favorite') {
                // Fetch favorites from localStorage
                var favorites = JSON.parse(localStorage.getItem('favorites')) || [];

                // Send favorites as JSON
                $.ajax({
                    url: "myData.php",
                    type: "POST",
                    data: {
                        filterOption: selectedValue,
                        customnumber:customnumber,
                        favoriteSymbols: JSON.stringify(favorites) // Send favorites array as JSON
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
            } else {
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
        });
    });
</script>

<?php get_footer(); // Include the footer ?>
