<?php
require_once 'config.php';
require_once 'functions.php';
require_once 'error_tracker.php';

$date = getTradingDate();

// $companies = [
//     'CINF' => 'Cincinnati Financial',
//     'CL' => 'Colgate-Palmolive',
//     'CLX' => 'Clorox',
//     'CMA' => 'Comerica',
//     'CMCSA' => 'Comcast',
//     'CME' => 'CME Group',
//     'CMG' => 'Chipotle Mexican Grill',
//     'CMI' => 'Cummins',
//     'CMS' => 'CMS Energy',
//     'CNC' => 'Centene Corporation',
//     'CNP' => 'CenterPoint Energy',
//     'COF' => 'Capital One',
//     'COO' => 'CooperCompanies',
//     'COP' => 'ConocoPhillips',
//     'COR' => 'Cencora',
//     'COST' => 'Costco',
//     'CPAY' => 'Corpay',
//     'CPB' => 'Campbell Soup Company',
//     'CPRT' => 'Copart',
//     'CPT' => 'Camden Property Trust',
//     'CRL' => 'Charles River Laboratories',
//     'CRM' => 'Salesforce',
//     'CSCO' => 'Cisco',
//     'CSGP' => 'CoStar Group',
//     'CSX' => 'CSX',
//     'CTAS' => 'Cintas',
//     // 'CTLT' => 'Catalent',
//     'CTRA' => 'Coterra',
//     'CTSH' => 'Cognizant',
//     'CTVA' => 'Corteva',
//     'CVS' => 'CVS Health',
//     'CVX' => 'Chevron Corporation',
//     'CZR' => 'Caesars Entertainment',
//     'D' => 'Dominion Energy',
//     'DAL' => 'Delta Air Lines',
//     'DAY' => 'Dayforce',
//     'DD' => 'DuPont',
//     'DE' => 'John Deere',
//     'DECK' => 'Deckers Brands',
//     // 'DFS' => 'Discover Financial',
//     'DG' => 'Dollar General',
//     'DGX' => 'Quest Diagnostics',
//     'DHI' => 'DR Horton',
//     'DHR' => 'Danaher Corporation',
//     'DIS' => 'Walt Disney Company (The)',
//     'DLR' => 'Digital Realty',
//     'DLTR' => 'Dollar Tree',
//     'DOC' => 'Healthpeak',
//     'DOV' => 'Dover Corporation',
//     'DOW' => 'Dow Inc.',
//     'DPZ' => 'Domino\'s',
//     'DRI' => 'Darden Restaurants',
//     'DTE' => 'DTE Energy',
//     'DUK' => 'Duke Energy',
//     'DVA' => 'DaVita Inc.',
//     'DVN' => 'Devon Energy',
//     'DXCM' => 'Dexcom',
//     'EA' => 'Electronic Arts',
//     'EBAY' => 'eBay',
//     'ECL' => 'Ecolab',
//     'ED' => 'Consolidated Edison',
//     'EFX' => 'Equifax',
//     'EG' => 'Everest Re',
//     'EIX' => 'Edison International',
//     'EL' => 'Estée Lauder Companies (The)',
//     'ELV' => 'Elevance Health',
//     'EMN' => 'Eastman Chemical Company',
//     'EMR' => 'Emerson Electric',
//     'ENPH' => 'Enphase',
//     'EOG' => 'EOG Resources',
//     'EPAM' => 'EPAM Systems',
//     'EQIX' => 'Equinix',
//     'EQR' => 'Equity Residential',
//     'EQT' => 'EQT Corporation',
//     'ES' => 'Eversource',
//     'ESS' => 'Essex Property Trust',
//     'ETN' => 'Eaton Corporation',
//     'ETR' => 'Entergy',
//     'ETSY' => 'Etsy',
//     'EVRG' => 'Evergy',
//     'EW' => 'Edwards Lifesciences',
//     'EXC' => 'Exelon',
//     'EXPD' => 'Expeditors International',
//     'EXPE' => 'Expedia Group',
//     'EXR' => 'Extra Space Storage',
//     'F' => 'Ford Motor Company',
//     'FANG' => 'Diamondback Energy',
//     'FAST' => 'Fastenal',
//     'FCX' => 'Freeport-McMoRan',
//     'FDS' => 'FactSet',
//     'FDX' => 'FedEx',
//     'FE' => 'FirstEnergy',
//     'FFIV' => 'F5, Inc.',
//     // 'FI' => 'Fiserv'
// ];


$con = getDbConnection();
$errorTracker = new ErrorTracker($con, __FILE__);

$companies = getCompaniesChunk($con, 1);

processCompanies($companies, $con, $errorTracker, $date);

// Errors are now saved to database only - no email sending from stock scripts
// Email will be sent by nightly script (send_stock_error_email.php)

$con->close();
?>
