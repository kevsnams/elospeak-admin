// dependencies: underscore-1.9.1, uikit-3.1.7, axios-0.19.0, moment-2.24.0

/**
 * Because fuck this shit. IDGAF!
 * (c) Kevin Namuag - but cared about copyrights? I'm a fucking hypocrite
 * 
 * Example Usage:
 * 
 * var cdv = new ClassroomDateView('#component', [slots...], {
 *      'source': {
 *          'type': 'xhr',
 *          'url': 'http://localhost/get-something'
 *          'params': {foo: 'bar'}
 *      }
 * });
 * 
 * @param {DOMObject|string} selector 
 * @param {array} timeslots
 * @param {Object} options 
 */
function ClassroomDateView(selector, timeslots, options) {
    /**
     * [START]
     * Private properties declaration
     * Must be prefixed with _ (underscore)
     */
    var _component = _.isElement(selector) ? selector : document.querySelector(selector);
    var _daysbar = document.createElement('div');
    var _sidebar = document.createElement('div');
    var _calendar = document.createElement('div');
    var _options = options;
    /**
     * [END]
     */

    var days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

    // Set the initial css classes
    _component.setAttribute('class', 'dv-main');
    _sidebar.setAttribute('class', 'dv-sidebar');
    _daysbar.setAttribute('class', 'dv-daysbar');
    _calendar.setAttribute('class', 'dv-calendar');

    var viewType = typeof _options.view === 'undefined' ? 'week' : _options.view;

    var now = moment();
    var start, end;
    /**
     * This ensures that viewType will always have the proper values
     * Defaults to 'week' or if viewType is not found, use 'week'
     */
    switch (viewType) {
        case 'today':
            viewType = 'today';
            start = moment().startOf('day');
            end = moment().endOf('day');
            break;
        case 'week':
            viewType = 'week';
            start = moment().startOf('isoWeek');
            end = moment().endOf('isoWeek');
            break;
        case 'month':
            viewType = 'month';
            break;
        default:
            viewType = 'week';
            break;
    }

    var drawSidebar = function() {
        // Create the timeslots on the sidebar
        var timeslotHTML = '';

        if (viewType == 'week') {
            _.each(timeslots, function (timeslot) {
                timeslotHTML += '<div class="dv-timeslot"><span class="dv-time">'+ timeslot[0] +' - '+ timeslot[1] +'</span></div>';
            });
        }

        // Other viewTypes here

        _sidebar.innerHTML = timeslotHTML;

        // Add _sidebar to _component
        _component.appendChild(_sidebar);
    };

    var prepareDrawViewWeek = function() {
        drawSidebar();

        // Create _daysbar elements
        var daysbarHTML = '', classesHTML = '';
        _.each(days, function (day) {
            // Header
            daysbarHTML += '<div class="dv-day">'+ day +'</div>';

            // Content
            classesHTML += '<div class="dv-classes dv-class-'+ day.toLowerCase() +'">CLASSROOMS</div>';
        });
        _daysbar.innerHTML = daysbarHTML;

        // _daysbar to _component
        _component.appendChild(_daysbar);

        // Set calendar's height to be the same with _sidebar. Then add to _component
        _calendar.style.height = _sidebar.offsetHeight +'px';
        _calendar.innerHTML = classesHTML;
        _component.appendChild(_calendar);
    };

    if (viewType == 'week') {
        prepareDrawViewWeek();
    }
    
    /**
     * @var {string|array} source
     * This can only be a URL or an array of values
     */
    var source = _options.source;
    var data = [];
}