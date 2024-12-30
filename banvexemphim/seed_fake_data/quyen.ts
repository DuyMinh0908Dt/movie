import { Prisma, PrismaClient, Quyen } from "@prisma/client";
import { readFileSync } from "fs";
import dayjs from "dayjs";
const prisma = new PrismaClient();
const resouces = [
  "combo",
  "ghe",
  "hoandon",
  "khuyenmai",
  "loaighe",
  "loaive",
  "nguoidung",
  "nhomquyen",
  "phim",
  "phongchieu",
  "quyen",
  "rapchieu",
  "suatchieu",
  "taikhoan",
  "theloai",
  "thucpham",
  "ve",
];
const resouces_vi = [
  "Combo",
  "Ghế",
  "Hóa đơn",
  "Khuyến mãi",
  "Loại ghế",
  "Loại vé",
  "Người dùng",
  "Nhóm quyền",
  "Phim",
  "Phòng chiếu",
  "Rạp chiếu",
  "Suất chiếu",
  "Tài khoản",
  "Thể loại",
  "Thức phẩm",
  "Vé",
];

const actions = ["create", "read", "update", "delete"];
const actions_vi = ["Thêm", "Đọc", "Cập nhật", "Xóa"];

let phpEnum = `
  enum Permission: string {

`;
const listPermission = [];
for (const resource of resouces) {
  for (const action of actions) {
    listPermission.push({
      MoTa: `Quyền ${actions_vi[actions.indexOf(action)]} ${
        resouces_vi[resouces.indexOf(resource)]
      }`,
      TenQuyen: `${action}:${resource}`,
    });
    phpEnum += `   case ${action.toUpperCase()}_${resource.toUpperCase()} = "${action}:${resource}"\n`;
  }
}
phpEnum += `
  }
`;
import { writeFileSync } from "fs";
writeFileSync("permission_enum.txt", phpEnum);

(async () => {
  await prisma.quyen.deleteMany({});
  await prisma.quyen.createMany({
    data: listPermission,
  });
})();
