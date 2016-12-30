$(document).ready(
    (ev) => {
        var page = 2,
        morevimeo = () =>{
            $.ajax(
                {
                    url: '?limit=50&page='+page,
                    success: function(data) {
                        $('.content-centre').append(data);
                        page++;
                    }
                }
            );
        };
        $(window).on(
            'scroll', (ev) => {
                if (window.innerHeight + document.documentElement.scrollTop < document.documentElement.scrollHeight) {
                    return;
                }

                morevimeo();
            }
        );
    }
);
