<div class="container">
  <div class="row text-center">
    <form action="../includes/upload.php" class="dropzone" id="TeknikUpload" name="TeknikUpload">
      <div class="dz-message text-center" id="upload_message">
        <div class="row">
          <div class="col-sm-12">
            <h1>Drop your files here</h1>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <h2>Or just click here</h2>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <h3>Your Choice</h3>
          </div>
        </div>
      </div>
      <div class="fallback text-center">
        <div class="row">
          <div class="col-sm-12">
            <input name="file" type="file" class="form-control" multiple />
          </div>
        </div>
      </div>
    </form>
  </div>
  <br />
  <div class="progress">
    <div class="progress-bar progress-bar-success" id="progressBar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 0%">0%</div>
  </div>
  <div class="container" id="upload-links">
  </div>
  <br />
  <div class="well text-center">Each file is encrypted on upload using an AES-256-CBC cipher.  If you wish to view the file decrypted, you must use the direct Teknik link.</div>
  <div class="text-center">
    Useful Tools: <a href="<?php echo get_page_url('git', $CONF); ?>/Tools.git/blob/master/Upload/upload.sh">Bash Upload Script</a> | <a href="https://github.com/jschx/poomf">Poomf Uploader</a>
    <br />
    <br />
    You can now upload your screenshots automatically using <a href="https://github.com/KittyKatt/screenFetch">Screenfetch</a>!
  </div>
</div>
<script>
Dropzone.options.TeknikUpload = {
  paramName: "file", // The name that will be used to transfer the file
  maxFilesize: <?php echo $CONF['max_upload_size']; ?>, // MB
  addRemoveLinks: true,
  clickable: true,
  init: function() {
    this.on("addedfile", function(file, responseText) {
      $("#upload_message").css('display', 'none', 'important');
    });
    this.on("success", function(file, responseText) {
      obj = JSON.parse(responseText);
      var name = obj.results.file.name;
      var short_name = file.name.split(".")[0].hashCode();
      $("#upload-links").css('display', 'inline', 'important');
      $("#upload-links").prepend(' \
        <div class="row link_'+short_name+'"> \
          <div class="col-sm-6"> \
            '+file.name+' \
          </div> \
          <div class="col-sm-3"> \
            <a href="<?php echo get_page_url('u', $CONF); ?>/'+name+'" target="_blank" class="alert-link"><?php echo get_page_url('u', $CONF); ?>/'+name+'</a> \
          </div> \
          <div class="col-sm-3"> \
            <button type="button" class="btn btn-default btn-xs generate-delete-link-'+short_name+'" id="'+name+'">Generate Deletion URL</button> \
          </div> \
        </div> \
      ');
      linkUploadDelete('.generate-delete-link-'+short_name+'');
    });
    this.on("removedfile", function(file) {
      var name = file.name.split(".")[0].hashCode();
      $('.link_'+name).remove();
    });
    this.on("reset", function(file, responseText) {
      $("#upload_message").css('display', 'inline', 'important');
      $(".progress").children('.progress-bar').css('width', '0%');
      $(".progress").children('.progress-bar').html('0%');
    });
    this.on("error", function(file, errorMessage) {
      this.removeFile(file);
      $("#top_msg").css('display', 'inline', 'important');
      $("#top_msg").html('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+errorMessage+'</div>');
    });
    this.on("totaluploadprogress", function(progress, totalBytes, totalBytesSent) {
      $(".progress").children('.progress-bar').css('width', progress.toFixed(2)+'%');
      if (progress != 100)
      {
        $(".progress").children('.progress-bar').html(progress.toFixed(2)+'%');
      }
      else
      {
        $(".progress").children('.progress-bar').html('Encrypting');
      }
    });
    this.on("queuecomplete", function() {
      $(".progress").children('.progress-bar').html('Complete');      
    });
  }
};
</script>
