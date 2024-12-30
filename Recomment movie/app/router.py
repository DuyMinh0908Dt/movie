from fastapi import  APIRouter, UploadFile, File
import schema
from controller import *

router = APIRouter()
#Hàm router đường truyền

@router.get("/search_content")
async def search_content(content):
    """
    {
  "content": "Phim về thể loại live action sống động, không biết bạn có phim thể loại này không"
}
    """
    return search_movies(content)
@router.post("/search_image", response_model=list[schema.SearchResult])
async def search_image(query_img: UploadFile = File(...)):
    """
    Tìm kiếm phim dựa trên hình ảnh.
    """
    query_image = await query_img.read()
    return search_image_content(query_image)

@router.get("/rate_movie")
async def check_movie_recommendations_rate(id: int):
    data = await get_movie_recommendations_rate_async(id)
    return data

@router.get("/rate_avg_movie")
async def get_movie_recommendations_avg_rate():
    data =  get_movie_avg_rates()
    return data