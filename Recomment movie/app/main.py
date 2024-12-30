from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from router import router as router_module

app = FastAPI()
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # Cho phép tất cả các nguồn (domain). Hãy hạn chế trong môi trường production.
    allow_credentials=True,
    allow_methods=["*"],  # Cho phép tất cả các phương thức HTTP
    allow_headers=["*"],  # Cho phép tất cả các header
)
# @app.on_event("startup")
# async def startup_event():
#     """
#     Sự kiện chạy khi API server khởi động.
#     """
#     print("Starting background feature extraction...")
#     # Chạy quá trình trích xuất đặc trưng trong một thread riêng
#     threading.Thread(target=extract_features, daemon=True).start()
#     process_event.wait()
#     print("Feature extraction thread has finished and stopped.")
# Đăng ký router
app.include_router(router_module, prefix="/api", tags=["Search"])


# from fastapi import FastAPI, Query
# from pydantic import BaseModel
# from typing import List, Optional
# import pandas as pd
# from bs4 import BeautifulSoup
# import yake
# from sqlalchemy import create_engine
# import pymysql
# # Khởi tạo ứng dụng FastAPI
# app = FastAPI()

# # Tải dữ liệu CSV
# DATABASE_URL = "mysql+pymysql://root:@localhost:3306/test1"

# # Tạo engine kết nối với MySQL
# engine = create_engine(DATABASE_URL)

# # Truy vấn dữ liệu từ bảng 'movie' và chuyển thành DataFrame
# query = "SELECT * FROM movies"
# df = pd.read_sql(query, engine)

# # Làm sạch cột 'information' nếu tồn tại
# if 'information' in df.columns:
#     def clean_html(text):
#         if pd.isna(text):
#             return text
#         soup = BeautifulSoup(text, "html.parser")
#         cleaned_text = soup.get_text().replace("&nbsp;", " ").replace("&amp;", "&").strip()
#         return cleaned_text

#     df['information_cleaned'] = df['information'].apply(clean_html)

# # Khởi tạo trích xuất từ khóa
# kw_extractor = yake.KeywordExtractor(lan="vi", n=2, dedupLim=0.9, top=20)

# # Hàm trích xuất từ khóa
# def extract_keywords(text):
#     if pd.isnull(text) or not isinstance(text, str):
#         return "[]"
#     keywords = kw_extractor.extract_keywords(text)
#     keyword_list = [f'"{keyword}"' for keyword, score in keywords]
#     return f"[{', '.join(keyword_list)}]"

# # Hàm kiểm tra từ khóa
# def check_keywords(content, df):
#     # Trích xuất các từ khóa từ nội dung tìm kiếm (content_input)
#     content_keywords = extract_keywords(content.lower())  # Chuyển content về chữ thường
    
#     filtered_content_keywords = [
#         keyword.strip('"') for keyword in content_keywords.strip('[]').split(',')
#         if len(keyword.strip('"').split()) > 1
#     ]
#     print(f"Keywords from search content: {filtered_content_keywords}")
#     # So sánh với từng dòng trong cột 'information_cleaned' của DataFrame
#     similar_rows = []

#     for index, row in df.iterrows():
#         # Chuyển 'information_cleaned' về chữ thường trước khi so sánh
#         cleaned_info = row['information_cleaned'].lower()

#         # Kiểm tra nếu có sự trùng lặp từ khóa giữa nội dung tìm kiếm và mô tả phim trong 'information_cleaned'
#         if any(keyword in cleaned_info for keyword in filtered_content_keywords):
#             similar_rows.append(row)

#     # Chuyển danh sách các dòng tìm thấy thành DataFrame
#     return pd.DataFrame(similar_rows)

# # Trích xuất từ khóa từ dữ liệu ban đầu
# df['keywords'] = df['information_cleaned'].apply(extract_keywords)

# # API Model
# class ContentInput(BaseModel):
#     content: str

# # Endpoint kiểm tra từ khóa
# @app.post("/search_content")
# async def check_keywords_endpoint(input: ContentInput):
#     result = check_keywords(input.content, df)
#     if not result.empty:
#         return {
#             "matches": result[['id','eng_name', 'vie_name']].to_dict(orient='records')
#         }
#     return {"message": "No matching movies found."}
