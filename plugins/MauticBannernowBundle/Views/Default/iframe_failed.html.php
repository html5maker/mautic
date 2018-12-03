<?php

// Extend the base content
$view->extend('MauticCoreBundle:Default:content.html.php');

// Set the page and header title
$view['slots']->set('headerTitle', 'Bannernow');

?>
<div>
    <pre><?php echo htmlspecialchars($exception->getMessage()) ?></pre>
    <p><a href="../plugins">Try to Reauthorize app</a></p>
</div>
