<?php

use humhub\modules\cleanTheme\Module;
use humhub\modules\ui\mail\DefaultMailStyle;

/** @var Module $module */
$module = Yii::$app->getModule('clean-theme');
$configuration = $module->getConfiguration();
?>

<tr>
    <td align="center" valign="top" class="fix-box">

        <!-- start  container width 600px -->
        <table width="600" align="center" border="0" cellspacing="0" cellpadding="0" class="container"
               style="background-color: <?= $configuration->backgroundColorMain ?>; border-bottom-left-radius: 4px; border-bottom-right-radius: 4px;">


            <tr>
                <td valign="top">

                    <!-- start container width 560px -->
                    <table width="540" align="center" border="0" cellspacing="0" cellpadding="0" class="full-width"
                           style="background-color:<?= $configuration->backgroundColorMain ?>;">


                        <!-- start text content -->
                        <tr>
                            <td valign="top">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">


                                    <!-- start text content -->
                                    <tr>
                                        <td valign="top">
                                            <table border="0" cellspacing="0" cellpadding="0" align="left">

                                                <tr>
                                                    <td style="font-size: 14px; line-height: 22px; font-family: <?= Yii::$app->view->theme->variable('mail-font-family', DefaultMailStyle::DEFAULT_FONT_FAMILY) ?>; color:<?= $configuration->textColorMain ?>; font-weight:300; text-align:left; ">

                                                        <?php echo $message; ?>

                                                    </td>
                                                </tr>

                                            </table>
                                        </td>
                                    </tr>
                                    <!-- end text content -->


                                </table>
                            </td>
                        </tr>
                        <!-- end text content -->

                        <!--start space height -->
                        <tr>
                            <td height="15"></td>
                        </tr>
                        <!--end space height -->


                    </table>
                    <!-- end  container width 560px -->
                </td>
            </tr>
        </table>
        <!-- end  container width 600px -->
    </td>
</tr>
