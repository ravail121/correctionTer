   <script>
    // List of companies (symbol + name) for Smart Search – partial match by symbol or name
    const companiesSearch = [
<?php
include "db.php";
$companies_query = mysqli_query($con, "SELECT symbl, name FROM companies WHERE name IS NOT NULL AND name != '' AND symbl IS NOT NULL AND symbl != '' ORDER BY name");
$items = [];
while ($row = mysqli_fetch_array($companies_query)) {
    $items[] = '{ symbl: ' . json_encode($row['symbl']) . ', name: ' . json_encode($row['name']) . ' }';
}
echo "  " . implode(",\n  ", $items);
?>
];

    function showSuggestions() {
        const input = document.getElementById('company-input').value.trim().toUpperCase();
        const suggestionBox = document.getElementById('suggestions');
        suggestionBox.innerHTML = '';

        if (input.length === 0) return;

        const filtered = companiesSearch.filter(function (c) {
            return (c.name && c.name.toUpperCase().indexOf(input) !== -1) ||
                   (c.symbl && c.symbl.toUpperCase().indexOf(input) !== -1);
        });

        if (filtered.length === 0) {
            const div = document.createElement('div');
            div.className = 'suggestion-item suggestion-empty';
            div.textContent = 'No matches';
            suggestionBox.appendChild(div);
            return;
        }

        var baseUrl = 'all';
        var q = encodeURIComponent(document.getElementById('company-input').value.trim());

        // "All" option – show table with all matching results
        var allDiv = document.createElement('div');
        allDiv.className = 'suggestion-item suggestion-all';
        allDiv.textContent = 'All (' + filtered.length + ' result' + (filtered.length !== 1 ? 's' : '') + ')';
        allDiv.onclick = function () {
            window.location = baseUrl + (q ? '?q=' + q : '');
        };
        suggestionBox.appendChild(allDiv);

        // Individual matches – selecting navigates to result and scrolls to row
        filtered.forEach(function (c) {
            var div = document.createElement('div');
            div.className = 'suggestion-item';
            div.textContent = (c.symbl || '') + ' – ' + (c.name || '');
            div.onclick = function () {
                window.location = baseUrl + (q ? '?q=' + q : '') + '#row-' + encodeURIComponent(c.symbl || '');
            };
            suggestionBox.appendChild(div);
        });
    }

    document.addEventListener('click', function (e) {
        var box = document.getElementById('suggestions');
        var input = document.getElementById('company-input');
        if (box && input && !box.contains(e.target) && e.target !== input) box.innerHTML = '';
    });
</script>
