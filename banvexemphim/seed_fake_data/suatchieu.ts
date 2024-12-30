import { Phim, Prisma, PrismaClient, Ve } from "@prisma/client";
import { readFileSync } from "fs";
import dayjs from "dayjs";
import { fakerVI as faker } from "@faker-js/faker";
import { config } from "dotenv";
config();

const prisma = new PrismaClient();

//  insert suất chiếu nè

// MaXuatChieu	NgayGioChieu	NgayGioKetthuc	GiaVe	MaPhim	MaPhongChieu

// điều kiện:
// - MaPhim phải tồn tại trong bảng phim
// - MaPhongChieu phải tồn tại trong bảng phòng chiếu
// - NgayGioChieu phải sau thời gian hiện tại
// - NgayGioKetthuc = NgayGioChieu + thời lượng phim + 15 phút
// - GiaVe in[20000,25000,30000,40000,55000]
// - cùng một phòng chiếu không thể có 2 suất chiếu cùng thời gian
// sau khi tạo xong 1 suất chiếu, thì cần tạo ra 1 loạt các vé cho suất chiếu đó (số lượng vé = số lượng ghế của phòng chiếu)
// - mỗi vé sẽ có trạng thái là "Chưa đặt" và giá vé chưa tiết lộ

enum TrangThaiPhim {
  DangChieu = 1,
  SapChieu = 2,
  NgungChieu = 3,
}

// (async () => {
//   const phi = await prisma.phim.findMany({});
//   // set TrangThaiPhim cho phim
//   const today = dayjs().add(4, "day");
//   for (let i = 0; i < phi.length; i++) {
//     let ngayChieu = dayjs(phi[i].NgayPhathanh);
//     if (ngayChieu.isAfter(today)) {
//       await prisma.phim.update({
//         where: {
//           MaPhim: phi[i].MaPhim,
//         },
//         data: {
//           TrangThai: TrangThaiPhim.SapChieu,
//         },
//       });
//       continue;
//     }
//     if (ngayChieu.isBefore(today.subtract(1, "month"))) {
//       await prisma.phim.update({
//         where: {
//           MaPhim: phi[i].MaPhim,
//         },
//         data: {
//           TrangThai: TrangThaiPhim.NgungChieu,
//         },
//       });
//       continue;
//     }
//     await prisma.phim.update({
//       where: {
//         MaPhim: phi[i].MaPhim,
//       },
//       data: {
//         TrangThai: TrangThaiPhim.DangChieu,
//       },
//     });
//   }
// })();

(async () => {
  const phim = await prisma.phim.findMany({
    where: {
      TrangThai: TrangThaiPhim.DangChieu,
    },
  });
  for (const phimItem of phim) {
    await createSuatChieuForFilm(phimItem);
  }
  await createVeForSuatChieu();
})();

async function isRoomValid(
  MaPhongChieu: number,
  ngayBatDau: dayjs.Dayjs,
  ngayKetThuc: dayjs.Dayjs
) {
  const suatChieu = await prisma.suatChieu.findMany({
    where: {
      MaPhongChieu,
      NgayGioChieu: {
        gte: ngayBatDau.toDate(),
        lte: ngayKetThuc.toDate(),
      },
    },
  });
  return suatChieu.length === 0;
}
const giaVes = [20000, 25000, 30000, 40000, 55000];
const stepMinutes = [0, 15, 30, 45];

async function createSuatChieuForFilm(phim: Phim) {
  if (phim.ThoiLuong === null || phim.ThoiLuong === 0) {
    return;
  }
  let today = dayjs().add(8, "day");

  const phongChieu = await prisma.phongChieu.findMany({});

  const listDay = [];
  for (let i = 1; i < 7; i++) {
    listDay.push(today.add(i, "day").hour(0).minute(0).second(0));
  }
  const suatChieu = [];
  for (const ngayChieu of listDay) {
    const numberOfSuatChieu = faker.number.int({
      max: 6,
      min: 1,
    });

    for (let i = 0; i < numberOfSuatChieu; i++) {
      const randomPhongChieu =
        phongChieu[Math.floor(Math.random() * phongChieu.length)];
      const thoiGianBatDau = ngayChieu
        .add(
          faker.number.int({
            max: 23,
            min: 8,
          }),
          "hour"
        )
        .add(stepMinutes[faker.number.int({ max: 3, min: 0 })], "minute");
      const thoiGianKetThuc = thoiGianBatDau.add(
        phim.ThoiLuong! + 15,
        "minute"
      );
      const isValid = await isRoomValid(
        randomPhongChieu.MaPhongChieu,
        thoiGianBatDau,
        thoiGianKetThuc
      );
      console.log(
        `create suat chieu for phim longed ${
          phim.ThoiLuong
        } from ${thoiGianBatDau.format("DD HH:mm")} to ${thoiGianKetThuc.format(
          "DD HH:mm"
        )} in room `
      );
      if (!isValid) {
        continue;
      }

      const giaVe = giaVes[Math.floor(Math.random() * giaVes.length)];
      const newSuatChieu = {
        NgayGioChieu: thoiGianBatDau.toDate(),
        NgayGioKetThuc: thoiGianKetThuc.toDate(),
        GiaVe: giaVe,
        MaPhim: phim.MaPhim,
        MaPhongChieu: randomPhongChieu.MaPhongChieu,
        TrangThai: 16,
      };
      await prisma.suatChieu.create({
        data: newSuatChieu,
      });
      suatChieu.push(newSuatChieu);
    }
  }
}
async function createVeForSuatChieu() {
  const suatChieu = await prisma.suatChieu.findMany({});
  const listVe = [];
  console.log(suatChieu.length);

  for (const suatChieuItem of suatChieu) {
    const veCount = await prisma.ve.count({
      where: {
        MaSuatChieu: suatChieuItem.MaXuatChieu,
      },
    });
    if (veCount > 0) {
      continue;
    }
    const ghe = await prisma.ghe.findMany({
      where: {
        MaPhongChieu: suatChieuItem.MaPhongChieu,
      },
    });

    const localVe = ghe.map((gheItem) => {
      return {
        TrangThai: 4,
        MaGhe: gheItem.MaGhe,
        MaSuatChieu: suatChieuItem.MaXuatChieu,
      };
    });
    await prisma.ve.createMany({
      data: localVe,
    });
  }
}
