<?php
use yii\grid\GridView;
?>
<h1>Результат</h1>
<?php
echo GridView::widget([
  'dataProvider' => $dataProvider,
  'pager' => ['maxButtonCount' => 5],
  'columns' => [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'label' =>"ip",
        'attribute' => 'ip',
        'value'=>function($data){return $data["ip"];}
    ],
    [
        'label' =>"URL с которого зашел первый раз",
        'attribute' => 'url_from',
        'value'=>function($data){return $data["url_from"];}
    ],
    [
        'label' =>"Дата первого захода",
        'attribute' => 'first_date',
        'value'=>function($data){return $data["first_date"];}
    ],
    [
        'label' =>"URL на который зашел последний раз",
        'attribute' => 'url_to',
        'value'=>function($data){return $data["url_to"];}
    ],
    [
        'label' =>"Дата последнего захода",
        'attribute' => 'last_date',
        'value'=>function($data){return $data["last_date"];}
    ],
    [
        'label' =>"Кол-во просмотренных уникальных URL-адресов",
        'attribute' => 'uniq_number',
        'value'=>function($data){return $data["uniq_number"];}
    ],
    [
        'label' =>"OS",
        'attribute' => 'os',
        'value'=>function($data){return $data["os"];}
    ],
    [
        'label' =>"Браузер",
        'attribute' => 'browser',
        'value'=>function($data){return $data["browser"];}
    ],
    ['class' => 'yii\grid\ActionColumn'],
  ],
]);
