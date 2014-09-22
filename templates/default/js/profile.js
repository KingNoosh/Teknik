$(document).ready(function() {      

  linkKeyDelete('.public_key_delete');
  
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
  
  $("#add_public_key").click(function() {
    bootbox.prompt("Enter your Public Key (including ssh-rsa)", function(result) {                
      if (result)
      {
        prev_keys = $("#update_public_key").val();
        var prev_split = prev_keys.split(",");
        var key_used = false;
        var index = prev_split.indexOf(podFile);
        if (index != -1)
        {
          key_used = true;
        }
        if (!key_used)
        {
          if (prev_split)
          {
            $("#update_public_key").val(prev_keys + ',' + result);
          }
          else
          {
            $("#update_public_key").val(result);
          }
          $("#public_key_list").append('<div class="row public_key_'+result+'"><input type="text" class="form-control" id="public_key_input_'+result+'" placeholder="'+result+'" readonly><span class="input-group-btn"><button class="btn btn-danger public_key_delete" type="button" id="'+result+'">Remove</button></span></div>');
          linkKeyDelete('.public_key_delete');
        }
        else
        {
          bootbox.alert("The key '"+result+"' is already added.", function() { });
        }
      }
    });
    return false;
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

function linkKeyDelete(selector)
{  
  $(selector).click(function(){
    var object = $(this);
    key=encodeURIComponent(object.attr('id'));    
    prev_keys = $("#update_public_key").val();
    var prev_split = prev_keys.split(",");    
    var index = prev_split.indexOf(key);
    if (index != -1)
    {
      prev_split.splice(index, 1);
      $("#update_public_key").val(prev_split.toString());
    }
    $(".public_key_"+key).remove();
    return false;
  });
}