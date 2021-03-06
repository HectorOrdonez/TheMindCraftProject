<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * View chunk for the Error exception display.
 * Date: 23/07/13 13:30
 *
 * @var \application\engine\View $this
 */
?>
<?php $this->printChunk('header'); ?>

                <div class='exception'>
                    <table class="exceptionTable">
                        <tbody>
                        <tr class="exceptionRow">
                            <td class="exceptionLabel">
                                Exception
                            </td>
                            <td class="exceptionValue"><?php echo $this->getParameter('exception'); ?></td>
                        </tr>
                        <tr>
                            <td class="exceptionLabel">
                                File
                            </td>
                            <td class="exceptionValue"><?php echo $this->getParameter('file'); ?></td>
                        </tr>
                        <tr>
                            <td class="exceptionLabel">
                                Line
                            </td>
                            <td class="exceptionValue"><?php echo $this->getParameter('line'); ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class='exceptionTrack'>
                    <?php foreach ($this->getParameter('backtrace') as $traces): ?>
                        <table class="backtraceTable">
                            <?php foreach ($traces as $tracedField => $tracedValue): ?>
                                <tr class="backtraceRow">
                                    <td class="backtraceLabel"><?php echo $tracedField; ?></td>
                                    <td class="backtraceValue"><?php echo $tracedValue; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php endforeach; ?>
                </div>

<?php $this->printChunk('footer'); ?>