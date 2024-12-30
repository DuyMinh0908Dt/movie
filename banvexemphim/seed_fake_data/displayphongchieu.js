import { PrismaClient } from "@prisma/client";

const prisma = new PrismaClient();

(async () => {
  await prisma.$connect();
  const phong = await prisma.phongChieu.findFirst({
    include: {
      Ghe: true,
    },
  });
  const dai = phong.ChieuDai;
  const rong = phong.ChieuRong;
  // print out the phong
  for (let i = 0; i < dai; i++) {
    for (let j = 0; j < rong; j++) {
      const ghe = phong.Ghe.find((g) => g.X === i && g.Y === j);
      if (ghe) {
        process.stdout.write(
          ghe.MaLoaiGhe === 1 ? ghe.SoGhe.toLocaleLowerCase() : ghe.SoGhe
        );
      } else {
        process.stdout.write("  ");
      }
      process.stdout.write(" ");
    }
    console.log();
  }
  console.log();
})().then(() => {
  prisma.$disconnect();
});
