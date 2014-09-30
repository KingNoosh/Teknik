function update_user_list(search)
{
  $.ajax({
    type: "POST",
    url: "../../../admin/get_user.php",
    data: "query="+search,
    success: function(html)
    {
      $(".user_list").html(html);
      linkUserAddRole('.add_user_role');
      //linkUserRemoveRole('.remove_role');
      //linkUserDelete('.user_delete');
    }
  });
}

function linkUserAddRole(selector)
{
  $(selector).click(function() {
    var object = $(this);
    user_id=encodeURIComponent(object.attr("id"));
    var selectObj = $('#role_select_'+user_id);
    role=encodeURIComponent(selectObj.val());
    $.ajax({
      type: "POST",
      url: "../../../admin/add_user_role.php",
      data: "id="+user_id+"&role="+role,
      success: function(html)
      {
        if(!html)
        {          
          update_user_list($('#userSearch').val());
        }
        else
        {
          $("#top_msg").css('display', 'inline', 'important');
          $("#top_msg").html('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+html+'</div>');
        }
      }
    });
  });
}

function linkUserUpdate(selector)
{
  $(selector).click(function() {
    var object = $(this);
    post_id=encodeURIComponent(object.attr("id"));
    bootbox.confirm("Are you sure you want to delete your post?", function(result) {
      if (result)
      {
        $.ajax({
          type: "POST",
          url: "../../../delete_blog.php",
          data: "id="+post_id,
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
      }
    });
  });
}