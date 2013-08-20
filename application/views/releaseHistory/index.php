<?php
/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description:
 * View chunk for the Release History content.
 * Date: 23/07/13 12:00
 */
?>

                <div>
                    <h1>
                        Version <?php echo $this->developmentVersion['version']; ?> released
                        on <?php echo $this->developmentVersion['date']; ?>.
                    </h1>

                    <ul>
                        <?php foreach ($this->developmentVersion['changes'] as $change) : ?>
                            <li><?php echo $change; ?></li>
                        <?php endforeach; ?>
                    </ul>

                    <hr/>

                    <h1>
                        Historical Log
                    </h1>
                    <?php foreach ($this->historicalLog as $log) : ?>
                        <h2>Version <?php echo $log['version']; ?>, released <?php echo $log['date']; ?></h2>
                        <ul>
                            <?php foreach ($log['changes'] as $change) : ?>
                                <li><?php echo $change; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endforeach; ?>
                </div>
                <div id='test'>
                </div>
