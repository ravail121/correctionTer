<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorites</title>
    <style>
        .bg-danger {
            background-color: red;
            color: white;
        }
        .bg-warning {
            background-color: yellow;
            color: black;
        }
        .bg-success {
            background-color: green;
            color: white;
        }
    </style>
</head>
<body>
    <h1>Favorites</h1>
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
            <!-- Fetched favorites will be inserted here -->
        </tbody>
    </table>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const favorites = JSON.parse(localStorage.getItem('favorites')) || [];
            console.log('Favorites:', favorites); // Debugging

            if (favorites.length > 0) {
                fetch('fetch_favorites.php', {
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
    
</body>
</html>
