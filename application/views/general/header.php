<?php
/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description:
 * View chunk for the footer.
 * Date: 23/07/13 13:00
 */
?>
<!doctype html>
<html>
<head>
    <!-- Title -->
    <title>
        <?php echo $this->_getTitle(); ?>

    </title>

    <!-- Meta Tags -->
    <?php foreach ($this->_getMeta() as $meta) : ?>
        <meta <?php foreach ($meta as $tagName => $tagValue) : echo "{$tagName}='{$tagValue}' "; endforeach; ?>/>
    <?php endforeach; ?>

    <!-- CSS Stylesheets -->
    <?php foreach ($this->_getCss() as $css) : ?>
        <link rel='stylesheet' href='<?php echo $css; ?>' />
    <?php endforeach; ?>

    <!-- JS Libraries -->
    <?php foreach ($this->_getJs() as $js) : ?>
        <script src='<?php echo $js; ?>'></script>
    <?php endforeach; ?>

</head>

<div id='header'>
    <div id='logo'></div>

    <div id='leftPanel'>
        <?php if ($this->userLogin == TRUE): ?>
            <a class='option brainstorm' href="<?php echo _SYSTEM_BASE_URL; ?>brainstorm"></a>
            <a class='option ideaToAction' href="<?php echo _SYSTEM_BASE_URL; ?>ideaToAction"></a>
        <?php endif; ?>
    </div>

    <div id='rightPanel'>
        <?php if ($this->userLogin == TRUE): ?>
            <a class='option loggedIn' href="<?php echo _SYSTEM_BASE_URL; ?>index/logout"></a>
        <?php else: ?>
            <a class='option loggedOut' href="<?php echo _SYSTEM_BASE_URL; ?>index"></a>
        <?php endif; ?>
        <a class='option releaseHistory' href="<?php echo _SYSTEM_BASE_URL; ?>releaseHistory"></a>
    </div>
</div>
<body>
<!-- Opening Content -->
<div id='content'>
