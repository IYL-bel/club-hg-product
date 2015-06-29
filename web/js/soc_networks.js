/*!
 * Base JavaScript Document
 *
 * copyright 2015, Yury Istomenok <iyl@tut.by>
 */


function clickShare(type, urlAddScore) {

    if (type == 'vk' || type == 'ok') {

        $.ajax({
            type: "POST",
            url: urlAddScore,
            dataType: "json",
            success: function(data){
                if (data.success) {

                }
            }
        });
    }

    return false;
}
