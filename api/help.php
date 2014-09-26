<div class="row api">
  <h2><b>Teknik API</b></h3>
    <hr>
    <h3>Overview</h4>
      <p>
        The Teknik API is free for everyone to use, and is defined on a per service basis.
        <br />
        <br />
        The general API calls can be summarized as follows: <code>https://api.teknik.io/<b>Service</b>/<b>Action</b></code>
      </p>
      <h4>Responses</h4>
        <p>
          All responses are returned as json.  The returned json can contain any of the following sections.
          <br />
          <br />
          <strong>Results</strong>
          <pre><code>{"results":{"&lt;result_type&gt;":{"&lt;result_data&gt;":"&lt;value&gt;"}}}</code></pre>
          <strong>Errors</strong>
          <pre><code>{"error":{"code":&lt;value&gt;, "message":"&lt;error_message&gt;"}}</code></pre>
        </p>
  <h3><b>Paste</b></h3>
    <hr>
    <p>This is a description of the API commands available for the Paste service.</p>
    <h3>Submit a Paste</h3>
      <pre><code>POST https://api.teknik.io/paste</code></pre>
      <h4>Parameters</h4>
        <table>
          <thead>
            <tr>
              <th>Name</th>
              <th>Type</th>
              <th>Default</th>
              <th>Description</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <code>code</code>
              </td>
              <td>
                <code>string</code>
              </td>
              <td>
                <var>NULL</var>
              </td>
              <td>
                <strong>Required</strong>
                The text that will be submitted as the paste content.
              </td>
            </tr>
            <tr>
              <td>
                <code>title</code>
              </td>
              <td>
                <code>string</code>
              </td>
              <td>
                <var>NULL</var>
              </td>
              <td>
                The title for the paste.
              </td>
            </tr>
            <tr>
              <td>
                <code>expiry</code>
              </td>
              <td>
                <code>string</code>
              </td>
              <td>
                <var>never</var>
              </td>
              <td>
                The expiration for the paste.  Must be either <code>d</code> (1 Day) or <code>m</code> (1 Month)
              </td>
            </tr>
            <tr>
              <td>
                <code>format</code>
              </td>
              <td>
                <code>string</code>
              </td>
              <td>
                text
              </td>
              <td>
                The format of the paste.
                <br />
                This can be one of the following:
                <select name="format" class="selectpicker">		
                 <optgroup label="Popular Formats">
                  <?php // Show popular GeSHi formats
                    foreach ($CONF['geshiformats'] as $code=>$name)
                    {
                      if (in_array($code, $CONF['popular_formats']))
                      {
                        echo '<option value="' . $code . '">' . $code . '</option>';
                      }
                    }
                    
                    echo '</optgroup><optgroup label="All Formats">';

                    // Show all GeSHi formats.
                    foreach ($CONF['geshiformats'] as $code=>$name)
                    {
                      echo '<option value="' . $code . '">' . $code . '</option>';
                    }
                  ?>
                  </optgroup>
                </select>
              </td>
            </tr>
            <tr>
              <td>
                <code>password</code>
              </td>
              <td>
                <code>string</code>
              </td>
              <td>
                <var>EMPTY</var>
              </td>
              <td>
                Specify a password to lock the paste with.
              </td>
            </tr>
          </tbody>
        </table>
      <h4>Response</h4>
        <pre><code>{"results":{"paste":{"id":<var>id_num</var> "url":"<var>url</var>", "title":"<var>paste_title</var>", "format":"<var>text</var>", "expiration":"<var>date</var>", "password":"<var>password</var>"}}}</code></pre>
        <table>
          <thead>
            <tr>
              <th>Name</th>
              <th>Type</th>
              <th>Description</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <code>id</code>
              </td>
              <td>
                <code>integer</code>
              </td>
              <td>
                The id of the paste.
              </td>
            </tr>
            <tr>
              <td>
                <code>url</code>
              </td>
              <td>
                <code>string</code>
              </td>
              <td>
                The direct url to the paste.
              </td>
            </tr>
            <tr>
              <td>
                <code>title</code>
              </td>
              <td>
                <code>string</code>
              </td>
              <td>
                The title of the paste.
              </td>
            </tr>
            <tr>
              <td>
                <code>format</code>
              </td>
              <td>
                <code>string</code>
              </td>
              <td>
                The format of the pasted code.
              </td>
            </tr>
            <tr>
              <td>
                <code>expiration</code>
              </td>
              <td>
                <code>string</code>
              </td>
              <td>
                The date of expiration of the paste.
              </td>
            </tr>
            <tr>
              <td>
                <code>password</code>
              </td>
              <td>
                <code>string</code>
              </td>
              <td>
                The password of the paste.
              </td>
            </tr>
          </tbody>
        </table>
      <h4>Example</h4>
        <pre><code>$ curl --data "title=Paste%20Title&format=text&expiration=d" --data-urlencode "code=This%20is%20my%20test%20code." https://api.teknik.io/paste</code></pre>
  <h3><b>Ricehalla</b></h3>
    <hr>
    <p>This is a description of the API commands available for the Ricehalla service.</p>
    <h3>Get Submitted Images</h3>
      <pre><code>POST https://api.teknik.io/ricehalla/get</code></pre>
      <h4>Parameters</h4>
        <table>
          <thead>
            <tr>
              <th>Name</th>
              <th>Type</th>
              <th>Default</th>
              <th>Description</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <code>id</code>
              </td>
              <td>
                <code>integer</code>
              </td>
              <td>
                <var>NULL</var>
              </td>
              <td>
                Get a submitted image based on the images id.
              </td>
            </tr>
            <tr>
              <td>
                <code>owner</code>
              </td>
              <td>
                <code>string</code>
              </td>
              <td>
                <var>NULL</var>
              </td>
              <td>
                Get a submitted image based on the owner.
              </td>
            </tr>
            <tr>
              <td>
                <code>limit</code>
              </td>
              <td>
                <code>integer</code>
              </td>
              <td>
                <var>all</var>
              </td>
              <td>
                The number of submissions you want.
              </td>
            </tr>
            <tr>
              <td>
                <code>order</code>
              </td>
              <td>
                <code>string</code>
              </td>
              <td>
                DESC
              </td>
              <td>
                The order of the results.  Choose between <code>DESC</code> and <code>ASC</code>.
              </td>
            </tr>
            <tr>
              <td>
                <code>order_by</code>
              </td>
              <td>
                <code>string</code>
              </td>
              <td>
                votes
              </td>
              <td>
                The property of the image that you want the results sorted by.
                <br />
                It can be one of the following:
                <select name="order_by" class="selectpicker">
                  <option value="id">id</option>
                  <option value="owner">owner</option>
                  <option value="votes">votes</option>
                  <option value="date">date</option>
                </select>
              </td>
            </tr>
          </tbody>
        </table>
      <h4>Response</h4>
        <pre><code>{"results":{"image":{"id":<var>id_num</var>, "url":"<var>url</var>", "image_src":"<var>url</var>", "owner":"<var>name</var>", "date_posted":"<var>date</var>", "tags":["<var>tag</var>", ..., "<var>tag</var>"]}}}</code></pre>
        <table>
          <thead>
            <tr>
              <th>Name</th>
              <th>Type</th>
              <th>Description</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <code>id</code>
              </td>
              <td>
                <code>integer</code>
              </td>
              <td>
                The id of the submitted image.
              </td>
            </tr>
            <tr>
              <td>
                <code>url</code>
              </td>
              <td>
                <code>string</code>
              </td>
              <td>
                The url to the submitted image.
              </td>
            </tr>
            <tr>
              <td>
                <code>image_src</code>
              </td>
              <td>
                <code>string</code>
              </td>
              <td>
                The direct url to the image source.
              </td>
            </tr>
            <tr>
              <td>
                <code>votes</code>
              </td>
              <td>
                <code>integer</code>
              </td>
              <td>
                The total points for the submitted image.
              </td>
            </tr>
            <tr>
              <td>
                <code>owner</code>
              </td>
              <td>
                <code>string</code>
              </td>
              <td>
                The owner for the submitted image.
              </td>
            </tr>
            <tr>
              <td>
                <code>date_posted</code>
              </td>
              <td>
                <code>string</code>
              </td>
              <td>
                The date the image was submitted.
              </td>
            </tr>
            <tr>
              <td>
                <code>tags</code>
              </td>
              <td>
                <code>array</code>
              </td>
              <td>
                The tags for the submitted image.
              </td>
            </tr>
          </tbody>
        </table>
      <h4>Example</h4>
        <pre><code>$ curl -d "limit=10&order=ASC&order_by=date" https://api.teknik.io/ricehalla/get</code></pre>
    <h3>Submit an Image</h3>
      <pre><code>POST https://api.teknik.io/ricehalla/post</code></pre>
      <h4>Parameters</h4>
        <table>
          <thead>
            <tr>
              <th>Name</th>
              <th>Type</th>
              <th>Default</th>
              <th>Description</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <code>username</code>
              </td>
              <td>
                <code>string</code>
              </td>
              <td>
                <var>NULL</var>
              </td>
              <td>
                <strong>Required</strong>
                Your Teknik username.
              </td>
            </tr>
            <tr>
              <td>
                <code>password</code>
              </td>
              <td>
                <code>string</code>
              </td>
              <td>
                <var>NULL</var>
              </td>
              <td>
                <strong>Required</strong>
                The password for your username.
              </td>
            </tr>
            <tr>
              <td>
                <code>file</code>
              </td>
              <td>
                <code>file</code>
              </td>
              <td>
                <var>NULL</var>
              </td>
              <td>
                <strong>Required</strong>
                The image file you want to submit.
              </td>
            </tr>
          </tbody>
        </table>
      <h4>Response</h4>
        <pre><code>{"results":{"image":{"id":<var>id_num</var>, "url":"<var>url</var>", "image_src":"<var>url</var>", "owner":"<var>name</var>", "date_posted":"<var>date</var>", "tags":["<var>tag</var>", ..., "<var>tag</var>"]}}}</code></pre>
        <table>
          <thead>
            <tr>
              <th>Name</th>
              <th>Type</th>
              <th>Description</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <code>id</code>
              </td>
              <td>
                <code>integer</code>
              </td>
              <td>
                The id of the submitted image.
              </td>
            </tr>
            <tr>
              <td>
                <code>url</code>
              </td>
              <td>
                <code>string</code>
              </td>
              <td>
                The url to the submitted image.
              </td>
            </tr>
            <tr>
              <td>
                <code>image_src</code>
              </td>
              <td>
                <code>string</code>
              </td>
              <td>
                The direct url to the image source.
              </td>
            </tr>
            <tr>
              <td>
                <code>votes</code>
              </td>
              <td>
                <code>integer</code>
              </td>
              <td>
                The total points for the submitted image.
              </td>
            </tr>
            <tr>
              <td>
                <code>owner</code>
              </td>
              <td>
                <code>string</code>
              </td>
              <td>
                The owner for the submitted image.
              </td>
            </tr>
            <tr>
              <td>
                <code>date_posted</code>
              </td>
              <td>
                <code>string</code>
              </td>
              <td>
                The date the image was submitted.
              </td>
            </tr>
            <tr>
              <td>
                <code>tags</code>
              </td>
              <td>
                <code>array</code>
              </td>
              <td>
                The tags for the submitted image.
              </td>
            </tr>
          </tbody>
        </table>
      <h4>Example</h4>
        <pre><code>$ curl -F "username=TestUser" -F "password=TestPass" -F "file=@image.png" https://api.teknik.io/ricehalla/post</code></pre>
  <h3><b>Upload</b></h3>
    <hr>
    <p>This is a description of the API commands available for the Upload service.</p>
    <h3>Upload a File</h3>
      <pre><code>POST https://api.teknik.io/upload/post</code></pre>
      <h4>Parameters</h4>
        <table>
          <thead>
            <tr>
              <th>Name</th>
              <th>Type</th>
              <th>Default</th>
              <th>Description</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <code>file</code>
              </td>
              <td>
                <code>file</code>
              </td>
              <td>
                <var>NULL</var>
              </td>
              <td>
                <strong>Required</strong>
                The file that you would like to upload.
              </td>
            </tr>
            <tr>
              <td>
                <code>get_delete_key</code>
              </td>
              <td>
                <code>string</code>
              </td>
              <td>
                <var>no</var>
              </td>
              <td>
                Whether you would like to create a deletion link.  Choose <code>yes</code> or <code>no</code>
              </td>
            </tr>
          </tbody>
        </table>
      <h4>Response</h4>
        <pre><code>{"results":{"file":{"name":"<var>file_name</var>", "url":"<var>url</var>", "type":"<var>file_type</var>", "size":<var>size</var>}}}</code></pre>
        <table>
          <thead>
            <tr>
              <th>Name</th>
              <th>Type</th>
              <th>Description</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <code>name</code>
              </td>
              <td>
                <code>string</code>
              </td>
              <td>
                The filename of the uploaded file.
              </td>
            </tr>
            <tr>
              <td>
                <code>url</code>
              </td>
              <td>
                <code>string</code>
              </td>
              <td>
                The direct url to the uploaded file.
              </td>
            </tr>
            <tr>
              <td>
                <code>type</code>
              </td>
              <td>
                <code>string</code>
              </td>
              <td>
                The MIME file type of the uploaded file.
              </td>
            </tr>
            <tr>
              <td>
                <code>size</code>
              </td>
              <td>
                <code>integer</code>
              </td>
              <td>
                The size of the uploaded file in bytes.
              </td>
            </tr>
            <tr>
              <td>
                <code>delete_key</code>
              </td>
              <td>
                <code>string</code>
              </td>
              <td>
                <strong>Optional</strong>
                The deletion key for file.  Use it as follows: <code>https://u.teknik.io/<var>file.jpg</var>/<var>deletion_key</var></code>
              </td>
            </tr>
          </tbody>
        </table>
      <h4>Example</h4>
        <pre><code>$ curl -F "get_delete_key=yes" -F "file=@image.png" https://api.teknik.io/upload/post</code></pre>
  <br />
  <br />
</div>