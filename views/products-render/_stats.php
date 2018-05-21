<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 09.05.2018
 * Time: 6:43
 */
?>
<div>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Статистика
                по категориям</a></li>
        <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Profile</a></li>
        <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Messages</a>
        </li>
        <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Settings</a>
        </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="home">
            <?php
            $categories = \app\models\ProductsRender::find()->select('category')->distinct()->column(); ?>
            <table class="table table-bordered">
                <tbody>
                <?php foreach ($categories as $category) { ?>
                    <tr>
                        <td> <?php echo $category; ?></td>
                        <td><? echo \app\models\ProductsRender::find()->where(['like', 'category', $category])->count(); ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td> <?php echo "<b>БЕЗ КАТЕГОРИИ</b>"; ?></td>
                    <td><? echo \app\models\ProductsRender::find()->where(['OR',
                            ['category' => ''],
                            ['IS', 'category', NULL],
                        ])->count(); ?>
                    </td>
                </tr>
                </tbody>
            </table>


        </div>
        <div role="tabpanel" class="tab-pane" id="profile">...</div>
        <div role="tabpanel" class="tab-pane" id="messages">...</div>
        <div role="tabpanel" class="tab-pane" id="settings">...</div>
    </div>

</div>