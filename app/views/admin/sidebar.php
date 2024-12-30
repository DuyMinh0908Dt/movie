<?php


function isActive($path)
{
    $currentPath = $_SERVER['REQUEST_URI'];
    //currentPath includes path
    if (!isset($path))
        return '';
    if (strpos($currentPath, $path) !== false) {
        return 'active';
    } else {
        return '';
    }
}
?>
<nav style="position: relative" class="sidebar">
    <header class="d-flex">
        <div class="image-text d-flex align-items-center">
            <span class="image-logo">
                <img src="/public/assets/img/logo.png" alt width="60px" />
            </span>

            <div class="text header-text d-flex flex-column align-items-center">
                <span class="name fs-5"><strong>Pixcel Cinema</strong></span>
            </div>
        </div>

        <i class="fa-solid fa-angle-right toggle"></i>
    </header>

    <div class="menu-bar" x-init="
        window.$('.menu-links__item.active')[0].scrollIntoView({
            behavior: 'smooth',
            block: 'center',
            inline: 'center'
        })
    ">
        <div class="menu">
            <?php
            use App\Dtos\AdminMenu;


            $menu = AdminMenu::getUserMenu($_SESSION['user']['permissions']);

            ?>

            <ul class="menu-links scrollable " style=" transition: height 0.3s;">
                <?php foreach ($menu as $key => $item): ?>
                    <?php if (isset($item['hasChildren'])): ?>
                        <li x-data="
                        {
                            open: false,
                            toggle() {
                                this.open = !this.open
                            }
                        }" x-init="
                                const subMenu = document.getElementById('sub-menu-for-<?= $key ?>')
                                     subMenu.querySelectorAll('li').forEach(item => {
                                    item.style.display = 'none'
                                })
                        $watch('open', value => {
                            if (value) {
                                subMenu.querySelectorAll('li').forEach(item => {
                                    item.style.display = 'block'
                                })  
                            } else {
                                subMenu.querySelectorAll('li').forEach(item => {
                                    item.style.display = 'none'
                                })
                            }
                             
                        })" class="menu-links__item  " x-on:click="toggle()">
                            <a class>
                                <i class="fa-solid fa-chart-simple menu-links__item-icon"></i>
                                <span class="text nav-text">
                                    <?= $item['text'] ?>
                                </span>
                                <i class="icon-drop-down fa-solid fa-sort-down"
                                    :style="open ? 'transform: rotate(90deg)' : 'transform: rotate(-90deg)'">
                                </i>
                            </a>
                        </li>
                        <div id="sub-menu-for-<?= $key ?>" style="overflow: hidden; transition: height 0.3s;">

                            <?php foreach ($item['childrens'] as $child): ?>
                                <li class="menu-links__item <?= isActive($child['href']) ?>">
                                    <a href="<?= $child['href'] ?>">
                                        <i class="menu-links__item-icon"></i>

                                        <span class="text nav-text">
                                            <?= $child['text'] ?>
                                        </span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </div>

                    </ul>

                <?php else: ?>

                    <li class="menu-links__item <?= isActive($item['href']) ?>">
                        <a href="<?= $item['href'] ?>">
                            <i class="menu-links__item-icon <?= $item['icon'] ?>"></i>

                            <span class="text nav-text">
                                <?= $item['text'] ?>
                            </span>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
            </ul>

            <div class="py-2 menu-links__item">
                <a href="/dang-xuat">
                    <i class="fa-solid fa-right-from-bracket menu-links__item-icon"></i>
                    <span class="text nav-text">Đăng xuất</span>
                </a>
            </div>
        </div>
    </div>
</nav>