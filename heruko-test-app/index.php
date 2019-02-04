<?php
require('vendor/autoload.php');
$buck =getenv('S3_BUCKET')?: die('No "S3_BUCKET" config var in found in env!');
$ke =getenv('AWS_ACCESS_KEY_ID')?: die('No "S3_BUCKET" config var in found in env!');
$se =getenv('AWS_SECRET_ACCESS_KEY')?: die('No "S3_BUCKET" config var in found in env!');
$re =getenv('AWS_REGION')?: die('No "S3_BUCKET" config var in found in env!');
$sess =getenv('AWS_SESSION_TOKEN')?: die('No "S3_BUCKET" config var in found in env!');

$config = [
        "bucket" => $buck,        // Fill in with your aws bucket name
        "key" => $ke,           // Fill in with your AWS Key
        "secret" => $se,        // Fill in with your AWS Secret
        "region" => $re,  // Fill in with your S3 Bucket's region
        "session" =>$sess,
        "version" => "2019-02-03",
    ];
// this will simply read AWS_ACCESS_KEY_ID and AWS_SECRET_ACCESS_KEY from env vars
//$s3 = Aws\S3\S3Client::factory();
$s3 = Aws\S3\S3Client($config);
$bucket = getenv('S3_BUCKET')?: die('No "S3_BUCKET" config var in found in env!');
?>
<html>
    <head><meta charset="UTF-8"></head>
    <body>
        <h1>Hello SOFE4630</h1>

		<a href="https://assignment2-sofe4630.herokuapp.com/list.php">Files List</a>
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
    </body>
</html>
