<div class="col-lg-12">
    <div class="card">
        <div class="card-header border-0">
            <div class="d-flex justify-content-between">
                <h3 class="card-title">Thêm danh mục bài viết </h3>
            </div>
        </div>
        <div class="card-body">
            <form method="post" action="quanlydanhmucbaiviet/xuly.php" class="p-4 rounded">
                <div class="form-group mb-3">
                    <label for="ten_dmbv">Tên danh mục</label>
                    <input type="text" class="form-control" id="tendanhmuc" name="ten_dmbv" placeholder="Nhập tên danh mục">
                </div>
            
                <div class="form-group mb-3">
                    <label for="thutu">Thứ tự</label>
                    <input type="text" class="form-control" id="thutu" name="thutu" placeholder="Nhập thứ tự">
                </div>
            
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" name="themdanhmucbaiviet" value="Thêm danh mục bài viết">
                </div>
            </form>                                                                       
        </div>
        
    </div>
</div>