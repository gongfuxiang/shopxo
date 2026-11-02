(function ($) {
    $.fn.hiwprint = function (options) {
        var usedFrame = document.getElementById('hiwprint_iframe');
        if (usedFrame) usedFrame.parentNode.removeChild(usedFrame);
        var opt = $.extend({}, $.fn.hiwprint.defaults, options);
        var $element = this;
        var $iframe = $('<iframe id="hiwprint_iframe"  style="visibility: hidden; height: 0; width: 0; position: absolute;"></iframe>');
        var css = '';
        if (opt.importCss) {
            if ($("link[media=print]").length > 0) {
                $("link[media=print]").each(function () {
                    css += '<link rel="stylesheet" type="text/css" media="print" href="' + $(this).attr("href") + '">';
                });
            }
            else {
                $("link").each(function () {
                    css += '<link rel="stylesheet" type="text/css" media="print" href="' + $(this).attr("href") + '">';
                });
            }
        }
        $iframe[0].srcdoc = '<!DOCTYPE html><html><head><title></title><meta charset="UTF-8">' + css + '</head><body></body></html>';

        $iframe[0].onload = function () {
            var printDocument = $iframe[0].contentWindow || $iframe[0].contentDocument;
            if (printDocument.document) printDocument = printDocument.document;
            if (!$iframe.attr('srcdoc')) {
                printDocument.write('<!DOCTYPE html><html><head><title></title><meta charset="UTF-8">' + css + '</head><body></body></html>');
            }
            if (opt.printContainer) {
                printDocument.body.innerHTML = $element[0].outerHTML;
            }
            else {
                printDocument.body.innerHTML = $element.html();
            }
            loadAllImages(printDocument, function () {

                performPrint($iframe[0]);
            });

        };

        $iframe.appendTo("body");

    };

    $.fn.hiwprint.defaults = {
        importCss: true,
        printContainer: true
    };

    function performPrint(iframeElement) {
        try {
            iframeElement.focus();
            if (isEdge() || isIE()) {
                try {
                    iframeElement.contentWindow.document.execCommand('print', false, null);
                } catch (e) {
                    iframeElement.contentWindow.print();
                }
            } else {
                // Other browsers
                iframeElement.contentWindow.print();
            }
        } catch (error) {
            console.log(error);
        }
    }


    function isIE() {
        return navigator.userAgent.indexOf('MSIE') !== -1 || !!document.documentMode;
    }

    // Edge 20+
    function isEdge() {
        return !isIE() && !!window.StyleMedia;
    }



    function loadAllImages(printDocument, callback, time) {
        
        if (time === undefined) {
            time = 0;
        }
        var images = printDocument.getElementsByTagName('img');
        var allLoaded = true;
        for (var i = 0; i < images.length; i++) {
            var image = images[i];
            if (image.src && image.src !== window.location.href && image.src.indexOf('base64') == -1) {
               
                if (!image || typeof image.naturalWidth === 'undefined' || image.naturalWidth === 0 || !image.complete) {
                    if (!image.complete) {
                        allLoaded = false;
                    }
                    
                }
            }
        }
        time++;
        if (!allLoaded && time < 10) {
            
            setTimeout(function () {
               loadAllImages(printDocument, callback, time);
            }, 500);
        } else {
            callback();
        }
    }


})(jQuery);