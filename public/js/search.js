$(function() {
    $.extend({
        getUrlVars: function(){
            var vars = [], hash;
            var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
            for(var i = 0; i < hashes.length; i++)
            {
                hash = hashes[i].split('=');
                vars.push(hash[0]);
                vars[hash[0]] = hash[1];
            }
            return vars;
        },
        getUrlVar: function(name){
            return $.getUrlVars()[name];
        }
    });
    intSkip = 50;
	$(window).scroll(function () {
        if (0 < $(this).scrollTop() + $(this).innerHeight() - $('#content').innerHeight() - $('footer').height()) {
            $.ajax({
                url: '/search/youtube/?search='+$.getUrlVar('search')+'&limit=50&skip='+intSkip,
                success: function(data) {
                    $('#youtubevids').append(data);
                    intSkip += 50;
                }
            });
        }
    });
});