<!doctype html>
<html>
<head>
    <!-- Title -->
    <title>
        <?php echo $this->_getTitle(); ?>

    </title>

    <!-- Meta Tags -->
    <?php foreach ($this->_getMeta() as $meta) : ?><meta <?php foreach ($meta as $tagName=>$tagValue) : echo "{$tagName}='{$tagValue}' "; endforeach; ?>/>
    <?php endforeach; ?>

    <!-- CSS Stylesheets -->
    <?php foreach ($this->_getCss() as $css) : ?><link rel='stylesheet' href='<?php echo $css; ?>' />
    <?php endforeach; ?>

    <!-- JS Libraries -->
    <?php foreach ($this->_getJs() as $js) : ?><script src='<?php echo $js; ?>'></script>
    <?php endforeach; ?>

</head>

<div id='header'>
    <div id='headerOptions'>
        <?php if ($this->userLogin == TRUE): ?>
            Here, some options for you.
        <?php else: ?>
            No options for not logged-in users.
        <?php endif; ?>
    </div>

    <div id='headerAccess'>
<?php if ($this->userLogin == TRUE): ?>
        <a id='loggedIn' href="<?php echo _SYSTEM_BASE_URL; ?>index/logout"></a>
<?php else: ?>
        <a id='loggedOut' href="<?php echo _SYSTEM_BASE_URL; ?>index"></a>
<?php endif; ?>
    </div>
</div>
<body>
<!-- Opening Content -->
<div id='content'>
