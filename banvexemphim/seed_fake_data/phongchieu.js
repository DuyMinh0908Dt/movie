import { PrismaClient } from "@prisma/client";

const prisma = new PrismaClient();
import { faker } from "@faker-js/faker";
import PQueue from "p-queue";

const col = {
  min: 6,
  max: 12,
};

const row = {
  min: 6,
  max: 12,
};

let queue = new PQueue({ concurrency: 5 });

(async () => {
  console.log("Tạo phòng chiếu");
  await prisma.$connect();
  await prisma.ghe.deleteMany({});
  // const raps = await prisma.rapChieu.findMany({});
  // console.log("Found", raps.length, "raps");
  // for (let i = 0; i < raps.length; i++) {
  //   queue.add(() => taoPhong(raps[i].MaRapChieu));
  // }
  // await queue.onIdle();
  const phongs = await prisma.phongChieu.findMany({});
  for (let i = 0; i < phongs.length; i++) {
    queue.add(() => taoGhe(phongs[i]));
  }
  await queue.onIdle();
  // update sum of ghe
  const phongs2 = await prisma.phongChieu.findMany({});
  for (let i = 0; i < phongs2.length; i++) {
    const ghes = await prisma.ghe.findMany({
      where: {
        MaPhongChieu: phongs2[i].MaPhongChieu,
      },
    });
    await prisma.phongChieu.update({
      where: {
        MaPhongChieu: phongs2[i].MaPhongChieu,
      },
      data: {
        SoGhe: ghes.length,
      },
    });
  }
})().then(() => {
  console.log("Tạo phòng chiếu thành công");
  prisma.$disconnect();
});

let manHinhs = ["Curved Screen", "ScreenX", "Fixed Frame", "IMAX"];

async function taoPhong(rap_id) {
  const soPhong = faker.number.int({
    max: 5,
    min: 2,
  });
  for (let i = 0; i < soPhong; i++) {
    await prisma.phongChieu.create({
      data: {
        ChieuDai: faker.number.int(row),
        ChieuRong: faker.number.int(col),
        ManHinh: manHinhs[faker.number.int({ min: 0, max: 3 })],
        MaRapChieu: rap_id,
        TenPhongChieu: "Phòng chiếu số " + (i + 1),
      },
    });
  }
}
async function taoGhe(phong) {
  const colLoiDi = faker.number.int({ min: 2, max: phong.ChieuRong - 2 }); // => dont have ghe
  for (let i = 0; i < phong.ChieuDai; i++) {
    let skiped = 0;
    for (let j = 0; j < phong.ChieuRong; j++) {
      if (j === colLoiDi) {
        skiped++;
        continue;
      }
      await prisma.ghe.create({
        data: {
          MaPhongChieu: phong.MaPhongChieu,
          MaLoaiGhe: i < 5 ? 1 : 2,
          SoGhe: String.fromCharCode(65 + i) + (j + 1 - skiped).toString(),
          X: j,
          Y: i,
          TrangThai: 19,
        },
      });
      console.log(
        "Tạo ghế ",
        String.fromCharCode(65 + i) + (j + 1).toString(),
        " tại ",
        `(${j},${i})`,
        "Chieu rong",
        phong.ChieuRong,
        "Col loi di",
        colLoiDi,
        "Ma: ",
        phong.MaPhongChieu
      );
    }
  }
}
