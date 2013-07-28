<?php
/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description:
 * View chunk for the logged users header.
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
        <link rel='stylesheet' href='<?php echo $css; ?>'/>
    <?php endforeach; ?>

    <!-- JS Libraries -->
    <?php foreach ($this->_getJs() as $js) : ?>
        <script src='<?php echo $js; ?>'></script>
    <?php endforeach; ?>

</head>

<body>

<div id='header'>
    <div id='logo'></div>
    <div id='headerPanel'>
        <div class="stayFocused"></div>
        <a class='option settings' href="<?php echo _SYSTEM_BASE_URL; ?>settings"></a>
        <a class='option logOut' href="<?php echo _SYSTEM_BASE_URL; ?>index"></a>
    </div>
</div>

<div id='contentWrapper'>
    <div id='wrappedContentLeft'>
        <div id='leftPanel'>
            <a class='option profile' href="<?php echo _SYSTEM_BASE_URL; ?>profile"></a>
            <a class='option brainstorm' href="<?php echo _SYSTEM_BASE_URL; ?>brainstorm"></a>
            <a class='option workOut' href="<?php echo _SYSTEM_BASE_URL; ?>workOut"></a>

            <div class='workOut_subOptions'>
                <a class='subOption stepSelection'
                   href="<?php echo _SYSTEM_BASE_URL; ?>workOut/index/stepSelection"></a>
                <a class='subOption stepTiming' href="<?php echo _SYSTEM_BASE_URL; ?>workOut/index/stepTiming"></a>
                <a class='subOption stepPrioritizing'
                   href="<?php echo _SYSTEM_BASE_URL; ?>workOut/index/stepPrioritizing"></a>
            </div>
            <a class='option action' href="<?php echo _SYSTEM_BASE_URL; ?>action"></a>
        </div>
    </div>

    <div id='wrappedContentMain'>
        <!-- Opening Content -->
        <div id='content'>