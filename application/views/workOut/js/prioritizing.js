/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description: Prioritizing JS Library
 * Date: 25/07/13 21:00
 */

function createPrioritizingGrid(){
    var grid = '#grid';
    var pagination = '#gridpager';

    // General creation of the Grid.
    jQuery(grid).jqGrid({
        //Url from where jqGrid gets the data. The columns will be filled in the order in which the definition in this url is done.
        url: root_url + '/workOut/getIdeas/stepPrioritizing',
        datatype: 'json',
        mtype: 'post',
        colNames: [
            //ColNames is the visual name of the column, which appears at the top.
            'Idea Id',
            'Title',
            'Prioritizing Tools'
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
                name: 'prioritizingTools', width: '10', editable: false
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
            resizeJQGridWidth('grid', 'content', 400);
        }
    });
}