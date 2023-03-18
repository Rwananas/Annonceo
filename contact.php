<?php
require_once('include/init.php');

require_once('include/header.php');
?>


<form action="#" method="POST">
  <div class="form-group">
    <label for="name">Nom complet</label>
    <input type="text" class="form-control" id="name" name="name" required>
  </div>
  <div class="form-group">
    <label for="email">Adresse e-mail</label>
    <input type="email" class="form-control" id="email" name="email" required>
  </div>
  <div class="form-group">
    <label for="subject">Sujet</label>
    <input type="text" class="form-control" id="subject" name="subject" required>
  </div>
  <div class="form-group">
    <label for="message">Message</label>
    <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
  </div>
  <button type="submit" class="btn btn-primary">Envoyer</button>
</form>











<?php require_once('include/footer.php') ?>