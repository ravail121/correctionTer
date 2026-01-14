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
    .cname {
        text-align: left !important;
        vertical-align: middle !important;
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
    <h3 class="mb-4">Favourite Stocks List</h3>
    <p class="text-muted">Last Updated:
        <?php
        date_default_timezone_set('America/New_York'); // Set the timezone to New York

        $current_time = strtotime(date('H:i')); // Get the current time in hours and minutes
        $threshold_time = strtotime('16:30'); // 4:30 PM in 24-hour format

        // Check if the current time is before 4:30 PM
        if ($current_time < $threshold_time) {
            $last_updated = date('Y-m-d', strtotime('-1 day')); // Subtract 1 day from current date
        } else {
            $last_updated = date('Y-m-d'); // Use current date
        }
        echo $last_updated . ',  4:30 PM';
        ?>
    </p>

    <div class="table-responsive">
        <table id="favoritesTable" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th class="cname" data-column="name" width="25%">Company</th>
            <th data-column="lastClosePrice">Last Close Price ($)</th>
            <th data-column="allTimeHighPrice">All Time High ($)</th>
            <th data-column="marketCap">Market Cap</th>
            <th class="hidemobile" data-column="averageVolume">Average Volume</th>
            <th data-column="correctionRange">Correction Range (%)</th>
        </tr>
    </thead>
    <tbody id="favoritesTableBody">
        
    </tbody>
</table>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const favorites = JSON.parse(localStorage.getItem('favorites')) || [];
            console.log('Favorites:', favorites); // Debugging

            if (favorites.length > 0) {
                fetch('http://localhost/correction1/fetch_favorites.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ favorites }),
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Fetched Data:', data); // Debugging
                    const tableBody = document.getElementById('favoritesTableBody');
                    tableBody.innerHTML = ''; // Clear previous content

                    if (data.error) {
                        console.error(data.error);
                        tableBody.innerHTML = `<tr><td colspan="6">${data.error}</td></tr>`;
                    } else {
                        data.forEach(company => {
                            const row = document.createElement('tr');

                            row.innerHTML = `
                                <td class="cname">
                                    <a href="https://www.google.com/finance/quote/${company.symbl}:${company.exchange_name}?hl=en&window=1Y" target="_blank">
                                        ${company.name} 
                                    </a>
                                   <span class="heart-icons favorited" onClick="removeFromFavorites('${company.symbl}')" data-symbol="${company.symbl}">&#9829;</span>
                                </td>
                                <td>${company.last_close}</td>
                                <td>${company.all_time_high}</td>
                                <td>${company.converted_market_cap}</td>
                                <td class="hidemobile">${company.converted_average_volume}</td>
                                <td class="${company.correctionRangeClass}">${company.correction_range}</td>
                            `;

                            tableBody.appendChild(row);
                        });
                    }
                })
                .catch(error => console.error('Error fetching favorites:', error));
            } else {
                const tableBody = document.getElementById('favoritesTableBody');
                tableBody.innerHTML = `<tr><td colspan="6">No favorites selected.</td></tr>`;
            }
        });
    </script>

<script>
    // Function to handle removal from favorites
    function removeFromFavorites(symbolToRemove) {
        // Find the corresponding <tr> element and remove it
        const tableBody = document.getElementById('favoritesTableBody');
        const rows = tableBody.getElementsByTagName('tr');

        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            const symbol = row.querySelector('.heart-icons').getAttribute('data-symbol');

            if (symbol === symbolToRemove) {
                row.remove();
                break; // Exit loop once the row is removed
            }
        }

        // Update localStorage or perform other actions if needed
        // Example: Update localStorage favorites list
        const favorites = JSON.parse(localStorage.getItem('favorites')) || [];
        const index = favorites.indexOf(symbolToRemove);
        if (index > -1) {
            favorites.splice(index, 1);
            localStorage.setItem('favorites', JSON.stringify(favorites));
        }
    }
</script>
<style>
    .heart-icons {
        cursor: pointer;
        margin-left: 10px;
        color: red !important;
    }

    .heart-icons.favorited {
        color: red;
    }
</style>

<?php
        function convertVolume($volume) {
            $billions = $volume / 1_000_000_000;
            $billions_rounded = round($billions, 2);
            $millions = $volume / 1_000_000;
            $millions_rounded = round($millions, 2);
            return [
                'billions' => $billions_rounded . ' B',
                'millions' => $millions_rounded . ' M'
            ];
        }
?>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.selectpicker').selectpicker();
        $('#favoritesTable').DataTable({
        searching: false,
        paging: false,
        info: false,
        sorting: false
        // Add more options as needed
    });

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
<?php get_footer(); // Include the footer ?>
 