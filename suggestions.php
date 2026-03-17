   <script>
    // Root Smart Search suggestions: symbol + name
    // Clicking a suggestion navigates to /all with exact symbol match.
    window.ALL_BASE = "/all";
    const companiesSearch = [
<?php
include "db.php";
$companies_query = mysqli_query($con, "SELECT symbl, name FROM companies WHERE name IS NOT NULL AND name != '' AND symbl IS NOT NULL AND symbl != '' ORDER BY name");
$items = [];
while ($row = mysqli_fetch_array($companies_query)) {
    $symbl = json_encode($row['symbl']);
    $name = json_encode($row['name']);
    if ($symbl === false) $symbl = '""';
    if ($name === false) $name = '""';
    $items[] = '{ symbl: ' . $symbl . ', name: ' . $name . ' }';
}
echo "  " . implode(",\n  ", $items);
$con->close();
?>
];
 function showSuggestions() {
        const input = document.getElementById('company-input').value.trim().toUpperCase();
        const suggestionBox = document.getElementById('suggestions');
        suggestionBox.innerHTML = '';

        if (input.length === 0) return;

        const filtered = companiesSearch.filter(function (c) {
            return (c.name && c.name.toUpperCase().indexOf(input) === 0) ||
                   (c.symbl && c.symbl.toUpperCase().indexOf(input) === 0);
        });

        if (filtered.length === 0) {
            const div = document.createElement('div');
            div.className = 'suggestion-item suggestion-empty';
            div.textContent = 'No matches';
            suggestionBox.appendChild(div);
            return;
        }

        const baseUrl = (typeof window !== 'undefined' && window.ALL_BASE) ? window.ALL_BASE : 'all';

        filtered.forEach(function (c) {
            var div = document.createElement('div');
            div.className = 'suggestion-item';
            div.textContent = (c.symbl || '') + ' – ' + (c.name || '');
            div.onclick = function () {
                var sym = encodeURIComponent(c.symbl || '');
                window.location = baseUrl + '?q=' + sym + '&symbol=' + sym + '#row-' + sym;
            };
            suggestionBox.appendChild(div);
        });
    }
</script>
