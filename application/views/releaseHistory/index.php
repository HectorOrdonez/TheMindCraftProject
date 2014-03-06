<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * View chunk for the Release History content.
 * Date: 23/07/13 12:00
 *
 * @var \application\engine\View $this
 */
?>
<?php $this->printChunk('header'); ?>

    <div class='ftype_contentA' id='learnMore'>
        Learn More will be where you can learn to use this website. Cool, huh?<br />

        <div class='verticalSpace15'></div>
        
        Sadly though, right now it is not developed - it is not even developed what the website will do!<br />

        <div class='verticalSpace15'></div>

        So a bit of patience here! In the meantime you can see all the updates: time ain't wasted for nothing here!<br />
        
        <div class='verticalSpace30'></div>
        
        <a id='showMe'><span class='ftype_titleA1'>Alright, </span><span class='ftype_titleA2'>show me </span><span class='ftype_titleA3'>what you are doing!</span></a>
    </div>

    <div id='websiteHistory'>
        <h1>
            Version <?=$this->getParameter('developmentVersion')['version']; ?> released
            on <?=$this->getParameter('developmentVersion')['date']; ?>.
        </h1>

        <ul>
<?php   foreach ($this->getParameter('developmentVersion')['changes'] as $change) : ?>
            <li><?=$change; ?></li>
<?php   endforeach; ?>
        </ul>
        <hr/>

        <h1>
            Historical Log
        </h1>

<?php   foreach ($this->getParameter('historicalLog') as $log) : ?>
        <h2>Version <?php echo $log['version']; ?>, released <?php echo $log['date']; ?></h2>
        <ul>
<?php       foreach ($log['changes'] as $change) : ?>
            <li><?=$change; ?></li>
<?php       endforeach; ?>
        </ul>

<?php   endforeach; ?>
    </div>
    <div id='test'>
    </div>

<?php $this->printChunk('footer'); ?>