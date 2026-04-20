<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Contact - Seed Store</title>
  <link rel="stylesheet" href="style.css">
  <script src="script.js" defer></script>
</head>
<body>
<?php include 'header.php'; ?>
<main class="container">
  <section class="card">
    <h2>Contact Us</h2>
    <p>Any question or remarks? Just write us a message!</p>
  </section>

  <section class="contact-grid">
    <div class="info">
      <h3>Contact Information</h3>
      <p>Say something to start a live chat!</p>
      <p>📞 +6393 5497 7812</p>
      <p>✉️ ambotlang@gmail.com</p>
      <p>📍 Jamisola St, Sta. Lucia Pagadian City, Pagadian City, Philippines, 7016</p>
    </div>
    <div class="form">
      <form action="contactform.php" method="post">
        <label for="name">Name</label>
        <input id="name" name="name" required>
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
        <label for="phone">Phone Number</label>
        <input id="phone" name="phone">

        <div class="subject-radio-group">
          <span>Select subject</span>
          <label><input type="radio" name="subject" value="General Inquiry" checked> General Inquiry</label>
          <label><input type="radio" name="subject" value="Order Support"> Order Support</label>
          <label><input type="radio" name="subject" value="Product Info"> Product Info</label>
          <label><input type="radio" name="subject" value="Partnership"> Partnership</label>
        </div>

        <label for="message">Write your message</label>
        <textarea id="message" name="message" rows="6" required></textarea>
        <button type="submit" class="btn btn-primary">Send Message</button>
      </form>
    </div>
  </section>
</main>
<?php include 'footer.php'; ?>
</body>
</html>