/**
 * Created by ahmedeltaweel on 07/04/16.
 */

jQuery(function ($) {
    $('#sr-save-reward-form').submit(function (e) {
        e.preventDefault();
        $.post(sr_ajax_object.sr_ajaxurl, {
            action: 'sr_update_reward',
            data: $(this).serializeArray(),
        }, function (data) {
            if (data.success) {
                // successful request
                var returnedData = data.data;
                if (returnedData.length > 0) {
                    // handling the response of ajax and append new dates and time last update fields
                    for (var i = 0; i < returnedData.length; i++) {
                        $('#sr_last_action' + returnedData[i].sr_id).text( returnedData[i].sr_time );
                    }
                }
            } else {
                //unsuccessful request
                console.log(data);
                alert(data.data);
            }
        });
    });
});
