$(function(){

    var ul = $('#upload ul');

    $('#drop a').click(function(){
        // Simulate a click on the file input button
        // to show the file browser dialog
        $(this).parent().find('input').click();
    });

    // Initialize the jQuery File Upload plugin
    $('#upload').fileupload({

        // This element will accept file drag/drop uploading
        dropZone: $('#drop'),

        // This function is called when a file is added to the queue;
        // either via the browse button, or via drag/drop:
        add: function (e, data) {

            var tpl = $('<li class="working"><input class="knob" type="text" value="0" data-width="48" data-height="48"'+
                ' data-fgColor="#0788a5" data-readOnly="1" data-bgColor="#3e4043" /><p></p>'+
		'<div class="control">Controller: <input type="text" class="link" value="control" readonly="readonly" /><a class="go" target="_blank">Go</a></div>'+
		'<div class="follow">Follower: <input type="text" class="link" value="follow" readonly="readonly" /><a class="go" target="_blank">Go</a></div>'+
		'<div class="email"><a class="mail">Email Links</a></div><span></span></li>');

            // Append the file name and file size
            tpl.find('p').text(data.files[0].name)
                         .append('<i>' + formatFileSize(data.files[0].size) + '</i>');

            // Add the HTML to the UL element
            data.context = tpl.appendTo(ul);

            // Initialize the knob plugin
            tpl.find('input.knob').knob();

            // Listen for clicks on the cancel icon
            tpl.find('span').click(function(){

                if(tpl.hasClass('working')){
                    jqXHR.abort();
                }

                tpl.fadeOut(function(){
                    tpl.remove();
                });

            });

            // Automatically upload the file once it is added to the queue
            var jqXHR = data.submit();
        },

        progress: function(e, data){

            // Calculate the completion percentage of the upload
            var progress = parseInt(data.loaded / data.total * 100, 10);

            // Update the hidden input field and trigger a change
            // so that the jQuery knob plugin knows to update the dial
            data.context.find('input').val(progress).change();

            if(progress == 100){
                data.context.removeClass('working');
            }
        },

	done: function(e,data){
		var results = JSON.parse(data.result);
		//alert(results.control);
		$("li").last().find("div.control").find("input.link").val("http://glunami.com/viewer/view.php?id="+results.control);
		$("li").last().find("div.control").find("a.go").attr('href',"http://glunami.com/viewer/view.php?id="+results.control);
		$("li").last().find("div.follow").find("input.link").val("http://glunami.com/viewer/view.php?id="+results.follow);
		$("li").last().find("div.follow").find("a.go").attr('href',"http://glunami.com/viewer/view.php?id="+results.follow);
		$("li").last().find("div.email").find("a.mail").attr('href',"mailto:?subject=GlunamiView Links&body=Controller: http://glunami.com/viewer/view.php?id="+results.control+"%0D%0AFollower: http://glunami.com/viewer/view.php?id="+results.follow);
	},

        fail:function(e, data){
            // Something has gone wrong!
            data.context.addClass('error');
        }

    });


    // Prevent the default action when a file is dropped on the window
    $(document).on('drop dragover', function (e) {
        e.preventDefault();
    });

    // Helper function that formats the file sizes
    function formatFileSize(bytes) {
        if (typeof bytes !== 'number') {
            return '';
        }

        if (bytes >= 1000000000) {
            return (bytes / 1000000000).toFixed(2) + ' GB';
        }

        if (bytes >= 1000000) {
            return (bytes / 1000000).toFixed(2) + ' MB';
        }

        return (bytes / 1000).toFixed(2) + ' KB';
    }

});
