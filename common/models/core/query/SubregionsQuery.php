<?php

namespace common\models\core\query;

/**
 * This is the ActiveQuery class for [[Students]].
 *
 * @see Students
 */
class SubregionsQuery extends \yii\db\ActiveQuery
{

    /**
     * USA States Only
     * @return $this
     */
    public function usa()
    {
        return $this->andWhere(['region_id' => [840, 630]]);
    }

    public function abc()
    {
        return $this->orderBy(['name'=> SORT_ASC]);
    }

    public function states(){
        return $this->andWhere([
            'region_id' => 840,
            'name'      => [
                'Alaska',
                'Alabama',
                'Arkansas',
                'American Samoa',
                'Arizona',
                'California',
                'Colorado',
                'Connecticut',
                'District of Columbia',
                'Delaware',
                'Florida',
                'Georgia',
                'Guam',
                'Hawaii',
                'Iowa',
                'Idaho',
                'Illinois',
                'Indiana',
                'Kansas',
                'Kentucky',
                'Louisiana',
                'Massachusetts',
                'Maryland',
                'Maine',
                'Michigan',
                'Minnesota',
                'Missouri',
                'Mississippi',
                'Montana',
                'North Carolina',
                'North Dakota',
                'Nebraska',
                'New Hampshire',
                'New Jersey',
                'New Mexico',
                'Nevada',
                'New York',
                'Ohio',
                'Oklahoma',
                'Oregon',
                'Pennsylvania',
                'Puerto Rico',
                'Rhode Island',
                'South Carolina',
                'South Dakota',
                'Tennessee',
                'Texas',
                'Utah',
                'Virginia',
                'Virgin Islands',
                'Vermont',
                'Washington',
                'Wisconsin',
                'West Virginia',
                'Wyoming'
            ]
        ]);
    }
}