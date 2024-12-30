 <?php
 use App\Dtos\TrangThaiCombo;

 ?>
 <?php foreach ($combos as $combo): ?>
     <tr>
         <th scope="row" class="col-id table-plus">
             <?= $combo['MaCombo'] ?>
         </th>
         <td class="col-name ps-0">
             <div class="d-flex align-items-center">
                 <div class="tb-img-product mr-2 flex-shrink-0">
                     <img src="<?= $combo['HinhAnh'] ?>" alt="" width="40" height="40">
                 </div>
                 <div>
                     <span class="">
                         <?= $combo['TenCombo'] ?>
                     </span>
                 </div>
             </div>
         </td>


         <td class="col-price">
             <?= number_format($combo['GiaCombo']) ?>đ
         </td>
         <td class="col-status">
             <?php
             foreach ($statuses as $status) {
                 if ($status['MaTrangThai'] == $combo['TrangThai']) {
                     echo $status['Ten'];
                     break;
                 }
             }
             ?>
         </td>
         <td class="col-crud">
             <div class="dropdown">
                 <button type="button " class="btn btn-light btn-icon rounded-circle" data-bs-toggle="dropdown"
                     aria-expanded="false">
                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                         class="bi bi-three-dots-vertical" viewBox="0 0 16 16" class="icon">
                         <path
                             d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
                     </svg>
                 </button>
                 <ul class="dropdown-menu">

                     <li>
                         <div onclick="window.location.href = '/admin/combo/<?= $combo['MaCombo'] ?>/sua'"
                             class="dropdown-item !tw-text-yellow-400">
                             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                 class="bi bi-pencil-square" viewBox="0 0 16 16">
                                 <path
                                     d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                 <path fill-rule="evenodd"
                                     d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                             </svg>
                             <span class="px-xl-3 ">Sửa</span>
                         </div>
                     </li>

                     <li>
                         <?php
                         $funcName = $combo['TrangThai'] == TrangThaiCombo::An->value ? 'onRecoverCombo' : 'showDeleteModal';
                         $isAn = $combo['TrangThai'] == TrangThaiCombo::An->value;
                         ?>
                         <div class="dropdown-item <?= $isAn ? '!tw-text-green-500' : '!tw-text-red-500' ?>"
                             onclick="<?= $funcName ?>(<?= $combo['MaCombo'] ?>)">
                             <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                 class="tw-w-6 tw-h-6">
                                 <path
                                     d="M3.375 3C2.339 3 1.5 3.84 1.5 4.875v.75c0 1.036.84 1.875 1.875 1.875h17.25c1.035 0 1.875-.84 1.875-1.875v-.75C22.5 3.839 21.66 3 20.625 3H3.375Z" />
                                 <path fill-rule="evenodd"
                                     d="m3.087 9 .54 9.176A3 3 0 0 0 6.62 21h10.757a3 3 0 0 0 2.995-2.824L20.913 9H3.087Zm6.133 2.845a.75.75 0 0 1 1.06 0l1.72 1.72 1.72-1.72a.75.75 0 1 1 1.06 1.06l-1.72 1.72 1.72 1.72a.75.75 0 1 1-1.06 1.06L12 15.685l-1.72 1.72a.75.75 0 1 1-1.06-1.06l1.72-1.72-1.72-1.72a.75.75 0 0 1 0-1.06Z"
                                     clip-rule="evenodd" />
                             </svg>
                             <span class="px-xl-3 ">
                                 <?= $isAn ? 'Hiện' : 'Xoá/Ẩn' ?>
                             </span>
                         </div>
                     </li>
                 </ul>
             </div>
         </td>
     </tr>
 <?php endforeach; ?>