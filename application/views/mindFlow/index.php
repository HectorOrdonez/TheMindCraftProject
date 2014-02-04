<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * View chunk for the Index content.
 * @date 23/07/13 13:30
 *
 * @var \application\engine\View $this
 */
?>
<?php $this->printChunk('header'); ?>

    <div id='initStep'><?php echo $this->getParameter('initStep'); ?></div>

    <div id='flowMenu'>
        <div id='flowLine'>
            <div class='section' id='pastFlow'></div>
            <div class='section' id='futureFlow'></div>
        </div>

        <ul>
            <li class='flowOption' id='step1'>
                <a>
                    <img src='<?php echo _SYSTEM_BASE_URL . 'public/images/'; ?>BrainStorm.png' alt='BrainStorm'/>
                </a>

                <div class='text'>
                    <span class='ftype_titleB1'>Brain</span><span class='ftype_titleB2'>Storm</span><span
                        class='ftype_titleB3'>!</span>
                </div>
            </li>
            <li class='flowOption' id='step2'>
                <a>
                    <img src='<?php echo _SYSTEM_BASE_URL . 'public/images/'; ?>WorkOut.png' alt='WorkOut'/>
                </a>

                <div class='text'>
                    <span class='ftype_titleB1'>Work</span><span class='ftype_titleB2'>Out</span><span
                        class='ftype_titleB3'>!</span>
                </div>
            </li>
            <li class='miniFlowOption' id='step21'>
                <a class='imgBox'>
                    <img src='<?php echo _SYSTEM_BASE_URL . 'public/images/'; ?>Select.png' alt='Select'/>
                </a>

                <div class='text'>
                    <span class='ftype_contentA'>Select!</span>
                </div>
            </li>
            <li class='miniFlowOption' id='step22'>
                <a class='imgBox'>
                    <img src='<?php echo _SYSTEM_BASE_URL . 'public/images/'; ?>Prioritize.png' alt='Prioritize'/>
                </a>

                <div class='text'>
                    <span class='ftype_contentA'>Prioritize!</span>
                </div>
            </li>
            <li class='miniFlowOption' id='step23'>
                <a class='imgBox'>
                    <img src='<?php echo _SYSTEM_BASE_URL . 'public/images/'; ?>ApplyTime.png' alt='ApplyTime'/>
                </a>

                <div class='text'>
                    <span class='ftype_contentA'>ApplyTime!</span>
                </div>
            </li>
            <li class='flowOption' id='step3'>
                <a>
                    <img src='<?php echo _SYSTEM_BASE_URL . 'public/images/'; ?>PerForm.png' alt='PerForm'/>
                </a>

                <div class='text'>
                    <span class='ftype_titleB1'>Per</span><span class='ftype_titleB2'>Form</span><span
                        class='ftype_titleB3'>!</span>
                </div>
            </li>
        </ul>
    </div>

    <div id='stepContent'>
    </div>

    <div id='infoDisplayer'></div>

    <!-- Helpers -->
    <div id='applyTimeDialog'>
        <div class='fullOverlay' id='applyTimeOverlay'></div>

        <div id='setTodoDialogWrapper'>
            <div id='datePickerWrapper'>
                <div id='datePicker'></div>
            </div>
            <div class='verticalSpace30'></div>
            <div id='timeRangeWrapper'>
                <div id='fromSelectionWrapper'>
                    <div class='text ftype_contentB'>
                        from:
                    </div>
                    <div class='inputs'>
                        <div class='hours'></div>
                        <div class='minutes'></div>
                    </div>
                </div>
                <div id='tillSelectionWrapper'>
                    <div class='text ftype_contentB'>
                        till:
                    </div>
                    <div class='inputs'>
                        <div class='hours'></div>
                        <div class='minutes'></div>
                    </div>
                </div>
            </div>
            <div class='verticalSpace30'></div>
            <div id='moreOftenWrapper'>
                <div class='text ftype_contentC'>
                    More often?
                </div>
                <div class='action'>
                    <a class='setRoutineAction'></a>
                </div>
            </div>
            <div class='verticalSpace30'></div>
            <div id='submitTodo'>
                <a class='ftype_titleC'>Apply Time!</div>
        </div>
    </div>

<?php $this->printChunk('footer'); ?>