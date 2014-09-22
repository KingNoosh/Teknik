<div class="container">
  <div class="row">
    <div class="col-xs-10">
      <div name="Git" data-unique="Git"></div>
      <h2><b>Git</b></h2>
      <hr>
      <div name="GitRepositoryAccess" data-unique="GitRepositoryAccess"></div>
      <h3>Git Repository Access</h3>
      <p>
        Every user is given the option to add their public key to the authorised users list to access the Teknik git repository.
        <br />
        <br />
        To add your public key, just login, click your username on the top navbar and click 'Profile'.  This will bring you to your profile page where you can add your public key or edit your existing one.
        <br />
        <br />
        <div class="bs-callout bs-callout-warning">
          <div name="PublicKeyFormat" data-unique="PublicKeyFormat"></div>
          <h4>Public Key Format</h4>
          <p>
            The Public Key must be in the following format: <b>ssh-rsa [0-9A-Za-z/+]</b>
          </p>
        </div>
      </p>
      <h3>Examples</h3>
      <p>
        Once you have your key added, you will be able to access the git repository via ssh access
        <br />
        <br />
        <b>Clone a Repo:</b> <code>~$git clone ssh://git@teknik.io/~/[repository_name]</code>
      </p>
    </div>
  </div>
</div>