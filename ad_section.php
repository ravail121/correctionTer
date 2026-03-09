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

<div class="ad-row" style="display:flex; flex-wrap:wrap; gap:12px; margin:12px 0;">
  <div style="flex:1; min-width:280px;">
    <ins class="adsbygoogle"
         style="display:block"
         data-ad-client="ca-pub-8754771874266985"
         data-ad-slot="7225743774"
         data-ad-format="auto"
         data-full-width-responsive="true"></ins>
    <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
  </div>
  <div style="flex:1; min-width:280px;">
    <ins class="adsbygoogle"
         style="display:block"
         data-ad-client="ca-pub-8754771874266985"
         data-ad-slot="7225743774"
         data-ad-format="auto"
         data-full-width-responsive="true"></ins>
    <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
  </div>
</div>


