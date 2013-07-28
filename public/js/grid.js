/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description:
 * JS Library for the use of grids.
 * Date: 27/07/13 20:30
 */

function Grid(parameters) {
    console.log('Grid construction');

    // Parameters initialization
    var self = this;
    var gridData = [];
    var gridDataReady = false;
    var dataSource = parameters['url'];
    var columns = parameters['columns'];
    var afterComplete = parameters['afterComplete'];
    var afterRender = parameters['afterRender'];
    var specialOptions = parameters['specialOptions'];
    self.table = new Table(parameters['location']);

    // Data initialization
    getData();

// Defining Grid private methods
    function getData() {
        console.log('Getting Data');

        jQuery.ajax({
            type: 'post',
            url: dataSource,
            data: {
            }
        }).done(
            function (dataList) {
                console.log('GetData Done');
                var jsonObject = jQuery.parseJSON(dataList);

                if (jsonObject.length >0 && Object.keys(jsonObject[0]).length != columns.length) {
                    alert('Columns defined (' + columns.length + ') and columns received (' + Object.keys(jsonObject[0]).length + ') are not the same!');
                    return;
                }

                jQuery.each(jsonObject, function (index, data) {
                    gridData.push(data);
                });
            }
        ).fail(
            function () {
                console.log('GetData Fail');
                gridData.push('Ajax request unsuccessful :(');
            }
        ).complete(
            function () {
                gridDataReady = true;
            }
        );
    }

    function renderWhenReady(i) {

        setTimeout(function () {
            if (gridDataReady == true) {
                proceedRendering(i);
            }
            else {
                if (i < 3) {
                    renderWhenReady(i + 1);
                } else {
                    dataUnavailable();
                }
            }
        }, 1000);
    }

    function constructHeader() {
        console.log('Constructing Header');

        var headerClass = 'header';

        if (typeof(specialOptions['headerClass']) != 'undefined') {
            console.log('Header Class as Special Option defined : ' + specialOptions['headerClass']);
            headerClass += ' ' + specialOptions['headerClass'];
        }

        var row = new Row(
            {
                class: headerClass
            }
        );

        jQuery.each(columns, function (colIndex, column) {
            if (column['display'] != '')
            {
                var cell = new Cell([column['display']], {});
                row.addCell(cell);
            }
        });
        self.table.addRow(row);
    }

    function constructContent() {
        jQuery.each(gridData, function (rowNumber, dataArray) {
            var row = new Row({});

            jQuery.each(columns, function (colIndex, column) {
                if (column['display'] != '')
                {
                    var cell = new Cell(dataArray[column['name']], {});
                    row.addCell(cell);
                }
            });
            self.table.addRow(row);
        });
    }

    function proceedRendering(i) {
        console.log('Proceed Rendering after ' + i + ' trials!');

        constructHeader();

        constructContent();

        render();
    }

    function dataUnavailable() {
        var row = new Row(
            {
                class: 'loadingError font_error'
            }
        );

        var cell = new Cell('Data not available');

        row.addCell(cell);
        self.table.addRow(row);

        render();
    }

    function render() {
        if (typeof(afterComplete) != 'undefined')
        {
            afterComplete();
        }

        self.table.render();

        if (typeof(afterRender) != 'undefined')
        {
            afterRender();
        }
    }

// Defining Grid public methods
    this.createGrid = function () {
        console.log('Rendering action requested.');

        var i = 0;
        renderWhenReady(i);
    };
}
