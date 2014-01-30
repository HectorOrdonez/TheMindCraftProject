/**
 * Project: The Mindcraft Project
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
            new Cell({'html': 'Id', 'classList': ['col_id']}),
            new Cell({'html': 'Username', 'classList': ['col_username', 'ftype_titleC']}),
            new Cell({'html': 'Mail', 'classList': ['col_mail', 'ftype_titleC']}),
            new Cell({'html': 'Role', 'classList': ['col_role', 'ftype_titleC', 'centered']}),
            new Cell({'html': 'State', 'classList': ['col_state', 'ftype_titleC', 'centered']}),
            new Cell({'html': 'Actions', 'classList': ['col_actions', 'ftype_titleC']}),
            new Cell({'html': 'Last login', 'classList': ['col_last_login', 'ftype_titleC', 'centered']})
        ],
            'classList': ['header']
        });

    // Users management footer construction
    var footerRow = new Row(
        {'cells': [
            new Cell({
                'html': '<a href="#" id="linkNewUser"></a><form id="formNewUser" action="' + root_url + 'usersManagement/createUser"><input type="text" name="newUsername" class="ftype_contentA" id="inputNewUsername" maxlength="100" /></form>',
                'classList': ['newUserCell']
            })
        ], 'classList': ['footer']}
    );

    // User Management table construction
    var table = new Table('usersManagement_grid', {
        colModel: [
            {colIndex: 'id'},
            {colIndex: 'username', classList: ['ftype_contentA']},
            {colIndex: 'mail', classList: ['ftype_contentA']},
            {colIndex: 'role', classList: ['ftype_contentA', 'centered']},
            {colIndex: 'state', classList: ['ftype_contentA', 'centered']},
            {colIndex: 'actions', customContent: function (rowData) {
                var actionBox = '<div class="actionBox">';
                var newPassword = '<div class="action"><a class="openNewPasswordDialog">' + rowData.id + '</a></div>';
                var deleteUser = '<div class="action"><a class="deleteUser">' + rowData.id + '</a></div>';
                actionBox = actionBox + newPassword + deleteUser + '</div>';
                return actionBox;
            },
                classList: ['actions']
            },
            {colIndex: 'last_login', classList: ['last_login', 'ftype_contentA', 'centered']}
        ]});

    table.addHeaderElement(headerRow.toHTML());
    table.addFooterElement(footerRow.toHTML());

    // UsersManagement Grid parameters definition
    var gridParameters = {
        'url': root_url + 'usersManagement/getUsers'
    };

    // UsersManagement Grid construction
    usersManagementGrid = new Grid(table, gridParameters);

    // Adding Event Listeners
    jQuery('#linkNewUser').click(function () {
        submitNewUser();
    });
    jQuery('#formNewUser').keypress(function (event) {
        if (event.which == 13) {
            event.preventDefault();
            submitNewUser();
        }
    });
    $grid.delegate('.content .col_username', 'click', function () {
        console.log('col_username clicked');
        openEditUsernameDialog(jQuery(this));
    });
    $grid.delegate('.content .col_role', 'click', function () {
        console.log('col_role clicked');
        openChangeRoleDialog(jQuery(this));
    });
    $grid.delegate('.content .col_state', 'click', function () {
        console.log('col_state clicked');
        openChangeStateDialog(jQuery(this));
    });
    $grid.delegate('.openNewPasswordDialog', 'click', function () {
        console.log('openNewPasswordDialog clicked');
        openChangePasswordDialog(jQuery(this));
    });
    $grid.delegate('.deleteUser', 'click', function () {
        console.log('Delete User clicked');
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
        var passwordBox = document.createElement('DIV');
        passwordBox.setAttribute('id', 'passwordBox');
        passwordBox.innerHTML = '' +
            '<div id="passwordDialog">' +
            '   <form id="formChangePassword" action="' + root_url + 'usersManagement/editUserPassword">' +
            '       <input id="inputNewPassword" type="password" name="password" placeholder="Enter a new password" />' +
            '   </form>' +
            '   <div class="ftype_errorA" id="passwordDialogInfoDisplayer"></div>' +
            '</div>';
        jQuery('.bodyContent').append(passwordBox);
        var $passwordBox = jQuery('#passwordBox');

        jQuery('.passwordInnerDialog').click(function (event) {
            event.stopPropagation();
        });
        $passwordBox.click(function () {
            closePasswordBox()
        });
        jQuery('body').bind('keyup.editPassword', function (event) {
            if (event.which == 27) {
                closePasswordBox();
            }
        });
        jQuery('#formChangePassword').keypress(function (event) {
            if (event.which == 13) {
                event.preventDefault();
                submitNewPassword(userId, $passwordBox.find('#inputNewPassword').val(), closePasswordBox);
            }
        });

        // Show Dialog!
        $passwordBox.fadeIn(function () {
            jQuery('#inputNewPassword').focus();
            allowNewUniqueRequests();
        });
    });
}

/**
 * Fades out the password box and removes it from the DOM.
 */
function closePasswordBox() {
    // Defining box to close
    var passwordBox = document.getElementById('passwordBox');
    var $passwordBox = jQuery('#passwordBox');

    // Unbinding Escape event
    jQuery('body').unbind('keyup.editPassword');

    // Fading out dialog
    $passwordBox.fadeOut(function () {
        // When dialog is faded, remove the dialog completely
        document.getElementById('loggedContent').removeChild(passwordBox);
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
    var username = jQuery('#inputNewUsername').val();

    if (username == '') {
        setInfoMessage($infoDisplayer, 'error', 'Who you say?', 2000);
        return;
    } else if (username.length > 100) {
        setInfoMessage($infoDisplayer, 'error', 'That WAS a long name. We do not expect any Egyptian, I fear.', 2000);
        return;
    }

    jQuery.ajax({
        type: 'post',
        url: url,
        data: {username: username}
    }).done(function (data) {
            usersManagementGrid.table.addContentData(jQuery.parseJSON(data));
            setInfoMessage($infoDisplayer, 'success', 'User added.', 2000);
            jQuery('#inputNewUsername').val('');
        }
    ).fail(function (data) {
            setInfoMessage($infoDisplayer, 'error', data.statusText, 2000);
        }
    );
}

/**
 * Opens the name edition dialog.
 * @param $usernameCell
 */
function openEditUsernameDialog($usernameCell) {
    // Checking if this cell is already open
    if ($usernameCell.hasClass('open')) {
        return;
    }

    // Setting this cell as open to avoid multiple "opening" requests
    $usernameCell.addClass('open');

    // Declaring parameters
    var userId = $usernameCell.parent().find('.col_id').html(); // The user id for the request
    var previousUsername = $usernameCell.html();                // The previous username, in case no alteration is required
    var editableContent = '' +
        '<form id="formEditUsername">' +
        '<input type="text" name="username" id="inputEditUsername" maxlength="100" value="" />' +
        '</form>';                                              // The cell content to offer User the edit input

    // Now, changing visuals
    $usernameCell.html(editableContent);
    var $inputEditUsername = jQuery('#inputEditUsername');
    $inputEditUsername.focus();
    $inputEditUsername.val(previousUsername);

    // Adding new Event Listeners
    $inputEditUsername.blur(function () {
        // Restore normality
        $usernameCell.html(previousUsername);
        $usernameCell.removeClass('open');
    });
    jQuery('#formEditUsername').keypress(function (event) {
        if (event.which == 13) {
            event.preventDefault();
            submitEditUser(userId, $inputEditUsername.val(), function (newUsername) {
                $usernameCell.html(newUsername);
                $usernameCell.removeClass('open');
            });
        }
    });
}

/**
 * * Function requested when User edits the name of a user. Using the name parameter, requests to the server its edition
 * and, when successful, restores the normality of the table calling the callback.
 * @param userId
 * @param newUsername
 * @param callback
 */
function submitEditUser(userId, newUsername, callback) {
    var $infoDisplayer = jQuery('#infoDisplayer');
    var url = root_url + 'usersManagement/editUsername';
    var data = {
        id: userId,
        username: newUsername
    };

    jQuery.ajax({
        type: 'post',
        url: url,
        data: data
    }).done(function () {
            callback(newUsername);
        }
    ).fail(function (data) {
            setInfoMessage($infoDisplayer, 'error', data.statusText, 2000);
        }
    );
}

/**
 * Opens the role edition dialog.
 * @param $roleCell
 */
function openChangeRoleDialog($roleCell) {
    // Checking if this cell is already open
    if ($roleCell.hasClass('open')) {
        return;
    }

    // Setting this cell as open to avoid multiple "opening" requests
    $roleCell.addClass('open');

    // Declaring parameters
    var userId = $roleCell.parent().find('.col_id').html(); // The user id for the request
    var previousRole = $roleCell.html();                // The previous username, in case no alteration is required
    var editableContent = '' +
        '<select id="roleSelector">' +
        '   <option>admin</option>' +
        '   <option>basic</option>' +
        '</select>';                                      // The cell content to offer User the role selection

    // Now, changing visuals
    $roleCell.html(editableContent);
    var roleSelector = jQuery('#roleSelector');
    roleSelector.val(previousRole);
    roleSelector.focus();

    // Adding new Event Listeners
    roleSelector.blur(function () {
        // Check if Role has changed
        var newRole = roleSelector.val();
        if (newRole != previousRole) {
            submitSetRole(userId, newRole, function () {
                $roleCell.html(newRole);
            });
        } else {
            $roleCell.html(previousRole);
        }

        $roleCell.removeClass('open');
    });
}

/**
 * Opens the state edition dialog.
 * @param $stateCell
 */
function openChangeStateDialog($stateCell) {
    // Checking if this cell is already open
    if ($stateCell.hasClass('open')) {
        return;
    }

    // Setting this cell as open to avoid multiple "opening" requests
    $stateCell.addClass('open');

    // Declaring parameters
    var userId = $stateCell.parent().find('.col_id').html(); // The user id for the request
    var previousState = $stateCell.html();                // The previous username, in case no alteration is required
    var editableContent = '' +
        '<select id="stateSelector">' +
        '   <option>active</option>' +
        '   <option>inactive</option>' +
        '   <option>pending</option>' +
        '</select>';                                      // The cell content to offer User the role selection

    // Now, changing visuals
    $stateCell.html(editableContent);
    var $stateSelector = jQuery('#stateSelector');
    $stateSelector.val(previousState);
    $stateSelector.focus();

    // Adding new Event Listeners
    $stateSelector.blur(function () {
        // Check if Status has changed
        var newState = $stateSelector.val();
        if (newState != previousState) {
            submitSetState(userId, newState, function () {
                $stateCell.html(newState);
                $stateCell.removeClass('open');
            });
        } else {
            $stateCell.html(previousState);
            $stateCell.removeClass('open'); 
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
 * * Function requested when User changes the state of a user. Using the state parameter, requests its change to the
 * server and, when successful, restores the normality of the table calling the callback.
 * @param userId
 * @param newState
 * @param callback
 */
function submitSetState(userId, newState, callback) {
    var $infoDisplayer = jQuery('#infoDisplayer');
    var url = root_url + 'usersManagement/editUserState';
    var data = {
        id: userId,
        state: newState
    };

    jQuery.ajax({
        type: 'post',
        url: url,
        data: data
    }).done(function () {
            callback(newState);
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
    if (!confirm('Every time you kill a User, God eats a cookie. Proceed? (Fat Gods are bad seen in some cultures and religions, as shown in a German research in 1965)')) return;

    var $infoDisplayer = jQuery('#infoDisplayer');
    var url = root_url + 'usersManagement/deleteUser';
    var data = {'id': $element.html()};

    jQuery.ajax({
        type: 'post',
        url: url,
        data: data
    }).done(function () {
            usersManagementGrid.table.removeContentId($element.closest('.row').attr('id'));
        }
    ).fail(function (data) {
            setInfoMessage($infoDisplayer, 'error', data.statusText, 2000);
        }
    );
}


/**
 * Function requested when User inputs a new password. Sets a new password to the user.
 * @param userId
 * @param newPassword
 * @param callback - To close the password dialog if the edition is successful.
 */
function submitNewPassword(userId, newPassword, callback) {
    var $infoDisplayer = jQuery('#infoDisplayer');
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
            setInfoMessage($infoDisplayer, 'success', 'Password changed.', 2000);
            callback();
        }
    ).fail(function (data) {
            setInfoMessage($infoDisplayer, 'error', data.statusText, 2000);
        }
    );
}