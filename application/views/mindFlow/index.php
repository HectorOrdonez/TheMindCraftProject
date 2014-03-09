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
                <a>
                    <img src='<?php echo _SYSTEM_BASE_URL . 'public/images/'; ?>Select.png' alt='Select'/>
                </a>

                <div class='text'>
                    <span class='ftype_contentB'>Select!</span>
                </div>
            </li>
            <li class='miniFlowOption' id='step22'>
                <a>
                    <img src='<?php echo _SYSTEM_BASE_URL . 'public/images/'; ?>Prioritize.png' alt='Prioritize'/>
                </a>

                <div class='text'>
                    <span class='ftype_contentB'>Prioritize!</span>
                </div>
            </li>
            <li class='miniFlowOption' id='step23'>
                <a>
                    <img src='<?php echo _SYSTEM_BASE_URL . 'public/images/'; ?>ApplyTime.png' alt='ApplyTime'/>
                </a>

                <div class='text'>
                    <span class='ftype_contentB'>ApplyTime!</span>
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
    <div id='showRoutinesWrapper'>
        <span class='mindCraft-ui-button mindCraft-ui-button-checkbox'></span>
        <span class='ftype_contentC'>Show all routines</span>
    </div>

    <div id='applyTimeDialog'>
        <div class='fullOverlay' id='applyTimeOverlay'></div>
        <input type='hidden' id='ideaId'/>
        <input type='hidden' id='ideaType'/>

        <!-- To do sub-dialog -->
        <div id='setTodoDialogWrapper'>

            <div id='datePickerWrapper'>
                <div id='datePicker'></div>
            </div>

            <div class='verticalSpace30'></div>

            <div id='timeRangeWrapper'>
                <div id='fromSelectionWrapper'>
                    <div class='text ftype_contentA'>
                        from:
                    </div>
                    <div class='inputs'>
                        <div class='hours ftype_contentB' id='todoFromHoursSelector'></div>
                        <div class='minutes ftype_contentB' id='todoFromMinutesSelector'></div>
                    </div>
                </div>
                <div id='tillSelectionWrapper'>
                    <div class='text ftype_contentA'>
                        till:
                    </div>
                    <div class='inputs'>
                        <div class='hours ftype_contentB' id='todoTillHoursSelector'></div>
                        <div class='minutes ftype_contentB' id='todoTillMinutesSelector'></div>
                    </div>
                </div>
            </div>

            <div class='verticalSpace30'></div>

            <div class='submitApplyTime' id='submitTodo'>
                <a class='ftype_contentA'>Apply Time!</a>
            </div>

            <div id='setTodoInfo'></div>
        </div>
        <!-- End to do sub-dialog -->

        <!-- Routine sub-dialog -->
        <div id='setRoutineDialogWrapper'>

            <div id='weeklyRepetitionWrapper'>
                <span class='left ftype_contentA'>
                    Repeat every
                </span>
                <span class='right ftype_contentA'>
                    week
                </span>

                <div class='ftype_contentB' id='weeklyRepetitionSelector'></div>
            </div>

            <div class='verticalSpace30'></div>

            <div id='weekdaysSelectionWrapper'>
                <p class='ftype_contentA'>
                    Repeat on:
                </p>
                <ul class='weekdays ftype_contentB'>
                    <li>M</li>
                    <li>T</li>
                    <li>W</li>
                    <li>T</li>
                    <li>F</li>
                    <li>S</li>
                    <li>S</li>
                </ul>
            </div>

            <div class='verticalSpace30'></div>

            <div id='dateRangeWrapper'>
                <div id='startsSelectionWrapper'>
                    <label for='startDate' class='text ftype_contentA'>
                        starts:
                    </label>

                    <div class='datePickerWrapper'>
                        <input class='ftype_contentB' type='text' id='startDate'/>
                    </div>
                </div>
                <div id='finishesSelectionWrapper'>
                    <label for='finishDate' class='text ftype_contentA'>
                        finishes:
                    </label>

                    <div class='datePickerWrapper'>
                        <input class='ftype_contentB' type='text' id='finishDate'/>
                    </div>
                </div>
            </div>

            <div class='verticalSpace30'></div>

            <div id='timeRangeWrapper'>
                <div id='fromSelectionWrapper'>
                    <div class='text ftype_contentA'>
                        from:
                    </div>
                    <div class='inputs'>
                        <div class='hours ftype_contentB' id='routineFromHoursSelector'></div>
                        <div class='minutes ftype_contentB' id='routineFromMinutesSelector'></div>
                    </div>
                </div>
                <div id='tillSelectionWrapper'>
                    <div class='text ftype_contentA'>
                        till:
                    </div>
                    <div class='inputs'>
                        <div class='hours ftype_contentB' id='routineTillHoursSelector'></div>
                        <div class='minutes ftype_contentB' id='routineTillMinutesSelector'></div>
                    </div>
                </div>
            </div>

            <div class='verticalSpace30'></div>

            <div class='submitApplyTime' id='submitRoutine'>
                <a class='ftype_contentA'>Apply Time !</a>
            </div>

            <div id='setRoutineInfo'></div>
        </div>
        <!-- End routine sub-dialog -->
    </div>

    <div id='perFormLayout'>
        <div class='perFormColumn dayBlock' id='perFormYesterday'>
            <div class='blockHeader ftype_contentA'>yesterday</div>
            <ul class='hourList'>
                <!-- 06:00 -->
                <li></li>
                <!-- 07:00 -->
                <li></li>
                <!-- 08:00 -->
                <li></li>
                <!-- 09:00 -->
                <li></li>
                <!-- 10:00 -->
                <li></li>
                <!-- 11:00 -->
                <li></li>
                <!-- 12:00 -->
                <li></li>
                <!-- 13:00 -->
                <li></li>
                <!-- 14:00 -->
                <li></li>
                <!-- 15:00 -->
                <li></li>
                <!-- 16:00 -->
                <li></li>
                <!-- 17:00 -->
                <li></li>
                <!-- 18:00 -->
                <li></li>
                <!-- 19:00 -->
                <li></li>
                <!-- 20:00 -->
                <li></li>
                <!-- 21:00 -->
                <li></li>
                <!-- 22:00 -->
                <li></li>
                <!-- 23:00 -->
                <li></li>
                <!-- 00:00 -->
                <li></li>
            </ul>
        </div>

        <div class='perFormColumn timeMarkups'>
            <ul class='ftype_contentB'>
                <li>6</li>
                <li>7</li>
                <li>8</li>
                <li>9</li>
                <li>10</li>
                <li>11</li>
                <li>12</li>
                <li>13</li>
                <li>14</li>
                <li>15</li>
                <li>16</li>
                <li>17</li>
                <li>18</li>
                <li>19</li>
                <li>20</li>
                <li>21</li>
                <li>22</li>
                <li>23</li>
                <li>00</li>
            </ul>
        </div>

        <div class='perFormColumn dayBlock' id='perFormToday'>
            <div class='blockHeader ftype_contentA'>today</div>
            <ul class='hourList'>
                <!-- 06:00 -->
                <li></li>
                <!-- 07:00 -->
                <li></li>
                <!-- 08:00 -->
                <li></li>
                <!-- 09:00 -->
                <li></li>
                <!-- 10:00 -->
                <li></li>
                <!-- 11:00 -->
                <li></li>
                <!-- 12:00 -->
                <li></li>
                <!-- 13:00 -->
                <li></li>
                <!-- 14:00 -->
                <li></li>
                <!-- 15:00 -->
                <li></li>
                <!-- 16:00 -->
                <li></li>
                <!-- 17:00 -->
                <li></li>
                <!-- 18:00 -->
                <li></li>
                <!-- 19:00 -->
                <li></li>
                <!-- 20:00 -->
                <li></li>
                <!-- 21:00 -->
                <li></li>
                <!-- 22:00 -->
                <li></li>
                <!-- 23:00 -->
                <li></li>
                <!-- 00:00 -->
                <li></li>
            </ul>
        </div>
        <div class='perFormColumn timeMarkups'>
            <ul class='ftype_contentB'>
                <!-- 06:00 -->
                <li></li>
                <!-- 07:00 -->
                <li></li>
                <!-- 08:00 -->
                <li></li>
                <!-- 09:00 -->
                <li></li>
                <!-- 10:00 -->
                <li></li>
                <!-- 11:00 -->
                <li></li>
                <!-- 12:00 -->
                <li></li>
                <!-- 13:00 -->
                <li></li>
                <!-- 14:00 -->
                <li></li>
                <!-- 15:00 -->
                <li></li>
                <!-- 16:00 -->
                <li></li>
                <!-- 17:00 -->
                <li></li>
                <!-- 18:00 -->
                <li></li>
                <!-- 19:00 -->
                <li></li>
                <!-- 20:00 -->
                <li></li>
                <!-- 21:00 -->
                <li></li>
                <!-- 22:00 -->
                <li></li>
                <!-- 23:00 -->
                <li></li>
                <!-- 00:00 -->
                <li></li>
            </ul>
        </div>

        <div class='perFormColumn dayBlock' id='perFormTomorrow'>
            <div class='blockHeader ftype_contentA'>tomorrow</div>
            <ul class='hourList'>
                <!-- 06:00 -->
                <li></li>
                <!-- 07:00 -->
                <li></li>
                <!-- 08:00 -->
                <li></li>
                <!-- 09:00 -->
                <li></li>
                <!-- 10:00 -->
                <li></li>
                <!-- 11:00 -->
                <li></li>
                <!-- 12:00 -->
                <li></li>
                <!-- 13:00 -->
                <li></li>
                <!-- 14:00 -->
                <li></li>
                <!-- 15:00 -->
                <li></li>
                <!-- 16:00 -->
                <li></li>
                <!-- 17:00 -->
                <li></li>
                <!-- 18:00 -->
                <li></li>
                <!-- 19:00 -->
                <li></li>
                <!-- 20:00 -->
                <li></li>
                <!-- 21:00 -->
                <li></li>
                <!-- 22:00 -->
                <li></li>
                <!-- 23:00 -->
                <li></li>
                <!-- 00:00 -->
                <li></li>
            </ul>
        </div>

        <div class='perFormColumn' id='perFormPool'>
            <div class='blockHeader'></div>
        </div>
    </div>
<?php $this->printChunk('footer'); ?>