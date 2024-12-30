import { Prisma, PrismaClient } from "@prisma/client";
import { readFileSync } from "fs";
import dayjs from "dayjs";
const prisma = new PrismaClient();
// ,
//   {
//     "TenPhim": "LỄ TRỪ TÀ (T18)",
//     "NgayPhathanh": "2/22/2024 12:00:00 AM",
//     "HanCheDoTuoi": "T18",
//     "HinhAnh": "https://cinestar.com.vn/pictures/Cinestar/02-2024/le-tru-ta.jpg",
//     "ThoiLuong": "100",
//     "NgonNgu": "VN",
//     "MoTa": "3 linh mục được triệu tập đến nhà thờ để thực hiện lễ trừ tà liên tục 3 ngày 3 đêm cho cô bé bị quỷ dữ chiếm lấy thân xác. Nó trở thành một cái bẫy để lũ quỷ từ địa ngục đưa lời tuyên chiến đến thế giới. Liệu các linh mục có chặn đứng được hay sẽ biến trái đất thành cuộc chiến giữ thiên đàng và địa ngục?",
//     "DaoDien": "Jose Prendes",
//     "TrangThai": "2",
//     "TheLoai": ["Kinh Dị"],
//     "Trailer": "https://www.youtube.com/watch?v=",
//     "QuocGia": "Khác"
//   },

async function findOrCreateTheLoai(theLoai: string) {
  const existingTheLoai = await prisma.theLoai.findFirst({
    where: {
      TenTheLoai: theLoai.trim(),
    },
  });
  if (existingTheLoai) {
    return existingTheLoai;
  }
  return prisma.theLoai.create({
    data: {
      TenTheLoai: theLoai.trim(),
    },
  });
}

(async () => {
  const phims = JSON.parse(readFileSync("phim.json", "utf-8"));
  const phim_theloai: Prisma.Prisma__CT_Phim_TheLoaiClient<{
    MaTheLoai: number;
    MaPhim: number;
  }>[] = [];
  for await (const phim of phims) {
    // check if the phim already exists
    const existingPhim = await prisma.phim.findFirst({
      where: {
        TenPhim: phim.TenPhim.trim(),
      },
    });
    if (existingPhim) {
      console.log(`Phim ${phim} already exists`);
      continue;
    }
    let trangThai = 1;
    if (dayjs(phim.NgayPhathanh, "MM/DD/YYYY hh:mm:ss A").isAfter(dayjs())) {
      trangThai = 2;
    }
    if (
      dayjs(phim.NgayPhathanh, "MM/DD/YYYY hh:mm:ss A").isBefore(
        dayjs().subtract(1, "month")
      )
    ) {
      trangThai = 3;
    }
    const createdPhim = await prisma.phim.create({
      data: {
        TenPhim: phim.TenPhim,
        DaoDien: phim.DaoDien,
        HanCheDoTuoi: phim.HanCheDoTuoi,
        HinhAnh: phim.HinhAnh,
        MoTa: phim.MoTa,
        NgayPhatHanh: dayjs(
          phim.NgayPhathanh,
          "MM/DD/YYYY hh:mm:ss A"
        ).toDate(),
        NgonNgu: phim.NgonNgu,
        ThoiLuong: Number(phim.ThoiLuong),
        DinhDang: phim.DinhDang,
        Trailer: phim.Trailer,
        // 1 is DangChieu, 2 is SapChieu, 3 is NgungChieu
        TrangThai: trangThai,
      } as any,
    });
    for await (const theLoai of phim.TheLoai) {
      const existingTheLoai = await findOrCreateTheLoai(theLoai);
      const a = await prisma.cT_Phim_TheLoai.create({
        data: {
          MaPhim: createdPhim.MaPhim,
          MaTheLoai: existingTheLoai!.MaTheLoai,
        },
      });
      console.log(a);
    }
  }
})();
