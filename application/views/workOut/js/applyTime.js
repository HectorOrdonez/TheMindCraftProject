/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Description: ApplyTime JS Library
 * Date: 11/01/14 14:30
 */

function ApplyTime($element, callback) {
    // Variable that contains the Grid Object.
    var grid;

    // Step content
    var $workspace;

    // Initializing ApplyTime
    $workspace = $element;
    $workspace.empty();
    $workspace.html(builtStepContent());
    builtGrid(callback);

    /***********************************/
    /** Private functions             **/
    /***********************************/

    /**
     * Step Content for Selection section
     * @returns {string}
     */
    function builtStepContent() {
        return "<div id='gridWrapper'><table id='applyTime_grid'></table></div><div class='ftype_errorA' id='errorDisplayer'></div>";
    }

    /**
     * ApplyTime grid constructor
     * @param callback
     */
    function builtGrid(callback) {
        // JQuery variable that stores the grid.
        var $grid = jQuery('#applyTime_grid');

        // ApplyTime header construction
        var headerRow = new Row(
            {'cells': [
                {'html': 'id', 'classList': ['col_id']},
                {'html': 'title', 'classList': ['col_title', 'ftype_titleC']},
                {'html': '', 'classList': ['col_tododate', 'ftype_titleC']},
                {'html': '', 'classList': ['col_todotime', 'ftype_titleC']},
                {'html': '', 'classList': ['col_frequency', 'ftype_titleC']},
                {'html': '', 'classList': ['col_actions']},
                {'html': 'input date', 'classList': ['col_date_creation', 'ftype_titleC', 'centered']}
            ],
                'classList': ['header']
            });

        // ApplyTime Table construction
        var table = new Table('applyTime_grid', {
            colModel: [
                {dataIndex: 'id', classList: ['id']},
                {dataIndex: 'title', classList: ['title', 'ftype_contentA']},
                {dataIndex: 'date_todo', classList: ['ftype_contentA']},
                {dataIndex: 'time_todo', classList: ['ftype_contentA']},
                {dataIndex: 'frequency', classList: ['frequency']},
                {staticElement: function (rowId) {
                    var actionBox = '<div class="actionBox">';
                    var applyTimeAction = '<div class="action"><a class="applyTimeAction">' + rowId + '</a></div>';
                    actionBox = actionBox + applyTimeAction + '</div>';
                    return actionBox;
                },
                    classList: ['actions']
                },
                {dataIndex: 'date_creation', classList: ['ftype_contentA', 'centered']}
            ]});
        table.addHeaderElement(headerRow.getRow());

        // ApplyTime Grid parameters definition
        var gridParameters = {
            'url': root_url + 'mindFlow/getIdeas/ApplyTime',
            'eventDL': function () {
                callback();
            }
        };

        // ApplyTime Grid construction
        grid = new Grid(table, gridParameters);

        // Adding edit and delete triggers
        $grid.delegate('.applyTimeAction', 'click', function () {
            openApplyTimeDialog(jQuery(this));
        });
    }
    
    /**
     * Creates an element that represents the Apply Time Dialog and appends it to the content of the page.
     * Its css defines it as floating and with JavaScript the events controls the User interactions with it and when to
     * close it.
     * @param $clickedAction The clicked thing.
     */
    function openApplyTimeDialog($clickedAction) {
        var $ideaRow = $clickedAction.parent().parent().parent().parent();
        var ideaId= $clickedAction.html();
        var dateTodoCell = $ideaRow.children().eq(2).html();
        var timeTodoCell = $ideaRow.children().eq(3).html();
        var frequencyCell = $ideaRow.children().eq(4).html();

        uniqueUserRequest(function (allowNewUniqueRequests) {
            // Building dialog
            var applyTimeDialog = document.createElement('DIV');
            applyTimeDialog.setAttribute('id', 'applyTimeDialog');
            applyTimeDialog.innerHTML = '' +
                '<div class="applyTimeInnerDialog">' +
                '   <form id="formApplyTime" action="' + root_url + 'mindFlow/applyTimeToIdea">' +
                '   <input type="hidden" class="inputApplyTimeIdeaId" name="id" value="' + ideaId + '" />' +
                '       <div class="title">Apply Time</div>' +
                '       <input id="datePicker" name="date" placeholder="Set the day" value="' + dateTodoCell +'" />' +
                '       <div class="timeSelection">' +
                '           <label>At a certain time?</label>' +
                '           <div><input id="timePicker" name="time" placeholder="Set the time" value="' + timeTodoCell +'"/></div>' +
                '       </div>' +
                '       <div id="moreOften">' +
                '           <label class="clicker">More often?</label>' +
                '           <div id="moreOftenPanel">' +
                '               <p><label>Monday</label><input type="checkbox" class="dayBox" name="howOften[]" value="monday"></p>' +
                '               <p><label>Tuesday</label><input type="checkbox" class="dayBox" name="howOften[]" value="tuesday"></p>' +
                '               <p><label>Wednesday</label><input type="checkbox" class="dayBox" name="howOften[]" value="wednesday"></p>' +
                '               <p><label>Thursday</label><input type="checkbox" class="dayBox" name="howOften[]" value="thursday"></p>' +
                '               <p><label>Friday</label><input type="checkbox" class="dayBox" name="howOften[]" value="friday"></p>' +
                '               <p><label>Saturday</label><input type="checkbox" class="dayBox" name="howOften[]" value="saturday"></p>' +
                '               <p><label>Sunday</label><input type="checkbox" class="dayBox" name="howOften[]" value="sunday"></p>' +
                '           </div>' +
                '       </div>' +
                '       <div class="" id="submitAppliedTime">Apply time</div>' +
                '   </form>' +
                '   <div class="ftype_errorA" id="applyTimeInfoDisplayer"></div>' +
                '</div>';
            jQuery('.bodyContent').append(applyTimeDialog);
            var $applyTimeDialog = jQuery('#applyTimeDialog');

            // Initializing features
            jQuery('#datePicker').datepicker({dateFormat: "dd/mm/yy"});
            jQuery('#timePicker').timePicker();
            setHowOftenBoxes(frequencyCell);

            // Initializing events
            jQuery('#moreOften').click(function () {
                jQuery('#moreOftenPanel').slideDown();
            });
            jQuery('#submitAppliedTime').click(function () {
                submitApplyTime($ideaRow, closeApplyTimeDialog);
            });
            jQuery('.applyTimeInnerDialog').click(function (event) {
                event.stopPropagation();
            });
            $applyTimeDialog.click(function () {
                closeApplyTimeDialog()
            });
            jQuery('body').bind('keyup.applytime', function (event) {
                if (event.which == 27) {
                    closeApplyTimeDialog();
                }
            });

            $applyTimeDialog.fadeIn(function(){
                allowNewUniqueRequests();
            });
        });
    }

    /**
     * Sets the checkboxes of the Apply Time dialog checked or not depending on the passed parameter.
     * @param frequencyString
     */
    function setHowOftenBoxes(frequencyString)
    {
        // Get Checkboxes
        var checkBoxes = jQuery('.dayBox');
        var frqArray = frequencyString.split('');
        jQuery.each(checkBoxes, function(i, element){
            jQuery(element).prop('checked', (frqArray[i] == 1));
        });
    }

    /**
     * Gets the check of the Apply Time dialog and builds with it a string that the Server can use to define the frequency
     * of the idea.
     * @returns {string}
     */
    function getHowOftenString()
    {
        // Get Checkboxes
        var checkBoxes = jQuery('.dayBox');
        var frqArray = [];
        jQuery.each(checkBoxes, function(i, element){
            frqArray[i] = (jQuery(element).prop('checked')) ? 1 : 0;
        });

        return frqArray.join('');
    }

    /**
     * Closes the Apply Time dialog.
     */
    function closeApplyTimeDialog() {
        uniqueUserRequest(function (allowNewUniqueRequests) {
            // Defining dialog to close
            var applyTimeDialog = document.getElementById('applyTimeDialog');
            var $applyTimeDialog = jQuery('#applyTimeDialog');

            // Removing Time picker
            jQuery('.time-picker').remove();

            // Unbinding Escape event
            jQuery('body').unbind('keyup.applytime');

            // Fading out dialog
            $applyTimeDialog.fadeOut(function () {
                // When dialog is faded, remove the dialog completely
                document.getElementById('loggedContent').removeChild(applyTimeDialog);
                allowNewUniqueRequests();
            });
        });
    }

    /**
     * Asynchronous request to the server to apply time to an idea.
     */
    function submitApplyTime($ideaRow, callback) {
        var $form = jQuery('#formApplyTime');
        var $infoDisplayer = jQuery('#applyTimeInfoDisplayer');
        var url = $form.attr('action');
        var data = $form.serialize();
        var dataArray = $form.serializeArray();

        jQuery.ajax({
            type: 'post',
            url: url,
            data: data
        }).done(function () {
                $ideaRow.children().eq(2).html(dataArray[1]['value']);
                $ideaRow.children().eq(3).html(dataArray[2]['value']);
                $ideaRow.children().eq(4).html(getHowOftenString());
                setInfoMessage($infoDisplayer, 'success', 'Applied time.', 2000);
                setTimeout(function(){
                    callback();
                }, 1000);
            }
        ).fail(function (data) {
                setInfoMessage($infoDisplayer, 'error', data.statusText, 2000);
            }
        );
    }
}