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
      var scriptLoaded = false;
      var scriptTimeout = setTimeout(function() {
        if (!scriptLoaded) {
          console.warn('AdSense script load timeout - continuing without blocking');
          // Mark as loaded to prevent further blocking
          window.adsbygoogle = window.adsbygoogle || [];
          scriptLoaded = true;
          window.adsenseScriptLoading = false;
        }
      }, 5000); // 5 second timeout
      
      var script = document.createElement('script');
      script.async = true;
      script.defer = true;
      script.src = 'https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8754771874266985';
      script.crossOrigin = 'anonymous';
      script.onload = function() {
        scriptLoaded = true;
        clearTimeout(scriptTimeout);
        window.adsenseScriptLoaded = true;
        window.adsenseScriptLoading = false;
      };
      script.onerror = function() {
        scriptLoaded = true;
        clearTimeout(scriptTimeout);
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
    // Prevent double initialization
    if (adsInitialized) {
      return;
    }
    
    try {
      // Ensure adsbygoogle array exists
      window.adsbygoogle = window.adsbygoogle || [];
      
      // Get all ad containers that haven't been initialized
      var adContainers = document.querySelectorAll('.adsbygoogle');
      var uninitialized = [];
      
      adContainers.forEach(function(adContainer) {
        // Check if this ad has been initialized
        if (!adContainer.hasAttribute('data-adsbygoogle-status')) {
          uninitialized.push(adContainer);
        }
      });
      
      // Initialize each uninitialized ad
      if (uninitialized.length > 0 && typeof adsbygoogle !== 'undefined') {
        uninitialized.forEach(function(adContainer) {
          try {
            (adsbygoogle = window.adsbygoogle || []).push({});
          } catch (e) {
            console.warn('AdSense push error for container:', e);
          }
        });
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
        return; // Successfully initialized
      }
      
      // Script not loaded yet, poll for it
      var attempts = 0;
      var maxAttempts = 60; // 6 seconds max (60 * 100ms)
      var pollInterval = setInterval(function() {
        attempts++;
        if (tryInitializeAds()) {
          clearInterval(pollInterval);
        } else if (attempts >= maxAttempts) {
          clearInterval(pollInterval);
          // Final attempt - initialize even if script status unclear
          // This handles cases where script loaded but flag wasn't set
          if (typeof adsbygoogle !== 'undefined') {
            initializeAds();
          }
        }
      }, 100);
    }, 1000); // Wait 1 second after page load for script to start loading
  }
  
  // Start the process
  waitForPageLoad();
})();
</script>

<!-- PWA Install Button (Chrome/Edge only) -->
<button id="pwaInstallBtn" style="display:none; position:fixed; bottom:90px; right:20px; z-index:9998; background-color:#333333; color:#ffffff; border:none; border-radius:50px; padding:12px 24px; font-size:14px; font-weight:600; cursor:pointer; box-shadow:0 4px 12px rgba(0,0,0,0.3); transition:all 0.3s ease; font-family:inherit;">
  <i class="fa fa-download" style="margin-right:8px;"></i> Install App
</button>

<style>
  #pwaInstallBtn:hover {
    background-color:#444444;
    transform:translateY(-2px);
    box-shadow:0 6px 16px rgba(0,0,0,0.4);
  }
  #pwaInstallBtn:active {
    transform:translateY(0);
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

  // Check if already installed
  function isPWAInstalled() {
    return window.matchMedia('(display-mode: standalone)').matches || 
           window.navigator.standalone === true;
  }

  // Only proceed if Chrome/Edge and not already installed
  if (!isChromeOrEdge() || isPWAInstalled()) {
    return;
  }

  let deferredPrompt = null;
  const installBtn = document.getElementById('pwaInstallBtn');
  let showTimeout = null;

  // Listen for the beforeinstallprompt event
  window.addEventListener('beforeinstallprompt', function(e) {
    // Prevent the mini-infobar from appearing on mobile
    e.preventDefault();
    deferredPrompt = e;

    // Show button after 10 seconds (only if not already visible)
    showTimeout = setTimeout(function() {
      if (deferredPrompt && !isPWAInstalled() && installBtn.style.display === 'none') {
        installBtn.style.display = 'block';
      }
    }, 10000); // 10 seconds
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

    if (showTimeout) {
      clearTimeout(showTimeout);
      showTimeout = null;
    }
  });

  // Hide button if app is installed
  window.addEventListener('appinstalled', function() {
    deferredPrompt = null;
    installBtn.style.display = 'none';
    if (showTimeout) {
      clearTimeout(showTimeout);
      showTimeout = null;
    }
  });

  // Clean up on page unload
  window.addEventListener('beforeunload', function() {
    if (showTimeout) {
      clearTimeout(showTimeout);
    }
  });
})();
</script>

