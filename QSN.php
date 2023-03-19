<?php
require_once('include/init.php');

require_once('include/header.php');
?>


<main>
  <section>
    <div class="container">

      <div class="section-title text-center mb-5">
        <h2>Contactez-nous</h2>
      </div>

      <div class="row">

        <div class="col-lg-5 d-flex align-items-stretch">
          <div class="info bg-light p-4">
            <div class="address mb-4">
              
              <h4>Adresse:</h4>
              <p>1 rue de la Banque, Paris 75002</p>
            </div>

            <div class="email mb-4">
              
              <h4>Email:</h4>
              <<p>annonceo@mail.com</p>
            </div>

            <div class="phone mb-4">
              
              <h4>Téléphone:</h4>
              <p>01 22 33 44 55</p>
            </div>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7211.841118067085!2d2.332304010876182!3d48.86803195321111!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66e3c954c68fd%3A0x50b82c368941a60!2sParis%202e%20Arrondissement%2C%2075002%20Paris!5e0!3m2!1sfr!2sfr!4v1678805225712!5m2!1sfr!2sfr" width="100%" height="290" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>

        </div>

        <div class="col-lg-7 mt-5 mt-lg-0 d-flex align-items-stretch">
          <form action="forms/contact.php" method="post" role="form" class="php-email-form">
            <div class="row">
              <div class="form-group col-md-6">
                <label for="name">Nom</label>
                <input type="text" name="name" class="form-control" id="name" required>
              </div>
              <div class="form-group col-md-6 mt-3 mt-md-0">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email" id="email" required>
              </div>
            </div>
            <div class="form-group mt-3">
              <label for="subject">Objet</label>
              <input type="text" class="form-control" name="subject" id="subject" required>
            </div>
            <div class="form-group mt-3">
              <label for="message">Message</label>
              <textarea class="form-control" name="message" rows="10" required></textarea>
            </div>
            <div class="text-center mt-4"><button type="submit" class="btn btn-primary">Envoyer</button></div>
          </form>
        </div>
      </div>
    </div>
  </section>
</main>



<?php require_once('include/footer.php') ?>