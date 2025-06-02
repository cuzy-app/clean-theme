<?php
/**
 * Clean Theme
 * @link https://github.com/cuzy-app/clean-theme
 * @license https://github.com/cuzy-app/clean-theme/blob/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun) for [CUZY.APP](https://www.cuzy.app)
 */

namespace humhub\modules\cleanTheme\models;

use humhub\modules\admin\permissions\ManageSettings;
use humhub\modules\cleanTheme\Module;
use humhub\modules\ui\form\widgets\ActiveForm;
use humhub\modules\ui\form\widgets\CodeMirrorInputWidget;
use humhub\modules\ui\view\components\View;
use humhub\widgets\Button;
use kartik\widgets\ColorInput;
use Yii;

/**
 * @var $this View
 * @var $model Configuration
 * @var $uploadModel UploadConfiguration
 */

/** @var Module $module */
$module = Yii::$app->getModule('clean-theme');

$colorInputWidgetOptions = ['options' => ['placeholder' => Yii::t('CleanThemeModule.config', 'Select color ...')]];
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <?= Yii::$app->user->can(ManageSettings::class) ? Button::defaultType(Yii::t('CleanThemeModule.config', 'Choose the theme'))
            ->link(['/admin/setting/design'])
            ->style('margin-left: 6px;')
            ->right()
            ->sm() : '' ?>
        <strong><?= $module->getName() ?></strong>
        <div class="help-block"><?= $module->getDescription() ?></div>
    </div>

    <div class="panel-body">
        <div class="alert alert-info cuzy-free-module-info">
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
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <?php $form = ActiveForm::begin([
                    'layout' => 'inline',
                ]); ?>
                <?= $form->field($uploadModel, 'jsonConfigurationFile')->fileInput(['class' => 'btn btn-primary']) ?>
                <?= Button::save(Yii::t('CleanThemeModule.config', 'Import'))->icon('upload')->confirm(null, Yii::t('CleanThemeModule.config', 'This will overwrite your current configuration'))->submit() ?>
                <?php ActiveForm::end(); ?>
                <?= Button::primary(Yii::t('CleanThemeModule.config', 'Export'))->icon('download')->link(['download-json'])->loader(false) ?>
            </div>
        </div>

        <br>

        <div id="clean-theme-configuration-form">
            <?php $form = ActiveForm::begin([
//                'acknowledge' => true, // Comment because doesn't work well (warn even if no change)
            ]); ?>

            <?= $form->beginCollapsibleFields(Yii::t('CleanThemeModule.config', 'Main colors')) ?>
            <?= $form->field($model, 'default')->widget(ColorInput::class, $colorInputWidgetOptions) ?>
            <?= $form->field($model, 'primary')->widget(ColorInput::class, $colorInputWidgetOptions) ?>
            <?= $form->field($model, 'info')->widget(ColorInput::class, $colorInputWidgetOptions) ?>
            <?= $form->field($model, 'success')->widget(ColorInput::class, $colorInputWidgetOptions) ?>
            <?= $form->field($model, 'warning')->widget(ColorInput::class, $colorInputWidgetOptions) ?>
            <?= $form->field($model, 'danger')->widget(ColorInput::class, $colorInputWidgetOptions) ?>
            <?= $form->field($model, 'link')->widget(ColorInput::class, $colorInputWidgetOptions) ?>
            <?= $form->endCollapsibleFields() ?>

            <?= $form->beginCollapsibleFields(Yii::t('CleanThemeModule.config', 'Text colors')) ?>
            <?= $form->field($model, 'textColorHeading')->widget(ColorInput::class, $colorInputWidgetOptions) ?>
            <?= $form->field($model, 'textColorMain')->widget(ColorInput::class, $colorInputWidgetOptions) ?>
            <?= $form->field($model, 'textColorDefault')->widget(ColorInput::class, $colorInputWidgetOptions) ?>
            <?= $form->field($model, 'textColorSecondary')->widget(ColorInput::class, $colorInputWidgetOptions) ?>
            <?= $form->field($model, 'textColorHighlight')->widget(ColorInput::class, $colorInputWidgetOptions) ?>
            <?= $form->field($model, 'textColorSoft')->widget(ColorInput::class, $colorInputWidgetOptions) ?>
            <?= $form->field($model, 'textColorSoft2')->widget(ColorInput::class, $colorInputWidgetOptions) ?>
            <?= $form->field($model, 'textColorSoft3')->widget(ColorInput::class, $colorInputWidgetOptions) ?>
            <?= $form->field($model, 'textColorContrast')->widget(ColorInput::class, $colorInputWidgetOptions) ?>
            <?= $form->endCollapsibleFields() ?>

            <?= $form->beginCollapsibleFields(Yii::t('CleanThemeModule.config', 'Background colors')) ?>
            <?= $form->field($model, 'backgroundColorMain')->widget(ColorInput::class, $colorInputWidgetOptions) ?>
            <?= $form->field($model, 'backgroundColorSecondary')->widget(ColorInput::class, $colorInputWidgetOptions) ?>
            <?= $form->field($model, 'backgroundColorPage')->widget(ColorInput::class, $colorInputWidgetOptions) ?>
            <?= $form->field($model, 'backgroundColorHighlight')->widget(ColorInput::class, $colorInputWidgetOptions) ?>
            <?= $form->field($model, 'backgroundColorHighlightSoft')->widget(ColorInput::class, $colorInputWidgetOptions) ?>
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
            <?= $form->field($model, 'panelBorderColor')->widget(ColorInput::class, $colorInputWidgetOptions) ?>
            <?= $form->field($model, 'panelBorderRadius')->textInput(['type' => 'number', 'step' => 1, 'min' => 1]) ?>
            <?= $form->field($model, 'panelBoxShadow')->textInput() ?>
            <?= $form->endCollapsibleFields() ?>

            <?= $form->beginCollapsibleFields(Yii::t('CleanThemeModule.config', 'Menus')) ?>
            <?= $form->field($model, 'menuFontSize')->textInput(['type' => 'number', 'step' => 1, 'min' => 6]) ?>
            <?= $form->field($model, 'menuTextColor')->widget(ColorInput::class, $colorInputWidgetOptions) ?>
            <?= $form->field($model, 'menuBorderColor')->widget(ColorInput::class, $colorInputWidgetOptions) ?>
            <?= $form->field($model, 'menuStyle')->dropDownList(Configuration::getMenuStyleOptions()) ?>
            <?= $form->endCollapsibleFields() ?>

            <?= $form->beginCollapsibleFields(Yii::t('CleanThemeModule.config', 'Top menu')) ?>
            <?= $form->field($model, 'topBarHeight')->textInput(['type' => 'number', 'step' => 1, 'min' => 50, 'max' => 150]) ?>
            <?= $form->field($model, 'topBarFontSize')->textInput(['type' => 'number', 'step' => 1, 'min' => 4]) ?>
            <?= $form->field($model, 'topMenuNavJustifyContent')->dropDownList(Configuration::getJustifyContentOptions()) ?>
            <?= $form->field($model, 'topMenuBackgroundColor')->widget(ColorInput::class, $colorInputWidgetOptions) ?>
            <?= $form->field($model, 'topMenuTextColor')->widget(ColorInput::class, $colorInputWidgetOptions) ?>
            <?= $form->field($model, 'topMenuButtonHoverBackgroundColor')->widget(ColorInput::class, $colorInputWidgetOptions) ?>
            <?= $form->field($model, 'topMenuButtonHoverTextColor')->widget(ColorInput::class, $colorInputWidgetOptions) ?>
            <?= $form->endCollapsibleFields() ?>

            <?= $form->beginCollapsibleFields(Yii::t('CleanThemeModule.config', 'Menus on small screens')) ?>
            <?= $form->field($model, 'hideTopMenuOnScrollDown')->checkbox() ?>
            <?= $form->field($model, 'hideBottomMenuOnScrollDown')->checkbox() ?>
            <?= $form->field($model, 'hideTextInBottomMenuItems')->checkbox() ?>
            <?= $form->endCollapsibleFields() ?>

            <?= $form->beginCollapsibleFields(Yii::t('CleanThemeModule.config', 'Other settings')) ?>
            <?= $form->field($model, 'containerMaxWidth')->textInput(['type' => 'number', 'step' => 1, 'min' => 800]) ?>
            <?= $form->endCollapsibleFields() ?>

            <?= $form->beginCollapsibleFields(Yii::t('CleanThemeModule.config', 'Custom code')) ?>
            <?= $form->field($model, 'scss')->widget(CodeMirrorInputWidget::class, ['mode' => 'text/x-scss']) ?>
            <?= $form->endCollapsibleFields() ?>

            <?= Button::asLink(Yii::t('CleanThemeModule.config', 'Reset everything to default values'))
                ->link(['reset-all-to-default'])
                ->icon('undo')
                ->cssClass('text-danger')
                ->confirm()
                ->right() ?>

            <?= Button::save()->submit() ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
