import axios from "axios";
import fs from "fs";
const DATA_API_URL =
  "https://cinestar.com.vn/api/search/?key=&type=&id_Server=0";

(async () => {
  const response = await axios.get(DATA_API_URL);
  const phim = response.data.data.movie.data.map(parseToPhimObj);
  fs.writeFileSync("phim.json", JSON.stringify(phim));
})();
let id = 1;
function parseToPhimObj(raw: any) {
  return {
    MaPhim: id++,
    TenPhim: raw.name_vn,
    NgayPhathanh: raw.release_date,
    HanCheDoTuoi: raw.limitage_vn,
    HinhAnh: raw.image,
    ThoiLuong: raw.time,
    NgonNgu: raw.language_vn,
    MoTa: raw.brief_vn,
    DaoDien: raw.director,
    TrangThai: raw.status,
    TheLoai: raw.type_name_vn.split(","),
    Trailer: "https://www.youtube.com/watch?v=" + raw.trailer,
    QuocGia: raw.country_name_vn,
    DinhDang: raw.formats_name_vn,
  };
}

// regex of email validation
const regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
