/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description: IdeaToAction JS Library
 * Date: 23/07/13 13:06
 */

jQuery().ready(function () {
    var grid = '#grid';
    var pagination = '#gridpager';

    // General creation of the Grid.
    jQuery(grid).jqGrid({
        //Url from where jqGrid gets the data. The columns will be filled in the order in which the definition in this url is done.
        url: 'brainstorm/getIdeas',
        datatype: 'json',
        mtype: 'post',
        colNames: [
            //ColNames is the visual name of the column, which appears at the top.
            'Idea Id',
            'Title',
            'Idea Actions'
        ],
        colModel: [
            //ColModel defines the columns and its names.
            //Name is the key of the column. Index is needed for sorting the grid.
            {
                name: 'id', index: 'id', hidden: true
            },
            {
                name: 'title', index: 'title', width: '90', editable: true, edittype: 'text', editrules: {required: true}
            },
            {
                name: 'ideaActions', width: '10', editable: false
            }
        ],
        //Toppager adds the pagination to the top of the form.
        //Besides is needed for allowing the option 'cloneToTop'. Without the toppager cloneToTop does nothing.
        toppager: true,
        rowNum: 40,
        rowList: [10, 40, 100],
        pager: pagination,
        sortname: 'id',
        sortorder: 'asc',
        viewrecords: true,
        caption: 'Ideas',
        jsonReader: {
            page: "page",
            total: "total",
            records: "records",
            root: "ideas",
            repeatitems: false,
            id: "id"
        },
        height: '100%',
        autowidth: true,
        loadComplete: function () {
            // Binding the resizing event.
            resizeJQGridWidth('grid', 'grid_container', 400);
            // Creating the actions per row.
            var rowIds = jQuery(grid).getDataIDs();
            for (var i = 0; i < rowIds.length; i++) {
                var rowId = rowIds[i];
                // Construction of the actions in this row
                var actions = '<button class=\'deleteIdea\' onClick=\'deleteIdea( ' + rowId + ');\'></button>';
                actions += '<button class=\'holdOverIdea\' onClick=\'holdOverIdea( ' + rowId + ');\'></button>';
                actions += '<button class=\'increasePriority\' onClick=\'increasePriority( ' + rowId + ');\'></button>';
                actions += '<button class=\'decreasePriority\' onClick=\'decreasePriority( ' + rowId + ');\'></button>';

                // Adding the button to the action column in this row
                jQuery(grid).setRowData(rowIds[i], {'ideaActions': actions});
            }

            //Construction of the visual features of the buttons.
            $(".deleteIdea").button({
                label: "deleteIdea",
                icons: {
                    primary: 'ui-icon-trash'
                },
                text: false
            });
            $(".holdOverIdea").button({
                label: "holdOverIdea",
                icons: {
                    primary: 'ui-icon-calendar'
                },
                text: false
            });
            $(".increasePriority").button({
                label: "increasePriority",
                icons: {
                    primary: 'ui-icon-arrowreturnthick-1-n'
                },
                text: false
            });
            $(".decreasePriority").button({
                label: "decreasePriority",
                icons: {
                    primary: 'ui-icon-arrowreturnthick-1-s'
                },
                text: false
            });
        }
    });
});

// Defining special functions for customized actions.
/**
 * function deleteIdea
 * @uses jqGrid at click
 * @param rowId on the grid, which is the user Id.
 */
function deleteIdea(rowId) {
    alert('delete Idea ' + rowId);
}
/**
 * function holdOverIdea
 * @uses jqGrid at click
 * @param rowId on the grid, which is the user Id.
 */
function holdOverIdea(rowId) {
    alert('hold overr Idea ' + rowId);
}
/**
 * function increasePriority
 * @uses jqGrid at click
 * @param rowId on the grid, which is the user Id.
 */
function increasePriority(rowId) {
    alert('Increase Priority to Idea ' + rowId);
}
/**
 * function decreasePriority
 * @uses jqGrid at click
 * @param rowId on the grid, which is the user Id.
 */
function decreasePriority(rowId) {
    alert('Decrease Priority Idea ' + rowId);
}