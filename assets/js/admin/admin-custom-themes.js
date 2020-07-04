function mg_init()
{
    jQuery('.mg-color-field').change(function(){
        var parent    = jQuery(this).closest('div.row');
        jQuery(parent).find('.currentColor').css('background-color', jQuery(this).val() );
    });

      jQuery('.meffects').click(function(){
              var id = jQuery(this).attr('id');
              if( id == "inlineCheckbox1" ){
                jQuery('div.progress').toggleClass("striped");
              }
              if( id == "inlineCheckbox2" ){
                jQuery('div.progress').toggleClass("mg_pulse");
              }
              if( id == "inlineCheckbox3" ){
                jQuery('div.progress').toggleClass("animated-striped");
                jQuery('div.progress').toggleClass("active");
              }
              if( id == "inlineCheckbox4" ){
                jQuery('div.progress').toggleClass("mg_percentage");
              }
      });

      jQuery('.spinner-up').on('click', function() {
        var _parent = jQuery(this).closest('.input-group');

        _parent.find('.spinner-input').val(  parseInt ( _parent.find('.spinner-input').val(), 10) + 1);

        console.log("up");
      });
      jQuery('.spinner-down').on('click', function() {
        var _parent = jQuery(this).closest('.input-group');

        _parent.find('.spinner-input').val(  parseInt(jQuery('.spinner-input').val(), 10) - 1);
        console.log("down");
      });

}

function mg_minicolor()
{
    jQuery('.mg-color-field').each( function() {
        jQuery(this).wpColorPicker();
    });
}

function mg_save_form_theme()
{
    jQuery('#migla_save_form').click(function(){

        var ColorCode1 = jQuery('#migla_backgroundcolor').val() + ',1';
        var ColorCode2 = jQuery('#migla_panelborder').val() + ",1," + jQuery('#migla_widthpanelborder').val();
        var ColorCode3 = jQuery('#migla_bglevelcolor').val() ;
        var ColorCode4  = jQuery('#migla_borderlevelcolor').val() ;

        var BorderlevelWidthSpinner = jQuery('#migla_Widthborderlevelcolor').val();
        var ColorCode5 = jQuery('#migla_bglevelcoloractive').val() ;
        var ColorCode6 = jQuery('#migla_tabcolor').val() ;

        jQuery.ajax({
            type : "post",
            url :miglaAdminAjax.ajaxurl,
            data : {    action  : 'TotalDonationsAjax_update_form_theme',
                        backgroundcolor : ColorCode1,
                        panelborder     : ColorCode2,
                        bglevelcolor    : ColorCode3,
                        borderlevelcolor: ColorCode4,

                        borderlevelWidth: BorderlevelWidthSpinner,

                        bglevelcoloractive: ColorCode5,
                        tabcolor : ColorCode6,

                        auth_token : jQuery('#__migla_auth_token').val(),
                        auth_owner : jQuery('#__migla_auth_owner').val(),
                        auth_session : jQuery('#__migla_session').val()
                    },
            success: function(msg) {
                      console.log(msg);
                    },
            error: function(xhr, status, error)
                    {
                      alert(error);
                    },
            complete: function(xhr, status, error)
                    {
                      saved('#migla_save_form');
                    }
          });


    });
}

function mg_save_progressBar_theme()
{
    jQuery('#migla_save_bar').click(function(){

        var border = "";
        border = border + jQuery('#mg_WBRtop-left').val() + ",";
        border = border + jQuery('#migla_WRBtopright').val() + ",";
        border = border + jQuery('#migla_radiusbottomleft').val() + ",";
        border = border + jQuery('#migla_radiusbottomright').val();

        var well = "";
        well = jQuery('#migla_wellshadow').val() + ",1,";
        well = well + jQuery('#migla_hshadow').val() + ",";
        well = well + jQuery('#migla_vshadow').val() + ",";
        well = well + jQuery('#migla_blur').val() + ",";
        well = well + jQuery('#migla_spread').val();

        var ColorCode = jQuery('#migla_barcolor').val() + ',1';
        var ColorCode2 = jQuery('#migla_wellcolor').val() + ',1';

        jQuery.ajax({
            type : "post",
            url :miglaAdminAjax.ajaxurl,
            data : {    action  : "TotalDonationsAjax_update_progressBar_theme",
                        borderRadius   : border,
                        wellboxshadow  : well,
                        progbar_info   : jQuery('#migla_progressbar_text').val(),
                        bar_color      : ColorCode,
                        progressbar_background : ColorCode2,
                        auth_token : jQuery('#__migla_auth_token').val(),
                        auth_owner : jQuery('#__migla_auth_owner').val(),
                        auth_session : jQuery('#__migla_session').val()
                    },
               success: function(msg) {
                    },
              error: function(xhr, status, error){

                    },
              complete: function(xhr, status, error){
                        saved('#migla_save_bar');
                      }
        });
    });
}

jQuery(document).ready(function() {
  console.log("Start");

  mg_init();
  mg_save_form_theme();
  mg_save_progressBar_theme();
  mg_minicolor();

  console.log("End");

}); //End of document
