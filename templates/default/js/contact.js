$(document).ready(function() {    
  $("#contact_submit").click(function(){
      var args = "";
      if ($("#contact_name").length && $("#contact_email").length)
      {
        name=encodeURIComponent($("#contact_name").val()); 
        email=encodeURIComponent($("#contact_email").val());
        subject=encodeURIComponent($("#contact_subject").val()); 
        message=encodeURIComponent($("#contact_message").val());
        arg = "name="+name+"&email="+email+"&subject="+subject+"&message="+message;
      }
      else
      {
        subject=encodeURIComponent($("#contact_subject").val()); 
        message=encodeURIComponent($("#contact_message").val());
        arg = "subject="+subject+"&message="+message;
      }
      $.ajax({
        type: "POST",
        url: "../../../includes/process_contact.php",
        data: arg,
        success: function(html)
        {
          if(html=='true')
          {
            $("#top_msg").css('display', 'inline', 'important');
            $("#top_msg").html('<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Your message has been sent.</div>');
          }
          else
          {
            $("#top_msg").css('display', 'inline', 'important');
            $("#top_msg").html('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+html+'</div>');
          }
        }
      });
    return false;
  });
});