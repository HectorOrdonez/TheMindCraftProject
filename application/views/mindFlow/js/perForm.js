/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Description: PerForm JS Library
 * Date: 13/02/14 23:00
 */

function PerForm($element, callback) {
    // Step content
    var $workspace;
    var $perFormSpace;

    /***********************************/
    /** Construct                     **/
    /***********************************/

    $workspace = $element;
    buildLayout();

    loadData(callback);

    /***********************************/
    /** Public functions              **/
    /***********************************/
    
    this.close = function (afterFadeOut) {
        var $perFormLayout = jQuery('#perFormLayout');
        $perFormLayout.fadeOut(function () {
            $workspace.after($perFormLayout);
            $workspace.empty();
            afterFadeOut();
        });
    };

    /***********************************/
    /** Private functions             **/
    /***********************************/

    function buildLayout() {
        var $perFormLayout = jQuery('#perFormLayout');
        $perFormLayout.appendTo($workspace);
        $perFormLayout.fadeIn();
    }

    function loadData() {
        console.log('Build Data');
        var url = root_url + 'mindFlow/getActions';

        jQuery.ajax({
            type: 'post',
            url: url
        }).done(
            function (dataList) {
                var i, data;
                console.log('Data list received : ');
                console.log(dataList);

                var jsonObject = jQuery.parseJSON(dataList);

                for (i = 0; i < jsonObject.length; i++) {
                    var action = new Action(jsonObject[i]);
                    action.showOn(jQuery('#perFormContent'));
                }

                callback();
            }
        ).fail(
            function () {
                setInfoMessage(jQuery('#infoDisplayer'), 'error', 'Data could not be load. Try again later.', 50000);
            }
        );
    }
}

function Action(data) {
    var title;
    var date_creation;
    var date_todo;
    var time_from;
    var time_till;
    var done;
    var important;
    var urgent;

    /***********************************/
    /** Construct                     **/
    /***********************************/
    initialize();

    /***********************************/
    /** Public functions              **/
    /***********************************/
    this.showOn = function ($location) {
        console.log('Showing action on this location -> ');
        console.log($location);

        var actionHTML = '<div class="action">' + title + '</div>';
        $location.append(actionHTML);
    };

    this.toggleDone = function () {
        console.log('Toggling Done');
    };

    /***********************************/
    /** Private functions             **/
    /***********************************/

    function initialize() {
        console.log('Initializing Action');
        title = data.title;
        date_creation = data.date_creation;
        date_todo = data.date_todo;
        time_from = data.time_from;
        time_till = data.time_till;
        done = data.done;
        important = data.important;
        urgent = data.urgent;
    }
}