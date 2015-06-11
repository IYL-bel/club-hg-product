
jQuery(document).ready(function($){

    $('#load_file_check').on('click', function(){
        $('#add_check_file_form_file').click();
    });



    $('#add_check_file_form_file').on('change', function(){
        uploadFile('add_check_file_form');
    });

    function uploadFile(nameForm){

        var timeStart = Date.now();
        var form = $('#' + nameForm);
        var errorMessage = $('#error-add-check_file');
        var errorText = $('#error-valid-add-check_file');
        var forWaiting = $('#for_waiting');

        var waiting = new Project.Base.WaitLoadFile();
        waiting.show(forWaiting);

        var fd = new FormData();
        fd.append('id', '123');
        fd.append('file', $('#add_check_file_form_file')[0].files[0]);

        $.ajax({
            type: 'POST',
            //contentType: form.attr('enctype'),
            url: form.attr('action'),
            data: fd,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(data){

                var tamePause = 0;
                var timeEnd = Date.now();
                var time = timeEnd - timeStart;
                if (time < 1500 ) {
                    tamePause = 1500 - time;
                }

                setTimeout(function() {
                    if (data.success) {
                        waiting.hide();
                        location.reload();
                    } else {
                        waiting.hide();
                        errorMessage.html( data.errorMessage );
                        errorText.html( data.errorValidMessage );
                    }
                }, tamePause );
            }
        });

    }
});
