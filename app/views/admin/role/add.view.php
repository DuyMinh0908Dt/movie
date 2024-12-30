<?php
title("Thêm nhóm quyền");
require ('app/views/admin/header.php');
?>
<link rel="stylesheet" href="/public/css/infoAccess.css" />

<div x-data="
{
    permissions: [],
    toggleCheckAll() {
      
        this.permissions= this.isAllChecked ? [] : [...document.querySelectorAll('input[name=permission]')].map(e => e.value);
    },
    isAllChecked:false,


}
" x-init="
    $watch('permissions', (permissions) => {
        isAllChecked = permissions.length === document.querySelectorAll('input[name=permission]').length;
    }, {deep: true});
" style="flex-grow: 1; flex-shrink: 1; overflow-y: auto ; max-height: 100vh;" class="wrapper p-5">
    <form x-on:submit.prevent="
        const payload = {
            tennhomquyen: document.getElementById('tennhomquyen').value,
            description: document.getElementById('description').value,
            permissions: permissions
        };
        const res= await axios.post('', payload);
        if(res.data){
            window.location.href = '/admin/nhom-quyen';
        }
    " class="info-access container-fluid p-4 shadow">
        <div class='tw-flex tw-items-center tw-justify-between'>
            <h4 class='tw-font-semibold tw-text-xl '>THÔNG TIN NHÓM QUYỀN</h4>
            <a href="/admin/nhom-quyen" data-ripple-light="true" class="  tw-btn tw-btn-ghost" type="button">

                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-narrow-left">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M5 12l14 0" />
                    <path d="M5 12l4 4" />
                    <path d="M5 12l4 -4" />
                </svg>
                Quay lại
            </a>
        </div>
        <div>
            <div class="mb-3">
                <label for="tennhomquyen" class="form-label">Tên nhóm quyền</label>
                <input type="text" class="form-control" id="tennhomquyen" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Mô
                    tả</label>
                <textarea class="form-control" id="description" rows="3" required></textarea>
            </div>
        </div>

        <!-- bang chua cac quyen -->
        <h4 class="mt-4 mb-0 tw-font-semibold tw-text-xl ">QUYỀN HẠN THUỘC NHÓM QUYỀN</h4>

        <div class="row m-3 table-responsive" style="flex: 1;">
            <table class="table align-middle" style="height: 100%;">
                <tbody>
                    <tr>
                        <td class='tw-flex'>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="checkAll"
                                    x-on:click="toggleCheckAll" x-bind:checked="isAllChecked">

                                <label class="form-check-label">

                                </label>
                            </div>
                            <span class='tw-font-bold tw-text-red-600'>Quyền quản trị viên (Toàn quyền)

                            </span>


                        </td>
                        <td>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Đối tượng</th>
                        <th class='tw-text-center' colspan="4" align="center">
                            Hành động
                        </th>
                    </tr>
                    <?php foreach ($permissions as $resource => $permission): ?>
                        <tr>
                            <?php if (isset($permission['resource_name'])): ?>

                                <td class='tw-font-semibold'><?= $permission['resource_name'] ?></td>
                                <?php foreach ($permission['actions'] as $action): ?>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" name="permission" type="checkbox"
                                                id="<?= $action['MaQuyen'] ?>" value="<?= $action['MaQuyen'] ?>"
                                                x-model="permissions">
                                            <label class="form-check-label" for="<?= $action['MaQuyen'] ?>">
                                                <?= $action['action_name'] ?>
                                            </label>
                                        </div>
                                    </td>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <?php if (!isset($permission['resource_name'])): ?>
                                <td></td>
                                <td colspan='4'>
                                    <div class="form-check">
                                        <input id="<?= $permission['MaQuyen'] ?>" class="form-check-input" name="permission"
                                            type="checkbox" value="<?= $permission['MaQuyen'] ?>" x-model="permissions">
                                        <label class="form-check-label" for="<?= $permission['MaQuyen'] ?>">
                                            <?= $permission['description'] ?>
                                        </label>
                                    </div>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <!-- het bang chua cac quyen -->

        <!-- nut reset va luu -->
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <button class="btn btn-primary" type="submit">
                Lưu nhóm quyền
            </button>
        </div>
        <!-- het nut rest va luu -->
    </form>
</div>
<?php
require ('app/views/admin/footer.php');

?>