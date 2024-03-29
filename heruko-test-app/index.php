<?php
require('vendor/autoload.php');
$buck =getenv('S3_BUCKET')?: die('No "S3_BUCKET" config var in found in env!');
$ke =getenv('AWS_ACCESS_KEY_ID')?: die('No "S3_BUCKET" config var in found in env!');
$se =getenv('AWS_SECRET_ACCESS_KEY')?: die('No "S3_BUCKET" config var in found in env!');
$re =getenv('AWS_REGION')?: die('No "S3_BUCKET" config var in found in env!');
$sess =getenv('AWS_SESSION_TOKEN')?: die('No "S3_BUCKET" config var in found in env!');

// this will simply read AWS_ACCESS_KEY_ID and AWS_SECRET_ACCESS_KEY from env vars
//$s3 = Aws\S3\S3Client::factory();
$s3 = Aws\S3\S3Client::factory(array(
    'credentials' => array(
        'key'    => $ke,
        'secret' => $se,
        'token'  => $sess,
    )
));
$bucket = getenv('S3_BUCKET')?: die('No "S3_BUCKET" config var in found in env!');
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


  <title>Assignment 2</title>

  <!-- Bootstrap core CSS -->
  <link href="../../dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>

<body>

  <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="index.php">Assignment 2</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="index.php">Home<span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="list.php">Analysis</a>
        </li>
      </ul>

    </div>
  </nav>

  <main role="main" class="container">

    <h1>Assignment 2</h1>
  </div>

</main><!-- /.container -->
<h1>Upload Photo To Analyze Your Age!</h1>
<video id="video" width="640" height="480" autoplay></video>
<button id="snap">Snap Photo</button>
<a href="#" class="button" id="btn-download" download="my-file-name.png">Download Picture</a>
<canvas id="canvas" width="640" height="480"></canvas>
<script>
function dataURItoBlob(dataURI) {
    var binary = atob(dataURI.split(',')[1]);
    var array = [];
    for(var i = 0; i < binary.length; i++) {
        array.push(binary.charCodeAt(i));
    }
    return new Blob([new Uint8Array(array)], {type: 'image/jpeg'});
}
// Grab elements, create settings, etc.
var video = document.getElementById('video');
// Elements for taking the snapshot
var canvas = document.getElementById('canvas');
var context = canvas.getContext('2d');
var video = document.getElementById('video');
var button = document.getElementById('btn-download');


// Trigger photo take
document.getElementById("snap").addEventListener("click", function() {
  context.drawImage(video, 0, 0, 640, 480);
  //var img = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream");
  var dataURL = canvas.toDataURL('image/png');
  button.href = dataURL;
  //window.location.href=img;
});
// Get access to the camera!
if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
  // Not adding `{ audio: true }` since we only want video now
  navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) {
    //video.src = window.URL.createObjectURL(stream);
    video.srcObject = stream;
    video.play();
  });
}
</script>
<?php
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['userfile']) && $_FILES['userfile']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['userfile']['tmp_name'])) {
    // FIXME: add more validation, e.g. using ext/fileinfo
    try {
        // FIXME: do not use 'name' for upload (that's the original filename from the user's computer)
        $upload = $s3->upload($bucket, $_FILES['userfile']['name'], fopen($_FILES['userfile']['tmp_name'], 'rb'), 'public-read');
?>
        <p>Upload <a href="<?=htmlspecialchars($upload->get('ObjectURL'))?>">successful</a> :)</p>
<?php } catch(Exception $e) {

        echo 'Caught exception: ',  $e->getMessage(), "\n";
        echo 'AWS_ACCESS_KEY_ID: ',  getenv('AWS_ACCESS_KEY_ID'), "\n";
        echo 'AWS_SECRET_ACCESS_KEY: ',getenv('AWS_SECRET_ACCESS_KEY'), "\n";

  ?>
        <p>Upload error :(</p>
<?php } } ?>
        <h2>Upload a file</h2>
        <form enctype="multipart/form-data" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
            <input name="userfile" type="file"><input type="submit" value="Upload">
        </form>



<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
<script src="../../assets/js/vendor/popper.min.js"></script>
<script src="../../dist/js/bootstrap.min.js"></script>
</body>
</html>
