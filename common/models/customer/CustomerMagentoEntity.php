<?php

namespace common\models\customer;

use Yii;

/**
 * This is the model class for table "customer_entity".
 *
 * @property int $entity_id Entity Id
 * @property int $entity_type_id Entity Type Id
 * @property int $attribute_set_id Attribute Set Id
 * @property int $website_id Website Id
 * @property string $email Email
 * @property int $group_id Group Id
 * @property string $increment_id Increment Id
 * @property int $store_id Store Id
 * @property string $created_at Created At
 * @property string $updated_at Updated At
 * @property int $is_active Is Active
 * @property int $disable_auto_group_change Disable automatic group change based on VAT ID
 */
class CustomerMagentoEntity extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer_entity';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['entity_type_id', 'attribute_set_id', 'website_id', 'group_id', 'store_id', 'is_active', 'disable_auto_group_change'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['email'], 'string', 'max' => 255],
            [['increment_id'], 'string', 'max' => 50],
            [['email', 'website_id'], 'unique', 'targetAttribute' => ['email', 'website_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'entity_id' => 'Entity ID',
            'entity_type_id' => 'Entity Type ID',
            'attribute_set_id' => 'Attribute Set ID',
            'website_id' => 'Website ID',
            'email' => 'Email',
            'group_id' => 'Group ID',
            'increment_id' => 'Increment ID',
            'store_id' => 'Store ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'is_active' => 'Is Active',
            'disable_auto_group_change' => 'Disable Auto Group Change',
        ];
    }

}
