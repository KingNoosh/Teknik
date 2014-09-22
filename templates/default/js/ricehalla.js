$(document).ready(function() {    

  $('.view_image a img')
    .wrap('<div></div>')
    .css('display', 'block')
    .parent()
    .zoom();


  $(".vote_up").click(function () {
      var object = $(this);
      row_id=encodeURIComponent(object.attr("value"));
      points=encodeURIComponent(1);
      $.ajax({
        type: "POST",
        url: "../../../includes/vote.php",
        data: "id="+row_id+"&vote="+points+"&table=ricehalla",
        success: function(html)
        {
          var newPoints = parseInt(html);
          $("#points_"+row_id).html('('+newPoints+')');
          if ($("#vote_down_"+row_id).hasClass("btn-hover"))
          {
            object.removeClass("btn-hover");
          }
          $("#vote_down_"+row_id).addClass("btn-hover");
        }
      });
    return false;
  });
  
  $(".vote_down").click(function () {
      var object = $(this);
      row_id=encodeURIComponent(object.attr("value"));
      points=encodeURIComponent(-1);
      $.ajax({
        type: "POST",
        url: "../../../includes/vote.php",
        data: "id="+row_id+"&vote="+points+"&table=ricehalla",
        success: function(html)
        {
          var newPoints = parseInt(html);
          $("#points_"+row_id).html('('+newPoints+')');
          if ($("#vote_up_"+row_id).hasClass("btn-hover"))
          {
            object.removeClass("btn-hover");
          }
          $("#vote_up_"+row_id).addClass("btn-hover");
        }
      });
    return false;
  });
  
  $('.delete_image').click(function() {
    var object = $(this);
    desktop_id=encodeURIComponent(object.attr("id"));
    bootbox.confirm("Are you sure you want to delete your image?", function(result) {
      if (result)
      {
        $.ajax({
          type: "POST",
          url: "../../../delete_image.php",
          data: "id="+desktop_id,
          success: function(html)
          {
            if(html=='true')
            {
              window.location.reload();
              $("#top_msg").css('display', 'inline', 'important');
              $("#top_msg").html('<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Your image has been successfully deleted.</div>');
            }
            else
            {
              $("#top_msg").css('display', 'inline', 'important');
              $("#top_msg").html('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Unable to delete your image.  Please contact an Administrator.</div>');
            }
          }
        });
      }
    });
  });
  
  
  $('.filterable .btn-filter').click(function(){
        var $panel = $(this).parents('.filterable'),
        $filters = $panel.find('.filters input'),
        $body = $panel.find('.panel-body');
        if ($filters.prop('disabled') == true) {
            $filters.prop('disabled', false);
            $filters.first().focus();
        } else {
            $filters.val('').prop('disabled', true);
            $body.find('.no-result').remove();
            $body.find('div').show();
        }
  });

  $('.filterable .filters input').keyup(function(e){
      /* Ignore tab key */
      var code = e.keyCode || e.which;
      if (code == '9') return;
      /* Useful DOM data and selectors */
      var $input = $(this),
      inputContent = $input.val().toLowerCase(),
      $panel = $input.parents('.filterable'),
      column = $panel.find('.filters .filter-title').index($input.parents('.filter-title')),
      $table = $panel.find('.panel-body'),
      $rows = $table.find('.row');
      /* Dirtiest filter function ever ;) */
      var $filteredRows = $rows.filter(function(){
          var value = $(this).find('.filter-col').eq(column).text().toLowerCase();
          return value.indexOf(inputContent) === -1;
      });
      /* Clean previous no-result if exist */
      $table.find('.no-result').remove();
      /* Show all rows, hide filtered ones (never do that outside of a demo ! xD) */
      $rows.show();
      $filteredRows.hide();
      /* Prepend no-result row if all rows are filtered */
      if ($filteredRows.length === $rows.length) {
          $table.find('.panel-body').prepend($('<div class="row no-result text-center"><div class="col-md-10">No result found</div></div>'));
      }
  });
  
  $('button.modalClose').on('click', function(e) {
    $("#viewCreation img").attr({'src': '', 'alt': ''});
  });
  
  $('a.modalButton').on('click', function(e) {
    var img = $(this).attr('data-img');
    var url = $(this).attr('data-url');
    var user = $(this).attr('data-user');
    $("#viewCreation a").attr({'href':url});
    $("#viewCreation img").attr({'src':img,
                                   'alt': user+"'s Creation"});
    $("#viewCreation h4").html(user+"'s Creation");
  });
  
  $(".modal-wide").on("show.bs.modal", function() {
    var height = $(window).height() - 200;
    $(this).find(".modal-body").css("max-height", height);
  });
});

$(function() {
  
  var myUpload = $('#uploader').upload({
        name: 'file',
        action: '../../../includes/upload.php',
        enctype: 'multipart/form-data',
        params: {},
        autoSubmit: true,
        onSubmit: function() {
          $("#top_msg").css('display', 'inline', 'important');
          $("#top_msg").html('Uploading Image...<br />');
        },
        onComplete: function(filename) {
          obj = JSON.parse(filename);
          if (!obj.error)
          {
            file=encodeURIComponent(obj.results.file.name);
            $.ajax({
              type: "POST",
              url: "../../../add_image.php",
              data: "file="+file,
              success: function(html)
              {
                $("#top_msg").css('display', 'none', 'important');
                if(html=='true')
                {
                  $("#top_msg").css('display', 'inline', 'important');
                  $("#top_msg").html('<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Thank you for adding your Creation!</div>');
                }
                else
                {
                  $("#top_msg").css('display', 'inline', 'important');
                  $("#top_msg").html('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+html+'</div>');
                }
              }
            });
          }
          else
          {
            $("#top_msg").css('display', 'inline', 'important');
            $("#top_msg").html('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Unable to upload your Creation.</div>');
          }
        }
  });
});