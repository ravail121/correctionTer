<!-- PWA Install Floating Button (Chrome users only) -->
<div id="pwaInstallButton" class="pwa-install-btn" style="display:none;">
    <i class="fa fa-download pwa-install-icon" aria-hidden="true"></i>
    <span class="pwa-install-label">Install App</span>
</div>

<script>
(function() {
    // Basic Chrome detection (exclude Edge/Opera and similar Chromium browsers)
    var ua = navigator.userAgent || '';
    var isChrome = ua.indexOf('Chrome') > -1 && ua.indexOf('Edg') === -1 && ua.indexOf('OPR') === -1;

    // Don't show if already installed / running as app
    var isStandalone = window.matchMedia && window.matchMedia('(display-mode: standalone)').matches;

    if (!isChrome || isStandalone) {
        return;
    }

    var deferredPrompt = null;
    var button = document.getElementById('pwaInstallButton');
    if (!button) return;

    function showButton() {
        button.style.display = 'flex';
    }

    // Listen for the beforeinstallprompt event
    window.addEventListener('beforeinstallprompt', function(e) {
        // Prevent the default mini-infobar
        e.preventDefault();
        deferredPrompt = e;
        setTimeout(showButton, 10000);
    });

    // Show button after 12s even if beforeinstallprompt never fired (e.g. no service worker yet)
    setTimeout(function() {
        if (!deferredPrompt && button.style.display !== 'flex') {
            showButton();
        }
    }, 12000);

    // When user clicks the button, show the install prompt
    button.addEventListener('click', function() {
        if (!deferredPrompt) return;

        button.style.display = 'none';
        deferredPrompt.prompt();

        deferredPrompt.userChoice.then(function(choiceResult) {
            // If user dismissed, we could re-show later; if accepted, keep it hidden
            deferredPrompt = null;
        });
    });

    // If the app gets installed, hide the button
    window.addEventListener('appinstalled', function() {
        button.style.display = 'none';
        deferredPrompt = null;
    });
})();
</script>

</body>
</html>


