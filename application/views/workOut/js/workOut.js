/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description: IdeaToAction JS Library
 * Date: 23/07/13 13:00
 */

jQuery().ready(function () {
    var grid = '#grid';
    var pagination = '#gridpager';

    // General creation of the Grid.
    jQuery(grid).jqGrid({
        //Url from where jqGrid gets the data. The columns will be filled in the order in which the definition in this url is done.
        url: 'workOut/getIdeas',
        datatype: 'json',
        mtype: 'post',
        colNames: [
            //ColNames is the visual name of the column, which appears at the top.
            'Idea Id',
            'Title',
            'Added date',
            'Hold over to date',
            'Selection Tools'
        ],
        colModel: [
            //ColModel defines the columns and its names.
            //Name is the key of the column. Index is needed for sorting the grid.
            {
                name: 'id', index: 'id', hidden: true
            },
            {
                name: 'title', index: 'title', width: 60, editable: true, edittype: 'text', editrules: {required: true}
            },
            {
                name: "date_creation", width: 20, align: "center", sorttype: "date",
                formatter: "date", formatoptions: { newformat: "Y-m-d" }, editable: false,
                editoptions: { required: false}
            },
            {
                name: "date_todo", hidden: true, width: 20, align: "center", sorttype: "date",
                formatter: "date", formatoptions: { newformat: "Y-m-d" }, editable: false,
                editoptions: { dataInit: function (elem) {
                    jQuery(elem).datepicker({
                        dateFormat: "yy-mm-dd"
                    });
                }},
                searchoptions: { sopt: ["eq", "ne", "lt", "le", "gt", "ge"], dataInit: function () {
                    setTimeout(function () {
                        jQuery(elem).datepicker({
                            dateFormat: "yy-mm-dd"
                        });
                    }, 100);
                }}
            },
            {
                name: 'selectionTools', width: '10', editable: false
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

                // Adding the button to the action column in this row
                jQuery(grid).setRowData(rowIds[i], {'selectionTools': actions});
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
    var grid = '#grid';

    jQuery(grid).jqGrid('delGridRow', rowId,
        {
            editCaption: 'Delete idea',
            width: 500,
            recreateForm: true,
            reloadAfterSubmit: true,
            closeAfterEdit: true,
            url: "workOut/deleteIdea",
            errorTextFormat: function (data) {
                return data.statusText;
            }
        }
    );
}
/**
 * function holdOverIdea
 * @uses jqGrid at click
 * @param rowId on the grid, which is the user Id.
 */
function holdOverIdea(rowId) {
    var grid = '#grid';

    jQuery(grid).jqGrid('editGridRow', rowId,
        {
            editCaption: 'Postpone to date',
            beforeInitData: function () {
                //Redefine of the cols that need to be set for this special editing.
                jQuery(grid).setColProp('title', {editable: false});
                jQuery(grid).setColProp('date_todo', {hidden: false, editable: true});
            },
            afterShowForm: function () {
                //Redefine of the cols to set them back to the normal state.
                jQuery(grid).setColProp('title', {editable: true});
                jQuery(grid).setColProp('date_todo', {hidden: true, editable: false});
            },
            width: 500,
            recreateForm: true,
            reloadAfterSubmit: true,
            closeAfterEdit: true,
            url: "workOut/holdOverIdea",
            errorTextFormat: function (data) {
                return data.statusText;
            }
        }
    );
}