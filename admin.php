<?php
require_once( dirname(__FILE__).'/form.lib.php' );

define( 'PHPFMG_USER', "lori@fayortho.com" ); // must be a email address. for sending password to you.
define( 'PHPFMG_PW', "7e19b1" );

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
    $public_modules   = false !== strpos('|captcha||ajax|', "|{$mod}|");
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
    if( !phpfmg_user_isLogin() ){
        exit;
    };

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
        
    $filelink =  base64_decode($_REQUEST['filelink']);
    $file = PHPFMG_SAVE_ATTACHMENTS_DIR . basename($filelink);

    // 2016-12-05:  to prevent *LFD/LFI* attack. patch provided by Pouya Darabi, a security researcher in cert.org
    $real_basePath = realpath(PHPFMG_SAVE_ATTACHMENTS_DIR); 
    $real_requestPath = realpath($file);
    if ($real_requestPath === false || strpos($real_requestPath, $real_basePath) !== 0) { 
        return; 
    }; 

    if( !file_exists($file) ){
        return ;
    };
    
    phpfmg_util_download( $file, $filelink );
}


class phpfmgDataManager
{
    var $dataFile = '';
    var $columns = '';
    var $records = '';
    
    function __construct(){
        $this->dataFile = PHPFMG_SAVE_FILE; 
    }

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

    function __construct( $text = '', $len = 4 ){
        $this->phpfmgImage( $text, $len );
    }

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
			'103F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXUlEQVR4nGNYhQEaGAYTpIn7GB0YAhhDGUNDkMRYHRhDWBsdHZDViTqwtjI0BDqg6hVpdECoAztpZda0lVlTV4ZmIbkPTR1CDMM8bHZgcUsI2M0oYgMVflSEWNwHAB96x0ZvftbhAAAAAElFTkSuQmCC',
			'3F67' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7RANEQx1CGUNDkMQCpog0MDo6NIggq2wVaWBtQBObAhIDqkdy38qoqWFLp65amYXsPpA6R4dWBgzzAqZgEQtgwHCLowOqm4GuCGVEERuo8KMixOI+AJW/y3hJPkeKAAAAAElFTkSuQmCC',
			'2C93' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7WAMYQxmA0AFJTGQKa6Ojo6NDAJJYQKtIg2tDQIMIsm6gGCtQLADZfdOmrVqZGbU0C9l9AUBdIXB1YMjoADIJ1TxWIM8RTQxoA4ZbQkMx3TxQ4UdFiMV9APpUzNJrCG3OAAAAAElFTkSuQmCC',
			'6D46' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7WANEQxgaHaY6IImJTBFpZWh1CAhAEgtoEQGqcnQQQBZrAIoFOjoguy8yatrKzMzM1Cwk94VMEWl0bXRENa8VKBYa6CCCJubQ6IgiBnZLI6pbsLl5oMKPihCL+wBrjM4KFS1ltgAAAABJRU5ErkJggg==',
			'632E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7WANYQxhCGUMDkMREpoi0Mjo6OiCrC2hhaHRtCEQVa2BoZUCIgZ0UGbUqbNXKzNAsJPeFTAGqa2VE1dvK0OgwBYtYAKoY2C0OqGIgN7OGBqK4eaDCj4oQi/sALSnJvcUP1IwAAAAASUVORK5CYII=',
			'C612' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nM2QsRGAMAhFfwo3iPvEDSiIhdOQIhtghsiUxkqMlnoXKDjewfEO1EcIRspf/CZ2DMUeDPN5ymAQGUbJJ8cueMukdQrxxm+rZa3lrJcfyZzbXAr33RQUGd2NxhS9i4J6ZxeXyAP878N88TsAFk7MMw3wSZgAAAAASUVORK5CYII=',
			'96E4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDHRoCkMREprC2sjYwNCKLBbSKNALFWtHEGoBiUwKQ3Ddt6rSwpaGroqKQ3MfqKgo0j9EBWS8D0DzXBsbQECQxAbAYAza3oIhhc/NAhR8VIRb3AQDuLczJ/S6QsgAAAABJRU5ErkJggg==',
			'A19A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7GB0YAhhCGVqRxVgDGAMYHR2mOiCJiUxhDWBtCAgIQBILaGUAigU6iCC5L2rpqqiVmZFZ05DcB1LHEAJXB4ahoUCxhsDQEDTzGBtQ1YHFHB3RxFhDGUIZUcQGKvyoCLG4DwDFccmUhS6uYQAAAABJRU5ErkJggg==',
			'2B27' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nM2QMQ6AMAgA6eAP6H9wcMek+AhfQYf+oPoHfaW40eio0d52QHIp7Jen8Cde6es4JpAgyTmsWEJPis5xwTwoNw4KFpsYrm9dpn2bt9n3se2duNtAmKlCbVrUHAN7h2otFMg7kZg6GRv31f89yE3fARbmywPKsEPzAAAAAElFTkSuQmCC',
			'B6A1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7QgMYQximMLQiiwVMYW1lCGWYiiLWKtLI6OgQiqpOpIEVKIPsvtCoaWFLV0UtRXZfwBTRViR1cPNcQ7GIoasDugVdL8jNQLHQgEEQflSEWNwHAGZnzk1UDSQnAAAAAElFTkSuQmCC',
			'0B20' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7GB1EQxhCGVqRxVgDRFoZHR2mOiCJiUwRaXRtCAgIQBILaBUB6gt0EEFyX9TSqWGrVmZmTUNyH1hdKyNMHUys0WEKqhjIDocABhQ7wG5xYEBxC8jNrKEBKG4eqPCjIsTiPgA43stkyXvrIgAAAABJRU5ErkJggg==',
			'A11D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7GB0YAhimMIY6IImxBjAGMIQwOgQgiYlMAYoCxUSQxAJawXphYmAnRS1dFbVq2sqsaUjuQ1MHhqGhmGLY1MHEAlDEWEMZQx1R3DxQ4UdFiMV9AIelyNbE5WkgAAAAAElFTkSuQmCC',
			'8DC0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAW0lEQVR4nGNYhQEaGAYTpIn7WANEQxhCHVqRxUSmiLQyOgRMdUASC2gVaXRtEAgIQFUHFGN0EEFy39KoaStTV63MmobkPjR1SOZhE8OwA8Mt2Nw8UOFHRYjFfQBok80vm/FZsQAAAABJRU5ErkJggg==',
			'D022' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7QgMYAhhCGaY6IIkFTGEMYXR0CAhAFmtlbWVtCHQQQRETaXRoCGgQQXJf1NJpK7NWZq2KQnIfWF0rQ6MDut4pDK0MaHYAXTOFAd0tDkBRNDezhgaGhgyC8KMixOI+AIvlzSTzH2ECAAAAAElFTkSuQmCC',
			'1174' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7GB0YAlhDAxoCkMRYHRgDGBoCGpHFRB1YQWKtAWh6GRodpgQguW9l1qqoVUtXRUUhuQ+sbgqjA4beAMbQEDQxIG5AV8fagComGsIaii42UOFHRYjFfQAPHMi3oq2kHgAAAABJRU5ErkJggg==',
			'C7B0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7WENEQ11DGVqRxURaGRpdGx2mOiCJBTQCxRoCAgKQxRoYWlkbHR1EkNwXtWrVtKWhK7OmIbkPqC4ASR1UjNGBtSEQVayRtYEVzQ6RVpEGVjS3sIYAxdDcPFDhR0WIxX0AwevNVSLfSy8AAAAASUVORK5CYII=',
			'A5D3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7GB1EQ1lDGUIdkMRYA0QaWBsdHQKQxESmAMUaAhpEkMQCWkVCQGIBSO6LWjp16VIgmYXkvoBWhkZXhDowDA2FiKGZh0WMtRXdLQGtjCHobh6o8KMixOI+ALb7zo6/xflVAAAAAElFTkSuQmCC',
			'1469' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7GB0YWhlCGaY6IImxOjBMZXR0CAhAEhN1YAhlbXB0EEHRy+jKCiRFkNy3Mmvp0qVTV0WFIbkPqKKV1dFhKqpe0VDXhoAGETS3sDYEOKCLYbglBNPNAxV+VIRY3AcAe7XImUxps/8AAAAASUVORK5CYII=',
			'11E8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAW0lEQVR4nGNYhQEaGAYTpIn7GB0YAlhDHaY6IImxOjAGsDYwBAQgiYk6sALFGB1E0PUi1IGdtDJrVdTS0FVTs5Dch6YOSQybeXjtgLglhDUU3c0DFX5UhFjcBwDwRsZu0ieAZAAAAABJRU5ErkJggg==',
			'D56F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7QgNEQxlCGUNDkMQCpog0MDo6OiCrC2gVaWBtwBALYW1ghImBnRS1dOrSpVNXhmYhuS+glaHRFcM8oFhDILp5mGJTWFvR3RIawBgCdDOK2ECFHxUhFvcBAE/4y2s3cqvXAAAAAElFTkSuQmCC',
			'04A9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdklEQVR4nGNYhQEaGAYTpIn7GB0YWhmmMEx1QBJjDWCYyhDKEBCAJCYyhSGU0dHRQQRJLKCV0ZW1IRAmBnZS1FIgWBUVFYbkvoBWkVbWhoCpqHpFQ11DAxpEUO0AqUOxA+gWkBiKW0BuBpmH7OaBCj8qQizuAwBOesvSg5c6rAAAAABJRU5ErkJggg==',
			'16C1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7GB0YQxhCHVqRxVgdWFsZHQKmIouJOog0sjYIhKLqFWlgbWCA6QU7aWXWtLClq1YtRXYfo4NoK5I6mN5GV6xiAmhiYLegiImGgN0cGjAIwo+KEIv7APhYyPieipmTAAAAAElFTkSuQmCC',
			'1911' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7GB0YQximMLQii7E6sLYyhDBMRRYTdRBpdAxhCEXVK9LogNALdtLKrKVLs6atWorsPqAdgQ5odjA6MDRiirFgEQO6BU1MNIQxhDHUITRgEIQfFSEW9wEAEF7JDsYYVFQAAAAASUVORK5CYII=',
			'E61C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7QkMYQximMEwNQBILaGBtZQhhCBBBERNpZAxhdGBBFWtgmMLogOy+0KhpYaumrcxCdl9Ag2grkjq4eQ44xFDtALplCqpbQG5mDHVAcfNAhR8VIRb3AQBVA8umOND7OAAAAABJRU5ErkJggg==',
			'ABE3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXUlEQVR4nGNYhQEaGAYTpIn7GB1EQ1hDHUIdkMRYA0RaWYEyAUhiIlNEGl1BNJJYQCtIHZBGcl/U0qlhS0NXLc1Cch+aOjAMDcVqHg47UN0S0Irp5oEKPypCLO4DAAhmzS3oaBuWAAAAAElFTkSuQmCC',
			'64A8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7WAMYWhmmMEx1QBITAfIZQhkCApDEAloYQhkdHR1EkMUaGF1ZGwJg6sBOioxaunTpqqipWUjuC5ki0oqkDqK3VTTUNTQQ1bxWBqA6VDGgWzD0gtwMFENx80CFHxUhFvcBAOpNzQ4P+2mCAAAAAElFTkSuQmCC',
			'6274' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nM2QMQ6AIAxF26E3wPt0cf8DDHKaksgNuIILp5QR0FGjfdtLfvJSqpcz+hOv9AnYS4Chc65IJkPqHXaX1JAHZ5Q0aUHXt8V6NGLs+nyhBuuwzQQCBz84VlaaW0xsdIIlrJP76n8PctN3AgQnzjKNk7AdAAAAAElFTkSuQmCC',
			'B6CC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7QgMYQxhCHaYGIIkFTGFtZXQICBBBFmsVaWRtEHRgQVEn0sDawOiA7L7QqGlhS1etzEJ2X8AU0VYkdXDzXLGKoduB6RZsbh6o8KMixOI+AFZ2zFrhSi6/AAAAAElFTkSuQmCC',
			'5763' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7QkNEQx1CGUIdkMQCGhgaHR0dHQLQxFwbHBpEkMQCAxhaWSFycPeFTVs1benUVUuzkN3XyhDA6ujQgGweQyujAytQBNm8AKBp6GIiU0QaGNHcwhoAVIHm5oEKPypCLO4DAGm6zO9TlJ3HAAAAAElFTkSuQmCC',
			'9A88' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7WAMYAhhCGaY6IImJTGEMYXR0CAhAEgtoZW1lbQh0EEERE2l0RKgDO2na1Gkrs0JXTc1Cch+rK4o6CGwVDXVFM08AaB66mMgUTL2sASKNDmhuHqjwoyLE4j4ASNnMgUovQUsAAAAASUVORK5CYII=',
			'5483' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7QkMYWhlCGUIdkMQCGhimMjo6OgSgioWyAkkRJLHAAEZXRkeHhgAk94VNW7p0VeiqpVnI7msVaUVSBxUTDXVFMy+glaEV3Q6RKQyt6G5hDcB080CFHxUhFvcBAHtGzF/qf7oLAAAAAElFTkSuQmCC',
			'DD76' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7QgNEQ1hDA6Y6IIkFTBFpBZIBAchirSKNDg2BDgLoYo2ODsjui1o6bWXW0pWpWUjuA6ubwohpXgCjgwiamKMDmhjQLawNDCh6wW5uYEBx80CFHxUhFvcBAFE2znm20FoTAAAAAElFTkSuQmCC',
			'F2D4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7QkMZQ1hDGRoCkMQCGlhbWRsdGlHFRBpdGwJaUcUYQGJTApDcFxq1aunSVVFRUUjuA8pPYW0IdEDTGwAUCw1BEWN0YAWSaG5pALoFTUw01BXNzQMVflSEWNwHAPTm0AcZw6AOAAAAAElFTkSuQmCC',
			'51C9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7QkMYAhhCHaY6IIkFNDAGMDoEBASgiLEGsDYIOoggiQUGMADFGGFiYCeFTVsVtXTVqqgwZPe1gtQxTEXWCxVrQBYLAIsJoNghMoUBwy1Al4Siu3mgwo+KEIv7ADI5ybQGhEH5AAAAAElFTkSuQmCC',
			'B8C5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7QgMYQxhCHUMDkMQCprC2MjoEOiCrC2gVaXRtEEQVA6pjbWB0dUByX2jUyrClq1ZGRSG5D6KOoUEEwzxsYoIOIg3obgkIQHYfxM0OUx0GQfhREWJxHwDrLMz0eJUE4AAAAABJRU5ErkJggg==',
			'FCBF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAVklEQVR4nGNYhQEaGAYTpIn7QkMZQ1mBOARJLKCBtdG10dGBAUVMpMG1IRBDjBWhDuyk0Khpq5aGrgzNQnIfmjqEGBbzMO3A5hawm1HEBir8qAixuA8Ag2XMX5MxxysAAAAASUVORK5CYII=',
			'8566' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7WANEQxlCGaY6IImJTBFpYHR0CAhAEgtoFWlgbXB0EEBVF8LawOiA7L6lUVOXLp26MjULyX0iUxgaXR0d0cwDijUEOoig2oEhJjKFtRXdLawBjCHobh6o8KMixOI+APOozB6DixG/AAAAAElFTkSuQmCC',
			'A0D3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7GB0YAlhDGUIdkMRYAxhDWBsdHQKQxESmsLayNgQ0iCCJBbSKNLoCxQKQ3Be1dNrKVCCZheQ+NHVgGBoKEUM1D5sdmG4JaMV080CFHxUhFvcBAA73zgCWbdaQAAAAAElFTkSuQmCC',
			'DD02' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7QgNEQximMEx1QBILmCLSyhDKEBCALNYq0ujo6Ogggibm2hDQIILkvqil01amrooCQoT7oOoaHTD1tjJg2OEwhQGLWzDdzBgaMgjCj4oQi/sApRLO5VZos6oAAAAASUVORK5CYII=',
			'B53B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7QgNEQxmB0AFJLGCKSANro6NDALJYqwiQDHQQQVUXwoBQB3ZSaNTUpaumrgzNQnJfwBSGRgcM84Bi6Oa1imCKTWFtRXdLaABjCLqbByr8qAixuA8AY3bN/UvQi+8AAAAASUVORK5CYII=',
			'F4B0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7QkMZWlmBGFksoIFhKmujw1QHVLFQ1oaAgAAUMUZX1kZHBxEk94VGLV26NHRl1jQk9wU0iLQiqYOKiYa6NgSiiQHdgmEHUAzTLRhuHqjwoyLE4j4AG1PN1llk1M8AAAAASUVORK5CYII=',
			'B459' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdklEQVR4nGNYhQEaGAYTpIn7QgMYWllDHaY6IIkFTGGYytrAEBCALNbKEMrawOgggqKO0ZV1KlwM7KTQqKVLl2ZmRYUhuS9gikgrkJyKordVNNShIaABVQzoloYANDsYWhkdHVDcAnIzQygDipsHKvyoCLG4DwAPrcz78mupngAAAABJRU5ErkJggg==',
			'D057' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7QgMYAlhDHUNDkMQCpjCGsAJpEWSxVtZWTDGRRtepQBrJfVFLp61MzcxamYXkPpA6ByDJgKYXKDaFAcOOgAAGNLcwOjo6oLuZIZQRRWygwo+KEIv7AIUuzQMFsjIrAAAAAElFTkSuQmCC',
			'6BD8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7WANEQ1hDGaY6IImJTBFpZW10CAhAEgtoEWl0bQh0EEEWawCqawiAqQM7KTJqatjSVVFTs5DcFzIFRR1EbysW87CIYXMLNjcPVPhREWJxHwBuqc4IRuLF8wAAAABJRU5ErkJggg==',
			'B875' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QgMYQ1hDA0MDkMQCprC2MjQEOiCrC2gVaXRAFwOpa3R0dUByX2jUyrBVS1dGRSG5D6xuCkODCLp5AZhijg6MDiJodrA2MAQguw/s5gaGqQ6DIPyoCLG4DwB3as0pUbJNzAAAAABJRU5ErkJggg==',
			'F3CB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXElEQVR4nGNYhQEaGAYTpIn7QkNZQxhCHUMdkMQCGkRaGR0CHQJQxBgaXRsEHURQxVpZGxhh6sBOCo1aFbZ01crQLCT3oalDMo8R3TwsdmBzC6abByr8qAixuA8AhGrMgCNhdcEAAAAASUVORK5CYII=',
			'E965' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7QkMYQxhCGUMDkMQCGlhbGR0dHRhQxEQaXRuwiTG6OiC5LzRq6dLUqSujopDcF9DAGOjq6NAggqKXAag3AE2MBSgW6CCC4RaHAGT3QdzMMNVhEIQfFSEW9wEA1uDM5SYh8twAAAAASUVORK5CYII=',
			'2210' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7WAMYQximMLQii4lMYW1lCGGY6oAkFtAq0ugYwhAQgKy7laHRYQqjgwiy+6atWrpq2sqsacjuCwDagFAHhowOIFFUMVaQ6BRUO0SAokAxFLeEhoqGOoY6oLh5oMKPihCL+wCB9srh4vDUaQAAAABJRU5ErkJggg==',
			'C2A3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdklEQVR4nGNYhQEaGAYTpIn7WEMYQximMIQ6IImJtLK2MoQyOgQgiQU0ijQ6Ojo0iCCLNTA0ugLJACT3Ra1atXTpqqilWUjuA8pPYUWog4kFsIYGoJrXyOgAUieC6pYG1oZAFLewhoiGAu1FcfNAhR8VIRb3AQAxtM37FzswGQAAAABJRU5ErkJggg==',
			'ED46' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7QkNEQxgaHaY6IIkFNIi0MrQ6BASgigFVOToIoIsFOjoguy80atrKzMzM1Cwk94HUuTY6YpjnGhroIIJuXqMjulgr0H0oerG5eaDCj4oQi/sAciXO2JAMXDwAAAAASUVORK5CYII=',
			'5001' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7QkMYAhimMLQiiwU0MIYwhDJMRRVjbWV0dAhFFgsMEGl0bQiA6QU7KWzatJWpq6KWorivFUUdTrGAVrAdKGIiU8BuQRFjDQC7OTRgEIQfFSEW9wEA0GXL019oHTYAAAAASUVORK5CYII=',
			'9659' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAeElEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDHaY6IImJTGFtZW1gCAhAEgtoFWlkbWB0EEEVa2CdChcDO2na1GlhSzOzosKQ3MfqKtoKVD0VWS8D0DyHhoAGZDEBoJhrQwCKHSC3MDo6oLgF5GaGUAYUNw9U+FERYnEfAC26y2ykCXavAAAAAElFTkSuQmCC',
			'21DE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7WAMYAlhDGUMDkMREpjAGsDY6OiCrC2hlDWBtCEQRY2hlQBaDuGnaqqilqyJDs5DdF8CAoZfRAVOMtQFTTAQkhuaW0FDWUHQ3D1T4URFicR8A7yLH/ZKvJdwAAAAASUVORK5CYII=',
			'C49D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7WEMYWhlCGUMdkMREWhmmMjo6OgQgiQU0MoSyNgQ6iCCLNTC6IomBnRS1aunSlZmRWdOQ3BcAMjEEXa8o0E40sUaGVkY0MaDOVnS3YHPzQIUfFSEW9wEAvOrLFXN0bjcAAAAASUVORK5CYII=',
			'9B98' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7WANEQxhCGaY6IImJTBFpZXR0CAhAEgtoFWl0bQh0EEEVa2VtCICpAztp2tSpYSszo6ZmIbmP1VWklSEkAMU8BqB5DmjmCQDFHNHEsLkFm5sHKvyoCLG4DwBn8MxPmkJdgAAAAABJRU5ErkJggg==',
			'0A61' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7GB0YAhhCGVqRxVgDGEMYHR2mIouJTGFtZW1wCEUWC2gVaXRtgOsFOylq6bSVqVNXLUV2H1ido0Mrql7RUFcgiWoHyLwANLeINDqi6WV0EGkEuiQ0YBCEHxUhFvcBANfszE4PZS1OAAAAAElFTkSuQmCC',
			'8B99' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7WANEQxhCGaY6IImJTBFpZXR0CAhAEgtoFWl0bQh0EEFTx4oQAztpadTUsJWZUVFhSO4DqWMICZgqgmaeQ0NAA7qYY0MAhh3obsHm5oEKPypCLO4DAIoxzJHa9gqQAAAAAElFTkSuQmCC',
			'C094' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7WEMYAhhCGRoCkMREWhlDGB0dGpHFAhpZW1kbAlpRxBpEGl0bAqYEILkvatW0lZmZUVFRSO4DqXMICXRA1+vQEBgagmYHI1AGi1tQxLC5eaDCj4oQi/sAQ1XN40DhHjQAAAAASUVORK5CYII=',
			'F8B6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAVklEQVR4nGNYhQEaGAYTpIn7QkMZQ1hDGaY6IIkFNLC2sjY6BASgiIk0ujYEOghgqHN0QHZfaNTKsKWhK1OzkNwHVYfVPBGCYtjcgunmgQo/KkIs7gMARIvN+FNx1CUAAAAASUVORK5CYII=',
			'EFDD' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAUUlEQVR4nGNYhQEaGAYTpIn7QkNEQ11DGUMdkMQCGkQaWBsdHQLQxRoCHURwi4GdFBo1NWzpqsisaUjuI0IvfjE0t4SGAMXQ3DxQ4UdFiMV9AH35zTlBAXt2AAAAAElFTkSuQmCC',
			'7D9E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7QkNFQxhCGUMDkEVbRVoZHR0dGFDFGl0bAlHFpqCIQdwUNW1lZmZkaBaS+xgdRBodQlD1sjYAxdDMEwGKOaKJBTRguiWgAYubByj8qAixuA8ADr7Kl8Etk1gAAAAASUVORK5CYII=',
			'D8E1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAV0lEQVR4nGNYhQEaGAYTpIn7QgMYQ1hDHVqRxQKmsLayNjBMRRFrFWl0bWAIRRUDq4PpBTspaunKsKWhq5Yiuw9NHbJ5hMWmYOqFujk0YBCEHxUhFvcBAH8EzUw44NvoAAAAAElFTkSuQmCC',
			'6938' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7WAMYQxhDGaY6IImJTGFtZW10CAhAEgtoEWl0aAh0EEEWawCKIdSBnRQZtXRp1tRVU7OQ3BcyhTHQAd28VgZM81pZMMSwuQWbmwcq/KgIsbgPAGLbzfOV/Mt0AAAAAElFTkSuQmCC',
			'92A1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7WAMYQximMLQii4lMYW1lCGWYiiwW0CrS6OjoEIoqxtDoCiKR3Ddt6qqlS1dFLUV2H6srwxRWhDoIbGUIYA1FFRNoZXRAVwd0SwO6GGuAaCjQ3tCAQRB+VIRY3AcAWoXMedQXtfwAAAAASUVORK5CYII=',
			'9322' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7WANYQxhCGaY6IImJTBFpZXR0CAhAEgtoZWh0bQh0EEEVawWSDSJI7ps2dVXYqpVZq6KQ3MfqygBS2YhsB5g/BaQfAQVAYgEMUxjQ3eLAEIDuZtbQwNCQQRB+VIRY3AcAfrbLcqEJ6VUAAAAASUVORK5CYII=',
			'FD60' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXElEQVR4nGNYhQEaGAYTpIn7QkNFQxhCGVqRxQIaRFoZHR2mOqCKNbo2OAQEYIgxOogguS80atrK1Kkrs6YhuQ+sztERpg5JbyAWsQB0O7C4BdPNAxV+VIRY3AcAuUDOTsu/vr4AAAAASUVORK5CYII=',
			'CF16' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7WENEQx2mMEx1QBITaRVpYAhhCAhAEgtoFGlgDGF0EEAWawCqm8LogOy+qFVTw1ZNW5maheQ+qDpU86B6RdDsQBcDu2UKqltYQ4BuCXVAcfNAhR8VIRb3AQByN8uwoZly5AAAAABJRU5ErkJggg==',
			'5A89' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nGNYhQEaGAYTpIn7QkMYAhhCGaY6IIkFNDCGMDo6BASgiLG2sjYEOoggiQUGiDQ6OjrCxMBOCps2bWVW6KqoMGT3tYLUOUxF1svQKhrqCjQVWSwAqA4ohmKHyBSwXhS3sALtdUBz80CFHxUhFvcBAIAazK3ZWzn1AAAAAElFTkSuQmCC',
			'0E47' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7GB1EQxkaHUNDkMRYA0QaGFodGkSQxESmAHlTUcUCWoG8QIeGACT3RS2dGrYyM2tlFpL7QOpYGx1aGdD0soYGTGFAt6PRIYAB3S2Njg5Y3IwiNlDhR0WIxX0A9rHLy+mrsvoAAAAASUVORK5CYII=',
			'08A0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7GB0YQximMLQii7EGsLYyhDJMdUASE5ki0ujo6BAQgCQW0MraytoQ6CCC5L6opSvDlq6KzJqG5D40dVAxkUbXUFQxkB2uDQEodoDcwtoQgOIWkJuBYihuHqjwoyLE4j4AJGnMbolNhVsAAAAASUVORK5CYII=',
			'8FD9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7WANEQ11DGaY6IImJTBFpYG10CAhAEgtoBYo1BDqIoKtDiIGdtDRqatjSVVFRYUjug6gLmCqCYV5AAxYxTDvQ3MIaABRDc/NAhR8VIRb3AQBc980v4zmLPgAAAABJRU5ErkJggg==',
			'8756' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7WANEQ11DHaY6IImJTGFodG1gCAhAEgtoBYkxOgigqmtlncrogOy+pVGrpi3NzEzNQnIfUF0AQ0MgmnkgfYEOIihirA2saGIiU0QaGB0dUPSyBgBVhDKguHmgwo+KEIv7AN95y9n2O10MAAAAAElFTkSuQmCC',
			'7454' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7QkMZWllDHRoCkEVbGaayNjA0oomFAsVaUcSmMLqyTmWYEoDsvqilS5dmZkVFIbmP0UGklaEh0AFZL2uDKNDWwNAQJDERoPmsQJcgqwOyWxkdHTDEGEIZUN08QOFHRYjFfQAhqc0dEBWxCQAAAABJRU5ErkJggg==',
			'D995' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7QgMYQxhCGUMDkMQCprC2Mjo6OiCrC2gVaXRtCMQm5uqA5L6opUuXZmZGRkUhuS+glTHQISSgQQRFL0OjQwO6GEujI9AOEQy3OAQguw/iZoapDoMg/KgIsbgPAIrYzWsDHr68AAAAAElFTkSuQmCC',
			'E406' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7QkMYWhmmMEx1QBILaGCYyhDKEBCAKhbK6OjoIIAixujK2hDogOy+0KilS5euikzNQnJfQINIK1Admnmioa5AvSKodrSC7EAXQ3cLNjcPVPhREWJxHwBQmMxg1TQy2gAAAABJRU5ErkJggg==',
			'5E9F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7QkNEQxlCGUNDkMQCGkQaGB0dHRjQxFgbAlHEAgNQxMBOCps2NWxlZmRoFrL7WkUaGEJQ9YLF0MwLAIoxoomJTMF0C2sA2M2o5g1Q+FERYnEfANahyUFE8KleAAAAAElFTkSuQmCC',
			'A1AC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7GB0YAhimMEwNQBJjDWAMYAhlCBBBEhOZAhR1dHRgQRILaGUIYG0IdEB2X9RSEIrMQnYfmjowDAWazxqKKgZTh2lHAIpbAlpZQ4FiKG4eqPCjIsTiPgDuFsoYMQkvCQAAAABJRU5ErkJggg==',
			'4E6A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpI37poiGMoQytKKIhYg0MDo6THVAEmMEirE2OAQEIImxTgGJMTqIILlv2rSpYUunrsyahuS+AJA6R0eYOjAMDQXpDQwNQXELWAxFHUiMEU0vxM2MqGIDFX7Ug1jcBwBnF8q6lNJocgAAAABJRU5ErkJggg==',
			'F188' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWUlEQVR4nGNYhQEaGAYTpIn7QkMZAhhCGaY6IIkFNDAGMDo6BASgiLEGsDYEOoigiDEgqwM7KTRqVdSq0FVTs5Dch6YOLobNPAJ2wNwSiu7mgQo/KkIs7gMAqYvLChNOrv8AAAAASUVORK5CYII=',
			'CFFC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWElEQVR4nGNYhQEaGAYTpIn7WENEQ11DA6YGIImJtIo0sDYwBIggiQU0gsQYHViQxRogYsjui1o1NWxp6MosZPehqcMthsUObG5hDQGLobh5oMKPihCL+wCmWsrizFTSmgAAAABJRU5ErkJggg==',
			'48A9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nGNYhQEaGAYTpI37pjCGMExhmOqALBbC2soQyhAQgCTGGCLS6Ojo6CCCJMY6hbWVtSEQJgZ20rRpK8OWroqKCkNyXwBYXcBUZL2hoSKNrqEBDSIobgGKNQQ4oIqB9aK4BeRmkHkobh6o8KMexOI+ADujzKPnNEWtAAAAAElFTkSuQmCC',
			'48A8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpI37pjCGMExhmOqALBbC2soQyhAQgCTGGCLS6Ojo6CCCJMY6hbWVtSEApg7spGnTVoYtXRU1NQvJfQGo6sAwNFSk0TU0EMU8hilAsQZ0MUy9IDcDxVDdPFDhRz2IxX0AtUnM9vNy/HEAAAAASUVORK5CYII=',
			'1B79' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7GB1EQ1hDA6Y6IImxOoi0MjQEBAQgiYk6iDQ6NAQ6iKDoBaprdISJgZ20Mmtq2Kqlq6LCkNwHVjeFYSqa3kaHAIYGdDFHBwYMO1gbGFDdEgJ0cwMDipsHKvyoCLG4DwAN0cmPysMPIgAAAABJRU5ErkJggg==',
			'E36F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWElEQVR4nGNYhQEaGAYTpIn7QkNYQxhCGUNDkMQCGkRaGR0dHRhQxBgaXRswxFpZGxhhYmAnhUatCls6dWVoFpL7wOqwmhdIhBimW6BuRhEbqPCjIsTiPgBejMqpTpszeAAAAABJRU5ErkJggg==',
			'FC44' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7QkMZQxkaHRoCkMQCGlgbHVodGlHFRBocpjq0oosxBDpMCUByX2jUtFUrM7OiopDcB1LH2ujogK6XNTQwNATdDmxuwRDDdPNAhR8VIRb3AQDoFtDURmOdrgAAAABJRU5ErkJggg==',
			'6FE9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7WANEQ11DHaY6IImJTBFpYG1gCAhAEgtoAYkxOoggizWgiIGdFBk1NWxp6KqoMCT3hUDMm4qitxUs1oBFDMUObG5hDQCKobl5oMKPihCL+wCHJcuzT3pBLAAAAABJRU5ErkJggg==',
			'7404' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7QkMZWhmmMDQEIIu2MkxlCGVoRBMLZXR0aEURm8LoytoQMCUA2X1RS5cuXRUVFYXkPkYHkVbWhkAHZL2sDaKhrg2BoSFIYiJAW4B2oLglAGwzA6YYupsHKPyoCLG4DwAbTM0Zce92DQAAAABJRU5ErkJggg==',
			'8B3E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAV0lEQVR4nGNYhQEaGAYTpIn7WANEQxhDGUMDkMREpoi0sjY6OiCrC2gVaXRoCEQRA6ljQKgDO2lp1NSwVVNXhmYhuQ9NHU7zcNmB7hZsbh6o8KMixOI+AGO2y5fgnV7JAAAAAElFTkSuQmCC',
			'8BF6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXElEQVR4nGNYhQEaGAYTpIn7WANEQ1hDA6Y6IImJTBFpZW1gCAhAEgtoFWl0bWB0EMBQx+iA7L6lUVPDloauTM1Cch9UHVbzRAiIYXML2M0NDChuHqjwoyLE4j4AmaHLvhab/k4AAAAASUVORK5CYII=',
			'1780' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7GB1EQx1CGVqRxVgdGBodHR2mOiCJiQLFXBsCAgJQ9DK0MgIViiC5b2XWqmmrQldmTUNyH1BdAJI6qBijA2tDIJoYawMrhh0iDYzobgkB6kJz80CFHxUhFvcBAB+GyOKD2Q2WAAAAAElFTkSuQmCC',
			'8552' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdklEQVR4nGNYhQEaGAYTpIn7WANEQ1lDHaY6IImJTBFpYG1gCAhAEgtoBYkxOoigqgthnQqkkdy3NGrq0qWZWauikNwnMoWh0aEhoNEBxTywWCsDqh2Nrg0BUxhQ7GBtZXR0CEB1M2MIQyhjaMggCD8qQizuAwDWacy1JGGI6wAAAABJRU5ErkJggg==',
			'18BB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWklEQVR4nGNYhQEaGAYTpIn7GB0YQ1hDGUMdkMRYHVhbWRsdHQKQxEQdRBpdGwIdRFD0oqgDO2ll1sqwpaErQ7OQ3MeIxTxGrOYRtAPilhBMNw9U+FERYnEfAH/IyVN/fu+qAAAAAElFTkSuQmCC',
			'1463' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7GB0YWhlCGUIdkMRYHRimMjo6OgQgiYk6MISyNjg0iKDoZXRlBdIBSO5bmbV06dKpq5ZmIbmP0UGkldXRoSEARa9oqCtQRATNLaxYxDDcEoLp5oEKPypCLO4DAKN6yXugUnDEAAAAAElFTkSuQmCC',
			'7C28' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7QkMZQxlCGaY6IIu2sjY6OjoEBKCIiTS4NgQ6iCCLTQHxAmDqIG6KmrZq1cqsqVlI7mME6WplQDGPtQEoNoURxTwRIHQIQBULaAC6xQFVb0ADYyhraACqmwco/KgIsbgPABmGzCLIPFVAAAAAAElFTkSuQmCC',
			'ABF6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7GB1EQ1hDA6Y6IImxBoi0sjYwBAQgiYlMEWl0BaoWQBILaAWpY3RAdl/U0qlhS0NXpmYhuQ+qDsW80FCIeSKo5mETw3BLQCvQzQ0MKG4eqPCjIsTiPgCdn8wGf/42XAAAAABJRU5ErkJggg==',
			'ED2E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7QkNEQxhCGUMDkMQCGkRaGR0dHRhQxRpdGwIxxBwQYmAnhUZNW5m1MjM0C8l9YHWtjJh6p2ARC8AQA+pEFQO5mTU0EMXNAxV+VIRY3AcAcprLhp0UOtsAAAAASUVORK5CYII=',
			'8885' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7WAMYQxhCGUMDkMREprC2Mjo6OiCrC2gVaXRtCEQRg6pzdUBy39KolWGrQldGRSG5D6LOoUEEw7wALGKBDiIYdjgEILsP4maGqQ6DIPyoCLG4DwAaQsuQQUrTDQAAAABJRU5ErkJggg==',
			'33BC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAW0lEQVR4nGNYhQEaGAYTpIn7RANYQ1hDGaYGIIkFTBFpZW10CBBBVtnK0OjaEOjAgiw2hQGoztEB2X0ro1aFLQ1dmYXiPlR1KOZhE0O2A5tbsLl5oMKPihCL+wB90cupFhgQBAAAAABJRU5ErkJggg==',
			'EE15' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXUlEQVR4nGNYhQEaGAYTpIn7QkNEQxmmMIYGIIkFNIg0MIQwOjCgiTFiEQPqdXVAcl9o1NSwVdNWRkUhuQ+ijgFEounFJsbogEVdALL7QG5mDHWY6jAIwo+KEIv7AEuiy7vXroMUAAAAAElFTkSuQmCC',
			'DF68' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7QgNEQx1CGaY6IIkFTBFpYHR0CAhAFmsVaWBtcHQQwRBjgKkDOylq6dSwpVNXTc1Cch9YHVbzArGYhyaGxS2hAUAVaG4eqPCjIsTiPgBgw83uLc0Y5QAAAABJRU5ErkJggg=='        
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