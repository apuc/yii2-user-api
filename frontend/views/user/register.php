<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

\frontend\assets\RegisterAsset::register($this);


$form = ActiveForm::begin([
    'id' => 'register-form',
    'options' => ['class' => 'form-horizontal'],
]) ?>

<?= $form->field($model, 'name') ?>
<?= $form->field($model, 'surname') ?>
<?= $form->field($model, 'fathername') ?>
<?= $form->field($model, 'birth_date')->widget(\yii\jui\DatePicker::class, [
    'language' => 'ru',
    'dateFormat' => 'd-M-y',
]) ?>
<?= $form->field($model, 'passport') ?>
<?= $form->field($model, 'email')->input('email') ?>
<?= $form->field($model, 'phone')->input('phone', ['id' => 'phone_number_field']) ?>


<div class="form-group">
    <div class="col-lg-offset-1 col-lg-11">
        <?= Html::submitButton('Авторизация', ['class' => 'btn btn-primary']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>