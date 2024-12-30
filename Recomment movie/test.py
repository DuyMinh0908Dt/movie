# from sklearn.feature_extraction.text import TfidfVectorizer
# from sklearn.metrics.pairwise import cosine_similarity
# import pandas as pd
# from bs4 import BeautifulSoup

# # Đọc dữ liệu từ file CSV
# file_path = "movies (1).csv"
# df = pd.read_csv(file_path)

# # Làm sạch HTML nếu có trong cột 'information'
# if 'information' in df.columns:
#     def clean_html(text):
#         if pd.isna(text):
#             return text
#         soup = BeautifulSoup(text, "html.parser")
#         cleaned_text = soup.get_text().replace("&nbsp;", " ").replace("&amp;", "&").strip()
#         return cleaned_text

#     df['information_cleaned'] = df['information'].apply(clean_html)
# search_description = "Có phim người nhện nào không?"  # Nội dung tìm kiếm của người dùng
# search_keywords = ["người nhện"] 
# # # Tìm kiếm từ khóa (ví dụ: "người nhện")
# # search_keywords = ["người nhện"]

# # Tạo vector TF-IDF với từ khóa có trọng số cao
# vectorizer = TfidfVectorizer(stop_words=None, vocabulary=search_keywords)  # Chỉ định từ khóa có trọng số cao
# tfidf_matrix = vectorizer.fit_transform(df['information_cleaned'] + [search_description])

# # Tính toán độ tương đồng cosine giữa mô tả tìm kiếm và các mô tả phim
# cosine_similarities = cosine_similarity(tfidf_matrix[-1], tfidf_matrix[:-1])

# # Hiển thị kết quả
# similarities = cosine_similarities.flatten()
# most_similar_idx = similarities.argmax()

# print(f"Phim có mô tả gần nhất với nội dung tìm kiếm là: {df['information_cleaned'].iloc[most_similar_idx]}")
# print(f"Độ tương tự: {similarities[most_similar_idx]}")
