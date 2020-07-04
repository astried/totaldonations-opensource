<?php
if (!function_exists('migla_shortcode_progressbar')){
function migla_shortcode_progressbar( $id, $btn , $btntext, $text, $btn_class )
{
    $obj = new MIGLA_CAMPAIGN;
    $data   = $obj->get_info( $id, get_locale() );

    if( !empty($data) )
    {
        $objD = new CLASS_MIGLA_DONATION;
        $objO = new MIGLA_OPTION;
        $objM = new MIGLA_MONEY;

        $total = $objD->get_total_donation_by_campaign( '',
                                                        '',
                                                        '',
                                                        $data['id'],
                                                        1
                                                    );

        $percent = 0;
        $target = 1.0;

        $campaign_name = '';

        if( isset($data['target']) ){
            $target = intval($data['target']);
        }

        $percent = ( $total / $target ) * 100 ;

        $reminder = $target - $total ;

        $info = $objO->get_option('migla_progbar_info');

        $symbol = $objM->get_default_currency();
        $thousandSep = $objM->get_default_thousand_separator();
        $decimalSep = $objM->get_default_decimal_separator();

        $before_total = '';
        $after_total = '';
        $before_target = '';
        $after_target = '';
        $before_reminder = '';
        $after_reminder = '';

        $res_total = $objM->full_format( $total, 2);
        $res_target = $objM->full_format( $target, 2);
        $res_reminder = $objM->full_format( $reminder, 2);

        $placement = strtolower( $objM->get_symbol_position() );

        if( $placement == 'before' ){
          $before_total = $res_total[1];
          $before_target = $res_target[1];
          $before_reminder = $res_reminder[1];
        }else{
          $after_total = $res_total[1];
          $after_target = $res_target[1];
          $after_reminder = $res_reminder[1];
        }

            $total_amount   = $before_total.' '.$res_total[0].' '.$after_total;

            $target_amount  = $before_target.' '.$res_target[0].' '.$after_target;

            $formatted_percentage = number_format( $percent, 2, $decimalSep, $thousandSep  );
            $bar_percentage = number_format( $percent, 2, '.', '' );
            $percentStr = $formatted_percentage . "%";

            if( $reminder < 0 ){
               $reminder_text = '';
            }else{
               $remainder_text = $before_total.' '.$res_reminder[0].' '.$after_total;
            }

            $campaign_name = str_replace("[q]", "'", $data['name'] );

            $placeholder = array( '[total]',
                                '[target]' ,
                                '[campaign]',
                                '[percentage]',
                                '[remainder]' );

            $replace = array( $total_amount ,
                            $target_amount ,
                            $campaign_name,
                            $percentStr ,
                            $remainder_text  );

            $content =  str_replace($placeholder, $replace, $info);

        ?>
        <div class='bootstrap-wrapper'>

            <?php
            if($text == 'yes')
            {
            ?>
                <div class='progress-bar-text'>
                    <p class='progress-bar-text'>
                        <?php echo $content;?>
                    </p>
                </div>
               <?php
            }


            // Five Row Progress Bar
            $effects = (array)unserialize($objO->get_option( 'migla_bar_style_effect' ));

            $effectClasses = "";

            if( isset($effects['Stripes']) && $effects['Stripes'] == 'yes' ){
                 $effectClasses = $effectClasses . " striped ";
            }
            if( isset($effects['Pulse']) && $effects['Pulse'] == 'yes' ){
                 $effectClasses = $effectClasses . " mg_pulse";
            }
            if( isset($effects['Animated_stripes']) && $effects['Animated_stripes'] =='yes' ){
                 $effectClasses = $effectClasses . " active animated-striped";
            }
            if( isset($effects['Percentage']) && $effects['Percentage'] == 'yes' ){
                 $effectClasses = $effectClasses . " mg_percentage";
            }

            $borderRadius = explode(",", $objO->get_option( 'migla_borderRadius' )); //4spinner

            $barcolor = explode(",", $objO->get_option( 'migla_bar_color' ));  //rgba

            $progressbar_bg = explode(",", $objO->get_option( 'migla_progressbar_background' )); //rgba

            $boxshadow_color = explode(",", $objO->get_option( 'migla_wellboxshadow' )); //rgba 4spinner

            $style1 = "";
            $style1 = "";
            $style1 .= "box-shadow:".$boxshadow_color[2]."px ".$boxshadow_color[3]."px ".$boxshadow_color[4]."px ".$boxshadow_color[5]."px " ;
            $style1 .= $boxshadow_color[0]." inset !important;";
            $style1 .= "background-color:".$progressbar_bg[0].";";

            $style1 .= "-webkit-border-top-left-radius:".$borderRadius[0]."px; -webkit-border-top-right-radius: ".$borderRadius[1]."px;";
            $style1 .= "-webkit-border-bottom-left-radius: ".$borderRadius[2]."px; -webkit-border-bottom-right-radius:".$borderRadius[3]."px;";

            $style1 .= "-moz-border-radius-topleft:".$borderRadius[0]."px; -moz-border-radius-topright: ".$borderRadius[1]."px;";
            $style1 .= "-moz-border-radius-bottomleft: ".$borderRadius[2]."px;-moz-border-radius-bottomright:".$borderRadius[3]."px;";

            $style1 .= "border-top-left-radius:".$borderRadius[0]."px; border-top-right-radius: ".$borderRadius[1]."px;";
            $style1 .= "border-bottom-left-radius:  ".$borderRadius[2]."px;border-bottom-right-radius:".$borderRadius[3]."px;";

            $stylebar = "background-color:".$barcolor[0].";";
            ?>

            <div id='me' class='progress <?php echo $effectClasses;?>' style='<?php echo $style1;?>'>
                <div class='progress-bar bar' role='progressbar' aria-valuenow='<?php echo $bar_percentage;?>'
                aria-valuemin='0' aria-valuemax='100'
                style='width:<?php echo $bar_percentage;?>%;<?php echo $stylebar;?>'>
                <?php echo $formatted_percentage;?>%
                </div>
            </div>

            <?php
            if( $btn == 'yes' ){
            ?>
                <p>
                    <a href=""><button><?php echo __($btntext, "migla-donation");?></button></a>
                </p>
            <?php
            }
            ?>
    </div>
        <?php
    }
}
}
?>
