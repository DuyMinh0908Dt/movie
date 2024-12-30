from sqlalchemy import create_engine
import pymysql
#Connect DB
import pandas as pd
from bs4 import BeautifulSoup
DATABASE_URL = "mysql+pymysql://root:@localhost:3306/cinema"

# Tạo engine kết nối với MySQL
engine = create_engine(DATABASE_URL)
def load_movies():
    query = "SELECT * FROM phim"
    df = pd.read_sql(query, engine)
    return df
def get_movies_dataframe():
# Truy vấn dữ liệu từ bảng 'movie' và chuyển thành DataFrame
    df = load_movies()
    if 'information' in df.columns:
        def clean_html(text):
            if pd.isna(text):
                return text
            soup = BeautifulSoup(text, "html.parser")
            cleaned_text = soup.get_text().replace("&nbsp;", " ").replace("&amp;", "&").strip()
            return cleaned_text

        df['information_cleaned'] = df['information'].apply(clean_html)
    return df

def get_movie_recommendations_rate(current_movie_id: int):
    # Câu truy vấn SQL để đề xuất phim
    query = f"""
    SELECT m.id, m.vie_name, m.eng_name
    FROM movies m
    JOIN rates r ON m.id = r.movie_id
    WHERE r.user_id IN (
        SELECT r.user_id
        FROM rates r
        WHERE r.movie_id = {current_movie_id}
        AND r.rate >=6
    )
    AND m.id != {current_movie_id}
    GROUP BY m.id
    ORDER BY COUNT(r.movie_id) DESC
    LIMIT 5;
    """
    # Thực hiện truy vấn và trả về DataFrame
    df = pd.read_sql(query, engine)

    # Convert DataFrame to list of dictionaries for JSON response
    return df.to_dict(orient="records")

def get_movie_recommendations_avg_rate():
    # Câu truy vấn SQL để đề xuất phim
    query = f"""
    SELECT m.id, AVG(r.rate) AS average_rating
    FROM test1.movies m
    JOIN test1.rates r ON m.id = r.movie_id
    GROUP BY m.id
    HAVING AVG(r.rate) >= 8  -- Chỉ chọn những bộ phim có đánh giá trung bình trên 8
    ORDER BY average_rating DESC
    LIMIT 10;
    """
    # Thực hiện truy vấn và trả về DataFrame
    df = pd.read_sql(query, engine)

    # Convert DataFrame to list of dictionaries for JSON response
    return df.to_dict(orient="records")


# from sqlalchemy import create_engine
# import pandas as pd
# from bs4 import BeautifulSoup
#
# # Kết nối tới cơ sở dữ liệu MySQL
# DATABASE_URL = "mysql+pymysql://root:@localhost:3306/cinema"
# engine = create_engine(DATABASE_URL)
#
# def load_movies():
#     """
#     Truy vấn toàn bộ dữ liệu từ bảng 'phim' và trả về dưới dạng DataFrame.
#     """
#     query = "SELECT * FROM phim"
#     df = pd.read_sql(query, engine)
#     return df
#
# def get_movies_dataframe():
#     """
#     Xử lý dữ liệu từ bảng 'phim', làm sạch cột 'MoTa' nếu chứa HTML.
#     """
#     df = load_movies()
#     if 'MoTa' in df.columns:
#         def clean_html(text):
#             if pd.isna(text):
#                 return text
#             soup = BeautifulSoup(text, "html.parser")
#             cleaned_text = soup.get_text().replace("&nbsp;", " ").replace("&amp;", "&").strip()
#             return cleaned_text
#
#         df['MoTa_cleaned'] = df['MoTa'].apply(clean_html)
#     return df
#
# def get_movie_recommendations_genre(current_movie_id: int):
#     """
#     Đề xuất phim dựa trên thể loại của phim hiện tại.
#     """
#     query = f"""
#     SELECT p.MaPhim, p.TenPhim, p.TheLoai
#     FROM phim p
#     WHERE p.TheLoai = (
#         SELECT TheLoai
#         FROM phim
#         WHERE MaPhim = {current_movie_id}
#     )
#     AND p.MaPhim != {current_movie_id}
#     LIMIT 5;
#     """
#     df = pd.read_sql(query, engine)
#     return df.to_dict(orient="records")
#
# def get_movie_recommendations_showtime():
#     """
#     Đề xuất phim dựa trên thời gian chiếu (sử dụng bảng 'suatchieu').
#     """
#     query = """
#     SELECT p.MaPhim, p.TenPhim, s.ThoiGianBatDau
#     FROM phim p
#     JOIN suatchieu s ON p.MaPhim = s.MaPhim
#     WHERE s.ThoiGianBatDau >= NOW()
#     ORDER BY s.ThoiGianBatDau ASC
#     LIMIT 10;
#     """
#     df = pd.read_sql(query, engine)
#     return df.to_dict(orient="records")
#
# # Ví dụ sử dụng
# if __name__ == "__main__":
#     # Đề xuất phim dựa trên thể loại
#     current_movie_id = 1  # ID của phim hiện tại
#     recommendations_genre = get_movie_recommendations_genre(current_movie_id)
#     print("Đề xuất phim dựa trên thể loại:")
#     print(recommendations_genre)
#
#     # Đề xuất phim dựa trên suất chiếu
#     recommendations_showtime = get_movie_recommendations_showtime()
#     print("Đề xuất phim dựa trên suất chiếu:")
#     print(recommendations_showtime)
# from sqlalchemy import create_engine
# import pandas as pd
# from bs4 import BeautifulSoup
#
# # Kết nối với cơ sở dữ liệu bán vé xem phim
# DATABASE_URL = "mysql+pymysql://root:@localhost:3306/cinema"
# engine = create_engine(DATABASE_URL)
#
#
# # Tải danh sách phim
# def load_movies():
#     query = "SELECT * FROM phim"
#     df = pd.read_sql(query, engine)
#     return df
#
#
# # Tìm kiếm phim theo tên
# def search_movies_by_name(keyword: str):
#     query = """
#     SELECT * FROM phim
#     WHERE TenPhim LIKE %(keyword)s
#     """
#     # Sử dụng `pd.read_sql` với tham số hoá
#     df = pd.read_sql(query, engine, params={"keyword": f"%{keyword}%"})
#     if df.empty:
#         return {"message": "Không tìm thấy phim nào khớp với từ khóa."}
#     return df.to_dict(orient="records")
#
#
#
# # Lấy danh sách xuất chiếu của một phim
# def get_showtimes_by_movie(movie_id: int):
#     query = f"""
#     SELECT * FROM suatchieu
#     WHERE MaPhim = {movie_id} AND TrangThai = 1
#     """
#     df = pd.read_sql(query, engine)
#     if df.empty:
#         return {"message": "Không có xuất chiếu nào cho phim này."}
#     return df.to_dict(orient="records")
#
#
# # Gợi ý phim dựa trên lịch sử đặt vé của người dùng
# def recommend_movies_by_user(user_id: int):
#     query = f"""
#     SELECT DISTINCT p.MaPhim, p.TenPhim, p.HinhAnh
#     FROM ve v
#     JOIN suatchieu xc ON v.MaSuatChieu = xc.MaXuatChieu
#     JOIN phim p ON xc.MaPhim = p.MaPhim
#     WHERE v.MaHoaDon IN (
#         SELECT hd.MaHoaDon
#         FROM hoadon hd
#         WHERE hd.MaNguoiDung = {user_id}
#     )
#     LIMIT 5
#     """
#     df = pd.read_sql(query, engine)
#     if df.empty:
#         return {"message": "Không tìm thấy phim nào để gợi ý."}
#     return df.to_dict(orient="records")
#
#
# # Tính doanh thu từ hóa đơn
# def calculate_revenue():
#     query = """
#     SELECT SUM(TongTien) AS DoanhThu
#     FROM hoadon
#     WHERE TrangThai = 1
#     """
#     df = pd.read_sql(query, engine)
#     return {"total_revenue": df.iloc[0]["DoanhThu"]}
#
#
# # Demo sử dụng
# if __name__ == "__main__":
#     print("Danh sách phim khớp từ khóa:")
#     print(search_movies_by_name("HÀNH TINH CÁT"))
#
#     print("\nXuất chiếu của phim ID 127:")
#     print(get_showtimes_by_movie(127))
#
#     print("\nGợi ý phim cho người dùng ID 1:")
#     print(recommend_movies_by_user(1))
#
#     print("\nDoanh thu hiện tại:")
#     print(calculate_revenue())
