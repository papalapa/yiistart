/**
 * Initialize handlers on DomReady
 */
function onDomReady() {

    // Maxlength
    $('input[maxlength], textarea[maxlength]').maxlength({
        threshold:         9999,
        warningClass:      "label label-success",
        limitReachedClass: "label label-danger",
        separator:         ' из ',
        preText:           '',
        postText:          '',
        validate:          true
    });

    // Clipboards
    var clipboard = new Clipboard('[data-toggle="clipboard"]');
    clipboard.on('success', function (e) {
        var btn   = $(e.trigger);
        var title = btn.attr('title') || '';
        btn.removeAttr('title');
        $(e.trigger).tooltip({
            'placement': 'bottom',
            'title':     'Скопировано!'
        }).tooltip('show');
        $(e.trigger).on('mouseleave', function () {
            btn.tooltip('destroy');
            btn.attr('title', title);
        });
    });

    // Ladda
    $('a.btn-link, a.btn-default').attr({'data-spinner-color': '#333'});
    $('a.ladda-button').ladda('bind', {timeout: 2000});
    $('a.btn:not(.dropdown-toggle)').ladda('bind', {timeout: 0});

    Ladda.bind('.ladda-button');
}

/**
 * onDomReady
 */
$(function () {

    $(document).on('beforeValidate', 'form', function (event, messages, deferreds) {
        //console.log(event.type);
        var btn = $(this).find('[type=submit]');
        btn.ladda();
        btn.ladda('start');
    }).on('afterValidate', 'form', function (event, messages, errorAttributes) {
        //console.log(event.type);
        var btn = $(this).find('[type=submit]');
        btn.ladda('stop');
        if (errorAttributes.length) {
            toastr.error('Сначала исправьте ошибки формы!');
        }
    }).on('beforeSubmit', 'form', function (event) {
        //console.log(event.type);
        var btn = $(this).find('[type=submit]');
        btn.ladda('start');
    });

    // Tooltips
    $(document).tooltip({
        selector: '[data-toggle=tooltip]'
    });

    // Popovers
    $(document).popover({
        selector: '[data-toggle=popover]'
    });

    // Invoke all states after pjax
    $(document).on('pjax:complete', function () {
        onDomReady();
    });

    $('.pjax-spinner').on('pjax:send', function () {
        $(this).spin({
            lines:       11 // The number of lines to draw
            , length:    20 // The length of each line
            , width:     12 // The line thickness
            , radius:    25 // The radius of the inner circle
            , scale:     1 // Scales overall size of the spinner
            , corners:   1 // Corner roundness (0..1)
            , color:     '#000' // #rgb or #rrggbb or array of colors
            , opacity:   0.25 // Opacity of the lines
            , rotate:    0 // The rotation offset
            , direction: 1 // 1: clockwise, -1: counterclockwise
            , speed:     2 // Rounds per second
            , trail:     60 // Afterglow percentage
            , fps:       20 // Frames per second when using setTimeout() as a fallback for CSS
            , zIndex:    2e9 // The z-index (defaults to 2000000000)
            , className: 'spinner' // The CSS class to assign to the spinner
            , top:       '50%' // Top position relative to parent
            , left:      '50%' // Left position relative to parent
            , shadow:    false // Whether to render a shadow
            , hwaccel:   false // Whether to use hardware acceleration
            , position:  'absolute' // Element positioning
        });
    }).on('pjax:complete', function () {
        $(this).spin(false);
    });

    onDomReady();
});
