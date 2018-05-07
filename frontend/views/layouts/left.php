<aside class="main-sidebar">

    <section class="sidebar">
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['label' => 'Поставщики', 'options' => ['class' => 'header']],
                    ['label' => '1000size', 'icon' => 'file', 'url' => ['/size-parser/categories']],
                    ['label' => 'Powergroup', 'icon' => 'file', 'url' => ['/power-parser/load']],

                    ['label' => 'Управление', 'options' => ['class' => 'header']],

                    ['label' => 'Панель управления', 'icon' => 'dashboard', 'url' => ['/dashboard']],
                    ['label' => 'Наценка', 'icon' => 'balance-scale', 'url' => ['/factor']],
                    ['label' => 'Настройки', 'icon' => 'gears', 'url' => ['/settings']],
                    [
						'label' => 'Свободно ' . \Yii::$app->formatter->asShortSize(disk_free_space('/')),
						'icon' => 'database', 
						'url' => '#',
						'options' => [
							'class' => 'bg-' . (disk_free_space('/') > 40000000 ? 'green' : 'red')
						]
					],
                ],
            ]
        ) ?>
    </section>
</aside>
