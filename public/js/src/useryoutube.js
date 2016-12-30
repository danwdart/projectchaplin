$(document).ready(
    (ev) => {
        var pageToken = $('a[data-next-page-token]')
                .last()
                .data('next-page-token'),
            more = () => {
                if (pageToken)
                    $.ajax(
                        {
                        url: '?limit=50&pageToken='+pageToken,
                        success: function(data) {
                        $('.content-centre').append(data);
                        pageToken = $('a[data-next-page-token]')
                        .last()
                        .data('next-page-token')
                        }
                        }
                    );
        };
        $(window).on(
            'scroll', (ev) => {
                if (window.innerHeight + document.documentElement.scrollTop <document.documentElement.scrollHeight
                ) {
                return;
                }

                more();
            }
        );
    }
);
