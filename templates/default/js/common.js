$(document).ready(function() {    
  $('.selectpicker').selectpicker({
    style: 'btn-default'
  });
  
  $("#top_msg").css('display', 'none', 'important');
  
  $("#login_dropdown").click(function(){
    $("#top_msg").css('display', 'none', 'important');
    $("#top_msg").html('');
  });
  
  $("#login_submit").click(function(){    
      username=encodeURIComponent($("#login_username").val());
      password=encodeURIComponent($("#login_password").val());
      remember=encodeURIComponent($("#login_remember_me").prop('checked'));
      $.ajax({
        type: "POST",
        url: "../../../includes/process_login.php",
        data: "username="+username+"&password="+password+"&remember_me="+remember,
        success: function(html)
        {
          if(html=='true')
          {
            window.location.reload();
          }
          else
          {
            $("#top_msg").css('display', 'inline', 'important');
            $("#top_msg").html('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Try Again</div>');
          }
        }
      });
    return false;
  });
  
  $("#logout").click(function(){   
      $.ajax({
        type: "POST",
        url: "../../../includes/process_logout.php",
        success: function(html)
        {
          if(html=='true')
          {
            window.location.reload();
          }
          else
          {
            $("#top_msg").css('display', 'inline', 'important');
            $("#top_msg").html('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Unable to log out.</div>');
          }
        }
      });
    return false;
  });
  
  $("#reg_dropdown").click(function(){
    $("#top_msg").css('display', 'none', 'important');
    $("#top_msg").html('');
  });
  
  $("#reg_submit").click(function(){    
      username=encodeURIComponent($("#reg_username").val());
      password=encodeURIComponent($("#reg_password").val());
      password_confirm=encodeURIComponent($("#reg_password_confirm").val());
      $.ajax({
        type: "POST",
        url: "../../../includes/process_registration.php",
        data: "username="+username+"&password="+password+"&password_confirm="+password_confirm,
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
  // Setup drop down menu
  $('.dropdown-toggle').dropdown();
 
  $(".alert").alert();
  
  $("#blocker").hide();
  
  // Fix input element click problem
  $('.dropdown input, .dropdown label').click(function(e) {
    e.stopPropagation();
  });
});

function removeAmp(code)
{
    code = code.replace(/&amp;/g, '&');
    return code;
}