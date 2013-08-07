/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description:
 * Description: UsersManagement JS Library
 * Date: 25/07/13 01:30
 */

/**
 * Global variable that will contain the grid.
 * @type {Grid}
 */
var usersManagementGrid;

jQuery().ready(function () {
    // JQuery variable that stores the grid.
    var $grid = jQuery('#usersManagement_grid');

    // Users management header construction
    var headerRow = new Row(
        {'cells': [
            {'html': 'Id', 'classList': ['col_id']},
            {'html': 'Name', 'classList': ['col_name']},
            {'html': 'Role', 'classList': ['col_role']},
            {'html': 'Actions', 'classList': ['col_actions']},
            {'html': 'Last login', 'classList': ['col_last_login']}
        ],
            'classList': ['header']
        });

    // Users management footer construction
    var footerRow = new Row(
        {'cells': [
            {
                'html': '<a href="#" id="linkNewUser" class="font_normal"></a><form id="formNewUser" action="' + root_url + 'usersManagement/createUser"><input type="text" name="title" id="inputNewUser" /></form>',
                'colspan': '4'
            }
        ], 'classList': ['footer']}
    );

    // Brainstorm table construction
    var table = new Table('usersManagement_grid', {
        colModel: [
            {dataIndex: 'id', classList: ['id']},
            {dataIndex: 'name', classList: ['name']},
            {dataIndex: 'role', classList: ['role']},
            {staticElement: function (rowId) {
                var newPassword = '<a class="openNewPasswordDialog">' + rowId + '</a>';
                var deleteUser = '<a class="deleteUser">' + rowId + '</a>';
                return newPassword + deleteUser;
            },
                classList: ['actions']
            },
            {dataIndex: 'last_login', classList: ['last_login']}
        ]});
    table.addHeaderElement(headerRow.getRow());
    table.addFooterElement(footerRow.getRow());

    // UsersManagement Grid parameters definition
    var gridParameters = {
        'url': root_url + 'usersManagement/getUsers'
    };

    // UsersManagement Grid construction
    usersManagementGrid = new Grid(table, gridParameters);

    // Adding effects to the grid buttons
    jQuery('#linkNewUser').click(function () {
        submitNewUser();
    });
    jQuery('#formNewUser').keypress(function (event) {
        if (event.which == 13) {
            event.preventDefault();
            submitNewUser();
        }
    });

    // Adding edit and delete triggers
    $grid.delegate('.name', 'dblclick', function () {
        openEditNameDialog(jQuery(this).parent());
    });
    $grid.delegate('.role', 'dblclick', function () {
        openChangeRoleDialog(jQuery(this).parent());
    });
    $grid.delegate('.openNewPasswordDialog', 'click', function () {
        openChangePasswordDialog(jQuery(this));
    });
    $grid.delegate('.deleteUser', 'click', function () {
        deleteUser(jQuery(this));
    });
});

/**
 * Opens the Password Dialog, allowing the User to change the password of the selected User.
 * @param $element
 */
function openChangePasswordDialog($element) {

    uniqueUserRequest(function (allowNewUniqueRequests) {
        var userId = $element.html();
        var passwordDialog = document.createElement('DIV');
        passwordDialog.setAttribute('id', 'passwordDialog');
        passwordDialog.innerHTML = '' +
            '<div class="passwordInnerDialog">' +
            '   <form id="formChangePassword" action="' + root_url + 'usersManagement/editUserPassword">' +
            '       <div class="title font_subTitle">New password</div>' +
            '       <input id="inputNewPassword" type="password" name="password" placeholder="Enter a new password" />' +
            '   </form>' +
            '   <div class="font_error" id="passwordDialogInfoDisplayer"></div>' +
            '</div>';
        jQuery('#content').append(passwordDialog);
        var $passwordDialog = jQuery('#passwordDialog');

        jQuery('.passwordInnerDialog').click(function (event) {
            event.stopPropagation();
        });
        $passwordDialog.click(function () {
            closePasswordDialog()
        });
        jQuery('body').bind('keyup.editPassword', function (event) {
            if (event.which == 27) {
                closePasswordDialog();
            }
        });
        jQuery('#formChangePassword').keypress(function (event) {
            if (event.which == 13) {
                event.preventDefault();
                submitNewPassword(userId, $passwordDialog.find('#inputNewPassword').val(), closePasswordDialog);
            }
        });

        // Show Dialog!
        $passwordDialog.fadeIn(function () {
            $passwordDialog.find('#inputNewPassword').focus();
            allowNewUniqueRequests();
        });
    });
}

/**
 * Function requested when User inputs a new password. Sets a new password to the user.
 * @param userId
 * @param newPassword
 * @param callback - To close the password dialog if the edition is successful.
 */
function submitNewPassword(userId, newPassword, callback) {
    var $infoDisplayer = jQuery('#passwordDialogInfoDisplayer');
    var url = root_url + 'usersManagement/editUserPassword';
    var data = {
        id: userId,
        password: newPassword
    };

    jQuery.ajax({
        type: 'post',
        url: url,
        data: data
    }).done(function () {
            callback();
        }
    ).fail(function (data) {
            setInfoMessage($infoDisplayer, 'error', data.statusText, 2000);
        }
    );
}

/**
 * Fades out the password dialog and removes it from the DOM.
 */
function closePasswordDialog() {
    uniqueUserRequest(function (allowNewUniqueRequests) {
        // Defining dialog to close
        var passwordDialog = document.getElementById('passwordDialog');
        var $passwordDialog = jQuery('#passwordDialog');

        // Unbinding Escape event
        jQuery('body').unbind('keyup.editPassword');

        // Fading out dialog
        $passwordDialog.fadeOut(function () {
            // When dialog is faded, remove the dialog completely
            document.getElementById('content').removeChild(passwordDialog);
            allowNewUniqueRequests();
        });
    });
}

/**
 * Function requested when User inputs a new user. Using the name parameter, requests to the server the creation of a
 * new user using the default role and an empty password.
 * A User created this way cannot be used till the password is set.
 */
function submitNewUser() {
    var $form = jQuery('#formNewUser');
    var $infoDisplayer = jQuery('#infoDisplayer');
    var url = $form.attr('action');
    var userName = jQuery('#inputNewUser').val();

    if (userName == '') {
        setInfoMessage($infoDisplayer, 'error', 'Who you say?.', 2000);
        return;
    } else if (userName.length > 200) {
        setInfoMessage($infoDisplayer, 'error', 'That WAS a long name. We do not expect any Egyptian, I fear.', 2000);
        return;
    }

    jQuery.ajax({
        type: 'post',
        url: url,
        data: {name: userName}
    }).done(function (data) {
            usersManagementGrid.table.addContentData(jQuery.parseJSON(data));
            setInfoMessage($infoDisplayer, 'success', 'User added.', 2000);
            jQuery('#inputNewUser').val('');
        }
    ).fail(function (data) {
            setInfoMessage($infoDisplayer, 'error', data.statusText, 2000);
        }
    );
}

/**
 * Opens the name edition dialog.
 * @param $element
 */
function openEditNameDialog($element) {
    var userId = $element.children().eq(0).html();
    var $nameCell = $element.children().eq(1);

    // Save previous user's name
    var previousName = $nameCell.html();

    // Replace name column text with input.
    var nameCellContent = '<form id="formEditName"><input type="text" name="title" id="inputEditUserName" value="' + previousName + '"/></form>';
    $nameCell.html(nameCellContent);

    // Focus user on Input and restore normality when unfocus.
    $nameCell.find('#inputEditUserName').focus();
    $nameCell.find('#inputEditUserName').blur(function () {
        // Restore normality
        $nameCell.html(previousName);
    });

    // Adding Enter event to the form
    jQuery('#formEditName').keypress(function (event) {
        if (event.which == 13) {
            event.preventDefault();
            submitEditUser(userId, $nameCell.find('#inputEditUserName').val(), function (newName) {
                $nameCell.html(newName);
            });
        }
    });
}

/**
 * * Function requested when User edits the name of a user. Using the name parameter, requests to the server its edition
 * and, when successful, restores the normality of the table calling the callback.
 * @param userId
 * @param newName
 * @param callback
 */
function submitEditUser(userId, newName, callback) {
    var $infoDisplayer = jQuery('#infoDisplayer');
    var url = root_url + 'usersManagement/editUserName';
    var data = {
        id: userId,
        name: newName
    };

    jQuery.ajax({
        type: 'post',
        url: url,
        data: data
    }).done(function () {
            callback(newName);
        }
    ).fail(function (data) {
            setInfoMessage($infoDisplayer, 'error', data.statusText, 2000);
        }
    );
}

/**
 * Opens the role edition dialog.
 * @param $element
 */
function openChangeRoleDialog($element) {
    var userId = $element.children().eq(0).html();
    var $roleCell = $element.children().eq(2);

    // Save previous user's role
    var previousRole = $roleCell.html();

    // Replace role column text with select.
    var roleCellContent = '' +
        '<select class="roleSelector">' +
        '   <option>admin</option>' +
        '   <option>basic</option>' +
        '</select>';
    $roleCell.html(roleCellContent);
    $roleCell.find('select').val(previousRole);

    // 5 - Focus user on Input
    $roleCell.find('select').focus();
    $roleCell.find('select').blur(function () {
        // Check if Role has changed
        var newRole = $roleCell.find('select').val();
        if (newRole != previousRole) {
            submitSetRole(userId, newRole, function () {
                $roleCell.html(newRole);
            });
        } else {
            $roleCell.html(previousRole);
        }
    });
}

/**
 * * Function requested when User changes the role of a user. Using the role parameter, requests its change to the
 * server and, when successful, restores the normality of the table calling the callback.
 * @param userId
 * @param newRole
 * @param callback
 */
function submitSetRole(userId, newRole, callback) {
    var $infoDisplayer = jQuery('#infoDisplayer');
    var url = root_url + 'usersManagement/editUserRole';
    var data = {
        id: userId,
        role: newRole
    };

    jQuery.ajax({
        type: 'post',
        url: url,
        data: data
    }).done(function () {
            callback(newRole);
        }
    ).fail(function (data) {
            setInfoMessage($infoDisplayer, 'error', data.statusText, 2000);
        }
    );
}

/**
 * Asynchronous request to the server to delete a User.
 * @param $element
 */
function deleteUser($element) {
    if (!confirm('Really really really?')) return;

    var $infoDisplayer = jQuery('#infoDisplayer');
    var url = root_url + 'usersManagement/deleteUser';
    var data = {'id': $element.html()};

    jQuery.ajax({
        type: 'post',
        url: url,
        data: data
    }).done(function () {
            usersManagementGrid.table.removeContentId($element.parent().parent().attr('id'));
        }
    ).fail(function (data) {
            setInfoMessage($infoDisplayer, 'error', data.statusText, 2000);
        }
    );
}