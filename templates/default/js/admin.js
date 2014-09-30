function update_user_list(search)
{
  blog_id=encodeURIComponent($(".blog-main").attr("id"));
  $.ajax({
    type: "POST",
    url: "../../../get_user.php",
    data: "query="+search,
    success: function(html)
    {
      if(html)
      {
        $(".user_list").append(obj.result);
        linkUserUpdate('.user_update');
      }
    }
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