<?php
/**
 * Add googleadsense  view  file
 * 
 * @category   FishFlicks
 * @package    Fliche Video Gallery
 * @version    0.7.0
 * @author     Company Juice <support@companyjuice.com>
 * @copyright  Copyright (C) 2016 Company Juice. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */
?>
<script type="text/javascript">
    function validateGoogleAdSense(){
         var error              = 0;
         var googleadsenseCode  = document.getElementById("googleadsense_code").value;
         var googleadsensetitle = document.getElementById("googleadsense_title").value;
         googleadsensetitle     =  googleadsensetitle.trim();
         googleadsenseCode      =  googleadsenseCode.trim();
         document.getElementById("googleadsense_codeerror").innerHTML  = '';
         document.getElementById("googleadsense_titleerror").innerHTML = '';
         if( googleadsenseCode =='' ) {
             document.getElementById("googleadsense_codeerror").innerHTML='<label>Please Enter the Google AdSense</label>';
             error++;
         }
         if(googleadsensetitle == '' ) { 
            document.getElementById("googleadsense_titleerror").innerHTML='<label>Enter the Google AdSense title</label>';
            error++;
         }
         if(error){
             return false;
         }else{
             return true;
         }
    }
</script>
<?php /** Display google adsense page starts */ ?>
<div class="fliche_gallery">
<?php /** Display title, icons in header */
    if( isset ( $editGoogleAdsense->id ) ) { ?>
    <h2 class="option_title"><?php echo '<img src="' . getImagesDirURL() .'google_adsense.png" alt="move" width="30"/>'; ?>
          <?php esc_attr_e( 'Update Google AdSense', FLICHE_VGALLERY ); ?></h2> 
<?php } else { ?> 
    <h2 class="option_title"><?php echo '<img src="' . getImagesDirURL() .'google_adsense.png" alt="move" width="30"/>'; ?>
            <?php esc_attr_e( 'Add new Google AdSense', FLICHE_VGALLERY ); ?></h2> 
<?php }
     
     /** Display status for the corresponding action */
     if ( isset( $msg ) ) { ?> 
         <div class="updated below-h2"> <p> <?php echo $msg ; ?> </p> </div> 
<?php } 
 
 /** Check if action is edit  */
 if (isset ( $editGoogleAdsense ) && $editGoogleAdsense !== '') {
    $adsense_code = $adsense_option = $adsense_reopen = $adsense_reopen_time = $adsenseshow_time = $adsense_status = $adsense_title = '';
    /** Get google adsense details */
    $googleadsense_details  = unserialize ( $editGoogleAdsense->googleadsense_details );
    /** Get google adsense title */
    if (isset ( $googleadsense_details ['googleadsense_title'] )) {
      $adsense_title        = $googleadsense_details ['googleadsense_title'];
    }
    /** Get google adsense code */
    $adsense_code           = $googleadsense_details ['googleadsense_code'];
    /** Get google adsense option */
    $adsense_option         = $googleadsense_details ['adsense_option'];
    /** Get google adsense reopen option */
    $adsense_reopen         = $googleadsense_details ['adsense_reopen'];
    /** Get google adsense reopen time */
    $adsense_reopen_time    = $googleadsense_details ['adsense_reopen_time'];
    /** Get google adsense display time */
    $adsenseshow_time       = $googleadsense_details ['adsenseshow_time'];
    /** Get google adsense status */
    $adsense_status         = $googleadsense_details ['publish'];
} else {
    $adsense_code = $adsense_option = $adsense_reopen = $adsense_reopen_time = $adsenseshow_time = $adsense_status = $adsense_title = '';
}
/**
 * Display google adsense form 
 * to add new google adsense details, 
 * edit adsense details
 */
?>
<div id="post-body" class="has-sidebar">
    <div id="post-body-content" class="has-sidebar-content">
        <form method="post" action="admin.php?page=googleadsense">
            <table>
                <tbody>
                    <tr>
                        <?php /** Display google adsense title */ ?>
                        <td width="150"> <?php esc_attr_e( 'Title', FLICHE_VGALLERY ) ?> </td>
                        <td colspan="2"><input type="text" name="googleadsense_title" id="googleadsense_title" value="<?php echo $adsense_title ; ?>" /> <div id="googleadsense_titleerror" style="color: #ff0000;"></div> </td> 
                    </tr>
                    <tr>
                    <?php /** Display google adsense code */ ?>
                        <td width="150"> <?php esc_attr_e( 'Google AdSense Code', FLICHE_VGALLERY ) ?> </td>
                        <td colspan="2"><textarea name="googleadsense_code" id="googleadsense_code" col="60" row="20"> <?php echo $adsense_code ; ?> </textarea> <div id="googleadsense_codeerror" style="color: #ff0000;"></div> </td> 
                    </tr> 
                    <tr> 
                    <?php /** Display google adsense option */ ?>
                        <td width="150"> <?php esc_attr_e('Option',FLICHE_VGALLERY); ?> </td> 
                        <?php /** Display google adsense always show option */ ?>
                        <td> <input value="always_show" <?php if($adsense_option =='always_show') { 
                                echo "checked";
                              } ?> type="radio" name="alway_open" checked="checked" />&nbsp;&nbsp;
                                <label> <?php esc_attr_e('Always show',FLICHE_VGALLERY);?> </label> &nbsp;&nbsp;
                                <?php /** Display google adsense close option */ ?>
                             <input value="close" <?php if($adsense_option =='close') { 
                                echo "checked";
                             } ?> type="radio" name="alway_open" />&nbsp;&nbsp;
                                <label><?php esc_attr_e('Close After:',FLICHE_VGALLERY);?></label> 
                        </td>
                        <?php /** Display google adsense show time */ ?>
                        <td>&nbsp;&nbsp;<input type="text" value="<?php echo $adsenseshow_time ;?>" name="adsense_show_second" size="15" /> 
                            <?php esc_attr_e('Sec',FLICHE_VGALLERY);?> 
                        </td> 
                    </tr> 
                    <tr> 
                    <?php /** Display google adsense reopen option */ ?>
                        <td width="150"><?php esc_attr_e('Reopen',FLICHE_VGALLERY); ?></td> 
                        <td><input type="checkbox" value="1" <?php if($adsense_reopen =='1') { 
                                echo "checked";
                              } ?> name="reopen" />&nbsp;&nbsp;
                              <?php /** Display google adsense reopen time */ ?>
                              <label><?php esc_attr_e('Reopen After:',FLICHE_VGALLERY);?></label> 
                        </td> 
                        <td>&nbsp;&nbsp;<input type="text" value="<?php echo $adsense_reopen_time ; ?>" name="adsense_reopen_second" size="15" /> 
                            <?php esc_attr_e('Sec',FLICHE_VGALLERY);?> 
                        </td> 
                    </tr> 
                    <tr> <?php /** Display google adsense publish option */ ?>
                        <td width="150"><?php esc_attr_e( 'Publish', FLICHE_VGALLERY ) ?></td> 
                        <td colspan="2"><input type="radio" name="status" checked="checked" value="1" <?php if($adsense_status =='1') { 
                                  echo "checked";
                              } ?> />&nbsp;&nbsp;
                              <?php /** Display google adsense yes option for publish */ ?>
                                  <label><?php esc_attr_e('Yes',FLICHE_VGALLERY);?></label> &nbsp;&nbsp;
                              <input type="radio" name="status" value="0" <?php if($adsense_status =='0') { 
                                  echo "checked";
                              } ?> />&nbsp;&nbsp;
                              <?php /** Display google adsense no option for publish */ ?>
                                  <label> <?php esc_attr_e('No',FLICHE_VGALLERY);?> </label> 
                        </td> 
                    </tr> 
                    <tr> <td colspan="3">&nbsp;</td> </tr> 
                    <tr> 
                    <?php /** If page is edit page then set hidden values */ ?>
                        <td colspan="3"> <?php if(isset($editGoogleAdsense->id)) {  ?> 
                        <input type="hidden" value="<?php echo $editGoogleAdsense->id; ?>" name="videogoogleadId"> 
                        <?php /** If page is edit page then display update button */ ?>
                        <input type="submit" value="<?php esc_attr_e('Update' , FLICHE_VGALLERY); ?>" name="updatebutton" onclick="return validateGoogleAdSense();" class="button-primary"> 
                        <?php } else { 
                        /** If page is edit page then display save button */ ?> 
                        <input type="submit" value="<?php esc_attr_e('Save' , FLICHE_VGALLERY); ?>" name="updatebutton" onclick="return validateGoogleAdSense();" class="button-primary"> 
                        <?php } ?> &nbsp;&nbsp; 
                        <?php /** Display cancel button */ ?>
                        <a href="<?php echo admin_url('admin.php?page=googleadsense');?>" class="button">Cancel</a> 
                        </td> 
                    </tr> 
                </tbody> 
            </table> 
        </form> 
    </div> 
</div>