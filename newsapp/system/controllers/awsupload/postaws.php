
<?php 
require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

// AWS S3 credentials
$bucket = 'streetbuzzbucket';
$keyname = 'path/to/image.jpg';
//$filename = '/path/to/local/image.jpg';
$region = 'ap-south-1';
$version = 'latest';
$accessKeyId = 'AKIAXABO2PU7FU5OIEMB';
$secretAccessKey = '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';


$filename=$_FILES['img']['name'];
$filetmp=$_FILES['img']['tmp_name'];




// Instantiate an Amazon S3 client
$s3 = new S3Client([
    'version' => $version,
    'region'  => $region,
    'credentials' => [
        'key'    => $accessKeyId,
        'secret' => $secretAccessKey,
    ],
]);

try {
    // Upload data.
    $result = $s3->putObject([
        'Bucket' => $bucket,
        'Key'    => $filename,
        'SourceFile' => $filetmp,
       
    ]);

    // Print the URL of the uploaded image
    print_r($result);
    echo $result['ObjectURL'];

} catch (S3Exception $e) {
    // Catch an S3 specific exception.
    echo $e->getMessage();
}


?>
