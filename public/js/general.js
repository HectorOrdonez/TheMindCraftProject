/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * General JS Library. Why, for general purposes!
 * Date: 25/07/13 1:30
 * @todo - Create a Config JS file?
 */

/**
 * Definition of the Root Url to use in links.
 * @type {string}
 */
var root_url = 'http://localhost/projects/themindcraftproject/';
// var root_url = 'http://192.168.1.55/projects/themindcraftproject/';
// var root_url = 'http://themindcraftproject.org/';

/**
 * Boolean global variable that allows us to know in any place if there is an ongoing ajax request.
 * @type {boolean}
 */
var ajaxInProgress = false;

/**
 * Checks if there is an Ajax request in progress. In that case returns false. Otherwise, runs the function.
 * The function being run needs to call the callback function that is being passed. Otherwise it will not allow further ajax requests.
 *
 * @param {function} uniqueFunction
 */
function uniqueUserRequest(uniqueFunction)
{
    if (ajaxInProgress == true){
        console.error('Please, be patient. A request is being processed.');
    }
    else {
        ajaxInProgress = true;
        uniqueFunction(function(){closeAjaxInProgress()});
    }
}

/**
 * Function that is passed as callback to all uniqueUserRequest requests. Once this method is called as callback, new unique ajax calls can be done.
 */
function closeAjaxInProgress(){
    ajaxInProgress = false;
}

/**
 * On Document Ready...
 */
jQuery().ready(function () {
    /**
     * Sets Logo to be a link to the main page.
     */
    jQuery('#logo').click(function() {
        window.location.href = root_url;
    });
});























/**
 TOOLS
 **/

function var_export (mixed_expression, bool_return) {
    // http://kevin.vanzonneveld.net
    // +   original by: Philip Peterson
    // +   improved by: johnrembo
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +   input by: Brian Tafoya (http://www.premasolutions.com/)
    // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
    // +   input by: Hans Henrik (http://hanshenrik.tk/)
    // -    depends on: echo
    // *     example 1: var_export(null);
    // *     returns 1: null
    // *     example 2: var_export({0: 'Kevin', 1: 'van', 2: 'Zonneveld'}, true);
    // *     returns 2: "array (\n  0 => 'Kevin',\n  1 => 'van',\n  2 => 'Zonneveld'\n)"
    // *     example 3: data = 'Kevin';
    // *     example 3: var_export(data, true);
    // *     returns 3: "'Kevin'"
    var retstr = '',
        iret = '',
        value,
        cnt = 0,
        x = [],
        i = 0,
        funcParts = [],
    // We use the last argument (not part of PHP) to pass in
    // our indentation level
        idtLevel = arguments[2] || 2,
        innerIndent = '',
        outerIndent = '',
        getFuncName = function (fn) {
            var name = (/\W*function\s+([\w\$]+)\s*\(/).exec(fn);
            if (!name) {
                return '(Anonymous)';
            }
            return name[1];
        },
        _makeIndent = function (idtLevel) {
            return (new Array(idtLevel + 1)).join(' ');
        },
        __getType = function (inp) {
            var i = 0, match, types, cons, type = typeof inp;
            if (type === 'object' && inp.constructor &&
                getFuncName(inp.constructor) === 'PHPJS_Resource') {
                return 'resource';
            }
            if (type === 'function') {
                return 'function';
            }
            if (type === 'object' && !inp) {
                return 'null'; // Should this be just null?
            }
            if (type === "object") {
                if (!inp.constructor) {
                    return 'object';
                }
                cons = inp.constructor.toString();
                match = cons.match(/(\w+)\(/);
                if (match) {
                    cons = match[1].toLowerCase();
                }
                types = ["boolean", "number", "string", "array"];
                for (i = 0; i < types.length; i++) {
                    if (cons === types[i]) {
                        type = types[i];
                        break;
                    }
                }
            }
            return type;
        },
        type = __getType(mixed_expression);

    if (type === null) {
        retstr = "NULL";
    } else if (type === 'array' || type === 'object') {
        outerIndent = _makeIndent(idtLevel - 2);
        innerIndent = _makeIndent(idtLevel);
        for (i in mixed_expression) {
            value = this.var_export(mixed_expression[i], 1, idtLevel + 2);
            value = typeof value === 'string' ? value.replace(/</g, '&lt;').
                replace(/>/g, '&gt;') : value;
            x[cnt++] = innerIndent + i + ' => ' +
                (__getType(mixed_expression[i]) === 'array' ?
                    '\n' : '') + value;
        }
        iret = x.join(',\n');
        retstr = outerIndent + "array (\n" + iret + '\n' + outerIndent + ')';
    } else if (type === 'function') {
        funcParts = mixed_expression.toString().
            match(/function .*?\((.*?)\) \{([\s\S]*)\}/);

        // For lambda functions, var_export() outputs such as the following:
        // '\000lambda_1'. Since it will probably not be a common use to
        // expect this (unhelpful) form, we'll use another PHP-exportable
        // construct, create_function() (though dollar signs must be on the
        // variables in JavaScript); if using instead in JavaScript and you
        // are using the namespaced version, note that create_function() will
        // not be available as a global
        retstr = "create_function ('" + funcParts[1] + "', '" +
            funcParts[2].replace(new RegExp("'", 'g'), "\\'") + "')";
    } else if (type === 'resource') {
        retstr = 'NULL'; // Resources treated as null for var_export
    } else {
        retstr = typeof mixed_expression !== 'string' ? mixed_expression :
            "'" + mixed_expression.replace(/(["'])/g, "\\$1").
                replace(/\0/g, "\\0") + "'";
    }

    if (!bool_return) {
        this.echo(retstr);
        return null;
    }
    return retstr;
}



/**
 * This function receives a JQuery element where has to be displayed a message.
 * @param $infoDiv - JQuery Element where the message will be displayed.
 * @param type - Type of the message (success / error) that defines the font to be used.
 * @param message - Message to display.
 * @param timeout - Time in mils of second that the message will remain displayed.
 */
function setInfoMessage($infoDiv, type, message, timeout) {
    $infoDiv.addClass('ftype_' + type + 'A');
    $infoDiv.html(message);

    setTimeout(function () {
        $infoDiv.fadeOut(function () {
            $infoDiv.html('');
            $infoDiv.removeClass('ftype_' + type + 'A');
            $infoDiv.fadeIn();
        });
    }, timeout);
}

/**
 * Function that checks if the received checkbox is checked or not.
 * @param checkboxId Id of the Checkbox Input.
 * @returns {boolean}
 */
function isChecked(checkboxId)
{
    return jQuery('#' + checkboxId).is(':checked');
}