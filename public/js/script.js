$(document).ready(function() {
    $('form.upload').submit(function() {
        $(window).bind('beforeunload', function(){ return 'Are you sure you want to leave? Files are currently uploading.'; });
        elFiles = document.getElementById('Files-0');
        //elFiles.addEventListener('change', function(e) {
        
        bar = document.createElement('div');
        bar.style.width = '400px';
        bar.style.height = '15px';
        bar.style.border ="1px solid black";
        bar.style.display='block';
        bar.style.position='relative';
        progressbar = document.createElement('div');
        progressbar.style.height="100%";
        progressbar.style.width="0%";
        progressbar.style.backgroundColor = "blue";
        bar.style.display='block';
        bar.style.position='relative';
        bar.appendChild(progressbar);
        elFiles.parentNode.insertBefore(bar, elFiles.nextSibling);
        
        xhr = new XMLHttpRequest();
        
        xhr.addEventListener('progress', function(e) {
                var done = e.position || e.loaded,
                    total = e.totalSize || e.total;
                //console.log('xhr progress: '+ (Math.floor(done/total*1000)/10) + '%');
                progressbar.style.width = (Math.floor(done/total*1000)/10) + '%';
            }, false);
            
        if (xhr.upload) {
            xhr.upload.onprogress = function(e) {
                var done = e.position || e.loaded,
                    total = e.totalSize || e.total;
                //console.log('xhr.upload progress: ' + done + ' / ' + total + ' = ' + (Math.floor(done/total*1000)/10) + '%');
                progressbar.style.width = (Math.floor(done/total*1000)/10) + '%';
            };
        }
        xhr.onreadystatechange = function(e) {
            if ( 4 == this.readyState ) {
                $(window).bind('beforeunload', function(){ return null; });
                // Check here what the status was
                window.location = '/video/name';
            }
        }
        
        formdata = new FormData();
        files = document.getElementById('Files-0').files;
        for(i=0;i<files.length; i++) {
            formdata.append('Files['+i+']', document.getElementById('Files-0').files[i]);
        }
        xhr.open("POST", "/video/upload", true);
        xhr.send(formdata);
        return false;
    });
});
