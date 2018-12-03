<?php

// Extend the base content
$view->extend('MauticCoreBundle:Default:content.html.php');

// Set the page and header title
$view['slots']->set('headerTitle', 'Bannernow');

?>
<style>
    .page-header { display: none; }
</style>
<div>
    <iframe id="bannernow-iframe" src="<?php echo htmlspecialchars($url) ?>" style="display: block; width: 100%; border: none;"></iframe>
    <?php $view['slots']->output('_content'); ?>
</div>
<script>
(function () {
    function resize() {
        var height = jQuery(window).height() - 107;
        jQuery('#bannernow-iframe').height(height);
    }
    jQuery(window).on('resize', resize);
    jQuery(resize);
})();
</script>
