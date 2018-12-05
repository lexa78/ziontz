<?php
use yii\widgets\ActiveForm;
?>
<h1>Загрузчик файлов логов</h1>
  <div>
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

    <?= $form->field($model, 'uploadedFile')->fileInput() ?>

      <button>Загрузить и обработать</button>

    <?php ActiveForm::end() ?>
  </div>
