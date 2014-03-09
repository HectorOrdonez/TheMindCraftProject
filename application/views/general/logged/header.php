<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * View chunk for the logged users header.
 * Date: 23/07/13 13:00
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
    <a class='logo' href="<?php echo _SYSTEM_BASE_URL; ?>">
        <div class='image'>
            <img src='<?php echo _SYSTEM_BASE_URL . 'public/images/'; ?>Logo.png' alt='The Mindcraft Project'/>
        </div>

        <div class='text'>
            <span class='ftype_logo1'>Mind</span>
            <span class='ftype_logo2'>Craft</span>
            <span class='ftype_logo3'>&nbsp;.</span>
        </div>
    </a>
    <div id='headerPanel'>

        <a class='panelAction' href="<?php echo _SYSTEM_BASE_URL; ?>releaseHistory" id='actionLearnMore'>
            <div class='image'>
                <img src='<?php echo _SYSTEM_BASE_URL . 'public/images/'; ?>LearnMore.png' alt='Learn More'/>
            </div>
            
            <div class='text'>
                <span class='ftype_titleB1'>Learn</span>
                <span class='ftype_titleB2'>More</span>
            </div>
        </a>

        <a class='panelAction' href="<?php echo _SYSTEM_BASE_URL; ?>settings" id='actionProFile'>
            <div class='image'>
                <img src='<?php echo _SYSTEM_BASE_URL . 'public/images/'; ?>ProFile.png' alt='ProFile'/>
            </div>

            <div class='text'>
                <span class='ftype_titleB1'>Pro</span>
                <span class='ftype_titleB2'>File</span>
            </div>
        </a>

        <a class='panelAction' href="<?php echo _SYSTEM_BASE_URL; ?>index/logout" id='actionLogOut'>
            <div class='image'>
                <img src='<?php echo _SYSTEM_BASE_URL . 'public/images/'; ?>LogOut.png' alt='LogOut'/>
            </div>

            <div class='text'>
                <span class='ftype_titleB1'>Log</span>
                <span class='ftype_titleB2'>Out</span>
            </div>
        </a>
    </div>
</div>

<!-- Opening logged-in Content -->
<div class='bodyContent' id='loggedContent'>
