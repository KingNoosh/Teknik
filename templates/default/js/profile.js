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
        var index = prev_split.indexOf(result);
        var num_keys = prev_split.length;
        if (index != -1)
        {
          key_used = true;
        }
        if (!key_used)
        {
          if (num_keys > 0)
          {
            $("#update_public_key").val(prev_keys + ',' + result);
          }
          else
          {
            $("#update_public_key").val(result);
          }
          var key_index = num_keys + 1;
          $("#public_key_list").append('<div class="public_key_'+key_index.toString()+'"><div class="input-group"><input type="text" class="form-control" id="public_key_input_'+key_index.toString()+'" value="'+result+'" readonly><span class="input-group-btn"><button class="btn btn-danger public_key_delete" type="button" id="'+key_index.toString()+'">Remove</button></span></div><br /></div>');
          linkKeyDelete('.public_key_delete');
        }
        else
        {
          bootbox.alert("That key is already in the list.", function() { });
        }
      }
    });
    return false;
  });
  
  $("#update_submit").click(function(){
      // Start Updating Animation
      $.isLoading({ text: "Working" });
      
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
            $.isLoading( "hide" );
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
    key_index=object.attr('id');
    key=$('#public_key_input_'+key_index.toString()).val();
    prev_keys = $("#update_public_key").val();
    var prev_split = prev_keys.split(",");
    var index = prev_split.indexOf(key);
    if (index != -1)
    {
      prev_split.splice(index, 1);
      $("#update_public_key").val(prev_split.toString());
    }
    $(".public_key_"+key_index.toString()).remove();
    return false;
  });
}