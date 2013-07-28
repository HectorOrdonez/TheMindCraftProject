/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description:
 * JS Library that defines grid elements required by the grid library.
 * Date: 28/07/13 15:30
 */

function Table(tableId) {
    console.log('Table construction');

// Parameters initialization
    var rows = [];
    var autoResizing = true;

    var $tableLocation = jQuery('#' + tableId);
    $tableLocation.append('<tbody id="' + tableId + '_gridTable_tbody"></tbody>');
    var $workspace = jQuery('#' + tableId + '_gridTable_tbody');

// Defining Table private methods
    function fitTableToFrame () {
        var parentDivWidth = $tableLocation.parent().width();
        $tableLocation.width(parentDivWidth);
        console.log('Fitted table!');
    }

// Defining Table public methods
    this.addRow = function (row) {
        console.log('Adding Row');
        rows.push(row);
    };

    this.render = function () {
        console.log('...TABLE RENDERING...');
        jQuery.each(rows, function(index, row) {
            console.log('...APPENDING ROW...');
            $workspace.append(row.toHTML());
        });

        if(autoResizing == true)
        {
            jQuery(window).bind('resize', function() {
                fitTableToFrame();
            }).trigger('resize');
        }
    };

}
function Row(parameters) {
// Parameters initialization
    var cells = [];

// Defining Row private methods
    function getRowClass()
    {
        if (typeof(parameters['class']) != 'undefined')
        {
            return " class=\'" + parameters['class'] + "\' ";
        } else {
            return '';
        }
    }

// Defining Row public methods
    this.addCell = function (cell) {
        cells.push(cell);
    };

    this.toHTML = function (){
        console.log('...ROW TO HTML...');

        var html = '<tr' + getRowClass() + '>';
        jQuery.each(cells, function(index, cell) {
            html += '<td' + cell.getClass() + cell.getColspan() + '>' + cell.getContent() + '</td>';
        });
        return html;
    };
}

function Cell(content, specialOptions) {
// Parameters initialization
    var cellClass = '';
    var cellColspan = '';

    initializeCell();

// Defining Row private methods
    function initializeCell()
    {
        if (typeof(specialOptions['colspan']) != 'undefined')
        {
            console.log('Cell with special colspan : ' + specialOptions['colspan']);
            cellColspan = ' colspan="' + specialOptions['colspan'] + '"';
        }

        if (typeof(specialOptions['class']) != 'undefined')
        {
            console.log('Cell with special class : ' + specialOptions['class']);
            cellColspan = ' class="' + specialOptions['class'] + '"';
        }
    }

// Defining Row public methods
    this.getColspan = function () {
        return cellColspan;
    };

    this.getClass = function () {
        return cellClass;
    };

    this.getContent = function () {
        return content;
    };
}
