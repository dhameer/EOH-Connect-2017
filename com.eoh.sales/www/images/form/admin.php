<?php
require_once( dirname(__FILE__).'/form.lib.php' );

define( 'PHPFMG_USER', "Maxine.mckbrown@eoh.co.za" ); // must be a email address. for sending password to you.
define( 'PHPFMG_PW', "806851" );

?>
<?php
/**
 * GNU Library or Lesser General Public License version 2.0 (LGPLv2)
*/

# main
# ------------------------------------------------------
error_reporting( E_ERROR ) ;
phpfmg_admin_main();
# ------------------------------------------------------




function phpfmg_admin_main(){
    $mod  = isset($_REQUEST['mod'])  ? $_REQUEST['mod']  : '';
    $func = isset($_REQUEST['func']) ? $_REQUEST['func'] : '';
    $function = "phpfmg_{$mod}_{$func}";
    if( !function_exists($function) ){
        phpfmg_admin_default();
        exit;
    };

    // no login required modules
    $public_modules   = false !== strpos('|captcha|', "|{$mod}|", "|ajax|");
    $public_functions = false !== strpos('|phpfmg_ajax_submit||phpfmg_mail_request_password||phpfmg_filman_download||phpfmg_image_processing||phpfmg_dd_lookup|', "|{$function}|") ;   
    if( $public_modules || $public_functions ) { 
        $function();
        exit;
    };
    
    return phpfmg_user_isLogin() ? $function() : phpfmg_admin_default();
}

function phpfmg_ajax_submit(){
    $phpfmg_send = phpfmg_sendmail( $GLOBALS['form_mail'] );
    $isHideForm  = isset($phpfmg_send['isHideForm']) ? $phpfmg_send['isHideForm'] : false;

    $response = array(
        'ok' => $isHideForm,
        'error_fields' => isset($phpfmg_send['error']) ? $phpfmg_send['error']['fields'] : '',
        'OneEntry' => isset($GLOBALS['OneEntry']) ? $GLOBALS['OneEntry'] : '',
    );
    
    @header("Content-Type:text/html; charset=$charset");
    echo "<html><body><script>
    var response = " . json_encode( $response ) . ";
    try{
        parent.fmgHandler.onResponse( response );
    }catch(E){};
    \n\n";
    echo "\n\n</script></body></html>";

}


function phpfmg_admin_default(){
    if( phpfmg_user_login() ){
        phpfmg_admin_panel();
    };
}



function phpfmg_admin_panel()
{    
    phpfmg_admin_header();
    phpfmg_writable_check();
?>    
<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td valign=top style="padding-left:280px;">

<style type="text/css">
    .fmg_title{
        font-size: 16px;
        font-weight: bold;
        padding: 10px;
    }
    
    .fmg_sep{
        width:32px;
    }
    
    .fmg_text{
        line-height: 150%;
        vertical-align: top;
        padding-left:28px;
    }

</style>

<script type="text/javascript">
    function deleteAll(n){
        if( confirm("Are you sure you want to delete?" ) ){
            location.href = "admin.php?mod=log&func=delete&file=" + n ;
        };
        return false ;
    }
</script>


<div class="fmg_title">
    1. Email Traffics
</div>
<div class="fmg_text">
    <a href="admin.php?mod=log&func=view&file=1">view</a> &nbsp;&nbsp;
    <a href="admin.php?mod=log&func=download&file=1">download</a> &nbsp;&nbsp;
    <?php 
        if( file_exists(PHPFMG_EMAILS_LOGFILE) ){
            echo '<a href="#" onclick="return deleteAll(1);">delete all</a>';
        };
    ?>
</div>


<div class="fmg_title">
    2. Form Data
</div>
<div class="fmg_text">
    <a href="admin.php?mod=log&func=view&file=2">view</a> &nbsp;&nbsp;
    <a href="admin.php?mod=log&func=download&file=2">download</a> &nbsp;&nbsp;
    <?php 
        if( file_exists(PHPFMG_SAVE_FILE) ){
            echo '<a href="#" onclick="return deleteAll(2);">delete all</a>';
        };
    ?>
</div>

<div class="fmg_title">
    3. Form Generator
</div>
<div class="fmg_text">
    <a href="http://www.formmail-maker.com/generator.php" onclick="document.frmFormMail.submit(); return false;" title="<?php echo htmlspecialchars(PHPFMG_SUBJECT);?>">Edit Form</a> &nbsp;&nbsp;
    <a href="http://www.formmail-maker.com/generator.php" >New Form</a>
</div>
    <form name="frmFormMail" action='http://www.formmail-maker.com/generator.php' method='post' enctype='multipart/form-data'>
    <input type="hidden" name="uuid" value="<?php echo PHPFMG_ID; ?>">
    <input type="hidden" name="external_ini" value="<?php echo function_exists('phpfmg_formini') ?  phpfmg_formini() : ""; ?>">
    </form>

		</td>
	</tr>
</table>

<?php
    phpfmg_admin_footer();
}



function phpfmg_admin_header( $title = '' ){
    header( "Content-Type: text/html; charset=" . PHPFMG_CHARSET );
?>
<html>
<head>
    <title><?php echo '' == $title ? '' : $title . ' | ' ; ?>PHP FormMail Admin Panel </title>
    <meta name="keywords" content="PHP FormMail Generator, PHP HTML form, send html email with attachment, PHP web form,  Free Form, Form Builder, Form Creator, phpFormMailGen, Customized Web Forms, phpFormMailGenerator,formmail.php, formmail.pl, formMail Generator, ASP Formmail, ASP form, PHP Form, Generator, phpFormGen, phpFormGenerator, anti-spam, web hosting">
    <meta name="description" content="PHP formMail Generator - A tool to ceate ready-to-use web forms in a flash. Validating form with CAPTCHA security image, send html email with attachments, send auto response email copy, log email traffics, save and download form data in Excel. ">
    <meta name="generator" content="PHP Mail Form Generator, phpfmg.sourceforge.net">

    <style type='text/css'>
    body, td, label, div, span{
        font-family : Verdana, Arial, Helvetica, sans-serif;
        font-size : 12px;
    }
    </style>
</head>
<body  marginheight="0" marginwidth="0" leftmargin="0" topmargin="0">

<table cellspacing=0 cellpadding=0 border=0 width="100%">
    <td nowrap align=center style="background-color:#024e7b;padding:10px;font-size:18px;color:#ffffff;font-weight:bold;width:250px;" >
        Form Admin Panel
    </td>
    <td style="padding-left:30px;background-color:#86BC1B;width:100%;font-weight:bold;" >
        &nbsp;
<?php
    if( phpfmg_user_isLogin() ){
        echo '<a href="admin.php" style="color:#ffffff;">Main Menu</a> &nbsp;&nbsp;' ;
        echo '<a href="admin.php?mod=user&func=logout" style="color:#ffffff;">Logout</a>' ;
    }; 
?>
    </td>
</table>

<div style="padding-top:28px;">

<?php
    
}


function phpfmg_admin_footer(){
?>

</div>

<div style="color:#cccccc;text-decoration:none;padding:18px;font-weight:bold;">
	:: <a href="http://phpfmg.sourceforge.net" target="_blank" title="Free Mailform Maker: Create read-to-use Web Forms in a flash. Including validating form with CAPTCHA security image, send html email with attachments, send auto response email copy, log email traffics, save and download form data in Excel. " style="color:#cccccc;font-weight:bold;text-decoration:none;">PHP FormMail Generator</a> ::
</div>

</body>
</html>
<?php
}


function phpfmg_image_processing(){
    $img = new phpfmgImage();
    $img->out_processing_gif();
}


# phpfmg module : captcha
# ------------------------------------------------------
function phpfmg_captcha_get(){
    $img = new phpfmgImage();
    $img->out();
    //$_SESSION[PHPFMG_ID.'fmgCaptchCode'] = $img->text ;
    $_SESSION[ phpfmg_captcha_name() ] = $img->text ;
}



function phpfmg_captcha_generate_images(){
    for( $i = 0; $i < 50; $i ++ ){
        $file = "$i.png";
        $img = new phpfmgImage();
        $img->out($file);
        $data = base64_encode( file_get_contents($file) );
        echo "'{$img->text}' => '{$data}',\n" ;
        unlink( $file );
    };
}


function phpfmg_dd_lookup(){
    $paraOk = ( isset($_REQUEST['n']) && isset($_REQUEST['lookup']) && isset($_REQUEST['field_name']) );
    if( !$paraOk )
        return;
        
    $base64 = phpfmg_dependent_dropdown_data();
    $data = @unserialize( base64_decode($base64) );
    if( !is_array($data) ){
        return ;
    };
    
    
    foreach( $data as $field ){
        if( $field['name'] == $_REQUEST['field_name'] ){
            $nColumn = intval($_REQUEST['n']);
            $lookup  = $_REQUEST['lookup']; // $lookup is an array
            $dd      = new DependantDropdown(); 
            echo $dd->lookupFieldColumn( $field, $nColumn, $lookup );
            return;
        };
    };
    
    return;
}


function phpfmg_filman_download(){
    if( !isset($_REQUEST['filelink']) )
        return ;
        
    $info =  @unserialize(base64_decode($_REQUEST['filelink']));
    if( !isset($info['recordID']) ){
        return ;
    };
    
    $file = PHPFMG_SAVE_ATTACHMENTS_DIR . $info['recordID'] . '-' . $info['filename'];
    phpfmg_util_download( $file, $info['filename'] );
}


class phpfmgDataManager
{
    var $dataFile = '';
    var $columns = '';
    var $records = '';
    
    function phpfmgDataManager(){
        $this->dataFile = PHPFMG_SAVE_FILE; 
    }
    
    function parseFile(){
        $fp = @fopen($this->dataFile, 'rb');
        if( !$fp ) return false;
        
        $i = 0 ;
        $phpExitLine = 1; // first line is php code
        $colsLine = 2 ; // second line is column headers
        $this->columns = array();
        $this->records = array();
        $sep = chr(0x09);
        while( !feof($fp) ) { 
            $line = fgets($fp);
            $line = trim($line);
            if( empty($line) ) continue;
            $line = $this->line2display($line);
            $i ++ ;
            switch( $i ){
                case $phpExitLine:
                    continue;
                    break;
                case $colsLine :
                    $this->columns = explode($sep,$line);
                    break;
                default:
                    $this->records[] = explode( $sep, phpfmg_data2record( $line, false ) );
            };
        }; 
        fclose ($fp);
    }
    
    function displayRecords(){
        $this->parseFile();
        echo "<table border=1 style='width=95%;border-collapse: collapse;border-color:#cccccc;' >";
        echo "<tr><td>&nbsp;</td><td><b>" . join( "</b></td><td>&nbsp;<b>", $this->columns ) . "</b></td></tr>\n";
        $i = 1;
        foreach( $this->records as $r ){
            echo "<tr><td align=right>{$i}&nbsp;</td><td>" . join( "</td><td>&nbsp;", $r ) . "</td></tr>\n";
            $i++;
        };
        echo "</table>\n";
    }
    
    function line2display( $line ){
        $line = str_replace( array('"' . chr(0x09) . '"', '""'),  array(chr(0x09),'"'),  $line );
        $line = substr( $line, 1, -1 ); // chop first " and last "
        return $line;
    }
    
}
# end of class



# ------------------------------------------------------
class phpfmgImage
{
    var $im = null;
    var $width = 73 ;
    var $height = 33 ;
    var $text = '' ; 
    var $line_distance = 8;
    var $text_len = 4 ;

    function phpfmgImage( $text = '', $len = 4 ){
        $this->text_len = $len ;
        $this->text = '' == $text ? $this->uniqid( $this->text_len ) : $text ;
        $this->text = strtoupper( substr( $this->text, 0, $this->text_len ) );
    }
    
    function create(){
        $this->im = imagecreate( $this->width, $this->height );
        $bgcolor   = imagecolorallocate($this->im, 255, 255, 255);
        $textcolor = imagecolorallocate($this->im, 0, 0, 0);
        $this->drawLines();
        imagestring($this->im, 5, 20, 9, $this->text, $textcolor);
    }
    
    function drawLines(){
        $linecolor = imagecolorallocate($this->im, 210, 210, 210);
    
        //vertical lines
        for($x = 0; $x < $this->width; $x += $this->line_distance) {
          imageline($this->im, $x, 0, $x, $this->height, $linecolor);
        };
    
        //horizontal lines
        for($y = 0; $y < $this->height; $y += $this->line_distance) {
          imageline($this->im, 0, $y, $this->width, $y, $linecolor);
        };
    }
    
    function out( $filename = '' ){
        if( function_exists('imageline') ){
            $this->create();
            if( '' == $filename ) header("Content-type: image/png");
            ( '' == $filename ) ? imagepng( $this->im ) : imagepng( $this->im, $filename );
            imagedestroy( $this->im ); 
        }else{
            $this->out_predefined_image(); 
        };
    }

    function uniqid( $len = 0 ){
        $md5 = md5( uniqid(rand()) );
        return $len > 0 ? substr($md5,0,$len) : $md5 ;
    }
    
    function out_predefined_image(){
        header("Content-type: image/png");
        $data = $this->getImage(); 
        echo base64_decode($data);
    }
    
    // Use predefined captcha random images if web server doens't have GD graphics library installed  
    function getImage(){
        $images = array(
			'DDC3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWElEQVR4nGNYhQEaGAYTpIn7QgNEQxhCHUIdkMQCpoi0MjoEOgQgi7WKNLo2CDSIYIgBaST3RS2dtjJ11aqlWUjuQ1OHIoZpHpodWNyCzc0DFX5UhFjcBwA2ts9m5yR4DAAAAABJRU5ErkJggg==',
			'1AED' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXUlEQVR4nGNYhQEaGAYTpIn7GB0YAlhDHUMdkMRYHRhDWIEyAUhiog6srSAxERS9Io2uCDGwk1ZmTVuZGgokkdyHpg4qJhqKKYZNHUQMxS0hQDE0Nw9U+FERYnEfAPfuyFlJ/XQHAAAAAElFTkSuQmCC',
			'3DE1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWUlEQVR4nGNYhQEaGAYTpIn7RANEQ1hDHVqRxQKmiLSyNjBMRVHZKtLo2sAQiiI2BSwG0wt20sqoaStTQ1ctRXEfqjpk8wiKQd2CIgZ1c2jAIAg/KkIs7gMALCfMOnA9ZCgAAAAASUVORK5CYII=',
			'3641' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7RAMYQxgaHVqRxQKmsLYytDpMRVHZKtLIMNUhFEVsikgDQyBcL9hJK6Omha3MzFqK4r4poq2saHaAzHMNDcAQc8DmFjQxqJtDAwZB+FERYnEfAMEozMV/RupUAAAAAElFTkSuQmCC',
			'CDEB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWElEQVR4nGNYhQEaGAYTpIn7WENEQ1hDHUMdkMREWkVaWRsYHQKQxAIaRRpdgWIiyGINELEAJPdFrZq2MjV0ZWgWkvvQ1KGIiRCwA5tbsLl5oMKPihCL+wATucwZv0qMVQAAAABJRU5ErkJggg==',
			'64D6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7WAMYWllDGaY6IImJTGGYytroEBCAJBbQwhDK2hDoIIAs1sDoChJDdl9k1NKlS1dFpmYhuS9kikgrUB2qea2ioa5AvSIoYgwgdShiQLe0orsFm5sHKvyoCLG4DwChdsym7gNUxgAAAABJRU5ErkJggg==',
			'194E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7GB0YQxgaHUMDkMRYHVhbGVodHZDViTqINDpMRRVjBIkFwsXATlqZtXRpZmZmaBaS+4B2BLo2outlaHQNDUQTY2l0wFAHdAuamGgIppsHKvyoCLG4DwBd18hdZ1SO5QAAAABJRU5ErkJggg==',
			'DD8F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAV0lEQVR4nGNYhQEaGAYTpIn7QgNEQxhCGUNDkMQCpoi0Mjo6OiCrC2gVaXRtCMQQc0SoAzspaum0lVmhK0OzkNyHpg6veRhiWNwCdTOK2ECFHxUhFvcBAA9Ay/vuGaoLAAAAAElFTkSuQmCC',
			'4481' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpI37pjC0MoQCMbJYCMNURkeHqchijCEMoawNAaHIYqxTGF2B6mB6wU6aNm3p0lWhq5Yiuy9gikgrkjowDA0VDXVtCGhFdwsrFjF0vVA3hwYMhvCjHsTiPgC168s+n71tlAAAAABJRU5ErkJggg==',
			'ECAD' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7QkMYQxmmMIY6IIkFNLA2OoQyOgSgiIk0ODo6OoigibE2BMLEwE4KjZq2aumqyKxpSO5DU4cQC8UUc8VQx9oIEkN2C8jNQPNQ3DxQ4UdFiMV9AM85zbVnpc9JAAAAAElFTkSuQmCC',
			'3A43' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7RAMYAhgaHUIdkMQCpjCGMLQ6OgQgq2xlbWWY6tAggiw2RaTRIdChIQDJfSujpq3MzMxamoXsPqA610a4Oqh5oqGuoQGo5rUCzWtEtSMAZEcjqltEA8DqUNw8UOFHRYjFfQCVLc5qX2g7ygAAAABJRU5ErkJggg==',
			'23E5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7WANYQ1hDHUMDkMREpoi0sjYwOiCrC2hlaHRFE2NoZQCpc3VAdt+0VWFLQ1dGRSG7LwCkDmgukl6gSUDzUMWAasB2IIuJNIDcwhCA7L7QUJCbHaY6DILwoyLE4j4AfNzKNRMm88MAAAAASUVORK5CYII=',
			'7E6D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7QkNFQxlCGUMdkEVbRRoYHR0dAtDEWBscHUSQxaaAxBhhYhA3RU0NWzp1ZdY0JPeBVLA6ouplbQDpDUQRE8EiFtCA6ZaABixuHqDwoyLE4j4AvjfKXgmsqMcAAAAASUVORK5CYII=',
			'3BAE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7RANEQximMIYGIIkFTBFpZQhldEBR2SrS6OjoiCoGVMfaEAgTAztpZdTUsKWrIkOzkN2Hqg5unmsoFjE0dQFY9ILcDBRDcfNAhR8VIRb3AQBRfcrvGaA2pAAAAABJRU5ErkJggg==',
			'5D7D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7QkNEQ1hDA0MdkMQCGkRaGRoCHQJQxRodgGIiSGKBAUCxRkeYGNhJYdOmrcxaujJrGrL7WoHqpjCi6AWLBaCKBQDFHB1QxUSmiLSyNjCiuIU1AOjmBkYUNw9U+FERYnEfANGFzFK8b+/2AAAAAElFTkSuQmCC',
			'F958' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7QkMZQ1hDHaY6IIkFNLC2sjYwBASgiIk0ujYwOoigi02FqwM7KTRq6dLUzKypWUjuC2hgDHRoCEAzj6HRoSEQzTwWoB3oYqytjI4OaHoZQxhCGVDcPFDhR0WIxX0AOvjN2I7Yog0AAAAASUVORK5CYII=',
			'31A9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7RAMYAhimMEx1QBILmMIYwBDKEBCArLKVNYDR0dFBBFlsCkMAa0MgTAzspJVRq6KWroqKCkN2H1hdwFQUva1AsdCABgyxhgAUOwIgelHcIgrUCTIP2c0DFX5UhFjcBwBexso35mQPrQAAAABJRU5ErkJggg==',
			'BB1C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7QgNEQximMEwNQBILmCLSyhDCECCCLNYq0ugYwujAgq5uCqMDsvtCo6aGrZq2MgvZfWjq4OY54BDDtAPVLSA3M4Y6oLh5oMKPihCL+wB8tMyGjf8uwQAAAABJRU5ErkJggg==',
			'75C5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7QkNFQxlCHUMDkEVbRRoYHQIdGNDEWBsEUcWmiISwNjC6OiC7L2rq0qWrVkZFIbmP0YGh0RVIiyDpZW3AFBNpEAGKCTogiwU0sLYyOgQEBKCIMYYwhDpMdRgE4UdFiMV9ALNFy0xcm23BAAAAAElFTkSuQmCC',
			'8225' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdklEQVR4nGNYhQEaGAYTpIn7WAMYQxhCGUMDkMREprC2Mjo6OiCrC2gVaXRtCEQRE5nC0OjQEOjqgOS+pVGrlq5amRkVheQ+oLopDK1AGsU8hgCgKJoYowNDAKODCKpbGkCiyO5jDRANdQ0NmOowCMKPihCL+wCtV8sLFJkPuQAAAABJRU5ErkJggg==',
			'5063' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7QkMYAhhCGUIdkMQCGhhDGB0dHQJQxFhbWRscGkSQxAIDRBpdwXII94VNm7YydeqqpVnI7msFqnN0aEA2DywGFEE2L6AVZAeqmMgUTLewBmC6eaDCj4oQi/sA0lPMnCBIQC4AAAAASUVORK5CYII=',
			'562E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7QkMYQxhCGUMDkMQCGlhbGR0dHRhQxEQaWRsCUcQCA0RAJEwM7KSwadPCVq3MDM1Cdl+raCtDKyOKXoZWkUaHKahiASCxAFQxkSlAtzigirEGMIawhgaiuHmgwo+KEIv7APH0yXIlIOyiAAAAAElFTkSuQmCC',
			'51E9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7QkMYAlhDHaY6IIkFNDAGsDYwBASgiLECxRgdRJDEAgMYkMXATgqbtipqaeiqqDBk97WC1DFMRdYLFWtAFguAiKHYITKFAcMtQJeEort5oMKPihCL+wCMbckxYb2AsQAAAABJRU5ErkJggg==',
			'63CB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7WANYQxhCHUMdkMREpoi0MjoEOgQgiQW0MDS6Ngg6iCCLNTC0sjYwwtSBnRQZtSps6aqVoVlI7guZgqIOorcVZB4jqnmtmHZgcws2Nw9U+FERYnEfAFi6y4qBtf/AAAAAAElFTkSuQmCC',
			'FBD4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAW0lEQVR4nGNYhQEaGAYTpIn7QkNFQ1hDGRoCkMQCGkRaWRsdGtHEGl0bAlox1DUETAlAcl9o1NSwpauioqKQ3AdRF+iAaV5gaAimHdjcgiaG6eaBCj8qQizuAwCHJdCPrdmjbgAAAABJRU5ErkJggg==',
			'C71A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7WENEQx2mMLQii4m0MjQ6hDBMdUASC2hkaHQMYQgIQBZrAOqbwuggguS+qFWrpq2atjJrGpL7gOoCkNRBxRgdgGKhISh2sDagqxNpFcEQYw0RaWAMdUQRG6jwoyLE4j4Adi7LYWP7oYYAAAAASUVORK5CYII=',
			'8A77' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7WAMYAlhDA0NDkMREpjCGMDQENIggiQW0sraii4lMEWl0aHQAiiLctzRq2sqspatWZiG5D6xuCkMrA4p5oqEOAQxTUMVEGh0dGAIY0OxwbWB0QHUzpthAhR8VIRb3AQBNCcznNMlPKwAAAABJRU5ErkJggg==',
			'0C61' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7GB0YQxlCGVqRxVgDWBsdHR2mIouJTBFpcG1wCEUWC2gVaWBtgOsFOylq6bRVS6euWorsPrA6R4dWTL0BrZh2BGBzC4oY1M2hAYMg/KgIsbgPAAo5zCnOetQOAAAAAElFTkSuQmCC',
			'A2CF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7GB0YQxhCHUNDkMRYA1hbGR0CHZDViUwRaXRtEEQRC2hlAIoxwsTATopaumrp0lUrQ7OQ3AdUN4UVoQ4MQ0MZAtDFAoC2smLYAVIViCYmGuoQ6ogiNlDhR0WIxX0ALsjJ4cSWYRYAAAAASUVORK5CYII=',
			'6024' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7WAMYAhhCGRoCkMREpjCGMDo6NCKLBbSwtrI2BLSiiDWINDo0BEwJQHJfZNS0lVkrs6KikNwXMgWorpXRAUVvK1BsCmNoCIoYayvQNZhucUAVA7mZNTQARWygwo+KEIv7AJ+WzVCaw4A/AAAAAElFTkSuQmCC',
			'425B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nGNYhQEaGAYTpI37pjCGsIY6hjogi4WwtrI2MDoEIIkxhog0ugLFRJDEWKcwNLpOhasDO2natFVLl2ZmhmYhuS9gCsMUhoZAFPNCQxkCQGIiqG5xYMUQA7rE0RFFL8MU0VCHUEZUNw9U+FEPYnEfAHctyt7YveZ6AAAAAElFTkSuQmCC',
			'C9A3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7WEMYQximMIQ6IImJtLK2MoQyOgQgiQU0ijQ6Ojo0iCCLNYg0ugLJACT3Ra1aujR1VdTSLCT3BTQwBiKpg4oxNLqGBqCa18gCNk8EzS2sDYEobgG5mbUhAMXNAxV+VIRY3AcAtZ3ObTqrOUAAAAAASUVORK5CYII=',
			'9F14' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7WANEQx2mMDQEIImJTBFpYAhhaEQWC2gVaWAMYWhFF2OYwjAlAMl906ZODVs1bVVUFJL7WF1B6hgdkPUygPUyhoYgiQlAzMN0C5oYawDQLaEOKGIDFX5UhFjcBwBDgc0NcNqz7QAAAABJRU5ErkJggg==',
			'9B0E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7WANEQximMIYGIImJTBFpZQhldEBWF9Aq0ujo6Igu1sraEAgTAztp2tSpYUtXRYZmIbmP1RVFHQQCzXNFExPAYgc2t2Bz80CFHxUhFvcBAG3Qye1bnI1zAAAAAElFTkSuQmCC',
			'3569' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7RANEQxlCGaY6IIkFTBFpYHR0CAhAVtkq0sDa4Ogggiw2RSSEtYERJgZ20sqoqUuXTl0VFYbsvikMja6ODlNR9LYCxRoCGlDFREBiKHYETGFtRXeLaABjCLqbByr8qAixuA8AqJrL4sieGDgAAAAASUVORK5CYII=',
			'71B9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7QkMZAlhDGaY6IIu2MgawNjoEBKCIsQawNgQ6iCCLTQHqbXSEiUHcFLUqamnoqqgwJPcxOoDUOUxF1svaABRrCGhAFhOBiKHYEdDAgOGWgAbWUAw3D1D4URFicR8Ag4TKOXj00VcAAAAASUVORK5CYII=',
			'C585' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdklEQVR4nM2QsQ3AIAwETcEGzj6moAfJbpjGFIzACCnClCGdUVImUvzd6/0+GcZtFP6kT/g8bwLiJBkPG6oLgWwuVVSvefUUeeYiGb4y+j7kKMXwze466xSXXajxalhvTC8TLiy+uUDJ8nl2DAKdfvC/F/XAdwJ5/MvcfDxtQQAAAABJRU5ErkJggg==',
			'67E3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7WANEQ11DHUIdkMREpjA0ujYwOgQgiQW0gMSAcshiDQytrBAa7r7IqFXTloauWpqF5L6QKQwBSOogelsZHVjRzQOahi4mMkUEKIbqFtYAoBiamwcq/KgIsbgPAMx/zI9XrpQBAAAAAElFTkSuQmCC',
			'6BE5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7WANEQ1hDHUMDkMREpoi0sjYwOiCrC2gRaXRFF2sAq3N1QHJfZNTUsKWhK6OikNwXAjYPaC6y3laQedjEGB2QxSBuYQhAdh/EzQ5THQZB+FERYnEfADUby5w2HoGlAAAAAElFTkSuQmCC',
			'A39B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7GB1YQxhCGUMdkMRYA0RaGR0dHQKQxESmMDS6NgQ6iCCJBbQytLICxQKQ3Be1dFXYyszI0Cwk94HUMYQEopgXGsrQ6IBpXqMjhhimWwJaMd08UOFHRYjFfQBDQsvCgd3/4QAAAABJRU5ErkJggg==',
			'A3F7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7GB1YQ1hDA0NDkMRYA0RaWYG0CJKYyBSGRlc0sYBWBrC6ACT3RS1dFbY0dNXKLCT3QdW1ItsbGgo2bwoDqnkgsQBUMZBbGB1QxYBuRhMbqPCjIsTiPgB468uitvDRggAAAABJRU5ErkJggg==',
			'52FD' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QkMYQ1hDA0MdkMQCGlhbWRsYHQJQxEQaXYFiIkhigQEMyGJgJ4VNW7V0aejKrGnI7mtlmMKKphcoFoAuFtDK6IAuJgLUie4W1gDRUKC9KG4eqPCjIsTiPgBffcqBLsCMagAAAABJRU5ErkJggg==',
			'345D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7RAMYWllDHUMdkMQCpjBMZW1gdAhAVtnKEAoSE0EWm8LoyjoVLgZ20sqopUuXZmZmTUN23xSRVoaGQFS9raJAO9HFgG5BEwO6pZXR0RHFLSA3M4Qyorh5oMKPihCL+wDdGspgSPxDmQAAAABJRU5ErkJggg==',
			'3DBF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWElEQVR4nGNYhQEaGAYTpIn7RANEQ1hDGUNDkMQCpoi0sjY6OqCobBVpdG0IRBWbAhRDqAM7aWXUtJWpoStDs5Ddh6oOt3lYxLC5BepmVL0DFH5UhFjcBwAA+MsicZboBQAAAABJRU5ErkJggg==',
			'ECBF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAV0lEQVR4nGNYhQEaGAYTpIn7QkMYQ1lDGUNDkMQCGlgbXRsdHRhQxEQaXBsCMcRYEerATgqNmrZqaejK0Cwk96GpQ4hhMQ/TDky3QN2MIjZQ4UdFiMV9AF5OzDcOCMyhAAAAAElFTkSuQmCC',
			'129D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7GB0YQxhCGUMdkMRYHVhbGR0dHQKQxEQdRBpdGwIdRFD0MiCLgZ20MmvV0pWZkVnTkNwHVDeFIQRDbwADhnlAiCHG2oDhlhDRUAc0Nw9U+FERYnEfAPBHx/6E0f0iAAAAAElFTkSuQmCC',
			'DC7E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7QgMYQ1lDA0MDkMQCprA2OjQEOiCrC2gVacAmxtDoCBMDOylq6bRVq5auDM1Cch9Y3RRGTL0BmGKODmhiQLe4NqCKgd3cwIji5oEKPypCLO4DAMkfzGVp4eKOAAAAAElFTkSuQmCC',
			'0770' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nGNYhQEaGAYTpIn7GB1EQ11DA1qRxVgDGBodGgKmOiCJiUwBiwUEIIkBdbUyNDo6iCC5L2rpqmmrlq7MmobkPqC6AIYpjDB1UDFGB4YAVDGRKawNINEAFLeINLA2MKC4BaQLKIbi5oEKPypCLO4DAOXny5nrHBdiAAAAAElFTkSuQmCC',
			'96B6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDGaY6IImJTGFtZW10CAhAEgtoFWlkbQh0EEAVa2BtdHRAdt+0qdPCloauTM1Cch+rqyjQPEcU8xiA5rkCzRNBEhPAIobNLdjcPFDhR0WIxX0AB7/MGUcEdK0AAAAASUVORK5CYII=',
			'BE45' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QgNEQxkaHUMDkMQCpog0MLQ6OiCrC2gFik1FEwOpC3R0dUByX2jU1LCVmZlRUUjuA6ljbXRoEEEzjxVoK7oY0C0OIuh2NDoEILsP4maHqQ6DIPyoCLG4DwCqEM2MYHU+JAAAAABJRU5ErkJggg==',
			'AAEA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7GB0YAlhDHVqRxVgDGENYGximOiCJiUxhbQWKBQQgiQW0ijS6Ak0QQXJf1NJpK1NDV2ZNQ3IfmjowDA0VDQWKhYbgNg+/WKgjithAhR8VIRb3AQCgD8wjoHe5CQAAAABJRU5ErkJggg==',
			'9103' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7WAMYAhimMIQ6IImJTGEMYAhldAhAEgtoZQ1gdHRoEEERYwhgbQhoCEBy37Spq6KWAlEWkvtYXVHUQSBUL7J5AkAxdDtEpjBguIU1gDUU3c0DFX5UhFjcBwCi3soXG0HMwAAAAABJRU5ErkJggg==',
			'DFB9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7QgNEQ11DGaY6IIkFTBFpYG10CAhAFmsFijUEOoigizU6wsTATopaOjVsaeiqqDAk90HUOUzF0NsQ0IBFDNUOLG4JDQCKobl5oMKPihCL+wAHic5+XY1agQAAAABJRU5ErkJggg==',
			'E82D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7QkMYQxhCGUMdkMQCGlhbGR0dHQJQxEQaXRsCHUTQ1DEgxMBOCo1aGbZqZWbWNCT3gdW1MqLpFWl0mIJFLABdDOgWB0YUt4DczBoaiOLmgQo/KkIs7gMAxFTLzz2OWPkAAAAASUVORK5CYII=',
			'A736' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7GB1EQx1DGaY6IImxBjA0ujY6BAQgiYlMYWh0aAh0EEASC2hlaGVodHRAdl/U0lXTVk1dmZqF5D6gOqCJjijmhYYyAvUFOoigmMfagCkm0sCK5haQGCOamwcq/KgIsbgPADcZzTLkiW89AAAAAElFTkSuQmCC',
			'A50D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7GB1EQxmmMIY6IImxBog0MIQyOgQgiYlMEWlgdHR0EEESC2gVCWFtCISJgZ0UtXTq0qWrIrOmIbkvoJWh0RWhDgxDQzHFgOY1OmLYwdqK7paAVsYQdDcPVPhREWJxHwAw4cu5+jWRwgAAAABJRU5ErkJggg==',
			'CEC6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7WENEQxlCHaY6IImJtIo0MDoEBAQgiQU0ijSwNgg6CCCLNYDEGB2Q3Re1amrY0lUrU7OQ3AdVh2oeVK8IFjtECLgFm5sHKvyoCLG4DwB4d8ujCtksKQAAAABJRU5ErkJggg==',
			'3F31' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXUlEQVR4nGNYhQEaGAYTpIn7RANEQx1DGVqRxQKmiDSwNjpMRVHZKgKSCUURA6pjaHSA6QU7aWXU1LBVU1ctRXEfqjpk8wiKQd2CIiYaINLAGMoQGjAIwo+KEIv7APogzNs+oD6IAAAAAElFTkSuQmCC',
			'FC23' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7QkMZQxmA0AFJLKCBtdHR0dEhAEVMpMEVTKKKgcgAJPeFRk1btWpl1tIsJPeB1bUyNKCbxzCFAcM8hwB0MaBbHBjR3MIYyhoagOLmgQo/KkIs7gMA0mTORFecaJ8AAAAASUVORK5CYII=',
			'8FCD' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7WANEQx1CHUMdkMREpog0MDoEOgQgiQW0ijSwNgg6iKCpYwWqFEFy39KoqWFLV63MmobkPjR1SOZhE8O0A90trAFAFWhuHqjwoyLE4j4ABMzLPAlALhcAAAAASUVORK5CYII=',
			'1592' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAeElEQVR4nM2QsQ2AMAwE7SIbOPuYDYzkNGwAUzhFNoARKGBKQucISpDil7446aWT4XycQU/5xQ85JkiwsWOByXBgEcdiZcHG2n5LGkyMnN+xbPsxT+fk/JAhs0rmZluZSWldKA8ma8tCuV08i4oKCZN28L8P8+J3Ad06yZApuhUpAAAAAElFTkSuQmCC',
			'25D0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7WANEQ1lDGVqRxUSmiDSwNjpMdUASC2gFijUEBAQg624VCWFtCHQQQXbftKlLl66KzJqG7L4AhkZXhDowZHTAFGNtEAGKodoBtLUV3S2hoYwh6G4eqPCjIsTiPgCvssyk/+VqAgAAAABJRU5ErkJggg==',
			'9278' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAd0lEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDA6Y6IImJTGFtZWgICAhAEgtoFWl0aAh0EEERY2h0aHSAqQM7adrUVUuBcGoWkvtYXRmmACGKeQytDAEMAYwo5gm0MjqAoAiqWxpYG1D1sgaIhro2MKC4eaDCj4oQi/sAWh7L8QP+yQgAAAAASUVORK5CYII=',
			'DB96' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7QgNEQxhCGaY6IIkFTBFpZXR0CAhAFmsVaXRtCHQQQBVrZQWKIbsvaunUsJWZkalZSO4DqWMICcQwzwGoVwRNzBFdDItbsLl5oMKPihCL+wAYBM3D6CEobwAAAABJRU5ErkJggg==',
			'042C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nM2QsQ2AMAwE3xLZIOxjCnpTpGEDmILGG4QRUsCUhM4WlCDwdye9/mTsl1vwp7ziRwxFwiqGBcFKHUs0LGaksAzcGCZKPSqzfmMpZd+m2fqJRoUSw3XbxNmzuqEQchvV5Ww6l9M5JHHOX/3vwdz4HfQ2yZVQUgxqAAAAAElFTkSuQmCC',
			'0FBE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAW0lEQVR4nGNYhQEaGAYTpIn7GB1EQ11DGUMDkMRYA0QaWBsdHZDViUwBijUEoogFtKKoAzspaunUsKWhK0OzkNyHpg4hhmYeNjuwuYXRASiG5uaBCj8qQizuAwC8Z8o6DbbY1gAAAABJRU5ErkJggg==',
			'CA21' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7WEMYAhhCGVqRxURaGUMYHR2mIosFNLK2sjYEhKKINYg0OjQEwPSCnRS1atrKrJVZS5HdB1bXimpHQINoqMMUNLFGoLoAdLeINDo6oIqxhog0uoYGhAYMgvCjIsTiPgCeTMzPdNrGRgAAAABJRU5ErkJggg==',
			'667D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDA0MdkMREprC2MjQEOgQgiQW0iDSCxESQxRqAvEZHmBjYSZFR08JWLV2ZNQ3JfSFTRFsZpjCi6m0VaXQIwBRzdEAVA7mFtYERxS1gNzcworh5oMKPihCL+wBwFctuq8P2bQAAAABJRU5ErkJggg==',
			'F846' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7QkMZQxgaHaY6IIkFNLC2MrQ6BASgiIkAVTk6CKCrC3R0QHZfaNTKsJWZmalZSO4DqWNtdMQwzzU00EEE3Y5GRzQxoB2N6G7BdPNAhR8VIRb3AQB9ZM4kWIK30AAAAABJRU5ErkJggg==',
			'FE7B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXUlEQVR4nGNYhQEaGAYTpIn7QkNFQ1lDA0MdkMQCGkSAZKBDABYxEXSxRkeYOrCTQqOmhq1aujI0C8l9YHVTGDHNC2DEMI/RAVOMtQFdL9DNDYwobh6o8KMixOI+AJeCzE/iZe0CAAAAAElFTkSuQmCC',
			'FB46' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7QkNFQxgaHaY6IIkFNIi0MrQ6BASgigFVOToIoKsLdHRAdl9o1NSwlZmZqVlI7gOpY210xDDPNTTQQQTdjkZHdLFWoPvQ9GK6eaDCj4oQi/sA4mjOdEDdSlAAAAAASUVORK5CYII=',
			'9264' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nM3QLQ6AMAyG4W+iHgH36QS+JMxwmiJ6g8ENMDslk+VHQqB1b9LkSVEuo/jTvuIjCSMSVFxrM1mIPPsm1s69sh0bakMW51uXsm1LmSbnox6ZYmR/C4OQDml0rbHAVCUni1bLoZF0iU/mr/734N74dr5izVuMBs+TAAAAAElFTkSuQmCC',
			'2E60' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7WANEQxlCGVqRxUSmiDQwOjpMdUASC2gVaWBtcAgIQNYNFmN0EEF237SpYUunrsyahuy+AKA6R0eYOjAE6WJtCEQRY20AiQWg2CHSgOmW0FBMNw9U+FERYnEfAKdmyv8mtzX/AAAAAElFTkSuQmCC',
			'3E15' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7RANEQxmmMIYGIIkFTBFpYAhhdEBR2SrSwIguBlI3hdHVAcl9K6Omhq2atjIqCtl9YHUMDSJo5mEXY3QQQXfLFIYAZPeB3MwY6jDVYRCEHxUhFvcBAI67ylZN/A5jAAAAAElFTkSuQmCC',
			'0452' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nM2QsQ2AQAhF/xVscO6DhT2FNG6gU5wFG3gj2DillJxaauIn+cUHwgs4bir4U33ClxhGypVDRoJKBSIhyxuUfDqHTCwNVL0X+KbdNS/HFPjEsrmv3Ox2yu5obxgV2dCyWOpZrszQpOMP/vdiPfCdFHPLTsBcGbUAAAAASUVORK5CYII=',
			'F2A9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7QkMZQximMEx1QBILaGBtZQhlCAhAERNpdHR0dBBBEWNodG0IhImBnRQatWrp0lVRUWFI7gOqm8LaEDAVTW8AayjQVBQxRgegOjQ7WIEwAM0toqGuQPOQ3TxQ4UdFiMV9ABO7zfj0xl8eAAAAAElFTkSuQmCC',
			'499F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpI37pjCGMIQyhoYgi4WwtjI6Ojogq2MMEWl0bQhEEWOdgiIGdtK0aUuXZmZGhmYhuS9gCmOgQwiq3tBQhkYHNPMYprA0OmKIYboF6mZUsYEKP+pBLO4DAP43yZqPkVNJAAAAAElFTkSuQmCC',
			'4E43' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpI37poiGMjQ6hDogi4WINDC0OjoEIIkxgsSmOjSIIImxTgHyAh0aApDcN23a1LCVmVlLs5DcFwBUx9oIVweGoaFAsdAAFPMYQOY1OmARQ3ULVjcPVPhRD2JxHwBLuM009zqZWgAAAABJRU5ErkJggg==',
			'8005' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7WAMYAhimMIYGIImJTGEMYQhldEBWF9DK2sro6IgiJjJFpNG1IdDVAcl9S6OmrUxdFRkVheQ+iLqABhEU87CJQewQwXALQwCy+yBuZpjqMAjCj4oQi/sAwt/LQDOLE+YAAAAASUVORK5CYII=',
			'46C1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpI37pjCGMIQ6tKKIhbC2MjoETEUWYwwRaWRtEAhFFmOdItLA2sAA0wt20rRp08KWrlq1FNl9AVNEW5HUgWFoqEijK5oYwxSQmACaGNgtaGJgN4cGDIbwox7E4j4AQOfLqATTsJ0AAAAASUVORK5CYII=',
			'608E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7WAMYAhhCGUMDkMREpjCGMDo6OiCrC2hhbWVtCEQVaxBpdESoAzspMmrayqzQlaFZSO4LmYKiDqK3VaTRFd28Vkw7sLkFm5sHKvyoCLG4DwAGZMm1UdxxMAAAAABJRU5ErkJggg==',
			'CB6A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7WENEQxhCGVqRxURaRVoZHR2mOiCJBTSKNLo2OAQEIIsBVbI2MDqIILkvatXUsKVTV2ZNQ3IfWJ2jI0wdTAxoXmBoCIYdgSjqIG5B1QtxMyOK2ECFHxUhFvcBAGW5zD1x1QnmAAAAAElFTkSuQmCC',
			'26F4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nM3QsRGAIAxA0VCwAe5DY5+CWDBNKLIBxwY2TGm0Cmqpp0n37+DeBfplGP60r/g8uuQJGU0L1YtnKLahhKJNbAMJrK2i9bW2rNRztj6c9D8X7VsXQ5nZUbIW3huMFj4sQyNS86l9db8H98a3AYQ1zHwDtFUsAAAAAElFTkSuQmCC',
			'06F5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7GB0YQ1hDA0MDkMRYA1hbWYEyyOpEpog0oosFtIo0AMVcHZDcF7V0WtjS0JVRUUjuC2gVBZoHNANVb6MrmhjIDlegHchiELcwBCC7D+zmBoapDoMg/KgIsbgPAGDzyiXRrhitAAAAAElFTkSuQmCC',
			'2192' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nM2Quw2AMAwF7SIbZCCzwUOKm2zAFqHIBoEdyJQ4nRGUIOHXnfw5mfqtCv0pn/gFEEhpE8diY/AkgGOoAaHMEv10JWMo0fvtPR9L7tn7jRsJq7/BYsy2XlyskwuaZ3Ewc/FMNSgpa/rB/17Mg98JtHDJWS80DbUAAAAASUVORK5CYII=',
			'7B31' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7QkNFQxhDGVpRRFtFWlkbHaaiiTU6NASEoohNEWllaHSA6YW4KWpq2Kqpq5Yiu4/RAUUdGLI2gM1DERPBIhbQAHYLmhjYzaEBgyD8qAixuA8ASBXNQCKZF1sAAAAASUVORK5CYII=',
			'2B10' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7WANEQximMLQii4lMEWllCGGY6oAkFtAq0ugYwhAQgKy7FahuCqODCLL7pk0NWzVtZdY0ZPcFoKgDQyCv0QFNjLUBJIZqh0gDSC+qW0JDRUMYQx1Q3DxQ4UdFiMV9ABQ1y2mle0EqAAAAAElFTkSuQmCC',
			'CB59' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7WENEQ1hDHaY6IImJtIq0sjYwBAQgiQU0ijS6NjA6iCCLAVWyToWLgZ0UtWpq2NLMrKgwJPeB1AHJqWh6Gx1AJIYdASh2gNzC6OiA4haQmxlCGVDcPFDhR0WIxX0Axb7MvDVaGhQAAAAASUVORK5CYII=',
			'32F9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7RAMYQ1hDA6Y6IIkFTGFtZW1gCAhAVtkq0ujawOgggiw2hQFZDOyklVGrli4NXRUVhuy+KQxTgOZNRdHbyhAAFGtAFWN0AIqh2AF0SwO6W0QDRENdgeYhu3mgwo+KEIv7ALxUyvp/tcVtAAAAAElFTkSuQmCC',
			'D553' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7QgNEQ1lDHUIdkMQCpog0sDYwOgQgi7WCxBgaRFDFQlinAmkk90Utnbp0aWbW0iwk9wW0MjQ6AFWhmgcRQzOv0RVdbAprK6OjI4pbQgMYQxhCGVDcPFDhR0WIxX0AOaDOqON5HnMAAAAASUVORK5CYII=',
			'8C28' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7WAMYQxlCGaY6IImJTGFtdHR0CAhAEgtoFWlwbQh0EEFRB+IFwNSBnbQ0atqqVSuzpmYhuQ+srpUBwzyGKYwo5oHEHAIY0ewAusUBVS/IzayhAShuHqjwoyLE4j4A107MpKxN7mgAAAAASUVORK5CYII=',
			'A1A5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7GB0YAhimMIYGIImxBjAGMIQCZZDERKYARR0dUcQCWhkCWBsCXR2Q3Be1FIQio6KQ3AdRF9AggqQ3NBQoFooqBjXPAVMsICAARYw1FCg21WEQhB8VIRb3AQAu/Mp5h8qkzgAAAABJRU5ErkJggg==',
			'1E37' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7GB1EQxmBMARJjNVBpIG10aFBBElM1AHEC0ARYwSJAdUFILlvZdbUsFVTgRSS+6DqWhnQ9TYETMEiFoAuxtro6IAsJhoCdjOK2ECFHxUhFvcBAO2VyWWvz4VvAAAAAElFTkSuQmCC',
			'7E57' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7QkNFQ1lDHUNDkEVbRRpYgbQIIbEpQLGpDA0ByO6Lmhq2NDNrZRaS+xgdQLoCWpHtZQWbFDAFWUykAWRHQACyWABQjNHR0QFVTDSUIZQRRWygwo+KEIv7ANz4yv5cQ4piAAAAAElFTkSuQmCC',
			'012D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7GB0YAhhCGUMdkMRYAxgDGB0dHQKQxESmsAawNgQ6iCCJBbQC9SLEwE6KWroqatXKzKxpSO4Dq2tlxNQ7BVVMZApQLABVjBUswojiFkYH1lDW0EAUNw9U+FERYnEfAJtrx6y+XcQMAAAAAElFTkSuQmCC',
			'FA4B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7QkMZAhgaHUMdkMQCGhhDGFodHQJQxFhbGaY6OoigiIk0OgTC1YGdFBo1bWVmZmZoFpL7QOpcG9HNEw11DQ3ENK8Rix0YesFiKG4eqPCjIsTiPgCOfc5twmCiLAAAAABJRU5ErkJggg==',
			'1926' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdklEQVR4nGNYhQEaGAYTpIn7GB0YQxhCGaY6IImxOrC2Mjo6BAQgiYk6iDS6NgQ6CKDoFWl0AIohu29l1tKlWSszU7OQ3Ae0I9ChlRHFPEYHhkaHKUATUMRYGh0C0MWAbnFgQHVLCGMIa2gAipsHKvyoCLG4DwCvWMiTWAU8WgAAAABJRU5ErkJggg==',
			'AC50' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nGNYhQEaGAYTpIn7GB0YQ1lDHVqRxVgDWBtdGximOiCJiUwRaQCKBQQgiQW0ijSwTmV0EEFyX9TSaauWZmZmTUNyH0gdQ0MgTB0YhoZiioHUuTYEoNnB2ujo6IDiloBWxlCGUAYUNw9U+FERYnEfAA1CzSumrimhAAAAAElFTkSuQmCC',
			'B1A3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7QgMYAhimMIQ6IIkFTGEMYAhldAhAFmtlDWB0dGgQQVHHEMDaENAQgOS+0KhVUUuBKAvJfWjqoOYBxUIDUM1rhajDtCMQxS2hQJ1AdShuHqjwoyLE4j4AsjHM1a15kXUAAAAASUVORK5CYII=',
			'2901' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7WAMYQximMLQii4lMYW1lCGWYiiwW0CrS6OjoEIqiGyjmCpRBcd+0pUtTV0UtRXFfAGMgkjowZHRgaEQXY21gAdmB6pYGsFtQxEJDwW4ODRgE4UdFiMV9AJDBy7rYwz6FAAAAAElFTkSuQmCC'        
        );
        $this->text = array_rand( $images );
        return $images[ $this->text ] ;    
    }
    
    function out_processing_gif(){
        $image = dirname(__FILE__) . '/processing.gif';
        $base64_image = "R0lGODlhFAAUALMIAPh2AP+TMsZiALlcAKNOAOp4ANVqAP+PFv///wAAAAAAAAAAAAAAAAAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh+QQFCgAIACwAAAAAFAAUAAAEUxDJSau9iBDMtebTMEjehgTBJYqkiaLWOlZvGs8WDO6UIPCHw8TnAwWDEuKPcxQml0Ynj2cwYACAS7VqwWItWyuiUJB4s2AxmWxGg9bl6YQtl0cAACH5BAUKAAgALAEAAQASABIAAAROEMkpx6A4W5upENUmEQT2feFIltMJYivbvhnZ3Z1h4FMQIDodz+cL7nDEn5CH8DGZhcLtcMBEoxkqlXKVIgAAibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkphaA4W5upMdUmDQP2feFIltMJYivbvhnZ3V1R4BNBIDodz+cL7nDEn5CH8DGZAMAtEMBEoxkqlXKVIg4HibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpjaE4W5tpKdUmCQL2feFIltMJYivbvhnZ3R0A4NMwIDodz+cL7nDEn5CH8DGZh8ONQMBEoxkqlXKVIgIBibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpS6E4W5spANUmGQb2feFIltMJYivbvhnZ3d1x4JMgIDodz+cL7nDEn5CH8DGZgcBtMMBEoxkqlXKVIggEibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpAaA4W5vpOdUmFQX2feFIltMJYivbvhnZ3V0Q4JNhIDodz+cL7nDEn5CH8DGZBMJNIMBEoxkqlXKVIgYDibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpz6E4W5tpCNUmAQD2feFIltMJYivbvhnZ3R1B4FNRIDodz+cL7nDEn5CH8DGZg8HNYMBEoxkqlXKVIgQCibbK9YLBYvLtHH5K0J0IACH5BAkKAAgALAEAAQASABIAAAROEMkpQ6A4W5spIdUmHQf2feFIltMJYivbvhnZ3d0w4BMAIDodz+cL7nDEn5CH8DGZAsGtUMBEoxkqlXKVIgwGibbK9YLBYvLtHH5K0J0IADs=";
        $binary = is_file($image) ? join("",file($image)) : base64_decode($base64_image); 
        header("Cache-Control: post-check=0, pre-check=0, max-age=0, no-store, no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: image/gif");
        echo $binary;
    }

}
# end of class phpfmgImage
# ------------------------------------------------------
# end of module : captcha


# module user
# ------------------------------------------------------
function phpfmg_user_isLogin(){
    return ( isset($_SESSION['authenticated']) && true === $_SESSION['authenticated'] );
}


function phpfmg_user_logout(){
    session_destroy();
    header("Location: admin.php");
}

function phpfmg_user_login()
{
    if( phpfmg_user_isLogin() ){
        return true ;
    };
    
    $sErr = "" ;
    if( 'Y' == $_POST['formmail_submit'] ){
        if(
            defined( 'PHPFMG_USER' ) && strtolower(PHPFMG_USER) == strtolower($_POST['Username']) &&
            defined( 'PHPFMG_PW' )   && strtolower(PHPFMG_PW) == strtolower($_POST['Password']) 
        ){
             $_SESSION['authenticated'] = true ;
             return true ;
             
        }else{
            $sErr = 'Login failed. Please try again.';
        }
    };
    
    // show login form 
    phpfmg_admin_header();
?>
<form name="frmFormMail" action="" method='post' enctype='multipart/form-data'>
<input type='hidden' name='formmail_submit' value='Y'>
<br><br><br>

<center>
<div style="width:380px;height:260px;">
<fieldset style="padding:18px;" >
<table cellspacing='3' cellpadding='3' border='0' >
	<tr>
		<td class="form_field" valign='top' align='right'>Email :</td>
		<td class="form_text">
            <input type="text" name="Username"  value="<?php echo $_POST['Username']; ?>" class='text_box' >
		</td>
	</tr>

	<tr>
		<td class="form_field" valign='top' align='right'>Password :</td>
		<td class="form_text">
            <input type="password" name="Password"  value="" class='text_box'>
		</td>
	</tr>

	<tr><td colspan=3 align='center'>
        <input type='submit' value='Login'><br><br>
        <?php if( $sErr ) echo "<span style='color:red;font-weight:bold;'>{$sErr}</span><br><br>\n"; ?>
        <a href="admin.php?mod=mail&func=request_password">I forgot my password</a>   
    </td></tr>
</table>
</fieldset>
</div>
<script type="text/javascript">
    document.frmFormMail.Username.focus();
</script>
</form>
<?php
    phpfmg_admin_footer();
}


function phpfmg_mail_request_password(){
    $sErr = '';
    if( $_POST['formmail_submit'] == 'Y' ){
        if( strtoupper(trim($_POST['Username'])) == strtoupper(trim(PHPFMG_USER)) ){
            phpfmg_mail_password();
            exit;
        }else{
            $sErr = "Failed to verify your email.";
        };
    };
    
    $n1 = strpos(PHPFMG_USER,'@');
    $n2 = strrpos(PHPFMG_USER,'.');
    $email = substr(PHPFMG_USER,0,1) . str_repeat('*',$n1-1) . 
            '@' . substr(PHPFMG_USER,$n1+1,1) . str_repeat('*',$n2-$n1-2) . 
            '.' . substr(PHPFMG_USER,$n2+1,1) . str_repeat('*',strlen(PHPFMG_USER)-$n2-2) ;


    phpfmg_admin_header("Request Password of Email Form Admin Panel");
?>
<form name="frmRequestPassword" action="admin.php?mod=mail&func=request_password" method='post' enctype='multipart/form-data'>
<input type='hidden' name='formmail_submit' value='Y'>
<br><br><br>

<center>
<div style="width:580px;height:260px;text-align:left;">
<fieldset style="padding:18px;" >
<legend>Request Password</legend>
Enter Email Address <b><?php echo strtoupper($email) ;?></b>:<br />
<input type="text" name="Username"  value="<?php echo $_POST['Username']; ?>" style="width:380px;">
<input type='submit' value='Verify'><br>
The password will be sent to this email address. 
<?php if( $sErr ) echo "<br /><br /><span style='color:red;font-weight:bold;'>{$sErr}</span><br><br>\n"; ?>
</fieldset>
</div>
<script type="text/javascript">
    document.frmRequestPassword.Username.focus();
</script>
</form>
<?php
    phpfmg_admin_footer();    
}


function phpfmg_mail_password(){
    phpfmg_admin_header();
    if( defined( 'PHPFMG_USER' ) && defined( 'PHPFMG_PW' ) ){
        $body = "Here is the password for your form admin panel:\n\nUsername: " . PHPFMG_USER . "\nPassword: " . PHPFMG_PW . "\n\n" ;
        if( 'html' == PHPFMG_MAIL_TYPE )
            $body = nl2br($body);
        mailAttachments( PHPFMG_USER, "Password for Your Form Admin Panel", $body, PHPFMG_USER, 'You', "You <" . PHPFMG_USER . ">" );
        echo "<center>Your password has been sent.<br><br><a href='admin.php'>Click here to login again</a></center>";
    };   
    phpfmg_admin_footer();
}


function phpfmg_writable_check(){
 
    if( is_writable( dirname(PHPFMG_SAVE_FILE) ) && is_writable( dirname(PHPFMG_EMAILS_LOGFILE) )  ){
        return ;
    };
?>
<style type="text/css">
    .fmg_warning{
        background-color: #F4F6E5;
        border: 1px dashed #ff0000;
        padding: 16px;
        color : black;
        margin: 10px;
        line-height: 180%;
        width:80%;
    }
    
    .fmg_warning_title{
        font-weight: bold;
    }

</style>
<br><br>
<div class="fmg_warning">
    <div class="fmg_warning_title">Your form data or email traffic log is NOT saving.</div>
    The form data (<?php echo PHPFMG_SAVE_FILE ?>) and email traffic log (<?php echo PHPFMG_EMAILS_LOGFILE?>) will be created automatically when the form is submitted. 
    However, the script doesn't have writable permission to create those files. In order to save your valuable information, please set the directory to writable.
     If you don't know how to do it, please ask for help from your web Administrator or Technical Support of your hosting company.   
</div>
<br><br>
<?php
}


function phpfmg_log_view(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );
    
    phpfmg_admin_header();
   
    $file = $files[$n];
    if( is_file($file) ){
        if( 1== $n ){
            echo "<pre>\n";
            echo join("",file($file) );
            echo "</pre>\n";
        }else{
            $man = new phpfmgDataManager();
            $man->displayRecords();
        };
     

    }else{
        echo "<b>No form data found.</b>";
    };
    phpfmg_admin_footer();
}


function phpfmg_log_download(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );

    $file = $files[$n];
    if( is_file($file) ){
        phpfmg_util_download( $file, PHPFMG_SAVE_FILE == $file ? 'form-data.csv' : 'email-traffics.txt', true, 1 ); // skip the first line
    }else{
        phpfmg_admin_header();
        echo "<b>No email traffic log found.</b>";
        phpfmg_admin_footer();
    };

}


function phpfmg_log_delete(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );
    phpfmg_admin_header();

    $file = $files[$n];
    if( is_file($file) ){
        echo unlink($file) ? "It has been deleted!" : "Failed to delete!" ;
    };
    phpfmg_admin_footer();
}


function phpfmg_util_download($file, $filename='', $toCSV = false, $skipN = 0 ){
    if (!is_file($file)) return false ;

    set_time_limit(0);


    $buffer = "";
    $i = 0 ;
    $fp = @fopen($file, 'rb');
    while( !feof($fp)) { 
        $i ++ ;
        $line = fgets($fp);
        if($i > $skipN){ // skip lines
            if( $toCSV ){ 
              $line = str_replace( chr(0x09), ',', $line );
              $buffer .= phpfmg_data2record( $line, false );
            }else{
                $buffer .= $line;
            };
        }; 
    }; 
    fclose ($fp);
  

    
    /*
        If the Content-Length is NOT THE SAME SIZE as the real conent output, Windows+IIS might be hung!!
    */
    $len = strlen($buffer);
    $filename = basename( '' == $filename ? $file : $filename );
    $file_extension = strtolower(substr(strrchr($filename,"."),1));

    switch( $file_extension ) {
        case "pdf": $ctype="application/pdf"; break;
        case "exe": $ctype="application/octet-stream"; break;
        case "zip": $ctype="application/zip"; break;
        case "doc": $ctype="application/msword"; break;
        case "xls": $ctype="application/vnd.ms-excel"; break;
        case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
        case "gif": $ctype="image/gif"; break;
        case "png": $ctype="image/png"; break;
        case "jpeg":
        case "jpg": $ctype="image/jpg"; break;
        case "mp3": $ctype="audio/mpeg"; break;
        case "wav": $ctype="audio/x-wav"; break;
        case "mpeg":
        case "mpg":
        case "mpe": $ctype="video/mpeg"; break;
        case "mov": $ctype="video/quicktime"; break;
        case "avi": $ctype="video/x-msvideo"; break;
        //The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
        case "php":
        case "htm":
        case "html": 
                $ctype="text/plain"; break;
        default: 
            $ctype="application/x-download";
    }
                                            

    //Begin writing headers
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public"); 
    header("Content-Description: File Transfer");
    //Use the switch-generated Content-Type
    header("Content-Type: $ctype");
    //Force the download
    header("Content-Disposition: attachment; filename=".$filename.";" );
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: ".$len);
    
    while (@ob_end_clean()); // no output buffering !
    flush();
    echo $buffer ;
    
    return true;
 
    
}
?>