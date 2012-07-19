<?php 

require('submission.php');

// this is a very simple demo of a contact form
// you can find a working demo of this here: http://bastianallgeier.com/submission/
$submission = new Submission(array(

  // set the required fields
  'required' => array('name', 'email', 'text'),
    
  // make sure that the final data array contains only
  // the fields we want to further use.
  'keep' => array('name', 'email', 'text'),

  // setup a validation event
  'validate' => function($self) {

    // sanitize data
    $self->value('email', filter_var($self->value('email'), FILTER_SANITIZE_EMAIL));
    $self->value('name',  trim(strip_tags($self->value('name'))));
    $self->value('text',  trim(strip_tags($self->value('text'))));
        
    // validate the email address
    if(!filter_var($self->value('email'), FILTER_VALIDATE_EMAIL)) $self->addInvalid('email');  
        
  },

  'submit' => function($self) {
    
    // normally you would save something in a database
    // here or send data via email. 
    // you can then trigger an error or…
        
    $self->trigger('success');
           
  },

  'success' => function($self) {
    // send to the "Thank you" page
    header('Location:?thankyou');
  },

  'error' => function($self) {
    $self->alert('Please make sure to fill in all fields correctly!');
  }

));

?>
<!DOCTYPE html>
<html lang="en">
<head>
  
  <title>Submission Example</title>
  <meta charset="utf-8" />

  <style>
  
  * {
    margin: 0;
    padding: 0;
  }
  body {
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    margin: 20px auto 100px;
    width: 400px;
    font-size: 1em;
    line-height: 1.5em;
  }
  a {
    color: red;
  }    
  h1 {
    font-weight: normal;
    border-bottom: 1px solid #ddd;
    padding: 20px 0;
  }
  form fieldset {
    border: 0;
  }
  form .alert {
    background: red;
    color: #fff;
    padding: .8em;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    font-weight: bold; 
    margin-top: 1em;   
  }
  form .field {
    border-bottom: 1px solid #ddd;
    padding: 1em 0;
  }
  form label {
    display: block;
    margin-bottom: .5em;
  }
  form label i {
    color: #aaa;
    font-style: normal;
    font-weight: bold;
  }
  form label small {
    color: red;
    float: right;  
  }
  form input[type=text], 
  form textarea {
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;   
    box-sizing: border-box;   
    width: 100%;
    padding: .8em;
    border: 1px solid #bbb;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    -webkit-box-shadow: rgba(0,0,0, .06) 0px 2px 5px inset;
    -moz-box-shadow: rgba(0,0,0, .06) 0px 2px 5px inset;
    box-shadow: rgba(0,0,0, .06) 0px 2px 5px inset;
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    font-size: 1em;
  }
  form textarea {
    height: 200px;
  }
  form .hint {
    font-style: italic;
    font-size: .8em;
    line-height: 1.4em;
    color: #999;
    text-align: left;
    padding: .8em 0;
  }
  form input[type=submit] {
    float: right;
  }
  .thankyou {
    padding: 1em 0;
    color: #888;
  }
            
  </style>

</head>
<body>

<?php if(isset($_GET['thankyou'])): ?>

<h1>Thank you!</h1>
<p class="thankyou">
  Actually nothing has been saved, because this is just a demo.
  <a href="example.php">Yay, let's do this again!</a>
</p>

<?php else: ?>

<form method="post">

  <h1>Form Submission Demo</h1>

  <fieldset>

    <?php if($alert = $submission->alert()): ?>
    <div class="alert"><?php echo $alert ?></div>
    <?php endif ?>

    <div class="field">
      <label for="name">
        Name <?php if($submission->isRequired('name')) echo '<i>*</i>' ?> 

        <?php if($submission->isError('name')): ?>
        <small>Please enter your name</small>
        <?php endif ?>
      </label>
      <input type="text" id="name" name="name" placeholder="Your name" value="<?php echo $submission->htmlValue('name') ?>" />
    </div>

    <div class="field">
      <label for="email">
        Email <?php if($submission->isRequired('email')) echo '<i>*</i>' ?> 

        <?php if($submission->isError('email')): ?>
        <small>Please enter your correct email address</small>
        <?php endif ?>
      </label>
      <input type="text" placeholder="john@doe.com" id="email" name="email" value="<?php echo $submission->htmlValue('email') ?>" />
    </div>

    <div class="field">
      <label for="text">
        Text <?php if($submission->isRequired('text')) echo '<i>*</i>' ?> 

        <?php if($submission->isError('text')): ?>
        <small>Please enter some text</small>
        <?php endif ?>
      </label>
      <textarea id="text" name="text" placeholder="Your text…"><?php echo $submission->htmlValue('text') ?></textarea>
    </div>
    
    <p class="hint">Fields with * are required<br />Nothing is going to be stored or sent.<br />This is just a demo!</p>
    <input class="submit" type="submit" name="submit" value="Send &rarr;" />
  
  </fieldset>
</form>

<?php endif ?>

</body>