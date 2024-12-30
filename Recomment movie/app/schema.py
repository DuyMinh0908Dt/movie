from pydantic import BaseModel
## ánh xạ data nhập vào
class ContentInput(BaseModel):
    content: str
class IdInput(BaseModel):
    id:int
class SearchResult(BaseModel):
    MaPhim: int
    TenPhim: str
    distance: float
    HinhAnh: str
    Trailer: str


