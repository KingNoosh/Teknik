$(document).ready(function() {    
  $("#upload-links").css('display', 'none', 'important');
  $("#upload-links").html('');  
});

function linkUploadDelete(selector)
{
  $(selector).click(function() {
      ID=encodeURIComponent($(this).attr('id'));
      $.ajax({
        type: "POST",
        url: "../../generate_delete_link.php",
        data: "uploadID="+ID,
        success: function(html)
        {
          obj = JSON.parse(html);
          if (!obj.error)
          {
            bootbox.dialog({
              title: "Direct Deletion URL",
              message: '<input type="text" class="form-control" id="deletionLink" onClick="this.select();" value="'+obj.result.url+'">'
            });

          }
          else
          {
            $("#top_msg").css('display', 'inline', 'important');
            $("#top_msg").html('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+obj.error.message+'</div>');
          }
        }
      });
    return false;
  });
}