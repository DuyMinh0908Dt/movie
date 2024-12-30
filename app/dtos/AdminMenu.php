<?php

namespace App\Dtos;

class AdminMenu
{
    private static
    $MENU_ITEMS = [
        [
            'href' => '/admin/tong-quan',
            'icon' => 'fa-solid fa-chart-pie',
            'text' => 'Tổng quan',
            'permissions' => [Permission::THONG_KE]
        ],
        [
            'href' => '/admin/phim',
            'icon' => 'fa-solid fa-film',
            'text' => 'Phim',
            'permissions' => [Permission::READ_PHIM, Permission::CREATE_PHIM, Permission::UPDATE_PHIM, Permission::DELETE_PHIM]
        ],
        [
            'href' => '/admin/the-loai',
            'icon' => 'fa-solid fa-grip',
            'text' => 'Thể loại',
            'permissions' => [Permission::READ_THELOAI, Permission::CREATE_THELOAI, Permission::UPDATE_THELOAI, Permission::DELETE_THELOAI]
        ],
        [
            'href' => '/admin/suat-chieu',
            'icon' => 'fa-solid fa-clock',
            'text' => 'Suất chiếu',
            'permissions' => [Permission::READ_SUATCHIEU, Permission::CREATE_SUATCHIEU, Permission::UPDATE_SUATCHIEU, Permission::DELETE_SUATCHIEU]
        ],
        [
            'href' => '/admin/phong-chieu',
            'icon' => 'fa-solid fa-person-booth',
            'text' => 'Phòng chiếu',
            'permissions' => [Permission::READ_PHONGCHIEU, Permission::CREATE_PHONGCHIEU, Permission::UPDATE_PHONGCHIEU, Permission::DELETE_PHONGCHIEU]
        ],
        [
            'href' => '/admin/rap-chieu',
            'icon' => 'fa-solid fa-building',
            'text' => 'Rạp chiếu',
            'permissions' => [Permission::READ_RAPCHIEU, Permission::CREATE_RAPCHIEU, Permission::UPDATE_RAPCHIEU, Permission::DELETE_RAPCHIEU]
        ],
        [
            'href' => '/admin/san-pham',
            'icon' => 'fa-solid fa-box',
            'text' => 'Sản phẩm',
            'permissions' => [Permission::READ_THUCPHAM, Permission::CREATE_THUCPHAM, Permission::UPDATE_THUCPHAM, Permission::DELETE_THUCPHAM]
        ],
        [
            'href' => '/admin/combo',
            'icon' => 'fa-solid fa-boxes-stacked',
            'text' => 'Combo',
            'permissions' => [Permission::READ_COMBO, Permission::CREATE_COMBO, Permission::UPDATE_COMBO, Permission::DELETE_COMBO]
        ],
        [
            'href' => '/admin/khuyen-mai',
            'icon' => 'fa-solid fa-tags',
            'text' => 'Khuyến mãi',
            'permissions' => [Permission::READ_KHUYENMAI, Permission::CREATE_KHUYENMAI, Permission::UPDATE_KHUYENMAI, Permission::DELETE_KHUYENMAI]
        ],
        [
            'href' => '/admin/loai-ve',
            'icon' => 'fa-solid fa-ticket',
            'text' => 'Loại vé',
            'permissions' => [Permission::READ_LOAIVE, Permission::CREATE_LOAIVE, Permission::UPDATE_LOAIVE, Permission::DELETE_LOAIVE]
        ],
        [
            'href' => '/admin/loai-ghe',
            'icon' => 'fa-solid fa-couch',
            'text' => 'Loại ghế',
            'permissions' => [Permission::READ_LOAIGHE, Permission::CREATE_LOAIGHE, Permission::UPDATE_LOAIGHE, Permission::DELETE_LOAIGHE]
        ],
        [
            'href' => '/admin/hoa-don',
            'icon' => 'fa-solid fa-receipt',
            'text' => 'Hóa đơn',
            'permissions' => [Permission::READ_HOANDON]
        ],
        [
            'href' => '/admin/nguoi-dung',
            'icon' => 'fa-solid fa-user',
            'text' => 'Người dùng',
            'permissions' => [Permission::READ_NGUOIDUNG, Permission::CREATE_NGUOIDUNG, Permission::UPDATE_NGUOIDUNG, Permission::DELETE_NGUOIDUNG]
        ],
        [
            'href' => '/admin/tai-khoan',
            'icon' => 'fa-solid fa-user-check',
            'text' => 'Tài khoản',
            'permissions' => [Permission::READ_TAIKHOAN, Permission::CREATE_TAIKHOAN, Permission::UPDATE_TAIKHOAN, Permission::DELETE_TAIKHOAN]
        ],
        [
            'href' => '/admin/nhom-quyen',
            'icon' => 'fa-solid fa-users',
            'text' => 'Nhóm quyền',
            'permissions' => [Permission::READ_NHOMQUYEN, Permission::CREATE_NHOMQUYEN, Permission::UPDATE_NHOMQUYEN, Permission::DELETE_NHOMQUYEN]
        ],
        [
            'hasChildren' => true,
            'icon' => 'fa-solid fa-chart-simple',
            'text' => 'Thống kê',
            'permissions' => [Permission::THONG_KE],
            'childrens' => [
                [
                    'href' => '/admin/thong-ke/rap-chieu',
                    'icon' => '',
                    'text' => 'Rạp chiếu',
                ],
                [
                    'href' => '/admin/thong-ke/phim',
                    'icon' => '',
                    'text' => 'Phim',
                ],
                [
                    'href' => '/admin/thong-ke/san-pham',
                    'icon' => '',
                    'text' => 'Sản phẩm',
                ]
            ],
        ],
        [
            'href' => '/admin/cai-dat-website',
            'icon' => 'fa-solid fa-gear',
            'text' => 'Website',
            'permissions' => [Permission::CONFIG_WEBSITE]
        ]
    ];
    public static function getUserMenu($permissions)
    {
        $menuItems = [];
        foreach (self::$MENU_ITEMS as $item) {
            if (isset($item['permissions'])) {
                $hasPermission = false;
                foreach ($item['permissions'] as $permission) {
                    if (in_array($permission, $permissions)) {
                        $hasPermission = true;
                        break;
                    }
                }
                if ($hasPermission) {
                    $menuItems[] = $item;
                }
            } else {
                $menuItems[] = $item;
            }
        }
        return $menuItems;
    }
}