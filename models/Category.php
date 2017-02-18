<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "categories".
 *
 * @property integer $id
 * @property string $name
 *
 * @property User[] $users
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'categories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['cat_id' => 'id']);
    }

    public static function getCategoriesForDropdown($withEmptyFirst = true)
    {
        $categoriesRaw = Category::find()->all();
        $categories = [];
        /* @var $item Category */
        if ($withEmptyFirst) {
            $categories[0] = 'Изберете категория...';
        }
        foreach ($categoriesRaw as $item) {
            $categories[$item->id] = $item->name;
        }

        return $categories;
    }
}
