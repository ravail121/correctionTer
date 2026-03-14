<?php
/**
 * Shared AdSense block (2 responsive units).
 * Include this file wherever we want the ad row to appear.
 */

?>

<?php if (!defined('ADSENSE_LOADED')): ?>
  <?php define('ADSENSE_LOADED', true); ?>
  <!-- AdSense script loaded only after page fully loads to prevent blocking -->
  <script>
  (function() {
    // Only load AdSense script after page is fully loaded (all resources loaded)
    function loadAdSenseScript() {
      // Prevent multiple loads
      if (window.adsenseScriptLoading || window.adsenseScriptLoaded) {
        return;
      }
      
      window.adsenseScriptLoading = true;
      
      var script = document.createElement('script');
      script.async = true;
      script.defer = true;
      script.src = 'https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8754771874266985';
      script.crossOrigin = 'anonymous';
      script.onload = function() {
        window.adsenseScriptLoaded = true;
        window.adsenseScriptLoading = false;
      };
      script.onerror = function() {
        window.adsenseScriptLoading = false;
        console.warn('AdSense script failed to load');
      };
      
      // Append script to head
      document.head.appendChild(script);
    }
    
    // Load AdSense script as soon as possible without extra delays
    if (document.readyState === 'complete') {
      // Page already fully loaded
      loadAdSenseScript();
    } else {
      // Load once page has fully loaded
      window.addEventListener('load', loadAdSenseScript, { once: true });
    }
  })();
  </script>
<?php endif; ?>

<div class="container" style="padding:0 5px; margin:4px auto; text-align:center;">
  <div style="margin:0 auto 8px; max-width:100%;">
    <ins class="adsbygoogle"
         style="display:block; margin:0 auto;"
         data-ad-client="ca-pub-8754771874266985"
         data-ad-slot="7225743774"
         data-ad-format="auto"
         data-full-width-responsive="true"></ins>
  </div>
  <div style="margin:0 auto 8px; max-width:100%;">
    <ins class="adsbygoogle"
         style="display:block; margin:0 auto;"
         data-ad-client="ca-pub-8754771874266985"
         data-ad-slot="2910158078"
         data-ad-format="auto"
         data-full-width-responsive="true"></ins>
  </div>
</div>

<!-- Initialize ads only after page fully loads and AdSense script is ready -->
<script>
(function() {
  var adsInitialized = false;
  
  function initializeAds() {
    // Don't prevent re-initialization - we need to check each ad individually
    try {
      // Ensure adsbygoogle array exists
      window.adsbygoogle = window.adsbygoogle || [];
      
      // Get all ad containers
      var adContainers = document.querySelectorAll('.adsbygoogle');
      var initializedCount = 0;
      
      // Initialize each ad container that hasn't been initialized yet
      adContainers.forEach(function(adContainer) {
        // Check if this specific ad has been initialized
        // AdSense sets data-adsbygoogle-status="done" when initialized
        if (!adContainer.hasAttribute('data-adsbygoogle-status') && typeof adsbygoogle !== 'undefined') {
          try {
            // Push empty object for each ad container - this is required for each ad
            (adsbygoogle = window.adsbygoogle || []).push({});
            initializedCount++;
          } catch (e) {
            console.warn('AdSense push error for container:', e);
          }
        } else if (adContainer.hasAttribute('data-adsbygoogle-status')) {
          initializedCount++;
        }
      });
      
      // Mark as initialized only if all ads have been processed
      if (initializedCount > 0) {
        adsInitialized = true;
      }
    } catch (e) {
      console.warn('AdSense initialization error:', e);
    }
  }
  
  // Function to check if AdSense script is loaded and initialize
  function tryInitializeAds() {
    // Check if the script has loaded by checking for adsbygoogle object
    // Also verify the script is actually loaded (not just the array initialized)
    if (typeof adsbygoogle !== 'undefined' && window.adsenseScriptLoaded) {
      initializeAds();
      return true;
    }
    return false;
  }
  
  // Wait for page to fully load before initializing ads
  function waitForPageLoad() {
    if (document.readyState === 'complete') {
      // Page already fully loaded, wait for script
      waitForAdSenseScript();
    } else {
      // Wait for page to fully load (all resources including images, stylesheets, etc.)
      window.addEventListener('load', function() {
        waitForAdSenseScript();
      }, { once: true });
    }
  }
  
  // Initialize ads when AdSense script is available, without timeouts/polling
  function waitForAdSenseScript() {
    if (tryInitializeAds()) {
      checkAndRetryAds();
    } else if (typeof adsbygoogle !== 'undefined') {
      // Fallback: initialize directly if script array exists
      initializeAds();
      checkAndRetryAds();
    }
  }
  
  // Check if all ads are initialized and retry once if needed (no timers)
  function checkAndRetryAds() {
    var adContainers = document.querySelectorAll('.adsbygoogle');
    var uninitializedCount = 0;
    
    adContainers.forEach(function(adContainer) {
      if (!adContainer.hasAttribute('data-adsbygoogle-status')) {
        uninitializedCount++;
      }
    });
    
    // If there are uninitialized ads, try initializing once more immediately
    if (uninitializedCount > 0 && typeof adsbygoogle !== 'undefined') {
      initializeAds();
    }
  }
  
  // Start the process
  waitForPageLoad();
})();
</script>

