<div class="col-lg-12">
    <div class="card">
        <div class="card-header border-0">
            <div class="d-flex justify-content-between">
                <h3 class="card-title">Thêm banner </h3>
            </div>
        </div>
        <div class="card-body">
            <form method="post" action="quanlybanner/xuly.php" enctype="multipart/form-data" class="p-4">
                <div class="form-group mb-3">
                    <label>Tên banner</label>
                    <input type="text" class="form-control" name="tenbanner" placeholder="Nhập tên banner">
                </div>
                
                <div class="form-group mb-3">
                    <label>Hình ảnh</label>
                    <input type="file" class="form-control" name="hinhanh">
                </div>
                
                <div class="form-group mb-3">
                    <input type="submit" class="btn btn-primary" name="thembanner" value="Thêm banner">
                </div>
                </form>
                                                                                    
        </div>
        
    </div>
</div>