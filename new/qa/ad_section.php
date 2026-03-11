<?php
/**
 * Shared AdSense block (2 responsive units).
 * Include this file wherever we want the ad row to appear.
 */

?>

<?php if (!defined('ADSENSE_LOADED')): ?>
  <?php define('ADSENSE_LOADED', true); ?>
  <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8754771874266985" crossorigin="anonymous"></script>
<?php endif; ?>

<div class="container" style="padding:0 5px; margin:12px auto; text-align:center;">
  <div style="margin:0 auto 12px; max-width:100%;">
    <ins class="adsbygoogle"
         style="display:block; margin:0 auto;"
         data-ad-client="ca-pub-8754771874266985"
         data-ad-slot="7225743774"
         data-ad-format="auto"
         data-full-width-responsive="true"></ins>
    <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
  </div>
  <div style="margin:0 auto 12px; max-width:100%;">
    <ins class="adsbygoogle"
         style="display:block; margin:0 auto;"
         data-ad-client="ca-pub-8754771874266985"
         data-ad-slot="2910158078"
         data-ad-format="auto"
         data-full-width-responsive="true"></ins>
    <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
  </div>
</div>

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
  let userDismissed = false; // Track if user dismissed the prompt

  // Listen for the beforeinstallprompt event
  window.addEventListener('beforeinstallprompt', function(e) {
    // Don't show if user already dismissed
    if (userDismissed) {
      return;
    }

    // Prevent the mini-infobar from appearing on mobile
    e.preventDefault();
    deferredPrompt = e;

    // Show button after 10 seconds (only if not dismissed)
    if (!userDismissed) {
      showTimeout = setTimeout(function() {
        if (deferredPrompt && !isPWAInstalled() && !userDismissed) {
          installBtn.style.display = 'block';
        }
      }, 10000); // 10 seconds
    }
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
    installBtn.style.display = 'none';

    // If user dismissed, mark as dismissed so button won't show again
    if (choiceResult.outcome === 'dismissed') {
      userDismissed = true;
    }

    // Clear timeout
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

