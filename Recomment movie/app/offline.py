from PIL import Image
from feature_extractor import FeatureExtractor
from pathlib import Path
import numpy as np
import time

def extract_features():
    """
    Hàm trích xuất đặc trưng và lưu vào thư mục ./static/feature.
    """
    fe = FeatureExtractor()

    while True:
        # Duyệt qua tất cả ảnh trong thư mục static/img
        for img_path in sorted(Path("./static/img").glob("*.jpg")):
            print(f"Processing: {img_path}")
            # Trích xuất đặc trưng từ ảnh
            feature = fe.extract(img=Image.open(img_path))
            feature_path = Path("./static/feature") / (img_path.stem + ".npy")

            # Lưu đặc trưng vào file .npy
            np.save(feature_path, feature)

        print("Feature extraction completed!")

        # Chờ 30 phút và thực hiện lại quá trình
        print("Waiting for 30 minutes before re-running feature extraction...")
        time.sleep(30 * 60)  # Chờ 30 phút (30 * 60 giây)

if __name__ == "__main__":
    # Bắt đầu quá trình trích xuất đặc trưng
    print("Starting feature extraction...")
    extract_features()

# def extract_features():
   
#     fe = FeatureExtractor()
    
#     # Duyệt qua tất cả ảnh trong thư mục static/img
#     for img_path in sorted(Path("./static/img").glob("*.jpg")):
#         print(f"Processing: {img_path}")
#         # Trích xuất đặc trưng từ ảnh
#         feature = fe.extract(img=Image.open(img_path))
#         feature_path = Path("./static/feature") / (img_path.stem + ".npy")
        
#         # Lưu đặc trưng vào file .npy
#         np.save(feature_path, feature)
 
#     print("Feature extraction completed!")
# from PIL import Image
# from feature_extractor import FeatureExtractor
# from pathlib import Path
# import numpy as np

# if __name__ == '__main__':
#     fe = FeatureExtractor()

#     for img_path in sorted(Path("./static/img").glob("*.jpg")):
#         print(img_path)  
#         feature = fe.extract(img=Image.open(img_path))
#         feature_path = Path("./static/feature") / (img_path.stem + ".npy")  
#         np.save(feature_path, feature)
