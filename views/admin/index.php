<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\editable\Editable;
use kartik\widgets\ActiveForm;
?>
<div id="currencyAdd" class="fade modal" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <?php
            $form = ActiveForm::begin([
                        'action' => Url::toRoute(['create']),
                        'type' => ActiveForm::TYPE_HORIZONTAL
            ]);
            ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">
                    <?= Yii::t('currencies', 'New currency'); ?>
                </h4>
            </div>
            <div class="modal-body">
                <?= $form->field($currencyForm, 'name') ?>
                <?= $form->field($currencyForm, 'symbol') ?>
                <?= $form->field($currencyForm, 'code') ?>
                <?= $form->field($currencyForm, 'rate') ?>
                <?= $form->field($currencyForm, 'is_active')->checkbox() ?>
                <?= $form->field($currencyForm, 'is_default')->checkbox() ?>
            </div>
            <div class="modal-footer">
                <?= Html::submitButton(Yii::t('currencies', 'Save'), ['class' => 'btn btn-success ']); ?>
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
        'pjax' => true,
        'bordered' => true,
        'striped' => false,
        'condensed' => false,
        'responsive' => true,
        'hover' => true,
        'export' => false,
        'floatHeader' => false,
        'showPageSummary' => false,
        'panel' => [
            'type' => \kartik\grid\GridView::TYPE_DEFAULT
        ],
        'layout' => "{toolbar}{items}{pager}",
        'toolbar' => [
            ['content' =>
                Html::a('<i class="glyphicon glyphicon-plus"></i>', NULL, [
                    'data-pjax' => 0,
                    'data-toggle' => 'modal',
                    'data-target' => '#currencyAdd',
                    'class' => 'btn btn-default',
                    'title' => Yii::t('app', 'Create new case')]
                ) . ' ' .
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', Url::toRoute(['']), [
                    'data-pjax' => 0,
                    'class' => 'btn btn-default',
                    'title' => Yii::t('app', 'Reset filter')]
                )
            ],
        ],
        'columns' => [
            [
                'attribute' => 'code',
                'width' => '10%',
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
                'width' => '45%',
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
                'width' => '10%',
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
                'width' => '10%',
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
                'attribute' => 'is_active',
                'width' => '10%',
                'class' => '\kartik\grid\BooleanColumn',
                'trueLabel' => Yii::t('yii', 'Yes'),
                'falseLabel' => Yii::t('yii', 'No'),
                'content' => function ($model) {
                    if ($model->is_active) {
                        return Html::a('<span class="glyphicon glyphicon-eye-close text-danger"></span>', Url::toRoute(['disable', 'code' => $model->code]), [
                                    'title' => Yii::t('currencies', 'Disable'),
                                    'data-method' => 'post',
                                    'data-confirm' => Yii::t('currencies', 'Are you sure you want to disable this currency?'),
                                    'data-pjax' => '0',
                        ]);
                    } else {
                        return Html::a('<span class="glyphicon glyphicon-eye-open text-success"></span>', Url::toRoute(['enable', 'code' => $model->code]), [
                                    'title' => Yii::t('currencies', 'Enable'),
                                    'data-method' => 'post',
                                    'data-confirm' => Yii::t('currencies', 'Are you sure you want to enable this currency?'),
                                    'data-pjax' => '0',
                        ]);
                    }
                }
            ],
            [
                'attribute' => 'is_default',
                'width' => '10%',
                'format' => 'raw',
                'class' => '\kartik\grid\BooleanColumn',
                'trueLabel' => Yii::t('yii', 'Yes'),
                'falseLabel' => Yii::t('yii', 'No'),
                'content' => function ($model) {
                    if (!$model->is_default) {
                        return Html::a('<span class="glyphicon glyphicon-star-empty"></span>', Url::toRoute(['default', 'code' => $model->code]), [
                                    'title' => Yii::t('currencies', 'Set default'),
                                    'data-method' => 'post',
                                    'data-confirm' => Yii::t('currencies', 'Are you sure you want to set this currency as default?'),
                                    'data-pjax' => '0',
                        ]);
                    } else {
                        return '<span class="glyphicon glyphicon-star text-success"></span>';
                    }
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