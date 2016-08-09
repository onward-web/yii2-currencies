<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\editable\Editable;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<div class="box">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?= Yii::t('currencies', 'New currency'); ?></h3>
        </div>
        <div class="panel-body">
            <?php
            $form = ActiveForm::begin([
                'action' => Url::toRoute(['create']),
                'options' => [
                    'class' => 'form-horizontal',
                ],
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-md-8\">{input}</div>\n<div class=\"col-md-offset-4 col-md-8\">{error}</div>",
                    'labelOptions' => ['class' => 'col-md-4 control-label'],
                ]
            ]);
            ?>
            <div class="col-md-6">
                <?= $form->field($currencyForm, 'name') ?>
                <?= $form->field($currencyForm, 'symbol') ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($currencyForm, 'code') ?>
                <?= $form->field($currencyForm, 'rate') ?>
            </div>
            <div class="form-group col-md-offset-2 col-md-10">
                <?= Html::submitButton(Yii::t('core', 'Save'), ['class' => 'btn btn-success ']); ?>
            </div>
            <?php
            ActiveForm::end();
            ?>
        </div>
    </div>
</div>

<div class="box">
    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $filterModel,
        'layout' => "{items}\n{pager}",
        'pjax' => true,
        'bordered' => true,
        'striped' => false,
        'condensed' => false,
        'responsive' => true,
        'hover' => true,
        'export' => false,
        'floatHeader' => false,
        'showPageSummary' => false,
        'columns' => [
            [
                'attribute' => 'rate',
                'width' => '20%',
                'format' => 'raw',
                'value' => function($model) use ($currencyForm) {
                    return Editable::widget([
                                'name' => $currencyForm->formName() . '[code]',
                                'ajaxSettings' => ['url' => Url::toRoute(['update', 'code' => $model->code, 'field' => 'code'])],
                                'asPopover' => true,
                                'placement' => 'top',
                                'value' => $model->code,
                                'header' => Yii::t('currencies', 'Currency code'),
                                'size' => 'md',
                                'options' => ['class' => 'url', 'placeholder' => '']
                    ]);
                }
            ],
            [
                'attribute' => 'name',
                'width' => '40%',
                'format' => 'raw',
                'value' => function($model) use ($currencyForm) {
                    return Editable::widget([
                                'name' => $currencyForm->formName() . '[name]',
                                'ajaxSettings' => ['url' => Url::toRoute(['update', 'code' => $model->code, 'field' => 'name'])],
                                'asPopover' => true,
                                'placement' => 'top',
                                'value' => $model->name,
                                'header' => Yii::t('currencies', 'Currency name'),
                                'size' => 'md',
                                'options' => ['class' => 'url', 'placeholder' => '']
                    ]);
                }
            ],
            [
                'attribute' => 'symbol',
                'width' => '20%',
                'format' => 'raw',
                'value' => function($model) use ($currencyForm) {
                    return Editable::widget([
                                'name' => $currencyForm->formName() . '[symbol]',
                                'ajaxSettings' => ['url' => Url::toRoute(['update', 'code' => $model->code, 'field' => 'symbol'])],
                                'asPopover' => true,
                                'placement' => 'top',
                                'value' => $model->symbol,
                                'header' => Yii::t('currencies', 'Currency symbol'),
                                'size' => 'md',
                                'options' => ['class' => 'url', 'placeholder' => '']
                    ]);
                }
            ],
            [
                'attribute' => 'rate',
                'width' => '20%',
                'format' => 'raw',
                'value' => function($model) use ($currencyForm) {
                    return Editable::widget([
                                'name' => $currencyForm->formName() . '[rate]',
                                'ajaxSettings' => ['url' => Url::toRoute(['update', 'code' => $model->code, 'field' => 'rate'])],
                                'asPopover' => true,
                                'placement' => 'top',
                                'value' => $model->rate,
                                'header' => Yii::t('currencies', 'Currency rate'),
                                'size' => 'md',
                                'options' => ['class' => 'url', 'placeholder' => '']
                    ]);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::toRoute(['delete', 'code' => $model->code]), [
                                    'title' => Yii::t('yii', 'Delete'),
                                    'data-method' => 'post',
                                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                    'data-pjax' => '0',
                        ]);
                    }
                ],
            ],
        ],
    ]);
    ?>
</div>