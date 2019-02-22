<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Upload CSV file';
?>
<div class="site-index">
    <div class="jumbotron">
        <h1>Upload your CSV here</h1>

        <p class="lead">Note, that your csv file should be 1mb or less!</p>
        <?php
        echo Html::beginForm(['/csv/upload'], 'post', [
                'class' => 'upload js-upload-form',
                'enctype' => 'multipart/form-data',
        ]);
        ?>
            <input type="file" class="upload--file js-file-select" name="csvu" accept="text/csv" />
            <p><button class="btn btn-lg btn-info js-file-select-trigger" href="http://www.yiiframework.com" onclick="return false;">Select file</button></p>
            <div class="progress progress-striped active upload--loader js-upload-loader" style="display: none;">
                <div class="progress-bar"  role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                    <span class="sr-only">uploading</span>
                </div>
            </div>
        <?php
        echo Html::endForm();
        ?>
    </div>
    <div class="body-content">

    </div>
</div>

<div class="modal fade" id="CSV_WARN">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Warning</h4>
            </div>
            <div class="modal-body">
                <p class="js-text"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
