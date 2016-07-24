/**
 * Created by ahmedeltaweel on 07/04/16.
 */
jQuery(function(a){a("#sr-save-reward-form").submit(function(b){b.preventDefault(),a.post(sr_ajax_object.sr_ajaxurl,{action:"sr_update_reward",data:a(this).serializeArray()},function(b){if(b.success){
// successful request
var c=b.data;if(c.length>0)
// handling the response of ajax and append new dates and time last update fields
for(var d=0;d<c.length;d++)a("#sr_last_action"+c[d].sr_id).text(c[d].sr_time)}else
//unsuccessful request
console.log(b),alert(b.data)})})});