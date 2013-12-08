<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * View chunk for the Work Out content.
 * Date: 25/07/13 13:30
 */
?>

                <div id='workOutMenu'>
                    <a href='#' class='stepSelector' id='stepSelection'>Selection</a>
                    <a href='#' class='stepSelector' id='stepTiming'>Timing</a>
                    <a href='#' class='stepSelector' id='stepPrioritizing'>Prioritizing</a>

                    <div id='stepPointer'><?php echo $this->startingStep; ?></div>
                </div>

                <div id='stepContent'>

                </div>

                <div id='infoDisplayer'>
                </div>

                <a href='#' class='' id='nextStep'></a>