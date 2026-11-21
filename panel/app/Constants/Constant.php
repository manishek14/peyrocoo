<?php


namespace App\Constants;

class Constant
{





    //test
    const FREE = "free";
    const MONY = "mony";

    //correct
    const CORRECT = "correct";
    const FALSE = "false";
    //questions
    const DESCRIPTIVE = "descriptive";
    const TEST = "test";

    //removable
    const REMOVABLE = "removable";
    const UNABLETODELETE = "unable_to_delete";

    //like
    const LIKE = "like";
    const DIS_LIKE = "dis_like";
    const HAS_IT = "has_it";
    const HAS_NO = "has_no";


    // Global Constant
    const UN_DEFINED = "un-defined";
    const ACTIVE = 'active';
    const IN_ACTIVE = 'in-active';
    const MALE = 'male';
    const FEMALE = 'female';
    const PENDING = 'pending';
    const CONFIRMED = 'confirm';
    const REJECTED = 'rejected';

    const DRAFT = 'draft';
    const PUBLISHED = 'published';
    const SCHEDULED = 'scheduled';

    const DELETED = 'deleted';
    const UN_DELETED = 'un-deleted';




    const SEARCHABLE = true;

    // categories entities
    const SIGNS = "signs";



    // File Constant
    const IMAGE = 'image';
    const SOUND = 'sound';
    const VIDEO = 'video';

    const WHITE_MIME_TYPE_LIST = [
        'image/jpeg', 'image/png', 'image/jpg', 'audio/mpeg', 'video/mp4',
        'application/zip', 'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'application/vnd.rar', 'text/plain', 'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];


    const PRODUCT_AVATAR_SIZE = [
        '100' => ['w' => '100', 'h' => '100'],
        '300' => ['w' => '300', 'h' => '300']
    ];
    const USER_AVATAR_SIZE = [
        '100' => ['w' => '200', 'h' => '200'],
    ];



    // admins
    const ADMINS_IMAGE_PATH = 'uploads' . DIRECTORY_SEPARATOR . 'admins' . DIRECTORY_SEPARATOR . 'image' . DIRECTORY_SEPARATOR . '';

    // users
    const USERS_AVATAR_PATH = 'uploads' . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . 'image' . DIRECTORY_SEPARATOR . '';

    // request

    const REQUEST_IMAGE_PATH = 'uploads' . DIRECTORY_SEPARATOR . 'request' . DIRECTORY_SEPARATOR . 'image' . DIRECTORY_SEPARATOR . '';





    public static function getStatuses($status = null)
    {
        if (is_null($status)) {
            return [
                Constant::ACTIVE => 'فعال',
                Constant::IN_ACTIVE => ' غیر فعال',
            ];
        }
        if (in_array($status, array_keys(self::getStatuses()))) {
            return self::getStatuses()[$status];
        }
        return Constant::UN_DEFINED;
    }
    public static function getStatusesViewer()
    {
        $activeStatuses = self::getStatuses();
        $activeStatusesViewer = [];
        foreach ($activeStatuses as $key => $value) {
            $activeStatusesViewer[] = [
                'id' => $key,
                'title' => $value
            ];
        }
        return $activeStatusesViewer;
    }


    //tests
    public static function getTestTypes($status = null)
    {
        if (is_null($status)) {
            return [
                Constant::MONY => 'پولی',
                Constant::FREE => ' رایگان ',
            ];
        }
        if (in_array($status, array_keys(self::getTestTypes()))) {
            return self::getTestTypes()[$status];
        }
        return Constant::UN_DEFINED;
    }
    public static function getTestTypesViewer()
    {
        $getTestTypes = self::getTestTypes();
        $getTestTypesViewer = [];
        foreach ($getTestTypes as $key => $value) {
            $getTestTypesViewer[] = [
                'id' => $key,
                'title' => $value
            ];
        }
        return $getTestTypesViewer;
    }

    // category entities
    public static function getCategoryEntities($status = null)
    {
        $categoryEntities = [

            self::SIGNS => 'تابلو راهنمایی',

        ];
        if (is_null($status)) {
            return $categoryEntities;
        }
        if (in_array($status, array_keys($categoryEntities))) {
            return $categoryEntities[$status];
        }
    }

    public static function getCategoryEntitiesView(): array
    {
        $categoryEntities = self::getCategoryEntities();
        $categoryEntitiesView = [];
        foreach ($categoryEntities as $key => $value) {
            $categoryEntitiesView[] = [
                'id' => $key,
                'title' => $value
            ];
        }
        return $categoryEntitiesView;
    }
}
