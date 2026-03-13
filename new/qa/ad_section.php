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
    
    // Wait for window.load event (page fully loaded including all resources)
    if (document.readyState === 'complete') {
      // Page already fully loaded, load script after a short delay
      setTimeout(loadAdSenseScript, 500);
    } else {
      // Wait for page to fully load
      window.addEventListener('load', function() {
        // Additional delay to ensure page is fully rendered
        setTimeout(loadAdSenseScript, 500);
      });
    }
  })();
  </script>
<?php endif; ?>

<div class="container" style="padding:0 5px; margin:12px auto; text-align:center;">
  <div style="margin:0 auto 12px; max-width:100%;">
    <ins class="adsbygoogle"
         style="display:block; margin:0 auto;"
         data-ad-client="ca-pub-8754771874266985"
         data-ad-slot="7225743774"
         data-ad-format="auto"
         data-full-width-responsive="true"></ins>
  </div>
  <div style="margin:0 auto 12px; max-width:100%;">
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
  
  // Wait for AdSense script to load, then initialize ads
  function waitForAdSenseScript() {
    // Give script time to load (it starts loading after page load)
    setTimeout(function() {
      if (tryInitializeAds()) {
        // Check if all ads are initialized, if not, try again
        checkAndRetryAds();
        return; // Successfully initialized
      }
      
      // Script not loaded yet, poll for it
      var attempts = 0;
      var maxAttempts = 60; // 6 seconds max (60 * 100ms)
      var pollInterval = setInterval(function() {
        attempts++;
        if (tryInitializeAds()) {
          clearInterval(pollInterval);
          checkAndRetryAds();
        } else if (attempts >= maxAttempts) {
          clearInterval(pollInterval);
          // Final attempt - initialize even if script status unclear
          // This handles cases where script loaded but flag wasn't set
          if (typeof adsbygoogle !== 'undefined') {
            initializeAds();
            checkAndRetryAds();
          }
        }
      }, 100);
    }, 1000); // Wait 1 second after page load for script to start loading
  }
  
  // Check if all ads are initialized and retry if needed
  function checkAndRetryAds() {
    var adContainers = document.querySelectorAll('.adsbygoogle');
    var uninitializedCount = 0;
    
    adContainers.forEach(function(adContainer) {
      if (!adContainer.hasAttribute('data-adsbygoogle-status')) {
        uninitializedCount++;
      }
    });
    
    // If there are uninitialized ads, try again after a delay
    if (uninitializedCount > 0 && typeof adsbygoogle !== 'undefined') {
      setTimeout(function() {
        initializeAds();
        // One more check after 2 seconds
        setTimeout(function() {
          var stillUninitialized = document.querySelectorAll('.adsbygoogle:not([data-adsbygoogle-status])');
          if (stillUninitialized.length > 0 && typeof adsbygoogle !== 'undefined') {
            initializeAds();
          }
        }, 2000);
      }, 500);
    }
  }
  
  // Start the process
  waitForPageLoad();
})();
</script>

<!-- PWA Install Button (Chrome/Edge only) -->
<button id="pwaInstallBtn" style="display:none; position:fixed; bottom:90px; right:0; z-index:9998; background-color:#f9f9f9; color:#000000; border:1px solid #000000; border-radius:3px 0 0 3px; height:74px; width:70px; font-size:11px; font-weight:500; cursor:pointer; box-shadow:0 2px 4px rgba(0,0,0,0.3); transition:all 0.2s ease; font-family:inherit; text-align:center; padding:0; display:flex; align-items:center; justify-content:center; flex-direction:column;">
  <i class="fa fa-download" style="margin-bottom:4px; font-size:20px; color:#000000;"></i>
  <span>Install App</span>
</button>

<style>
  #pwaInstallBtn:hover {
    background-color:#ffffff;
    box-shadow:0 2px 6px rgba(0,0,0,0.25);
  }
  #pwaInstallBtn:active {
    box-shadow:0 1px 3px rgba(0,0,0,0.2);
  }
</style>

<script>
(function() {
  // Detect Chrome or Edge browser only
  function isChromeOrEdge() {
    const userAgent = navigator.userAgent.toLowerCase();
    const isChrome = /chrome/.test(userAgent) && !/edg/.test(userAgent) && !/opr/.test(userAgent) && !/brave/.test(userAgent);
    const isEdge = /edg/.test(userAgent);
    return isChrome || isEdge;
  }

  // Only proceed if Chrome/Edge
  if (!isChromeOrEdge()) {
    return;
  }

  let deferredPrompt = null;
  const installBtn = document.getElementById('pwaInstallBtn');

  // Listen for the beforeinstallprompt event
  window.addEventListener('beforeinstallprompt', function(e) {
    // Prevent the mini-infobar from appearing on mobile
    e.preventDefault();
    deferredPrompt = e;

    // Show button 10 seconds after install prompt is available
    setTimeout(function() {
      installBtn.style.display = 'flex';
    }, 10000);
  });

  // Handle button click
  installBtn.addEventListener('click', async function() {
    if (!deferredPrompt) {
      return;
    }

    // Show the install prompt
    deferredPrompt.prompt();

    // Wait for the user to respond to the prompt
    const choiceResult = await deferredPrompt.userChoice;

    // Clear the deferredPrompt so it can only be used once
    deferredPrompt = null;

    // Only hide button if user accepted the install
    // If user dismissed/cancelled, keep button visible
    if (choiceResult.outcome === 'accepted') {
      installBtn.style.display = 'none';
    }
    // If dismissed, button stays visible so user can try again later
  });
})();
</script>

