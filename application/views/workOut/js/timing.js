/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description: Timing JS Library
 * Date: 25/07/13 21:00
 */

function createTimingGrid() {
    // JQuery variable that stores the grid.
    var $grid = jQuery('#timing_grid');

    // Timing header construction
    var headerRow = new Row(
        {'cells': [
            {'html': 'Id', 'classList': ['col_id']},
            {'html': 'Title', 'classList': ['col_title']},
            {'html': 'Date To do', 'classList': ['col_tododate']},
            {'html': 'Time To do', 'classList': ['col_todotime']},
            {'html': 'Frequency', 'classList': ['col_frequency']},
            {'html': 'Actions', 'classList': ['col_actions']},
            {'html': 'Date Creation', 'classList': ['col_date_creation']}
        ],
            'classList': ['header']
        });

    // Selection table construction
    var table = new Table('timing_grid', {
        colModel: [
            {dataIndex: 'id', classList: ['id']},
            {dataIndex: 'title', classList: ['title']},
            {dataIndex: 'date_todo', classList: ['date_todo']},
            {dataIndex: 'time_todo', classList: ['time_todo']},
            {dataIndex: 'frequency', classList: ['frequency']},
            {staticElement: function (rowId) {
                return '<a class="applyTimeAction">' + rowId + '</a>';
            },
                classList: ['actions']
            },
            {dataIndex: 'date_creation'}
        ]});
    table.addHeaderElement(headerRow.getRow());

    // Selection Grid parameters definition
    var gridParameters = {
        'url': root_url + 'workOut/getIdeas/stepTiming'
    };

    // Workout Grid construction
    workOutGrid = new Grid(table, gridParameters);

    // Adding edit and delete triggers
    $grid.delegate('.applyTimeAction', 'click', function () {
        openApplyTimeDialog(jQuery(this));
    });
}


/**
 * Creates an element that represents the Apply Time Dialog and appends it to the content of the page.
 * Its css defines it as floating and with JavaScript the events controls the User interactions with it and when to
 * close it.
 * @param $element
 */
function openApplyTimeDialog($element) {
    var $ideaRow = $element.parent().parent();
    var ideaId= $element.html();
    var dateTodoCell = $ideaRow.children().eq(2).html();
    var timeTodoCell = $ideaRow.children().eq(3).html();
    var frequencyCell = $ideaRow.children().eq(4).html();

    uniqueUserRequest(function (allowNewUniqueRequests) {
        // Building dialog
        var applyTimeDialog = document.createElement('DIV');
        applyTimeDialog.setAttribute('id', 'applyTimeDialog');
        applyTimeDialog.innerHTML = '' +
            '<div class="applyTimeInnerDialog">' +
            '   <form id="formApplyTime" action="' + root_url + 'workOut/applyTimeIdea">' +
            '   <input type="hidden" class="inputApplyTimeIdeaId" name="id" value="' + ideaId + '" />' +
            '       <div class="title font_subTitle">Apply Time</div>' +
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
            '       <div class="font_subTitle" id="submitAppliedTime">Apply time</div>' +
            '   </form>' +
            '   <div class="font_error" id="applyTimeInfoDisplayer"></div>' +
            '</div>';
        jQuery('#content').append(applyTimeDialog);
        var $applyTimeDialog = jQuery('#applyTimeDialog');

        // Initializing features
        jQuery('#datePicker').datepicker({dateFormat: "yy-mm-dd"});
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
            document.getElementById('content').removeChild(applyTimeDialog);
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