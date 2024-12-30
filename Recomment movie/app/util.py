import yake
import pandas as pd
# hàm để trích xuất từ khóa của một nội dung
# Khởi tạo trích xuất từ khóa
kw_extractor = yake.KeywordExtractor(lan="vi", n=2, dedupLim=0.9, top=20)

# Hàm trích xuất từ khóa
def extract_keywords(text):
    if pd.isnull(text) or not isinstance(text, str):
        return "[]"
    keywords = kw_extractor.extract_keywords(text)
    keyword_list = [f'"{keyword}"' for keyword, score in keywords]
    return f"[{', '.join(keyword_list)}]"


#
# from fastapi import HTTPException
#
# from db import *
# import pandas as pd
# from util import *
# from feature_extractor import FeatureExtractor
# from PIL import Image
# from datetime import datetime
# from pathlib import Path
# import numpy as np
# import io
# fe = FeatureExtractor()
# movies_df = load_movies()
# df = get_movies_dataframe()
# features = []
# img_paths = []
# for feature_path in Path("./static/feature").glob("*.npy"):
#     features.append(np.load(feature_path))
#     img_paths.append(str(Path("./static/img") / (feature_path.stem + ".jpg")))
# features = np.array(features)
#
#
# ## Xử lý nội dung content, tách nội dung nhập thành keyword và so vs từ trong nội dung trong data
# def search_movies_by_content(content: str, df: pd.DataFrame):
#     content_keywords = extract_keywords(content.lower())
#
#     filtered_keywords = [
#         keyword.strip('"') for keyword in content_keywords.strip('[]').split(',')
#         if len(keyword.strip('"').split()) > 1
#     ]
#
#     similar_rows = []
#     for _, row in df.iterrows():
#         cleaned_info = row['MoTa'].lower()
#         if any(keyword in cleaned_info for keyword in filtered_keywords):
#             similar_rows.append(row)
#
#     return pd.DataFrame(similar_rows)
# ## gọi hàm để sử dụng nếu có thì trả về ko có thì None
# def search_movies(content: str):
#     result = search_movies_by_content(content, df)
#     if not result.empty:
#         return {
#             "matches": result[['MaPhim', 'TenPhim', 'DinhDang']].to_dict(orient='records')
#         }
#     raise HTTPException(status_code=404, detail="No matching movies found.")
#
#
# def search_image_content(query_image: bytes):
#     """
#     Tìm kiếm phim dựa trên hình ảnh (image search).
#     """
#     img = Image.open(io.BytesIO(query_image))
#     query_feature = fe.extract(img)
#     dists = np.linalg.norm(features - query_feature, axis=1)  # Tính khoảng cách
#     ids = np.argsort(dists)[:5]  # Top 5 kết quả gần nhất
#
#     results = []
#     for idx in ids:
#         img_name = Path(img_paths[idx]).name
#         matched_row = movies_df[movies_df["poster_image"] == img_name]
#         if not matched_row.empty:
#             row = matched_row.iloc[0]
#             results.append({
#                 "MaPhim": int(row["MaPhim"]),
#                 "TenPhim": row["TenPhim"],
#                 "distance": float(dists[idx]),
#                 "HinhAnh": img_paths[idx]
#             })
#     return results
# import asyncio
# async def get_movie_recommendations_rate_async(current_movie_id: int):
#     return await asyncio.to_thread(get_movie_recommendations_rate, current_movie_id)
#
# def get_movie_avg_rates():
#     data = get_movie_recommendations_avg_rate()
#     return data