(function($) {

    'use strict';

    var options = {
        selector: '[data-confirm]',
        message: 'data-confirm',
        event: 'click'
    };

    $(document).ready(function() {
        $('body').on(options.event, options.selector, function(e) {
            e.stopPropagation();
            var confirmed = confirm($(this).attr(options.message));
            if (!confirmed) e.preventDefault();
        });
    });

})(jQuery);