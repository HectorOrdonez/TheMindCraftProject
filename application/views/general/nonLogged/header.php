<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * View chunk for the non-logged users header.
 * Date: 27/07/13 19:15
 *
 * @var \application\engine\View $this
 */
?>

<!doctype html>
<html>
<head>
    <!-- Title -->
    <title>
        <?php echo $this->getTitle(); ?> 
    </title>

    <!-- Meta Tags -->
    <?php foreach ($this->getMeta() as $meta) : ?><meta <?php foreach ($meta as $tagName => $tagValue) : echo "{$tagName}='{$tagValue}' "; endforeach; ?>/>
    <?php endforeach; ?>

    <!-- CSS Stylesheets -->
    <?php foreach ($this->getCss() as $css) : ?><link rel='stylesheet' href='<?php echo $css; ?>'/>
    <?php endforeach; ?>

    <!-- JS Libraries -->
    <?php foreach ($this->getJs() as $js) : ?><script src='<?php echo $js; ?>'></script>
    <?php endforeach; ?>

</head>

<body>

    <div id='header'>
        <div id='logo'></div>
        <div id='headerPanel'>
            <a class='option learnMore' href="<?php echo _SYSTEM_BASE_URL; ?>releaseHistory"></a>
            <a class='option signUp' href="<?php echo _SYSTEM_BASE_URL; ?>signUp"></a>
        </div>
    </div>

    <!-- Opening non-logged Content -->
    <div class='bodyContent' id='nonLoggedContent'>