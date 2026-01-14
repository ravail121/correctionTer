   <script>
    // List of companies fetched from database
    const companies = [
<?php
include "db.php";
$companies_query = mysqli_query($con, "SELECT DISTINCT name FROM companies WHERE name IS NOT NULL AND name != '' ORDER BY name");
$company_names = [];
while ($row = mysqli_fetch_array($companies_query)) {
    $company_names[] = json_encode($row['name']);
}
echo "  " . implode(",\n  ", $company_names);
$con->close();
?>
];
 function showSuggestions() {
            const input = document.getElementById('company-input').value.toUpperCase();
            const suggestionBox = document.getElementById('suggestions');
            suggestionBox.innerHTML = '';

            if (input.length > 0) {
                const filteredCompanies = companies.filter(company => company.toUpperCase().startsWith(input));

                filteredCompanies.forEach(company => {
                    const div = document.createElement('div');
                    div.textContent = company;
                    div.onclick = () => {
                        document.getElementById('company-input').value = company;
                        suggestionBox.innerHTML = '';
                    };
                    suggestionBox.appendChild(div);
                });
            }
        }
</script>
