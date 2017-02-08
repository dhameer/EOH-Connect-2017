<?php

// if the from is loaded from WordPress form loader plugin,
// the phpfmg_display_form() will be called by the loader
if( !defined('FormmailMakerFormLoader') ){
    # This block must be placed at the very top of page.
    # --------------------------------------------------
	require_once( dirname(__FILE__).'/form.lib.php' );
    phpfmg_display_form();
    # --------------------------------------------------
};


function phpfmg_form( $sErr = false ){
		$style=" class='form_text' ";

?>



<div><img src="http://eohict.co.za/form/imgs/nav-bg.jpg" width="100%" alt=""/><br>
<br>
</div>
<div id='frmFormMailContainer'>

<form name="frmFormMail" id="frmFormMail" target="submitToFrame" action='<?php echo PHPFMG_ADMIN_URL . '' ; ?>' method='post' enctype='multipart/form-data' onsubmit='return fmgHandler.onSubmit(this);'>

<input type='hidden' name='formmail_submit' value='Y'>
<input type='hidden' name='mod' value='ajax'>
<input type='hidden' name='func' value='submit'>

            
<ol class='phpfmg_form' >

<li class='field_block' id='field_0_div'><div class='col_label'>
	<label class='form_field'>Full Name </label> <label class='form_required' >*</label> </div>
	<div class='col_field'>
	<input type="text" name="field_0"  id="field_0" value="<?php  phpfmg_hsc("field_0", ""); ?>" class='text_box'>
	<div id='field_0_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_1_div'><div class='col_label'>
	<label class='form_field'>Business Unit </label> <label class='form_required' >*</label> </div>
	<div class='col_field'>
	<input type="text" name="field_1"  id="field_1" value="<?php  phpfmg_hsc("field_1", ""); ?>" class='text_box'>
	<div id='field_1_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_2_div'><div class='col_label'>
	<label class='form_field'>Morning - (Learning & Development) </label> <label class='form_required' >&nbsp;</label> </div>
	<div class='col_field'>
	<textarea name="field_2" id="field_2" rows=4 cols=25 class='text_area'><?php  phpfmg_hsc("field_2"); ?></textarea>

	<div id='field_2_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_3_div'><div class='col_label'>
	<label class='form_field'>Please rate the presenter </label> <label class='form_required' >&nbsp;</label> </div>
	<div class='col_field'>
	<?php phpfmg_radios( 'field_3', "Excellent|Good|Average|Bad" );?>
	<div id='field_3_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_4_div'><div class='col_label'>
	<label class='form_field'>Please rate the workshop </label> <label class='form_required' >&nbsp;</label> </div>
	<div class='col_field'>
	<?php phpfmg_radios( 'field_4', "Excellent|Good|Average|Bad" );?>
	<div id='field_4_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_5_div'><div class='col_label'>
	<label class='form_field'>Morning - (Key / Strategic account)</label> <label class='form_required' >&nbsp;</label> </div>
	<div class='col_field'>
	<textarea name="field_5" id="field_5" rows=4 cols=25 class='text_area'><?php  phpfmg_hsc("field_5"); ?></textarea>

	<div id='field_5_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_6_div'><div class='col_label'>
	<label class='form_field'>Please rate the presenter</label> <label class='form_required' >&nbsp;</label> </div>
	<div class='col_field'>
	<?php phpfmg_radios( 'field_6', "Excellent|Good|Average|Bad" );?>
	<div id='field_6_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_7_div'><div class='col_label'>
	<label class='form_field'>Please rate the workshop</label> <label class='form_required' >&nbsp;</label> </div>
	<div class='col_field'>
	<?php phpfmg_radios( 'field_7', "Excellent|Good|Average|Bad" );?>
	<div id='field_7_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_8_div'><div class='col_label'>
	<label class='form_field'>Afternoon - (Key / Strategic account)</label> <label class='form_required' >&nbsp;</label> </div>
	<div class='col_field'>
	<textarea name="field_8" id="field_8" rows=4 cols=25 class='text_area'><?php  phpfmg_hsc("field_8"); ?></textarea>

	<div id='field_8_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_9_div'><div class='col_label'>
	<label class='form_field'>Please rate the presenter</label> <label class='form_required' >&nbsp;</label> </div>
	<div class='col_field'>
	<?php phpfmg_radios( 'field_9', "Excellent|Good|Average|Bad" );?>
	<div id='field_9_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_10_div'><div class='col_label'>
	<label class='form_field'>Please rate the workshop</label> <label class='form_required' >&nbsp;</label> </div>
	<div class='col_field'>
	<?php phpfmg_radios( 'field_10', "Excellent|Good|Average|Bad" );?>
	<div id='field_10_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_11_div'><div class='col_label'>
	<label class='form_field'>Afternoon - (Learning & Development)</label> <label class='form_required' >&nbsp;</label> </div>
	<div class='col_field'>
	<textarea name="field_11" id="field_11" rows=4 cols=25 class='text_area'><?php  phpfmg_hsc("field_11"); ?></textarea>

	<div id='field_11_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_12_div'><div class='col_label'>
	<label class='form_field'>Please rate the presenter</label> <label class='form_required' >&nbsp;</label> </div>
	<div class='col_field'>
	<?php phpfmg_radios( 'field_12', "Excellent|Good|Average|Bad" );?>
	<div id='field_12_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_13_div'><div class='col_label'>
	<label class='form_field'>Please rate the workshop</label> <label class='form_required' >&nbsp;</label> </div>
	<div class='col_field'>
	<?php phpfmg_radios( 'field_13', "Excellent|Good|Average|Bad" );?>
	<div id='field_13_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_14_div'><div class='col_label'>
	<label class='form_field'>Overall content of the ICT Sales & Leadership Conference</label> <label class='form_required' >&nbsp;</label> </div>
	<div class='col_field'>
	<?php phpfmg_radios( 'field_14', "Excellent|Good|Average|Bad" );?>
	<div id='field_14_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_15_div'><div class='col_label'>
	<label class='form_field'>Guest speakers invited to the ICT Sales & Leadership Conference</label> <label class='form_required' >&nbsp;</label> </div>
	<div class='col_field'>
	<?php phpfmg_radios( 'field_15', "Excellent|Good|Average|Bad" );?>
	<div id='field_15_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_16_div'><div class='col_label'>
	<label class='form_field'>How would you rate the venue of the event</label> <label class='form_required' >&nbsp;</label> </div>
	<div class='col_field'>
	<?php phpfmg_radios( 'field_16', "Excellent|Good|Average|Bad" );?>
	<div id='field_16_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_17_div'><div class='col_label'>
	<label class='form_field'>Is there anything else you would like to share about the event</label> <label class='form_required' >&nbsp;</label> </div>
	<div class='col_field'>
	<textarea name="field_17" id="field_17" rows=4 cols=25 class='text_area'><?php  phpfmg_hsc("field_17"); ?></textarea>

	<div id='field_17_tip' class='instruction'></div>
	</div>
</li>


<li class='field_block' id='phpfmg_captcha_div'>
	<div class='col_label'></div><div class='col_field'>
	<?php phpfmg_show_captcha(); ?>
	</div>
</li>


            <li>
            <div class='col_label'>&nbsp;</div>
            <div class='form_submit_block col_field'>
	

               
		<input type="image" src="http://eohict.co.za/form/imgs/submit.jpg" width="70%" value='Submit' class='form_button'>
				<div id='err_required' class="form_error" style='display:none;'>
				    <label class='form_error_title'>Please check the required fields</label>
				</div>
				


                <span id='phpfmg_processing' style='display:none;'>
                    <img id='phpfmg_processing_gif' src='<?php echo PHPFMG_ADMIN_URL . '?mod=image&amp;func=processing' ;?>' border=0 alt='Processing...'> <label id='phpfmg_processing_dots'></label>
                </span>
            </div>
            </li>

</ol>
</form>

<iframe name="submitToFrame" id="submitToFrame" src="javascript:false" style="position:absolute;top:-10000px;left:-10000px;" /></iframe>

</div>
<!-- end of form container -->


<!-- [Your confirmation message goes here] -->
<div id='thank_you_msg' style='display:none;'>
Your form has been sent. Thank you!
</div>


            






<?php

    phpfmg_javascript($sErr);

}
# end of form




function phpfmg_form_css(){
    $formOnly = isset($GLOBALS['formOnly']) && true === $GLOBALS['formOnly'];
?>
<style type='text/css'>
<?php 
if( !$formOnly ){
    echo"
body{
    margin:0 auto;
	background-image: url(http://eohict.co.za/form/imgs/bg.jpg);
}
div#frmFormMailContainer{
	margin-left: 18px;}
body{
    font-family : Verdana, Arial, Helvetica, sans-serif;
    font-size : 13px;
    color : #474747;
    background-color: transparent;
}

select, option{
    font-size:13px;
}
";
}; // if
?>

ol.phpfmg_form{
    list-style-type:none;
    padding:0px;
    margin:0px;
}

ol.phpfmg_form input, ol.phpfmg_form textarea, ol.phpfmg_form select{
    border: 1px solid #ccc;
    -moz-border-radius: 3px;
    -webkit-border-radius: 3px;
    border-radius: 3px;
}

ol.phpfmg_form li{
    margin-bottom:15px;
    clear:both;
    display:block;
    overflow:hidden;
	width: 100%
}


.form_field, .form_required{
    font-weight : bold;
}

.form_required{
    color:red;
    margin-right:8px;
}

.field_block_over{
}

.form_submit_block{
    padding-top: 3px;
}

.text_box,.text_select {
    height: 32px;
}

.text_box, .text_area, .text_select {
    min-width:160px;
    max-width:300px;
    width: 100%;
    margin-bottom: 10px;
}

.text_area{
    height:80px;
}

.form_error_title{
    font-weight: bold;
    color: red;
}

.form_error{
    background-color: #F4F6E5;
    border: 1px dashed #ff0000;
    padding: 10px;
    margin-bottom: 10px;
}

.form_error_highlight{
    background-color: #F4F6E5;
    border-bottom: 1px dashed #ff0000;
}

div.instruction_error{
    color: red;
    font-weight:bold;
}

hr.sectionbreak{
    height:1px;
    color: #ccc;
}

#one_entry_msg{
    background-color: #F4F6E5;
    border: 1px dashed #ff0000;
    padding: 10px;
    margin-bottom: 10px;
}


#frmFormMailContainer input[type="submit"]{
    padding: 10px 25px; 
    font-weight: bold;
    margin-bottom: 10px;
    background-color: #FAFBFC;
}

#frmFormMailContainer input[type="submit"]:hover{
    background-color: #E4F0F8;
}

<?php phpfmg_text_align();?>    



</style>

<?php
}
# end of css
 
# By: formmail-maker.com
?>