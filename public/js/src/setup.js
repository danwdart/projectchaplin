import $ from 'jquery';

$.fn.serializeObject = function(){
    var self = this,
        json = {},
        push_counters = {},
        patterns = {
            "validate": /^[a-zA-Z][a-zA-Z0-9_]*(?:\[(?:\d*|[a-zA-Z0-9_]+)\])*$/,
            "key":      /[a-zA-Z0-9_]+|(?=\[\])/g,
            "push":     /^$/,
            "fixed":    /^\d+$/,
            "named":    /^[a-zA-Z0-9_]+$/
        };


    this.build = function(base, key, value){
        base[key] = value;
        return base;
    };

    this.push_counter = function(key){
        if(push_counters[key] === undefined){
            push_counters[key] = 0;
        }
        return push_counters[key]++;
    };

    $.each($(this).serializeArray(), function(){

        // skip invalid keys
        if(!patterns.validate.test(this.name)){
            return;
        }

        var k,
            keys = this.name.match(patterns.key),
            merge = this.value,
            reverse_key = this.name;

        while((k = keys.pop()) !== undefined){

            // adjust reverse_key
            reverse_key = reverse_key.replace(new RegExp("\\[" + k + "\\]$"), '');

            // push
            if(k.match(patterns.push)){
                merge = self.build([], self.push_counter(reverse_key), merge);
            }

            // fixed
            else if(k.match(patterns.fixed)){
                merge = self.build([], k, merge);
            }

            // named
            else if(k.match(patterns.named)){
                merge = self.build({}, k, merge);
            }
        }

        json = $.extend(true, json, merge);
    });

    return json;
};

$('a.switcher').click((ev) => {
    ev.preventDefault();
    var self = ev.currentTarget;
    $('.widgetbody').hide();
    $($(self).attr('href')).show();
});

$('a.ajax').click((ev) => {
    ev.preventDefault();
    var self = ev.currentTarget;
    $.post(
    	$(self).attr('data-url'),
    	$('#'+$(self).attr('data-form')).serialize(),
    	data => $('#'+$(self).attr('data-output-element')).html(data)
    );
})

$('.commit').click((ev) => {
    ev.preventDefault();
    vhost = $('#vhost').serializeObject();

    $.post(
        '/admin/setup/write',
        {
          	locale: 'en_GB',
          	amqp: {
            		servers: {
              			read: $('#amqp').serializeObject(),
              			write: $('#amqp').serializeObject()
            		}
          	},
          	smtp: {
          		  server: $('#smtp').serializeObject()
          	},
          	dbtype: 'sql',
          	sql: $('#sqlserver').serializeObject(),
          	vhost: vhost.vhost,
          	ssl: vhost.ssl,
          	short: vhost.short,
                  youtube: $('#youtube').serializeObject(),
                  vimeo: $('#vimeo').serializeObject()
        },
        data => $('#commitresult').text(data)
    );
});
