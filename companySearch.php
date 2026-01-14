<?php
 include "db.php";

 $main_id = $_POST['main_id'];
 
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
        background-image: none;
    }    
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #FFFFC5;
    }  
    @media (max-width: 768px) {
        .hidemobile {
            display: none;
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
    .heart-icon {
        cursor: pointer;
        color: #bbb;
        font-size: 16px;
    }
    .heart-icon.favorited {
        color: red;
    }
</style>

<table id="stockTable" class="table table-bordered table-striped">
    <thead>
         <tr>
            <th class="cname1" data-column="name" width="25%">Company</th>
            <th class="hidemobile" data-column="marketCap">Market Cap ($B)</th>
            <th data-column="lastClosePrice">Current Price ($)</th>
            <th data-column="allTimeHighPrice">All Time High ($)</th>
            <th data-column="allTimeHighCount">All-Time High Count*</th>
            <th  class="hidemobile" data-column="lastATH">Last ATH (Days)</th>
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
    
        $categories_query = mysqli_query($con, "SELECT * FROM companies where symbl='$main_id'");
   
    
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
                        <?php echo htmlspecialchars($fetch_categories["name"]); ?>
                    </a>
                    <span class="heart-icon" data-symbol="<?php echo htmlspecialchars($fetch_categories["symbl"]); ?>">&#9825;</span>
                </td>
                <td  class="hidemobile"><?php echo is_numeric($converted_market_cap['billions']) ? number_format($converted_market_cap['billions'], 2) : ''; ?></td>
                <td><?php echo is_numeric($fetch_categories["last_close"]) ? number_format($fetch_categories["last_close"], 2) : ''; ?></td>
                <td><?php echo is_numeric($fetch_categories["all_time_high"]) ? number_format($fetch_categories["all_time_high"], 2) : ''; ?></td>
                <td ><?php echo is_numeric($fetch_categories["all_time_high_count"]) ? number_format($fetch_categories["all_time_high_count"], 0) : ''; ?></td>
                <td class="hidemobile"><?php echo is_numeric($fetch_categories["ath_since"]) ? number_format($fetch_categories["ath_since"], 0) : ''; ?></td>
                
                <td class="<?php if ($correctionRange >= -5) { echo 'bg-danger'; } else if ($correctionRange >= -15) { echo 'bg-warning'; } else { echo 'bg-success'; } ?>">
                    <?php echo is_numeric($fetch_categories["correction_range"]) ? number_format($fetch_categories["correction_range"], 2) : ''; ?>
                </td>
            </tr>
<?php } $con->close(); ?>
    </tbody>
</table>

<script>
    $(document).ready(function() {
         $('#stockTable').DataTable({
                searching: false,
                paging: false,
                info: false,
                order: [[5, 'desc']] // Change the 0 to the index of the column you want to sort by
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
