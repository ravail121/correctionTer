<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Test</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"> <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"> <link href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</head>
<body>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"> <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"> <link href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  
<style>
        .sortable {
            cursor: pointer;
            position: relative;
            padding-right: 20px;
        } 

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
            text-align: left;
        }
         @media (max-width: 768px) {
        .hidemobile {
            display: none;
        }
        thead,table {
            font-size: 12px;
        }
    }
    </style>
    

    <div class="container">
        <h3 class="mb-4">All S&P 500 stocks and their current correction territory categories</h3>
        <p class="text-muted">Last Updated: <span id="currenttime"></span></p>
        <div class="row">
            <div class="col-md-12">
                <div class="mb-3">
                     <select id="companySelect" class="selectpicker" data-show-subtext="true" data-live-search="true">
                     <option selected disabled>Find a company</option>
                        <!-- Options for companies will be populated dynamically -->
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="filterOption" id="filterOption1" value="-10">
                        <label class="form-check-label" for="filterOption1">Stocks down more than 10%</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="filterOption" id="filterOption2" value="-20">
                        <label class="form-check-label" for="filterOption2">More than 20%</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="filterOption" id="filterOption3" value="-30">
                        <label class="form-check-label" for="filterOption3">More than 30%</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="filterOption" id="filterOption4" value="-40">
                        <label class="form-check-label" for="filterOption4">More than 40%</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="filterOption" id="filterOption5" value="magnificentSeven">
                        <label class="form-check-label" for="filterOption5">Magnificent Seven</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th  class="cname" data-column="name" width="25%">Company Name</th>
                    <th  data-column="lastClosePrice">Last Close Price ($)</th>
                    <th  data-column="allTimeHighPrice">All Time High ($)</th>
                    <th class="sortable" data-column="marketCap">Market Cap</th>
                    <th class="sortable hidemobile" data-column="averageVolume">Average Volume</th>
                    <th class="sortable" data-column="correctionRange">Correction Range (%)</th>
                </tr>
            </thead>
            <tbody id="stockTableBody" style="background: #ffff2f47;">
                <!-- Stock data will be appended here -->
            </tbody>
        </table>
        </div>
        <div id="loadingMessage" class="alert alert-info">Loading data, please wait...</div>
    </div>
    <script>
        const apiKey = 'zq_ujf8gikb5aQgmyimpTQ7CIIpN5x2x';
        const companies = {'Meta': 'Meta Platforms Inc.',
    'NVDA': 'NVIDIA Corporation',
    'AMZN': 'Amazon.com Inc.',
    'GOOGL': 'Alphabet Inc.',
    'MSFT': 'Microsoft Corporation'
                        };



        const fetchStockData = async (symbol) => {
            try {
                const responseLastClose = await fetch(`https://api.polygon.io/v2/aggs/ticker/${symbol}/prev?adjusted=true&apiKey=${apiKey}`);
                if (!responseLastClose.ok) throw new Error(`Error fetching last close data for ${symbol}`);
                const lastCloseData = await responseLastClose.json();
                const lastClosePrice = lastCloseData.results[0].c;

                const responseAllTimeHigh = await fetch(`https://api.polygon.io/v2/aggs/ticker/${symbol}/range/1/day/2010-01-01/2024-12-31?adjusted=true&apiKey=${apiKey}`);
                if (!responseAllTimeHigh.ok) throw new Error(`Error fetching all time high data for ${symbol}`);
                const allTimeHighData = await responseAllTimeHigh.json();
                const allTimeHighPrice = Math.max(...allTimeHighData.results.map(result => result.h));



                const responseCompany = await fetch(`https://api.polygon.io/v3/reference/tickers/${symbol}?apiKey=${apiKey}`);
                if (!responseCompany.ok) throw new Error(`Error fetching company data for ${symbol}`);
                const companyData = await responseCompany.json();
                const marketCap = companyData.results.market_cap;


                console.log(formatMarketCap(marketCap));



                const responseVolume = await fetch(`https://api.polygon.io/v2/aggs/ticker/${symbol}/range/1/day/2024-05-01/2024-05-31?adjusted=true&apiKey=${apiKey}`);
                if (!responseVolume.ok) throw new Error(`Error fetching volume data for ${symbol}`);
                const volumeData = await responseVolume.json();
                const averageVolume = volumeData.results.reduce((sum, day) => sum + day.v, 0) / volumeData.results.length;

                const correctionRange = ((lastClosePrice - allTimeHighPrice) / allTimeHighPrice * 100).toFixed(2);

                return {
                    symbol: symbol,
                    name: companies[symbol],
                    lastClosePrice: lastClosePrice.toFixed(2),
                    allTimeHighPrice: allTimeHighPrice.toFixed(2),
                    marketCap: marketCap,
                    averageVolume: averageVolume.toFixed(0),
                    correctionRange: correctionRange


                };
            } catch (error) {
                console.error(`Error fetching data for ${symbol}:`, error);
                return null;
            }
        };

const populateTable = async () => {
    const tableBody = document.getElementById('stockTableBody');
    const stockPromises = Object.keys(companies).map(fetchStockData);
    const stocksData = await Promise.all(stockPromises);

    stocksData.forEach(data => {
        if (data) {
            const correctionRangeColor = getCorrectionRangeColor(parseFloat(data.correctionRange));
            const row = document.createElement('tr');
            row.setAttribute('data-correction-range', data.correctionRange);
            row.setAttribute('data-symbol', data.symbol);
            row.innerHTML = `
                <td class="cname"><a href="https://www.google.com/finance/quote/${data.symbol}:NASDAQ?hl=en&window=1Y" target="_blank">${data.name}</a></td>
                <td style="text-align: center;">${data.lastClosePrice}</td>
                <td style="text-align: center;">${data.allTimeHighPrice}</td>
                <td style="text-align: center;"><div id="${data.symbol}">${data.marketCap}</div></td>
                <td style="text-align: center;" class="hidemobile">${data.averageVolume}</td>
                <td style="text-align: center;" class="centered ${correctionRangeColor}"><span>${data.correctionRange}</span></td>
            `;
            tableBody.appendChild(row);

            // Send data to PHP script for database insertion
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "insertData.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            const params = `id=${data.id}&symbl=${data.symbol}&name=${data.name}&last_close=${data.lastClosePrice}&all_time_high=${data.allTimeHighPrice}&market_cap=${data.marketCap}&average_volume=${data.averageVolume}&correction_range=${data.correctionRange}`;
            xhr.send(params);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log('Data inserted successfully:', xhr.responseText);
                } else if (xhr.readyState === 4) {
                    console.error('Error inserting data:', xhr.responseText);
                }
            };

           

        }
    });

    setLastUpdateDate();
};


        const filterStockData = (correctionRangeFilter) => {
            const stockDataElement = document.getElementById('stockTableBody');
            const rows = stockDataElement.getElementsByTagName('tr');
            const magnificentSeven = ['AAPL', 'GOOGL', 'MSFT', 'AMZN', 'META', 'TSLA', 'NVDA'];
            for (let i = 0; i < rows.length; i++) {
                const correctionRange = parseFloat(rows[i].getAttribute('data-correction-range'));
                const symbol = rows[i].getAttribute('data-symbol');
                if (correctionRangeFilter === 'magnificentSeven') {
                    if (!magnificentSeven.includes(symbol)) {
                        rows[i].style.display = 'none';
                    } else {
                        rows[i].style.display = '';
                    }
                } else {
                    if (correctionRange > correctionRangeFilter) {
                        rows[i].style.display = 'none';
                    } else {
                        rows[i].style.display = '';
                    }
                }
            }
        };

        const setLastUpdateDate = () => {
            const lastUpdateElement = document.getElementById('lastUpdate');
            const now = new Date();
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            const formattedDate = now.toLocaleDateString('en-US', options);
            lastUpdateElement.textContent = `Last Update: ${formattedDate}`;
        };

        const formatMarketCap = (marketCap) => {
            const billion = 1000000000;
            const trillion = 1000000000000;
            if (marketCap >= billion) {
                return (marketCap / billion).toFixed(2) + ' B';
            } else {
                return marketCap.toFixed(2) + ' M';
            }
        };

        const formatAverageVolume = (avgVolume) => {
            const million = 1000000;
            const billion = 1000000000;
            if (avgVolume >= billion) {
                return (avgVolume / billion).toFixed(2) + ' B';
            } else if (avgVolume >= million) {
                return (avgVolume / million).toFixed(2) + ' M';
            } else {
                return avgVolume.toFixed(2);
            }
        };

        const getCorrectionRangeColor = (correctionRange) => {
            if (correctionRange >= -5) {
                return 'bg-danger';
            } else if (correctionRange >= -15) {
                return 'bg-warning';
            } else {
                return 'bg-success';
            }
        };

        document.addEventListener('DOMContentLoaded', () => {
            populateTable();

            const filterOptions = document.getElementsByName('filterOption');
            filterOptions.forEach(option => {
                option.addEventListener('change', function() {
                    const selectedFilter = this.value;
                    filterStockData(selectedFilter);
                });
            });

            

            // Search functionality
            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('input', () => {
                const filter = searchInput.value.toLowerCase();
                const rows = document.querySelectorAll('#stockTableBody tr');
                rows.forEach(row => {
                    const companyName = row.querySelector('.cname').textContent.toLowerCase();
                    row.style.display = companyName.includes(filter) ? '' : 'none';
                });
            });
        });


        // Populate the select input with options for companies
        const dropDownCompanies = {
                        'AAPL': 'Apple Inc.',
                        'MSFT': 'Microsoft Corporation',
                        'GOOGL': 'Alphabet Inc.'
        };
        const companySelect = document.getElementById('companySelect');
        Object.entries(dropDownCompanies).forEach(([symbol, name]) => {
            const option = document.createElement('option');
            option.value = symbol;
            option.textContent = name;
            companySelect.appendChild(option);
        });


 // Function to filter stock data by selected company
const filterStockDataByCompany = (selectedCompany) => {
    const stockDataElement = document.getElementById('stockTableBody');
    const rows = stockDataElement.getElementsByTagName('tr');
    let found = false;

    // Iterate through existing rows to show or hide based on the selected company
    for (let i = 0; i < rows.length; i++) {
        const symbol = rows[i].getAttribute('data-symbol');
        if (symbol !== selectedCompany) {
            rows[i].style.display = 'none';
        } else {
            rows[i].style.display = '';
            found = true;
        }
    }

    console.log("Selected Company:", selectedCompany);
    console.log("Rows:", rows);
    console.log("Found:", found);

    // If the selected company is not found in existing rows, fetch and append new row
    // If the selected company is not found in existing rows, fetch and append new row
// If the selected company is not found in existing rows, fetch and append new row
if (!found) {
     const loadingMessage = document.getElementById('loadingMessage');
        loadingMessage.style.display = 'block';
    const newData = fetchStockData(selectedCompany); // Assuming you have a function to fetch data
    console.log("New Data:", newData);
    newData.then(data => {
        console.log("Data for", selectedCompany, ":", data);
        if (data) {
            console.log("Data structure:", data); // Add this line to inspect the structure of the data object
            const correctionRangeColor = getCorrectionRangeColor(parseFloat(data.correctionRange));
            const row = document.createElement('tr');
            row.setAttribute('data-correction-range', data.correctionRange);
            row.setAttribute('data-symbol', data.symbol);
            row.innerHTML = `
                <td class="cname"><a href="https://www.google.com/finance/quote/${selectedCompany}:NASDAQ?hl=en&window=1Y" target="_blank">${selectedCompany}</a></td>
                <td style="text-align: center;">${data.lastClosePrice}</td>
                <td style="text-align: center;">${data.allTimeHighPrice}</td>
                <td style="text-align: center;">${formatMarketCap(data.marketCap)}</td>
                <td style="text-align: center;"  class="hidemobile">${formatAverageVolume(data.averageVolume)}</td>
                <td style="text-align: center;" class="centered ${correctionRangeColor}"><span>${data.correctionRange}</span></td>
            `;
            stockDataElement.appendChild(row);
            loadingMessage.style.display = 'none';
        } else {
            console.log("No data found for", selectedCompany);
        }
    }).catch(error => {
        console.error("Error fetching data:", error);
    });
}


};

document.addEventListener('DOMContentLoaded', () => {
    // Other code...

    const companySelect = document.getElementById('companySelect');
    companySelect.addEventListener('change', function() {
        const selectedCompany = this.value;
        filterStockDataByCompany(selectedCompany);
    });

    

});
        // Simulate loading message display for 3 seconds
        const loadingMessage = document.getElementById('loadingMessage');
        loadingMessage.style.display = 'block';
        setTimeout(() => {
            loadingMessage.style.display = 'none';
            populateTable(stockData);
        }, 8000);

         var date = new Date();
        
        var days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        var dayName = days[date.getDay()];
        var monthName = months[date.getMonth()];
        var day = date.getDate();
        var year = date.getFullYear();

        var formattedDate = dayName + ' ' + monthName + ' ' + (day < 10 ? '0' : '') + day + ' ' + year;

        var formattedTime = date.toLocaleTimeString();

        document.getElementById("currenttime").innerHTML = formattedDate + ' ' + formattedTime;

    </script>
 <script>
        $(document).ready(function(){
            $('.selectpicker').selectpicker();
        });
    </script>

<script type="text/javascript">
  $(document).ready(function(){
    $('th[data-column="marketCap"], th[data-column="averageVolume"], th[data-column="correctionRange"]').click(function(){
        var table = $(this).parents('table').eq(0);
        var rows = table.find('tbody tr').toArray().sort(comparer($(this).index()));
        if (!this.asc) { // Check if sorting direction is ascending
            rows.reverse(); // Reversing rows for descending sort on first click
        }
        for (var i = 0; i < rows.length; i++) {
            table.append(rows[i]);
        }
        this.asc = !this.asc; // Toggling sorting direction after sorting
    });

    function comparer(index) {
        return function(a, b) {
            var valA = getCellValue(a, index), valB = getCellValue(b, index);
            return valA - valB;
        };
    }

    function getCellValue(row, index) {
        var cellText = $(row).children('td').eq(index).text();
        var numericValue = parseFloat(cellText);
        var unitMultiplier = d1;

        if (cellText.endsWith('B USD')) {
            unitMultiplier = 1e9; // 1 billion
        } else if (cellText.endsWith('T USD')) {
            unitMultiplier = 1e12; // 1 trillion
        } else if (cellText.endsWith('M USD')) {
            unitMultiplier = 1e6; // 1 million
        }

        return numericValue * unitMultiplier;
    }

    // Initialize sorting direction as false (descending)
    $('th[data-column="marketCap"], th[data-column="averageVolume"], th[data-column="correctionRange"]').each(function() {
        this.asc = false;
    });
});



</script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>