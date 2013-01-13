$(function() {
    $('a.ajax').click(function(e) {
        e.preventDefault();
        el = $(this);
        $.ajax({
            url: $(this).attr('href'),
            method: 'GET',
            success: function() {
                el.after(el.html());
                el.hide();
            }
        });
        console.log('tried');
        return false;
    });
    $('form.ajax input[type="submit"]').click(function(e) {
        e.preventDefault();
        parent = $('form.ajax');
        $.ajax({
            url: parent.attr('action'),
            type: parent.attr('method'),
            data: parent.serialize(),
            success: function() {
                parent.append('<span class="success" style="color:green;">Comment posted... </span>');
                parent.children('.success').fadeOut(3000, function() {$(this).remove();});
            },
            failure: function() {
                parent.append('<span class="failure" style="color:red;">Sorry, we couldn\'t post your comment.</span>');
            }
        });
        parentrel = parent.attr('rel');
        if (null != parentrel) {
            el = $('#'+parentrel);
            elrel = el.attr('rel');
            if (null != elrel) {
                $.ajax({
                    url: elrel,
                    type: 'GET',
                    success: function(data) {
                        el.html(data);
                    },
                    failure: function() {
                        console.log('failed to update');
                    }
                });
            }
        }
        return false;
    });
});

function addfullscreen(elem) {
    changefullscreen = function() {
        if(document.mozFullScreen || document.webkitIsFullScreen) {
        console.log('go nfs');
            if (elem.cancelFullScreen) {
            elem.cancelFullScreen();
            } else if (elem.mozCancelFullScreen) {
            elem.mozCancelFullScreen();
            } else if (elem.webkitCancelFullScreen) {
            elem.webkitCancelFullScreen();
            }
        } else {
        console.log('go fs');
            if (elem.requestFullScreen) {
            elem.requestFullScreen();
            } else if (elem.mozRequestFullScreen) {
            elem.mozRequestFullScreen();
            } else if (elem.webkitRequestFullScreen) {
            elem.webkitRequestFullScreen();
            }
        }
    };
    changefullscreenauto = function() {
        if(document.mozFullScreen || document.webkitIsFullScreen) {
            elem.oldwidth = elem.style.width;
            elem.oldheight = elem.style.height;
            elem.style.maxWidth = '100%';
            elem.style.maxHeight = '100%';
            elem.style.height = '100%';
            elem.style.width = '100%';
        } else {
            elem.style.maxWidth = '640px';
            elem.style.maxHeight = '480px';
            elem.style.height = elem.oldheight;
            elem.style.width = elem.oldwidth;
        }
    };
    elem.addEventListener('dblclick', changefullscreen);
    elem.addEventListener('mozfullscreenchange', changefullscreenauto);
    elem.addEventListener('webkitfullscreenchange', changefullscreenauto);
    elem.addEventListener('fullscreenchange', changefullscreenauto);
}

function enableclicktoplay(elem) {
    elem.addEventListener('click', function() {
        if(this.paused) {
            this.play();
        } else {
            this.pause();
        }
    });
}

$('.fullscreen').each(function(idx, elem) {
    addfullscreen(elem);
});
$('.clicktoplay').each(function(idx, elem) {
    enableclicktoplay(elem);
});