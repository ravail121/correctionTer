   <script>
    // Base URL for "All" and search – works on any host/path (localhost vs production)
    window.QA_ALL_BASE = <?php echo json_encode(rtrim(str_replace("\\", "/", dirname($_SERVER["SCRIPT_NAME"])), "/") . "/all"); ?>;
    // List of companies (symbol + name) for Smart Search – partial match by symbol or name
    const companiesSearch = [
<?php
include __DIR__ . "/db.php";
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

        var baseUrl = (typeof window !== 'undefined' && window.QA_ALL_BASE) ? window.QA_ALL_BASE : 'all';

        // Each match: click navigates to that specific symbol only (one result)
        filtered.forEach(function (c) {
            var div = document.createElement('div');
            div.className = 'suggestion-item';
            div.textContent = (c.symbl || '') + ' – ' + (c.name || '');
            div.onclick = function () {
                window.location = baseUrl + '?q=' + encodeURIComponent(c.symbl || '') + '#row-' + encodeURIComponent(c.symbl || '');
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
