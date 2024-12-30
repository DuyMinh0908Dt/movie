import { faker } from "@faker-js/faker/locale/vi";

// random địa chỉ
function randomFullAddress() {
  return faker.location.streetAddress() + ", " + faker.location.state();
}

function randomPhoneNumber() {
  return faker.phone.number().replace(/ /g, "");
}

function randomEmail() {
  return faker.internet.email();
}
function randomBirthday() {
  return faker.date.birthdate({
    max: 60,
    min: 14,
  });
}
function randomFullName() {
  return faker.person.fullName();
}

import { PrismaClient } from "@prisma/client";

const prisma = new PrismaClient();
const DEFAULT_PASSWORD = "123456789";
(async () => {
  for (let i = 0; i < 20; i++) {
    const user = await prisma.nguoiDung.create({
      data: {
        DiaChi: randomFullAddress(),
        Email: randomEmail(),
        TenNguoiDung: randomFullName(),
        SoDienThoai: randomPhoneNumber(),
        NgaySinh: randomBirthday(),
      },
    });
    await prisma.taiKhoan.create({
      data: {
        MaNguoiDung: user.MaNguoiDung,
        LoaiTaiKhoan: 2,
        MatKhau: DEFAULT_PASSWORD,
        TenDangNhap: user.Email,
      },
    });
  }
})();
