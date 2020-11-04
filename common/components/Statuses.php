<?php
namespace common\components;
class Statuses {
    /**
     * ====================
     * STATUS TYPES
     * ====================
     */
    const STATUS_ACTIVE     = 1;
    const STATUS_INACTIVE   = 2;
    const STATUS_DISABLED   = 3;
    const STATUS_SUSPENDED  = 4;
    const STATUS_REMOVED    = 5;
    const STATUS_DECLINED   = 6;
    const STATUS_RESOLVED   = 7;
    /**
     * @return array
     */
    public static function all() {
        return [
            self::STATUS_ACTIVE     => 'Active',
            self::STATUS_INACTIVE   => 'Inactive',
            self::STATUS_DISABLED   => 'Disabled',
            self::STATUS_SUSPENDED  => 'Suspended',
            self::STATUS_REMOVED    => 'Removed',
            self::STATUS_DECLINED   => 'Declined',
            self::STATUS_RESOLVED   => 'Resolved',
        ];
    }
    public static function display($status_id) {
        return !empty(self::all()[$status_id]) ? self::all()[$status_id] : NULL;
    }
}