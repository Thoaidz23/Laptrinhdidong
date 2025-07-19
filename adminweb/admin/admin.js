document.getElementById('them_hang').onclick = function() {
    const count = document.querySelectorAll('.thong_so').length + 1; // Đếm số hàng hiện tại
    const newRow = `
        <div class="thong_so mb-3 d-flex align-items-end">
            <div class="me-2 flex-grow-1">
                <input type="text" id="thuoc_tinh_${count}" name="thuoc_tinh[]" class="form-control" required>
            </div>
            <div class="me-2 flex-grow-1">
                <input type="text" id="gia_tri_${count}" name="gia_tri[]" class="form-control" required>
            </div>
            <button type="button" class="btn btn-danger mt-2 xoa_hang" onclick="removeRow(this)">Xóa</button>
        </div>
    `;
    
    const addButton = document.getElementById('them_hang');
    addButton.insertAdjacentHTML('beforebegin', newRow);
};


document.getElementById('thong_so_ky_thuat').addEventListener('click', function(event) {
    if (event.target.classList.contains('xoa_hang')) {
        const rowToDelete = event.target.closest('.thong_so');
        rowToDelete.remove();
    }
});


function removeRow(button) {
    var row = button.parentNode;
    row.parentNode.removeChild(row);
}