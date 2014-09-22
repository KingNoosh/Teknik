$(document).ready(function() {      
  $('#delete_account').click(function() {
    bootbox.confirm("Are you sure you want to delete your account?", function(result) 
    {
      if (result)
      {
        $.ajax({
          type: "POST",
          url: "../../../includes/delete_account.php",
          success: function(html)
          {
            if(html=='true')
            {
              window.location.href = "../../../";
              $("#top_msg").css('display', 'inline', 'important');
              $("#top_msg").html('<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Your account has been successfully deleted.</div>');
            }
            else
            {
              $("#top_msg").css('display', 'inline', 'important');
              $("#top_msg").html('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Unable to delete your account.  Please contact an Administrator.</div>');
            }
          }
        });
      }
    });
  });
  
  $("#update_submit").click(function(){  
      current_password=encodeURIComponent($("#update_password_current").val());
      password=encodeURIComponent($("#update_password").val());
      password_confirm=encodeURIComponent($("#update_password_confirm").val());
      theme=encodeURIComponent($("#update_theme").val());
      public_key=encodeURIComponent($("#update_public_key").val());
      website=encodeURIComponent($("#update_website").val());
      minecraft=encodeURIComponent($("#update_minecraft").val());
      quote=encodeURIComponent($("#update_quote").val());
      about=encodeURIComponent($("#update_about").val());
      blog_title=encodeURIComponent($("#update_blog_title").val());
      blog_desc=encodeURIComponent($("#update_blog_description").val());
      $.ajax({
        type: "POST",
        url: "../../../includes/update_profile.php",
        data: "current_password="+current_password+"&password="+password+"&password_confirm="+password_confirm+"&theme="+theme+"&public_key="+public_key+"&website="+website+"&minecraft="+minecraft+"&quote="+quote+"&about="+about+"&blog_title="+blog_title+"&blog_desc="+blog_desc,
        success: function(html)
        {
          if(html=='true')
          {
            window.location.reload();
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