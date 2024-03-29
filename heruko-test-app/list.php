<?php
require('vendor/autoload.php');
use Twilio\Rest\Client;
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
  <script src="aws-cognito-sdk.min.js"></script>
  <script src="amazon-cognito-identity.min.js"></script>
  <script src="https://sdk.amazonaws.com/js/aws-sdk-2.16.0.min.js"></script>
  <script src="./app.js"></script>
  <script>
  function drawDataURIOnCanvas(strDataURI, canvas) {
    var img = new window.Image();
    var myCanvas = document.getElementById(canvas);
    var myCanvasContext = myCanvas.getContext("2d");

    img.addEventListener("load", function () {
      //canvas.getContext("2d").drawImage(img, 0, 0);
      myCanvasContext.drawImage(img, 0, 0);
    });
    img.crossOrigin = "Anonymous";
    img.setAttribute("src", strDataURI);
}
  </script>
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
          <a class="nav-link" href="index.php">Home</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="list.php">Analysis<span class="sr-only">(current)</span></a>
        </li>
      </ul>

    </div>
  </nav>

  <main role="main" class="container">

    <h1>Assignment 2</h1>
  </div>

</main><!-- /.container -->
<h1>View Stored Images and Analyze Age</h1>
<form action="list.php" method="get">
  Share this Site with your Friends! Enter Their Phone Number Below:
  <input type="tel" id="phone" name="phone">
  <input type="submit" id="submit" name="submit" value="submit">
</form>
<script>

  /*document.getElementById("fileToUpload").addEventListener("change", function (event) {
    ProcessImage();
  }, false);*/

  //Calls DetectFaces API and shows estimated ages of detected faces
  function DetectFaces(imageData,x) {
    AWS.region = "RegionToUse";
    var rekognition = new AWS.Rekognition();
    var params = {
      Image: {
        Bytes: imageData
      },
      Attributes: [
        'ALL',
      ]
    };
    rekognition.detectFaces(params, function (err, data) {
      var lowCount = 0;
      var highCount = 0;
      if (err) console.log(err, err.stack); // an error occurred
      else {
       var table = "<table><tr><th>Low</th><th>High</th></tr>";
        // show each face and build out estimated age table
        for (var i = 0; i < data.FaceDetails.length; i++) {
          lowCount = data.FaceDetails[i].AgeRange.Low;
          highCount = data.FaceDetails[i].AgeRange.High;
          table += '<tr><td>' + data.FaceDetails[i].AgeRange.Low +
            '</td><td>' + data.FaceDetails[i].AgeRange.High + '</td></tr>';
        }
        table += "</table>";
        responsiveVoice.speak("Amazon Recognition has determine that you are between "+lowCount+ " and " +highCount+ " years old");
        document.getElementById("opResult"+x).innerHTML = table;
      }
    });
  }
  //Loads selected image and unencodes image bytes for Rekognition DetectFaces API
  function ProcessImage(x) {
    AnonLog();
  //  var control = document.getElementById("fileToUpload");
  //  var file = control.files[0];

    // Load base64 encoded image
    /*var reader = new FileReader();
    reader.onload = (function (theFile) {
      return function (e) {*/
        var imgg = document.createElement('imgg');
        //imgg.crossOrigin = "Anonymous"
        var image = null;
        //imgg.src = url;
        var canvasID = "canvas"+x;
        var canvas = document.getElementById(canvasID);
        //canvas.crossOrigin = "Anonymous";
        //canvas.crossOrigin = "use-credentials";
        //console.log(canvas.toDataUrl());
        imgg.src = canvas.toDataURL();
        var jpg = true;
        try {
          image = atob(canvas.toDataURL().split("data:image/jpeg;base64,")[1]);

        } catch (e) {
          jpg = false;
        }
        if (jpg == false) {
          try {
            image = atob(canvas.toDataURL().split("data:image/png;base64,")[1]);
          } catch (e) {
            alert("Not an image file Rekognition can process");
            return;
          }
        }
        //unencode image bytes for Rekognition DetectFaces API
        var length = image.length;
        imageBytes = new ArrayBuffer(length);
        var ua = new Uint8Array(imageBytes);
        for (var i = 0; i < length; i++) {
          ua[i] = image.charCodeAt(i);
        }
        //Call Rekognition
        DetectFaces(imageBytes,x);
      /*};
    })(file);
    reader.readAsDataURL(file);*/
  }
  //Provides anonymous log on to AWS services
  function AnonLog() {

    // Initialize the Amazon Cognito credentials provider
AWS.config.region = 'us-east-1'; // Region
AWS.config.credentials = new AWS.CognitoIdentityCredentials({
    IdentityPoolId: 'us-east-1:e6025a14-56cd-4432-a369-70544cd84a1e',
});
    // Make the call to obtain credentials
    AWS.config.credentials.get(function () {
      // Credentials will be available when this function is called.
      var accessKeyId = AWS.config.credentials.accessKeyId;
      var secretAccessKey = AWS.config.credentials.secretAccessKey;
      var sessionToken = AWS.config.credentials.sessionToken;
    });
  }

</script>
<?php
try {
$objects = $s3->getIterator('ListObjects', array(
"Bucket" => $bucket
));
$i = -1;
$url;
foreach ($objects as $object) {
  $i++;
  $url = htmlspecialchars($s3->getObjectUrl($bucket, $object['Key']));
?>

<p> <a href="<?=$url?>"> <?echo $object['Key'] . "<br>";?></a></p>
<canvas id="canvas<?=$i?>" width="640" height="480" crossorigin="Anonymous"></canvas>
<button type="button" id="button<?=$i?>">Click to view image</button>
<button type="button" id="analyze<?=$i?>">Click to analyze image</button>
<p id="opResult<?=$i?>"></p>
<? echo '<script>
document.getElementById("button'.$i.'").addEventListener("click", function() {
    drawDataURIOnCanvas("'.$url.'","canvas'.$i.'");
}, false);
document.getElementById("analyze'.$i.'").addEventListener("click", function () {
  ProcessImage("'.$i.'");
}, false);
 </script>';?>
<?		}?>
<?php } catch(Exception $e) {
echo 'Caught exception: ',  $e->getMessage(), "\n";

?>
<p>error :(</p>
<?php }  ?>
<?php
try{
$account_sid = 'AC138e7e2ddc666ae36984889a919123b8';
$auth_token = 'e3b25cd992d91aba1d2afdf2628233a5';
$twilio_number = "+12892747516";
$client = new Client($account_sid, $auth_token);
if(isset($_GET["submit"])) {
  $client->messages->create(
      // Where to send a text message (your cell phone?)
      $_GET['phone'],
      array(
          'from' => $twilio_number,
          'body' => 'Go to https://assignment2-sofe4630.herokuapp.com/index.php to have an AI guess your Age!'
      )
  );
}
}catch(Exception $e) {
echo 'Caught exception: ',  $e->getMessage(), "\n";
}
?>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
<script src="../../assets/js/vendor/popper.min.js"></script>
<script src="../../dist/js/bootstrap.min.js"></script>
<script src='https://code.responsivevoice.org/responsivevoice.js'></script>
</body>
</html>
