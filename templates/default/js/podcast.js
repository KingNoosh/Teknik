$(document).ready(function() {        
  $("#podcast_submit").click(function(){
      title=encodeURIComponent($("#podcast_title").val());
      post=encodeURIComponent($("#podcast_post").val());
      file=encodeURIComponent($("#podcast_file").val());
      $.ajax({
        type: "POST",
        url: "../../../publish_podcast.php",
        data: "title="+title+"&post="+post+"&file="+file,
        success: function(html)
        {
          if(html=='true')
          {
            window.location.reload();
          }
          else
          {
            $('#newPodcast').modal('hide');
            $("#top_msg").css('display', 'inline', 'important');
            $("#top_msg").html('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+html+'</div>');
          }
        }
      });
    return false;
  });
  
  $('#newPodcast').on('hide.bs.modal', function (e) {
    podFile=encodeURIComponent($("#podcast_file").val());
    if (podFile != '')
    {
      $.ajax({
        type: "POST",
        url: "../../../delete_podcast_file.php",
        data: "file="+podFile,
        success: function(html)
        {
          if(html)
          {
            $("#uploadedPodcasts").html('');
            $("#podcast_file").val('');
          }
        }
      });
    }
  });
  
  $('#editPodcast').on('show.bs.modal', function (e) {
    $("#edit_podcast_post").val("");
    $("#edit_podcast_title").val("");
    $("#edit_podcast_file").val("");
    $("#edit_uploadedPodcasts").val("");
    userID=encodeURIComponent($(e.relatedTarget).attr("id"));
    $("#edit_podcast_postid").val(userID);
    $.ajax({
      type: "POST",
      url: "../../../get_title_content.php",
      data: "id="+userID,
      success: function(html)
      {
        if(html)
        {
          $("#edit_podcast_title").val(html);
        }
      }
    });
    $.ajax({
      type: "POST",
      url: "../../../get_post_content.php",
      data: "id="+userID,
      success: function(html)
      {
        if(html)
        {
          $("#edit_podcast_post").val(html);
        }
      }
    });
    $.ajax({
      type: "POST",
      url: "../../../get_podcast_file.php",
      data: "id="+userID,
      success: function(html)
      {
        if(html)
        {
          var fileList = html.split(',');
          for (var i = 0; i < fileList.length; i++)
          {
            $("#edit_uploadedPodcasts").append('<div class="alert alert-success uploaded_file_'+fileList[i].replace(".", "_")+'"><button type="button" class="close podcast_file_delete" id="'+fileList[i]+'">&times;</button>'+fileList[i]+'</div>');
            linkEditFileDelete('.podcast_file_delete');
            if ($("#edit_podcast_file").val() == '')
            {
              $("#edit_podcast_file").val(fileList[i]);
            }
            else
            {
              $("#edit_podcast_file").val($("#edit_podcast_file").val()+','+fileList[i]);
            }
          }
        }
      }
    });
  });
  
  $('#editPodcast').on('hide.bs.modal', function (e) {
    userID=encodeURIComponent($("#edit_podcast_postid").val());
    $.ajax({
      type: "POST",
      url: "../../../get_podcast_file.php",
      data: "id="+userID,
      success: function(html)
      {
        if(html)
        {
          var fileList = $("#edit_podcast_file").val().split(',');
          var oldFileList = html.split(',');
          for (var i = 0; i < fileList.length; i++)
          {
            var index = oldFileList.indexOf(fileList[i]);
            if (index == -1)
            {
              $.ajax({
                type: "POST",
                url: "../../../delete_podcast_file.php",
                data: "file="+fileList[i],
                success: function(response)
                {
                  if(response)
                  {
                    $("#edit_uploadedPodcasts").html('');
                    $("#edit_podcast_file").val('');
                  }
                }
              });
            }
          }
        }
      }
    });
  });
  
  $("#edit_submit").click(function(){  
      postID=encodeURIComponent($("#edit_podcast_postid").val());
      title=encodeURIComponent($("#edit_podcast_title").val());
      post=encodeURIComponent($("#edit_podcast_post").val());
      file=encodeURIComponent($("#edit_podcast_file").val());
      $.ajax({
        type: "POST",
        url: "../../../edit_podcast.php",
        data: "postID="+postID+"&title="+title+"&post="+post+"&file="+file,
        success: function(html)
        {
          if(html=='true')
          {
            window.location.reload();
          }
          else
          {
            $('#editPodcast').modal('hide');
            $("#top_msg").css('display', 'inline', 'important');
            $("#top_msg").html('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+html+'</div>');
          }
        }
      });
    return false;
  });  
  
  $("#comment_submit").click(function(){  
      $('#newComment').modal('hide');
      postID=encodeURIComponent($("#post_id").val());
      post=encodeURIComponent($("#comment_post").val());
      $.ajax({
        type: "POST",
        url: "../../../includes/add_comment.php",
        data: "postID="+postID+"&service=podcast&comment="+post,
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
  
  $('#editComment').on('show.bs.modal', function (e) {
    $("#edit_comment_post").val("");
    commentID=encodeURIComponent($(e.relatedTarget).attr("id"));
    $("#edit_comment_postid").val(commentID);
    $.ajax({
      type: "POST",
      url: "../../../includes/get_comment_content.php",
      data: "id="+commentID,
      success: function(html)
      {
        if(html)
        {
          $("#edit_comment_post").val(html);
        }
      }
    });
  });
  
  $("#edit_comment_submit").click(function(){  
      $('#editComment').modal('hide');
      postID=encodeURIComponent($("#edit_comment_postid").val());
      post=encodeURIComponent($("#edit_comment_post").val());
      $.ajax({
        type: "POST",
        url: "../../../includes/edit_comment.php",
        data: "commentID="+postID+"&post="+post,
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

$(function() {
  $('#uploadPodcast').fileupload({
      dataType: 'json',
      url: '../../../upload_podcast.php',
      paramName: 'file',
      done: function (e, data) {
        $("#uploadedPodcasts").html('');
        if (!data.result.error)
        {
          podFile=$("#podcast_file").val();
          if (podFile != '')
          {
            $("#podcast_file").val(podFile+','+data.result.file.name);
          }
          else
          {
            $("#podcast_file").val(data.result.file.name);
          }
          $("#uploadedPodcasts").append('<div class="alert alert-success uploaded_file_'+data.result.file.name.replace(".", "_")+'"><button type="button" class="close podcast_file_delete" id="'+data.result.file.name+'">&times;</button>'+data.result.file.name+'</div>');
          linkFileDelete('.podcast_file_delete');
        }
        else
        {
          $("#top_msg").css('display', 'inline', 'important');
          $("#top_msg").html('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+data.result.error.message+'</div>');
        }
      }
  });
      
  $('#edit_uploadPodcast').fileupload({
      dataType: 'json',
      url: '../../../upload_podcast.php',
      paramName: 'file',
      done: function (e, data) {
        if (!data.result.error)
        {
          podFile=$("#edit_podcast_file").val();
          if (podFile != '')
          {
            $("#edit_podcast_file").val(podFile+','+data.result.file.name);
          }
          else
          {
            $("#edit_podcast_file").val(data.result.file.name);
          }
          $("#edit_uploadedPodcasts").append('<div class="alert alert-success uploaded_file_'+data.result.file.name.replace(".", "_")+'"><button type="button" class="close podcast_file_delete" id="'+data.result.file.name+'">&times;</button>'+data.result.file.name+'</div>');
          linkEditFileDelete('.podcast_file_delete');
        }
        else
        {
          $("#top_msg").css('display', 'inline', 'important');
          $("#top_msg").html('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+data.result.error.message+'</div>');
        }
      }
  });
});

function loadMorePosts(start, count)
{
  blog_id=encodeURIComponent($(".podcast-main").attr("id"));
  $.ajax({
    type: "POST",
    url: "../../../get_post.php",
    data: "userID="+blog_id+"&postCount="+count+"&startPost="+start,
    success: function(html)
    {
      if(html)
      {
        $(".podcast-main").append(html);
        linkPostDelete('.delete_post');
        linkAudioPlayer('audio');
        $(window).bind('scroll', bindScrollPosts);
      }
    }
  });
}

function loadMoreComments(start, count)
{
  post_id=encodeURIComponent($(".post-comments").attr("id"));
  $.ajax({
    type: "POST",
    url: "../../../includes/get_comment.php",
    data: "postID="+post_id+"&service=podcast&postCount="+count+"&startPost="+start,
    success: function(html)
    {
      if(html)
      {
        $(".post-comments").append(html);
        linkCommentDelete('.delete_comment');
        $(window).bind('scroll', bindScrollComments);
      }
    }
  });
}

function bindScrollPosts()
{
   if($(window).scrollTop() + $(window).height() > $(document).height() - 100)
   {
       $(window).unbind('scroll');
       loadMorePosts(start_post, posts);
       start_post = start_post + posts;
   }
}

function bindScrollComments()
{
   if($(window).scrollTop() + $(window).height() > $(document).height() - 100)
   {
       $(window).unbind('scroll');
       loadMoreComments(start_post, posts);
       start_post = start_post + posts;
   }
}

function linkPostDelete(selector)
{
  $(selector).click(function() {
    var object = $(this);
    post_id=encodeURIComponent(object.attr("id"));
    bootbox.confirm("Are you sure you want to delete your post?", function(result) {
      if (result)
      {
        $.ajax({
          type: "POST",
          url: "../../../delete_podcast.php",
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

function linkCommentDelete(selector)
{
  $(selector).click(function() {
    var object = $(this);
    post_id=encodeURIComponent(object.attr("id"));
    bootbox.confirm("Are you sure you want to delete your comment?", function(result) {
      if (result)
      {
        $.ajax({
          type: "POST",
          url: "../../../includes/delete_comment.php",
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

function linkFileDelete(selector)
{  
  $(selector).click(function(){
    var object = $(this);
    podFile=encodeURIComponent(object.attr('id'));
    allFiles=$("#podcast_file").val();
    $.ajax({
      type: "POST",
      url: "../../../delete_podcast_file.php",
      data: "file="+podFile,
      success: function(html)
      {
        var fileList = allFiles.split(',');
        var index = fileList.indexOf(podFile);
        if (index != -1)
        {
          fileList.splice(index, 1);
          $("#podcast_file").val(fileList.toString());
        }
        $(".uploaded_file_"+podFile.replace(".", "_")).remove();
      }
    });
    return false;
  });
}

function linkEditFileDelete(selector)
{  
  $(selector).click(function(){
    var object = $(this);
    podFile=encodeURIComponent(object.attr('id'));
    allFiles=$("#edit_podcast_file").val();
    $.ajax({
      type: "POST",
      url: "../../../delete_podcast_file.php",
      data: "file="+podFile,
      success: function(html)
      {
        var fileList = allFiles.split(',');
        var index = fileList.indexOf(podFile);
        if (index != -1)
        {
          fileList.splice(index, 1);
          $("#edit_podcast_file").val(fileList.toString());
        }
        $(".uploaded_file_"+podFile.replace(".", "_")).remove();
      }
    });
    return false;
  });
}

function linkAudioPlayer(selector)
{
  $(selector).audioPlayer(
  {
    classPrefix: 'audioplayer'
  });
}