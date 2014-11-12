<div class="row">
  <h2><b>Git</b></h2>
  <hr>
  <h3>Git Repository Access</h3>
  <p>
    Every user is given the option to add public keys to the authorised users list to access their git repositories, and also access the main Teknik repositories.
    <br />
    <br />
    To add a public key, just login, click your username on the top navbar and click 'Profile'.  This will bring you to your profile page where you can add your public key or edit your existing one.
    <br />
    <br />
    <div class="bs-callout bs-callout-warning">
      <h4>Public Key Format</h4>
      <p>
        The Public Key must be in the following format: <b>ssh-rsa [0-9A-Za-z/+]</b>
      </p>
    </div>
  </p>
  <h3>Creating a Git Repository</h3>
  <p>
    Once you have a public key added, you will have the ability to create a repo.  To do so, you just need to clone the repo you want to create, and the repo will be created.
    <br />
    <br />
    <code>~$git clone git@teknik.io:u/[username]/[repository_name]</code>
  </p>
  <h3>Viewing a user's git repositories</h3>
  <p>
    You can also view a list of the git repo's a user has by visiting: <code><?php echo get_page_url('git', $CONF); ?>/u/[username]/</code>
    <br />
  </p>
  <h3>Clone a Repository</h3>
  <p>
    To clone another user's repository to a local copy, perform the following command: <code>~$git clone git://teknik.io/u/[username]/[reponame]</code>
    <br />
  </p>
  <h3>Fork a Repository</h3>
  <p>
    When you fork a user's repository, you can make changes, and then send a pull request to the user to accept your changes.
    <br />
    <br />
    To fork the user's repo you will need to perform the following command:
    <br />
    <br />
    <code>~$ssh git@teknik.io fork u/[username]/[repository] u/[your username]/[repository]</code>
    <br />
    <b>or</b>
    <br />
    <code>~$ssh git@teknik.io fork [repository] u/[your username]/[repository]</code>
    <br />
  </p>
  <h3>Submit a Pull Request</h3>
  <p>
    In order to submit a pull request, you must have a forked repository of the original authors repository already in your personal repositories.
    <br />
    <br />
    Once you have made your edits and pushed your commits, execute the following command:
    <br />
    <br />
    <code>~$git request-pull [original_commit] git://teknik.io/u/[your username]/[repository] [branch name]</code>
    <br />
    <br />
    You will be given an output that will describe the pull request.  This must then be emailed to the original author to be considered for being pulled into the original repository.  The original author's email is <code>[username]@teknik.io</code>
    <br />
  </p>
  <h3>Modify Repository Permissions</h3>
  <p>
    By default, all personal repositories are semi-private (downloadable, but not forkable).  As the owner, you can set permissions for your repositories for different users and groups.
    <br />
    <br />
    Here is a list of the different permissions you can assign users:
    <br />
    <br />
    <div class="col-sm-6">
      <ul class="list-group">
        <li class="list-group-item">READERS<div class="pull-right">Allows the user to pull from the repo</div></li>
        <li class="list-group-item">WRITERS<div class="pull-right">Allows the user to push to the repo</div></li>
      </ul>
    </div>
    <br />
    <br />
    In order to change a repositories permissions, you run the following command, using the <b>+/-</b> mode to add or remove permissions.
    <br />
    <br />
    <code>~$ssh git@teknik.io perms u/[username]/[repository] [mode] [rolename] [username]</code>
    <br />
    <br />
    For example: <code>~$ssh git@teknik.io perms u/myUser/myRepo + WRITERS otherUser</code>
    <br />
    <br />
    The above command would give 'otherUser' write access to the repository 'myRepo'.
    <br />
  </p>
  <h3>Delete a Repository</h3>
  <p>
    To delete a repository, execute the following commands:
    <br />
    <br />
    <code>~$ssh git@teknik.io D unlock u/[username]/[repository]</code>
    <br />
    <code>~$ssh git@teknik.io D rm u/[username]/[repository]</code>
    <br />
  </p>
  <h3>More Information</h3>
  <p>
    <div class="bs-callout bs-callout-warning">
      <h4>Repository Name</h4>
      <p>
        When defining [repository], leave off the trailing .git</b>
      </p>
    </div>
    <br />
    For more information on the commands available to you, just type: `~$ssh git@teknik.io help`
    <br />
  </p>
  <h3>Examples</h3>
  <p>
    <b>Clone a Repo (Git Daemon):</b> <code>~$git clone git://teknik.io/u/[username]/[repository_name]</code>
    <br />
    <b>Clone a Repo (SSH):</b> <code>~$git clone git@teknik.io:u/[username]/[repository_name]</code>
  </p>
</div>