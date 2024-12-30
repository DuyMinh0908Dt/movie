  <html lang="vi">

  <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  </head>

  <body>
      <?php
      // create group of tickets instead of using lodash in js
      $ticketGroups = [];
      foreach ($tickets as $ticket) {
          if (!isset($ticketGroups[$ticket['MaLoaiVe']])) {
              $ticketGroups[$ticket['MaLoaiVe']] = [];
          }
          array_push($ticketGroups[$ticket['MaLoaiVe']], $ticket);
      }
      ?>
      <div id="ticket"
          style="width: 350px; padding: 12px; border: 2px solid black; background-color: white; margin: 0 auto;font-family: 'DejaVu Sans',Arial, Helvetica, sans-serif;">
          <div style=" padding: 4px; display: flex; flex-direction: column; gap: 4px;">
              <div style="display: flex; gap: 8px;">
                  <div style="flex: 1;">

                      <div style="text-transform: uppercase;  font-size: 20px; font-weight: bold;">
                          <?= $show['TenPhim'] ?>
                      </div>
                      <div style="margin-top: 8px; font-weight: 600;  font-size: 16px;">
                          <?= $show['TenRapChieu'] ?>
                      </div>
                      <div style="font-style: italic">
                          <?= $show['DiaChi'] ?>
                      </div>
                      <div style="margin-top: 12px; display: block;">
                          <span style="font-weight: bold;">Thời gian: </span>
                          <?= $show['NgayGioChieu'] ?>
                      </div>
                      <div style="margin-top: 12px; display: block;">
                          <span style="font-weight: bold;"> Phòng chiếu: </span>
                          <?= $show['TenPhongChieu'] ?>
                      </div>
                  </div>
              </div>

              <div style=" margin-top: 8px;">
                  <table style="width: 100%;">
                      <thead>
                          <tr>
                              <th align="left" style="font-weight: bold; text-align: left;">Loại vé</th>
                              <th align="left" style="font-weight: bold; text-align: left;">Số vé</th>
                          </tr>
                      </thead>
                      <tbody>

                          <?php foreach ($ticketGroups as $ticketGroup): ?>
                              <tr>
                                  <td><?= $ticketGroup[0]['TenLoaiVe'] ?></td>
                                  <td><?= count($ticketGroup) ?></td>
                              </tr>
                          <?php endforeach; ?>
                      </tbody>

                  </table>
              </div>
              <div style="margin: 8px 0;">
                  <span style="font-weight: bold;">Ghế: </span>
                  <?php foreach ($seats as $seat): ?>
                      <span><?= $seat['SoGhe'] ?> </span>
                  <?php endforeach; ?>
              </div>

              <div>
                  <span style="font-weight: bold;">Bắp nước: </span>
                  <?php foreach ($foods as $food): ?>
                      <span>
                          <?= $food['TenThucPham'] ?>
                          X
                          <?= $bookingData['ThucPhams'][array_search($food['MaThucPham'], array_column($bookingData['ThucPhams'], 'MaThucPham'))]['SoLuong'] ?>
                      </span>
                  <?php endforeach; ?>
                  <?php foreach ($combos as $combo): ?>
                      <span>
                          <?= $combo['TenCombo'] ?>
                          X
                          <?= $bookingData['Combos'][array_search($combo['MaCombo'], array_column($bookingData['Combos'], 'MaCombo'))]['SoLuong'] ?>
                      </span>
                  <?php endforeach; ?>
              </div>
          </div>
          <div style="border-bottom: 4px dashed black; margin: 8px 0;"></div>
          <div style="display: flex; padding: 8px; font-size:22px; justify-content: space-between;">
              <span>Tổng thanh toán: </span>
              <span><?= number_format($bookingData['TongTien']) ?>đ</span>
          </div>
          <div
              style="display: flex; flex-direction: column; margin-top: 8px; align-items: center; justify-content: flex-end; gap: 4px;">
              <img style="display: inline-block; margin: 0 auto ; mix-blend-mode: multiply; max-height: 80px;"
                  src="<?= $barCodeUrl ?>" />
              <div style="text-align: center; font-size: 12px;">
                  Mã hoá đơn:
                  <?= $orderId ?>
              </div>
          </div>
      </div>
  </body>

  </html>