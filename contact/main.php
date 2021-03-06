<div class="container">
    <div class="row">
        <div class="col-sm-12 col-lg-12">
            <h1 class="h1">
                Contact us</h1>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="well well-sm">
                <form>
                <div class="row">
                    <div class="col-md-6">
                      <?php if ($logged_in != 1) { ?>
                        <div class="form-group">
                            <label for="contact_name">
                                Name</label>
                            <input type="text" class="form-control" id="contact_name" placeholder="Enter name" required="required" />
                        </div>
                        <div class="form-group">
                            <label for="contact_email">
                                Email Address</label>
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span>
                                </span>
                                <input type="email" class="form-control" id="contact_email" placeholder="Enter email" required="required" /></div>
                        </div>
                      <?php } ?>
                        <div class="form-group">
                            <label for="contact_subject">
                                Subject</label>
                            <select id="contact_subject" name="contact_subject" class="form-control" required="required">
                                <option value="na" selected="">Choose One:</option>
                                <option value="general">General Questions</option>
                                <option value="suggestions">Suggestions</option>
                                <option value="service">Service Support</option>
                                <option value="feedback">Feedback</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="contact_message">
                                Message</label>
                            <textarea name="contact_message" id="contact_message" class="form-control" rows="9" cols="25" required="required"
                                placeholder="Message"></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary pull-right" id="contact_submit">
                            Send Message</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
        <div class="col-md-4">
            <form>
            <legend><span class="glyphicon glyphicon-globe"></span> Where to find us</legend>
            <address>
                <strong>/g/ Technology on IRC</strong><br>
                #/g/technology (<a href="irc://irc.rizon.net" target="_blank">irc.rizon.net</a>)<br>
            </address>
            <address>
                <strong>Teknik IRC</strong><br>
                #teknik (<a href="irc://irc.teknik.io" target="_blank">irc.teknik.io</a>)<br>
            </address>
            <address>
                <strong>Customer Support</strong><br>
                <a href="mailto:support@teknik.io">support@teknik.io</a>
            </address>
            </form>
        </div>
    </div>
</div>
