//upload image media query
function add_path(a,b,c){c("#"+a).click(function(a){a.preventDefault();var d=wp.media({title:"Select Image",
// mutiple: true if you want to upload multiple files at once
multiple:!1}).open().on("select",function(a){
// This will return the selected image from the Media Uploader, the result is an object
var e=d.state().get("selection").first();
// We convert uploaded_image to a JSON object to make accessing it easier
// Output to the console uploaded_image
console.log(e);var f=e.toJSON().url;
// Let's assign the url value to the input field
c("#"+b).val(f)})})}jQuery(document).ready(function(a){add_path("gold-upload-btn","sr_gold_image_option",a),add_path("silver-upload-btn","sr_silver_image_option",a)});