<hr class="row col-md-12">
<h3>About me</h3>
<pre>
    Username: <?php echo $_SESSION['USER']->username; ?><br>
    Email: <?php echo $_SESSION['USER']->email; ?><br>
    Joined: <?php echo $_SESSION['USER']->registration_date; ?><br>
</pre>
