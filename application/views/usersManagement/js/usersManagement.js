/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description:
 * Description: UsersManagement JS Library
 * Date: 25/07/13 01:30
 */

jQuery().ready(function () {
    var grid = '#grid';
    var pagination = '#gridpager';

    // General creation of the Grid.
    jQuery(grid).jqGrid({
        //Url from where jqGrid gets the data. The columns will be filled in the order in which the definition in this url is done.
        url: 'usersManagement/getUsers',
        datatype: 'json',
        mtype: 'post',
        colNames: [
            //ColNames is the visual name of the column, which appears at the top.
            'User Id',
            'Name',
            'Password',
            'Role',
            'User actions'
        ],
        colModel: [
            //ColModel defines the columns and its names.
            //Name is the key of the column. Index is needed for sorting the grid.
            {name: 'id', index: 'id', hidden: true},
            {name: 'name', index: 'name', editable: true, edittype: 'text', editrules: {required: true}},
            {name: 'password', edittype: 'password', hidden: true, editable: false},
            {name: 'role', index: 'role', editable: true, edittype: 'select', formatter: 'select', editoptions: {value: "admin:Administrator;basic:Basic User"}},
            {name: 'UserActions', editable: false}
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
        caption: 'User Management',
        jsonReader: {
            page: "page",
            total: "total",
            records: "records",
            root: "users",
            repeatitems: false,
            id: "id"
        },
        loadComplete: function () {
            // Binding the resizing event.
            resizeJQGridWidth('grid', 'grid_container', 400);

            // Creating the actions per row.
            var rowIds = jQuery(grid).getDataIDs();
            for (var i = 0; i < rowIds.length; i++) {
                var rowId = rowIds[i];
                // Construction of the actions in this row
                var actions = '<button class=\'editPassword\' onClick=\'editUserPassword( ' + rowId + ');\'></button>';

                // Adding the button to the action column in this row
                jQuery(grid).setRowData(rowIds[i], {'UserActions': actions});
            }

            //Construction of the visual features of the buttons.
            $(".editPassword").button({
                label: "Edit Password",
                icons: {
                    primary: 'ui-icon-pencil'
                },
                text: false
            });
        }
    });

    // 2 - Adding additional options to the grid
    jQuery(grid).navGrid(pagination,
        {edit: true, add: true, del: true, search: false, refresh: true, cloneToTop: false}, // Pagination options
        { // Edit options
            url: 'usersManagement/editUser',
            editCaption: 'Edit User',
            closeAfterAdd: true,
            closeAfterEdit: true,
            recreateForm: true,
            errorTextFormat: function (data) {
                return data.statusText;
            }
        },
        { // Add options
            url: 'usersManagement/createUser',
            addCaption: 'Create new User',
            closeAfterAdd: true,
            closeAfterEdit: true,
            recreateForm: true,
            errorTextFormat: function (data) {
                return data.statusText;
            },
            beforeInitData: function () {
                //Redefine of the cols that need to be set for adding.
                jQuery(grid).setColProp('password', {hidden: false, editable: true});
            },
            afterShowForm: function () {
                //Redefine of the cols, so other actions will not see them.
                jQuery(grid).setColProp('password', {hidden: true, editable: false, editrules: {required: false, edithidden: true}});
            }
        },
        { // Delete options
            url: 'usersManagement/deleteUser',
            caption: 'Delete User',
            errorTextFormat: function (data) {
                return data.statusText;
            }
        }, //del options
        {} // search options
    );
});

// Defining special functions for customized actions.
/**
 * function editUserPassword
 * Opens a dialog with which the user can modify the password of the selected User.
 * Once submitted the information is sent to the server and it updates it.
 *
 * @uses jqGrid at click
 * @param rowId on the grid, which is the user Id.
 */
function editUserPassword(rowId) {
    var grid = '#grid';

    jQuery(grid).jqGrid('editGridRow', rowId,
        {
            editCaption: 'Edit Password',
            beforeInitData: function () {
                //Redefine of the cols that need to be set for this special editing.
                jQuery(grid).setColProp('name', {editable: false});
                jQuery(grid).setColProp('password', {hidden: false, editable: true});
                jQuery(grid).setColProp('role', {editable: false});
            },
            afterShowForm: function () {
                //Redefine of the cols to set them back to the normal state.
                jQuery(grid).setColProp('name', {editable: true});
                jQuery(grid).setColProp('password', {hidden: true, editable: false});
                jQuery(grid).setColProp('role', {editable: true});
            },
            width: 500,
            recreateForm: true,
            reloadAfterSubmit: true,
            closeAfterEdit: true,
            url: "usersManagement/editUserPassword",
            errorTextFormat: function (data) {
                return data.statusText;
            }
        }
    );
}