<?php
/**
 * Clean Theme
 * @link https://github.com/cuzy-app/clean-theme
 * @license https://github.com/cuzy-app/clean-theme/blob/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun) for [CUZY.APP](https://www.cuzy.app)
 */

namespace humhub\modules\cleanTheme\models;

use humhub\components\View;
use humhub\helpers\Html;
use humhub\modules\admin\permissions\ManageSettings;
use humhub\modules\cleanTheme\Module;
use humhub\widgets\bootstrap\Button;
use humhub\widgets\form\ActiveForm;
use Yii;

/**
 * @var $this View
 * @var $model Configuration
 * @var $uploadModel UploadConfiguration
 */

/** @var Module $module */
$module = Yii::$app->getModule('clean-theme');

$colorInputOptions = ['options' => ['class' => 'd-flex flex-row-reverse justify-content-end align-items-center gap-2 mb-2']];
$darkColorInputOptions = $colorInputOptions;
$darkColorInputOptions['options']['class'] .= ' ms-5';
$colorInputLabelOptions = ['class' => 'm-0'];
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <?= Yii::$app->user->can(ManageSettings::class) ? Button::light(Yii::t('CleanThemeModule.config', 'Choose the theme'))
            ->link(['/admin/setting/design'])
            ->style('margin-left: 6px;')
            ->right()
            ->sm() : '' ?>
        <strong><?= $module->getName() ?></strong>
        <div class="text-body-secondary"><?= $module->getDescription() ?></div>
    </div>

    <div class="panel-body">
        <div class="alert alert-info cuzy-free-module-info" role="alert">
            This module was created and is maintained by
            <a href="https://www.cuzy.app/"
               target="_blank">CUZY.APP (view other modules)</a>.
            <br>
            It's free, but it's the result of a lot of design and maintenance work over time.
            <br>
            If it's useful to you, please consider
            <a href="https://www.cuzy.app/checkout/donate/"
               target="_blank">making a donation</a>
            or
            <a href="https://github.com/cuzy-app/clean-theme"
               target="_blank">participating in the code</a>.
            Thanks!
        </div>

        <div id="clean-theme-configuration-import-export">
            <h5><strong><?= Yii::t('CleanThemeModule.config', 'Import/Export the configuration') ?></strong></h5>
            <div class="d-flex justify-content-between align-items-start">
                <?php $form = ActiveForm::begin(); ?>
                <div class="input-group">
                    <?= $form->field($uploadModel, 'jsonConfigurationFile')
                        ->fileInput(['class' => 'btn btn-primary', 'style' => 'height: 40px; padding: 8px;'])
                        ->label(false) ?>
                    <?= Button::save(Yii::t('CleanThemeModule.config', 'Import'))
                        ->icon('upload')
                        ->confirm(null, Yii::t('CleanThemeModule.config', 'This will overwrite your current configuration'))
                        ->submit()
                        ->options(['style' => 'height: 40px;']) ?>
                </div>
                <?php ActiveForm::end(); ?>
                <?= Button::primary(Yii::t('CleanThemeModule.config', 'Export'))->icon('download')->link(['download-json'])->loader(false) ?>
            </div>
        </div>

        <br>

        <div id="clean-theme-configuration-form">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'containerMaxWidth')->textInput(['type' => 'number', 'step' => 1, 'min' => 800]) ?>

            <?= $form->beginCollapsibleFields(Yii::t('CleanThemeModule.config', 'Text colors')) ?>
            <?= $form->field($model, 'linkColor', $colorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'linkColorDark', $darkColorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'textColorHeading', $colorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'textColorHeadingDark', $darkColorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'textColorMain', $colorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'textColorMainDark', $darkColorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'textColorDefault', $colorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'textColorDefaultDark', $darkColorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'textColorSecondary', $colorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'textColorSecondaryDark', $darkColorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'textColorHighlight', $colorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'textColorHighlightDark', $darkColorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'textColorSoft', $colorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'textColorSoftDark', $darkColorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'textColorSoft2', $colorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'textColorSoft2Dark', $darkColorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'textColorSoft3', $colorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'textColorSoft3Dark', $darkColorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'textColorContrast', $colorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'textColorContrastDark', $darkColorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->endCollapsibleFields() ?>

            <?= $form->beginCollapsibleFields(Yii::t('CleanThemeModule.config', 'Background colors')) ?>
            <?= $form->field($model, 'backgroundColorMain', $colorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'backgroundColorMainDark', $darkColorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'backgroundColorSecondary', $colorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'backgroundColorSecondaryDark', $darkColorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'backgroundColorPage', $colorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'backgroundColorPageDark', $darkColorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'backgroundColorHighlight', $colorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'backgroundColorHighlightDark', $darkColorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'backgroundColorHighlightSoft', $colorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'backgroundColorHighlightSoftDark', $darkColorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->endCollapsibleFields() ?>

            <?= $form->beginCollapsibleFields(Yii::t('CleanThemeModule.config', 'Base fonts')) ?>
            <?= $form->field($model, 'fontFamily')->textInput() ?>
            <?= $form->field($model, 'fontSize')->textInput(['type' => 'number', 'step' => 1, 'min' => 6]) ?>
            <?= $form->field($model, 'fontWeight')->textInput() ?>
            <?= $form->field($model, 'fontBoldWeight')->textInput() ?>
            <?= $form->endCollapsibleFields() ?>

            <?= $form->beginCollapsibleFields(Yii::t('CleanThemeModule.config', 'Heading fonts')) ?>
            <?= $form->field($model, 'headingFontFamily')->textInput() ?>
            <?= $form->field($model, 'phFontSize')->textInput(['type' => 'number', 'step' => 1, 'min' => 6]) ?>
            <?= $form->field($model, 'phFontWeight')->textInput() ?>
            <?= $form->field($model, 'h1FontSize')->textInput(['type' => 'number', 'step' => 0.01, 'min' => 0.5]) ?>
            <?= $form->field($model, 'h1StreamFontSize')->textInput(['type' => 'number', 'step' => 0.01, 'min' => 0.5]) ?>
            <?= $form->field($model, 'h2FontSize')->textInput(['type' => 'number', 'step' => 0.01, 'min' => 0.5]) ?>
            <?= $form->field($model, 'h2StreamFontSize')->textInput(['type' => 'number', 'step' => 0.01, 'min' => 0.5]) ?>
            <?= $form->field($model, 'h3FontSize')->textInput(['type' => 'number', 'step' => 0.01, 'min' => 0.5]) ?>
            <?= $form->field($model, 'h4FontSize')->textInput(['type' => 'number', 'step' => 0.01, 'min' => 0.5]) ?>
            <?= $form->field($model, 'h5FontSize')->textInput(['type' => 'number', 'step' => 0.01, 'min' => 0.5]) ?>
            <?= $form->field($model, 'h6FontSize')->textInput(['type' => 'number', 'step' => 0.01, 'min' => 0.5]) ?>
            <?= $form->endCollapsibleFields() ?>

            <?= $form->beginCollapsibleFields(Yii::t('CleanThemeModule.config', 'Panel borders')) ?>
            <?= $form->field($model, 'panelBorderWidth')->textInput(['type' => 'number', 'step' => 1, 'min' => 0]) ?>
            <?= $form->field($model, 'panelBorderStyle')->dropDownList(Configuration::getBorderStyleOptions()) ?>
            <?= $form->field($model, 'panelBorderColor', $colorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'panelBorderColorDark', $darkColorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'panelBorderRadius')->textInput(['type' => 'number', 'step' => 1, 'min' => 1]) ?>
            <?= $form->field($model, 'panelBoxShadow')->textInput() ?>
            <?= $form->endCollapsibleFields() ?>

            <?= $form->beginCollapsibleFields(Yii::t('CleanThemeModule.config', 'Menus')) ?>
            <?= $form->field($model, 'menuFontSize')->textInput(['type' => 'number', 'step' => 1, 'min' => 6]) ?>
            <?= $form->field($model, 'menuTextColor', $colorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'menuTextColorDark', $darkColorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'menuBorderColor', $colorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'menuBorderColorDark', $darkColorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'menuStyle')->dropDownList(Configuration::getMenuStyleOptions()) ?>
            <?= $form->endCollapsibleFields() ?>

            <?= $form->beginCollapsibleFields(Yii::t('CleanThemeModule.config', 'Top menu')) ?>
            <?= $form->field($model, 'topBarHeight')->textInput(['type' => 'number', 'step' => 1, 'min' => 50, 'max' => 150]) ?>
            <?= $form->field($model, 'topBarFontSize')->textInput(['type' => 'number', 'step' => 1, 'min' => 4]) ?>
            <?= $form->field($model, 'topMenuNavJustifyContent')->dropDownList(Configuration::getJustifyContentOptions()) ?>
            <?= $form->field($model, 'topMenuBackgroundColor', $colorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'topMenuBackgroundColorDark', $darkColorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'topMenuTextColor', $colorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'topMenuTextColorDark', $darkColorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'topMenuButtonHoverBackgroundColor', $colorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'topMenuButtonHoverBackgroundColorDark', $darkColorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'topMenuButtonHoverTextColor', $colorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'topMenuButtonHoverTextColorDark', $darkColorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->endCollapsibleFields() ?>

            <?= $form->beginCollapsibleFields(Yii::t('CleanThemeModule.config', 'Bottom menu')) ?>
            <?= $form->field($model, 'topMenuBackgroundColor', $colorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'topMenuBackgroundColorDark', $darkColorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'bottomMenuTextColor', $colorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->field($model, 'bottomMenuTextColorDark', $darkColorInputOptions)->colorInput()->label(null, $colorInputLabelOptions) ?>
            <?= $form->endCollapsibleFields() ?>

            <?= $form->beginCollapsibleFields(Yii::t('CleanThemeModule.config', 'Menus on small screens')) ?>
            <?= $form->field($model, 'hideTopMenuOnScrollDown')->checkbox() ?>
            <?= $form->field($model, 'hideBottomMenuOnScrollDown')->checkbox() ?>
            <?= $form->field($model, 'hideTextInBottomMenuItems')->checkbox() ?>
            <?= $form->endCollapsibleFields() ?>

            <?= Button::save()->submit() ?>
            <?php ActiveForm::end(); ?>

            <?php ActiveForm::begin(); ?>
            <?= Html::input('hidden', 'reset', true) ?>
            <?= Button::asLink(Yii::t('CleanThemeModule.config', 'Reset everything to default values'))
                ->cssClass('text-danger')
                ->options(['style' => 'margin-top: -20px;'])
                ->icon('undo')
                ->confirm()
                ->submit()
                ->right() ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
